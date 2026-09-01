<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'remark',
        'next_follow_up_date',
    ];

    protected $casts = [
        'next_follow_up_date' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
