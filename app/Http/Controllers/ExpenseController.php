<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $query = Auth::user()->expenses()->with('category')
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year);

        // Filter by Type
        if ($request->has('type') && $request->type != 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('budget_type', $request->type);
            });
        }

        // Filter by Category
        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();
        
        // Calculate Stats
        $totalSpend = $expenses->sum('amount');
        $typeTotals = [
            'needs' => $expenses->filter(fn($e) => $e->category->budget_type == 'needs')->sum('amount'),
            'wants' => $expenses->filter(fn($e) => $e->category->budget_type == 'wants')->sum('amount'),
            'savings' => $expenses->filter(fn($e) => $e->category->budget_type == 'savings')->sum('amount'),
        ];

        $catBreakdown = $expenses->groupBy('category.name')->map(fn($group) => $group->sum('amount'));
        $highestCat = $catBreakdown->sortDesc()->keys()->first() ?? 'N/A';
        $lowestCat = $catBreakdown->sort()->keys()->first() ?? 'N/A';

        $categories = Auth::user()->categories()->orderBy('name')->get();

        return view('expenses.index', compact(
            'expenses', 'totalSpend', 'typeTotals', 
            'highestCat', 'lowestCat', 'categories',
            'month', 'year', 'catBreakdown'
        ));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->orderBy('name')->get()->groupBy('budget_type');
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Auth::user()->expenses()->create($request->all());

        return redirect()->route('dashboard')->with('success', 'Expense added successfully!');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $categories = Auth::user()->categories()->orderBy('name')->get()->groupBy('budget_type');
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $expense->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Expense updated!');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();
        return redirect()->route('dashboard')->with('success', 'Expense deleted!');
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'budget_type' => ['required', 'in:needs,wants,savings'],
        ]);

        $category = Auth::user()->categories()->create([
            'name' => $request->name,
            'budget_type' => $request->budget_type,
            'icon' => 'tag',
        ]);

        return response()->json($category);
    }
}
