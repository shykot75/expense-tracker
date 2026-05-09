<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyHistory extends Model
{
    use HasFactory, HasAuditColumns;

    protected $fillable = [
        'user_id',
        'billing_month',
        'total_income',
        'needs_spent',
        'wants_spent',
        'total_saved',
        'report_file_path',
    ];

    protected $casts = [
        'total_income' => 'decimal:2',
        'needs_spent' => 'decimal:2',
        'wants_spent' => 'decimal:2',
        'total_saved' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
