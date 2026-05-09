<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    use HasFactory, HasAuditColumns;

    protected $fillable = [
        'user_id',
        'monthly_income',
        'cycle_start_date',
        'needs_percentage',
        'wants_percentage',
        'savings_percentage',
        'needs_amount',
        'wants_amount',
        'savings_amount',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
        'needs_amount' => 'decimal:2',
        'wants_amount' => 'decimal:2',
        'savings_amount' => 'decimal:2',
        'needs_percentage' => 'integer',
        'wants_percentage' => 'integer',
        'savings_percentage' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
