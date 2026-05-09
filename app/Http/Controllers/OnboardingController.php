<?php

namespace App\Http\Controllers;

use App\Models\BudgetPlan;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If already setup, go to dashboard
        if ($user->budgetPlans()->exists()) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'monthly_income' => ['required', 'numeric', 'min:0'],
            'cycle_start_date' => ['required', 'integer', 'min:1', 'max:31'],
            'needs_ratio' => ['required', 'numeric', 'min:0', 'max:100'],
            'wants_ratio' => ['required', 'numeric', 'min:0', 'max:100'],
            'savings_ratio' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Ensure total ratio is 100
        if (round($request->needs_ratio + $request->wants_ratio + $request->savings_ratio) != 100) {
            return back()->withErrors(['ratio' => 'Total ratio must be 100%'])->withInput();
        }

        DB::transaction(function () use ($request) {
            $user = Auth::user();
            
            // Create Consolidated Budget Plan
            $plan = BudgetPlan::create([
                'user_id' => $user->id,
                'monthly_income' => $request->monthly_income,
                'cycle_start_date' => $request->cycle_start_date,
                'needs_percentage' => $request->needs_ratio,
                'wants_percentage' => $request->wants_ratio,
                'savings_percentage' => $request->savings_ratio,
                'needs_amount' => ($request->monthly_income * $request->needs_ratio) / 100,
                'wants_amount' => ($request->monthly_income * $request->wants_ratio) / 100,
                'savings_amount' => ($request->monthly_income * $request->savings_ratio) / 100,
            ]);

            // F-06: Dynamic Expense Categories (Default Setup)
            // Only create if user doesn't have any
            if ($user->categories()->count() === 0) {
                $defaults = [
                    'needs' => ['Rent', 'Utilities', 'Groceries', 'Transport'],
                    'wants' => ['Dining Out', 'Entertainment', 'Shopping'],
                    'savings' => ['Emergency Fund', 'Investment'],
                ];

                foreach ($defaults as $type => $cats) {
                    foreach ($cats as $cat) {
                        Category::create([
                            'user_id' => $user->id,
                            'budget_type' => $type,
                            'name' => $cat,
                            'icon' => 'tag',
                        ]);
                    }
                }
            }
        });

        return redirect()->route('dashboard')->with('success', 'Onboarding completed successfully!');
    }
}
