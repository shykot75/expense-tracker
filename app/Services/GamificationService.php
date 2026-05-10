<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class GamificationService
{
    public function updateStreak(User $user)
    {
        $lastExpense = $user->expenses()->orderBy('expense_date', 'desc')->first();
        
        if (!$lastExpense) {
            $user->update(['current_streak' => 1]);
            return;
        }

        $lastDate = Carbon::parse($lastExpense->expense_date);
        
        if ($user->current_streak == 0) {
            $user->update(['current_streak' => 1]);
            return;
        }

        if ($lastDate->isToday()) {
            return; // Already handled today
        }

        if ($lastDate->isYesterday()) {
            $user->increment('current_streak');
        } else {
            $user->update(['current_streak' => 1]);
        }
    }

    public function checkBadges(User $user)
    {
        $currentBadges = $user->badges ?? [];
        $newBadges = [];

        // 1. First Step (Log first expense)
        if ($user->expenses()->count() >= 1 && !in_array('first_step', $currentBadges)) {
            $newBadges[] = 'first_step';
        }

        // 2. Consistent (7-day streak)
        if ($user->current_streak >= 7 && !in_array('week_warrior', $currentBadges)) {
            $newBadges[] = 'week_warrior';
        }

        // 3. Month Master (30-day streak)
        if ($user->current_streak >= 30 && !in_array('month_master', $currentBadges)) {
            $newBadges[] = 'month_master';
        }

        // 4. Debt Slayer (Paid off a loan)
        if ($user->loans()->where('status', 'paid')->count() >= 1 && !in_array('debt_slayer', $currentBadges)) {
            $newBadges[] = 'debt_slayer';
        }

        // 5. Automator (Setup recurring bill)
        if ($user->recurringBills()->count() >= 1 && !in_array('automator', $currentBadges)) {
            $newBadges[] = 'automator';
        }

        // 6. Digital Twin (Linked Google Account)
        if ($user->google_id && !in_array('digital_twin', $currentBadges)) {
            $newBadges[] = 'digital_twin';
        }

        // 7. Dreamer (Created first Savings Goal)
        if ($user->savingsGoals()->count() >= 1 && !in_array('dreamer', $currentBadges)) {
            $newBadges[] = 'dreamer';
        }

        // 8. Goal Crusher (Achieved 100% of a goal)
        if ($user->savingsGoals()->where('status', 'achieved')->count() >= 1 && !in_array('goal_crusher', $currentBadges)) {
            $newBadges[] = 'goal_crusher';
        }

        if (!empty($newBadges)) {
            $user->update([
                'badges' => array_unique(array_merge($currentBadges, $newBadges))
            ]);
            return $newBadges;
        }

        return [];
    }

    public static function getBadgeDetails($badgeKey)
    {
        $all = [
            'first_step' => [
                'name' => 'First Step',
                'description' => 'Logged your first expense!',
                'icon' => '🚀',
                'color' => 'indigo'
            ],
            'week_warrior' => [
                'name' => 'Week Warrior',
                'description' => 'Maintained a 7-day tracking streak!',
                'icon' => '🔥',
                'color' => 'orange'
            ],
            'month_master' => [
                'name' => 'Month Master',
                'description' => 'Maintained a 30-day tracking streak!',
                'icon' => '👑',
                'color' => 'yellow'
            ],
            'debt_slayer' => [
                'name' => 'Debt Slayer',
                'description' => 'Successfully paid off your first loan!',
                'icon' => '⚔️',
                'color' => 'rose'
            ],
            'automator' => [
                'name' => 'Automator',
                'description' => 'Configured your first automated bill!',
                'icon' => '🤖',
                'color' => 'emerald'
            ],
            'digital_twin' => [
                'name' => 'Digital Twin',
                'description' => 'Securely linked your Google account!',
                'icon' => '🎭',
                'color' => 'sky'
            ],
            'dreamer' => [
                'name' => 'Dreamer',
                'description' => 'Defined your first financial target!',
                'icon' => '💭',
                'color' => 'violet'
            ],
            'goal_crusher' => [
                'name' => 'Goal Crusher',
                'description' => 'Hit 100% of a financial dream!',
                'icon' => '💎',
                'color' => 'amber'
            ]
        ];

        return $all[$badgeKey] ?? null;
    }
}
