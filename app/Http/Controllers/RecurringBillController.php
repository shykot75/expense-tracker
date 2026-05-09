<?php

namespace App\Http\Controllers;

use App\Models\RecurringBill;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringBillController extends Controller
{
    public function index()
    {
        $bills = Auth::user()->recurringBills()->with('category')->orderBy('next_deduction_date')->get();
        return view('recurring-bills.index', compact('bills'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->orderBy('name')->get();
        return view('recurring-bills.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:100'],
            'next_deduction_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        Auth::user()->recurringBills()->create($request->all());

        return redirect()->route('recurring-bills.index')->with('success', 'Recurring bill added!');
    }

    public function destroy(RecurringBill $recurringBill)
    {
        if ($recurringBill->user_id !== Auth::id()) {
            abort(403);
        }
        
        $recurringBill->delete();
        return redirect()->route('recurring-bills.index')->with('success', 'Bill removed.');
    }
}
