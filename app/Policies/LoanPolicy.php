<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LoanPolicy
{
    public function view(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }

    public function update(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }

    public function delete(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }
}
