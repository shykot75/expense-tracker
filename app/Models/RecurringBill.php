<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringBill extends Model
{
    use HasFactory, HasAuditColumns;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'description',
        'next_deduction_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'next_deduction_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
