<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class ForecastService
{
    /**
     * Project total savings over a period of months.
     *
     * @param User $user
     * @param int $months
     * @return array
     */
    public function projectSavings(User $user, int $months = 12)
    {
        $plan = $user->budgetPlans()->latest()->first();
        if (!$plan) return [];

        $monthlySavings = $plan->savings_amount;
        $projections = [];
        $currentDate = Carbon::now();

        for ($i = 1; $i <= $months; $i++) {
            $projections[] = [
                'month' => $currentDate->copy()->addMonths($i)->format('M Y'),
                'amount' => $monthlySavings * $i,
            ];
        }

        return $projections;
    }

    /**
     * Calculate the estimated achievement date for a savings goal.
     *
     * @param \App\Models\SavingsGoal $goal
     * @return Carbon|null
     */
    public function estimateGoalCompletion($goal)
    {
        $remaining = $goal->target_amount - $goal->current_amount;
        if ($remaining <= 0) return Carbon::now();

        // Calculate average monthly contribution from history if possible, 
        // otherwise use the user's current monthly savings allocation
        $user = $goal->user;
        $plan = $user->budgetPlans()->latest()->first();
        $monthlyCapacity = $plan ? $plan->savings_amount : 0;

        if ($monthlyCapacity <= 0) return null;

        $monthsToComplete = ceil($remaining / $monthlyCapacity);
        return Carbon::now()->addMonths($monthsToComplete);
    }

    /**
     * Get a comprehensive wealth overview.
     */
    public function getWealthOverview(User $user)
    {
        $plan = $user->budgetPlans()->latest()->first();
        if (!$plan) return null;

        return [
            'monthly_contribution' => $plan->savings_amount,
            'six_month_forecast' => $plan->savings_amount * 6,
            'one_year_forecast' => $plan->savings_amount * 12,
            'two_year_forecast' => $plan->savings_amount * 24,
        ];
    }
}
