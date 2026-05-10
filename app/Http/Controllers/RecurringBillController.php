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

    public function store(Request $request, \App\Services\GamificationService $gamification)
    {
        try {
            $validated = $request->validate([
                'category_id' => ['required', 'exists:categories,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
                'description' => ['required', 'string', 'max:100'],
                'next_deduction_date' => ['required', 'date', 'after_or_equal:today'],
            ]);

            Auth::user()->recurringBills()->create($validated + ['status' => 'active']);
            
            $newBadges = $gamification->checkBadges(Auth::user());
            $message = 'Recurring bill automation established!';
            
            if (!empty($newBadges)) {
                $badgeNames = array_map(fn($k) => \App\Services\GamificationService::getBadgeDetails($k)['name'], $newBadges);
                $message .= ' 🏆 Achievement Unlocked: ' . implode(', ', $badgeNames);
            }

            return redirect()->route('recurring-bills.index')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Recurring Bill Store Error: ' . $e->getMessage());
            return back()->with('error', 'Could not set up the recurring bill. Please try again.')->withInput();
        }
    }

    public function edit(RecurringBill $recurringBill)
    {
        if ($recurringBill->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Auth::user()->categories()->orderBy('name')->get();
        return view('recurring-bills.edit', compact('recurringBill', 'categories'));
    }

    public function update(Request $request, RecurringBill $recurringBill)
    {
        if ($recurringBill->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'category_id' => ['required', 'exists:categories,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
                'description' => ['required', 'string', 'max:100'],
                'next_deduction_date' => ['required', 'date'],
                'status' => ['required', 'in:active,paused'],
            ]);

            $recurringBill->update($validated);
            return redirect()->route('recurring-bills.index')->with('success', 'Automation settings updated.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Recurring Bill Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update automation settings.')->withInput();
        }
    }

    public function toggleStatus(RecurringBill $recurringBill)
    {
        if ($recurringBill->user_id !== Auth::id()) {
            abort(403);
        }

        $newStatus = $recurringBill->status === 'active' ? 'paused' : 'active';
        $recurringBill->update(['status' => $newStatus]);

        return back()->with('success', 'Bill status updated to ' . $newStatus);
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
