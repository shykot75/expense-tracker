<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory, HasAuditColumns;

    protected $fillable = [
        'user_id',
        'loan_type',
        'person_name',
        'amount',
        'loan_date',
        'deadline_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'loan_date' => 'date',
        'deadline_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
