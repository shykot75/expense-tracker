<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SavingsGoalController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'target_amount' => 'required|numeric|min:1',
                'deadline' => 'nullable|date|after:today',
                'color' => 'nullable|string|max:7',
            ]);

            Auth::user()->savingsGoals()->create($validated);

            // Award 'Dreamer' badge
            app(\App\Services\GamificationService::class)->checkBadges(Auth::user());

            return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'goals'])
                           ->with('success', 'Dream target locked in! Let\'s start saving.');
        } catch (\Exception $e) {
            \Log::error('Goal Store Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to create goal. Please try again.');
        }
    }

    public function update(Request $request, SavingsGoal $goal)
    {
        Gate::authorize('update', $goal);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'target_amount' => 'required|numeric|min:1',
                'deadline' => 'nullable|date',
            ]);

            $goal->update($validated);

            return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'goals'])
                           ->with('success', 'Goal updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update goal.');
        }
    }

    public function contribute(Request $request, SavingsGoal $goal)
    {
        Gate::authorize('update', $goal);

        try {
            $request->validate(['amount' => 'required|numeric|min:1']);

            $goal->increment('current_amount', $request->amount);

            if ($goal->current_amount >= $goal->target_amount) {
                $goal->update(['status' => 'achieved']);
                
                // Award 'Goal Crusher' badge
                app(\App\Services\GamificationService::class)->checkBadges(Auth::user());

                return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'goals'])
                               ->with('success', 'CONGRATULATIONS! You\'ve achieved your goal: ' . $goal->name);
            }

            return redirect()->route('settings.index', ['tab' => $request->active_tab ?? 'goals'])
                           ->with('success', '৳' . number_format($request->amount) . ' added to your target! Keep going.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to contribute to goal.');
        }
    }

    public function destroy(SavingsGoal $goal)
    {
        Gate::authorize('delete', $goal);

        try {
            $goal->delete();
            return redirect()->route('settings.index', ['tab' => 'goals'])
                           ->with('success', 'Goal removed from your plan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to remove goal.');
        }
    }
}
