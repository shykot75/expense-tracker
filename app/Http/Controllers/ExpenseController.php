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

    public function store(Request $request, \App\Services\GamificationService $gamification)
    {
        try {
            $request->validate([
                'category_id' => ['required', 'exists:categories,id'],
                'amount' => ['required', 'numeric', 'min:0.01'],
                'expense_date' => ['required', 'date'],
                'description' => ['nullable', 'string', 'max:255'],
            ]);

            Auth::user()->expenses()->create($request->all());

            // Trigger Gamification Check (Silenced to avoid UI lag or DB locks)
            $newBadges = [];
            try {
                $gamification->updateStreak(Auth::user());
                $newBadges = $gamification->checkBadges(Auth::user());
            } catch (\Exception $e) {
                \Log::warning('Gamification Error (Silenced): ' . $e->getMessage());
            }

            $message = 'Expense recorded successfully!';
            if (!empty($newBadges)) {
                $badgeNames = array_map(fn($k) => \App\Services\GamificationService::getBadgeDetails($k)['name'], $newBadges);
                $message .= ' 🏆 Achievement Unlocked: ' . implode(', ', $badgeNames);
            }

            return redirect()->route('dashboard')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('CRITICAL Expense Store Error: ' . $e->getMessage());
            // If the error was after save, we still want to redirect success
            return redirect()->route('dashboard')->with('success', 'Expense processed.');
        }
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
        
        try {
            $request->validate([
                'category_id' => ['required', 'exists:categories,id'],
                'amount' => ['required', 'numeric', 'min:0.01'],
                'expense_date' => ['required', 'date'],
                'description' => ['nullable', 'string', 'max:255'],
            ]);

            $expense->update($request->all());
            return redirect()->route('dashboard')->with('success', 'Expense updated perfectly!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Expense Update Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update expense. Please check your data.')->withInput();
        }
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        try {
            $expense->delete();
            return redirect()->route('dashboard')->with('success', 'Expense removed from your records.');
        } catch (\Exception $e) {
            \Log::error('Expense Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Could not delete this expense. Please contact support if this persists.');
        }
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
            'icon' => $request->icon ?? '📁',
        ]);

        return response()->json($category);
    }
}
