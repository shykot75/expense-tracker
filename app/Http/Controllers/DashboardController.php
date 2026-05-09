<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plan = $user->budgetPlans()->latest()->first();
        
        if (!$plan) {
            return redirect()->route('onboarding');
        }

        // Cycle calculation: starts on the user's chosen day of the month
        $cycleStart = Carbon::now()->startOfMonth()->setDay($plan->cycle_start_date);
        if (Carbon::now()->lt($cycleStart)) {
            $cycleStart->subMonth();
        }

        $expenses = $user->expenses()->with('category')
            ->where('expense_date', '>=', $cycleStart)
            ->get();

        $totalSpent = $expenses->sum('amount');
        $totalBudget = $plan->monthly_income;
        $totalRemaining = $totalBudget - $totalSpent;
        $spentPercentage = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;

        // Breakdown by Type (Needs, Wants, Savings)
        $types = ['needs', 'wants', 'savings'];
        $breakdown = [];
        foreach ($types as $type) {
            $budget = $plan->{$type . '_amount'};
            $spent = $expenses->filter(fn($e) => $e->category->budget_type == $type)->sum('amount');
            $remaining = $budget - $spent;
            $percentage = $budget > 0 ? ($spent / $budget) * 100 : 0;
            
            $breakdown[$type] = [
                'budget' => $budget,
                'spent' => $spent,
                'remaining' => $remaining,
                'percentage' => min($percentage, 100),
            ];
        }

        $recentExpenses = $expenses->sortByDesc('expense_date')->take(5);

        return view('dashboard', compact(
            'plan', 'totalSpent', 'totalBudget', 'totalRemaining', 
            'spentPercentage', 'breakdown', 'recentExpenses'
        ));
    }
}
