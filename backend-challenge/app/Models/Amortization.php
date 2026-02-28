<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amortization extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'state',
        'scheduled_date',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'amount' => 'integer', //
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
