<?php

namespace App\Http\Controllers;

use App\Models\BudgetPlan;
use App\Models\Category;
use App\Models\Expense;
use App\Models\RecurringBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingsController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $plan = $user->budgetPlans()->latest()->first();
        $categories = $user->categories()->get()->groupBy('budget_type');
        $goals = $user->savingsGoals()->latest()->get();

        return view('settings.index', compact('user', 'plan', 'categories', 'goals'));
    }

    public function updateBudget(Request $request)
    {
        try {
            $request->validate([
                'monthly_income' => ['required', 'numeric', 'min:0'],
                'cycle_start_date' => ['required', 'integer', 'min:1', 'max:31'],
                'needs_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
                'wants_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
                'savings_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            ]);

            if (round($request->needs_percentage + $request->wants_percentage + $request->savings_percentage) != 100) {
                return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'budget'])
                               ->with('error', 'The allocation ratios must total exactly 100%.')
                               ->withInput();
            }

            $user = Auth::user();
            $plan = $user->budgetPlans()->latest()->first();

            $plan->update([
                'monthly_income' => $request->monthly_income,
                'cycle_start_date' => $request->cycle_start_date,
                'needs_percentage' => $request->needs_percentage,
                'wants_percentage' => $request->wants_percentage,
                'savings_percentage' => $request->savings_percentage,
                'needs_amount' => ($request->monthly_income * $request->needs_percentage) / 100,
                'wants_amount' => ($request->monthly_income * $request->wants_percentage) / 100,
                'savings_amount' => ($request->monthly_income * $request->savings_percentage) / 100,
            ]);

            return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'budget'])
                           ->with('success', 'Your Financial Intelligence Hub has been updated.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Budget Update Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update financial settings.');
        }
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        try {
            $request->validate(['name' => 'required|string|max:255']);
            $category->update(['name' => $request->name]);
            return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'categories'])
                           ->with('success', 'Taxonomy entry updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update the category.');
        }
    }

    public function deleteCategory(Category $category)
    {
        $this->authorize('delete', $category);

        try {
            if ($category->expenses()->exists()) {
                return back()->with('error', 'Cannot delete a category with existing records. Re-categorize them first.');
            }

            $category->delete();
            return redirect()->route('settings.index', ['tab' => 'categories'])
                           ->with('success', 'Taxonomy entry removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Critical error during category deletion.');
        }
    }

    public function resetData(Request $request)
    {
        try {
            $request->validate(['confirmation' => 'required|string']);

            if (strtolower($request->confirmation) !== 'reset') {
                return back()->with('error', 'Authentication failed. Please type RESET exactly.');
            }

            DB::transaction(function () {
                $user = Auth::user();
                Expense::where('user_id', $user->id)->delete();
                RecurringBill::where('user_id', $user->id)->delete();
                BudgetPlan::where('user_id', $user->id)->delete();
                Category::where('user_id', $user->id)->delete();
            });

            return redirect()->route('onboarding')->with('success', 'Account wiped. Let\'s build a better future together.');
        } catch (\Exception $e) {
            \Log::error('Reset Error: ' . $e->getMessage());
            return back()->with('error', 'Nuclear reset failed. Please contact support immediately.');
        }
    }
}
