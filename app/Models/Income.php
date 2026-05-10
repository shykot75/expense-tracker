<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory, HasAuditColumns;

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'income_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'income_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
