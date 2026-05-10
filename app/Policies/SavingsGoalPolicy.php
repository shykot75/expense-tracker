<?php

namespace App\Policies;

use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SavingsGoalPolicy
{
    use HandlesAuthorization;

    public function update(User $user, SavingsGoal $goal)
    {
        return $user->id === $goal->user_id;
    }

    public function delete(User $user, SavingsGoal $goal)
    {
        return $user->id === $goal->user_id;
    }
}
