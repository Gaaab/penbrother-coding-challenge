<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'promoter_id',
        'wallet_balance',
        'investment_goal',
        'name',
        'description',
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:5',
        'investment_goal' => 'decimal:5',
    ];

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function amortizations()
    {
        return $this->hasMany(Amortization::class);
    }
}
