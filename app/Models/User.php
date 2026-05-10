<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Income;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, \App\Traits\HasAuditColumns;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'google_id',
        'avatar',
        'monthly_income',
        'cycle_start_date',
        'current_streak',
        'badges',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'badges' => 'array',
            'monthly_income' => 'decimal:2',
        ];
    }

    public function budgetPlans()
    {
        return $this->hasMany(BudgetPlan::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function recurringBills()
    {
        return $this->hasMany(RecurringBill::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function savingsGoals()
    {
        return $this->hasMany(SavingsGoal::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function monthlyHistories()
    {
        return $this->hasMany(MonthlyHistory::class);
    }
}
