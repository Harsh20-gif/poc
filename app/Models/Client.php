<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'client_name',
        'company_name',
        'deal_amount',
        'finalized_services',
        'conversion_date',
        'verification_status',
        'survey_date',
    ];

    protected $casts = [
        'finalized_services' => 'array',
        'conversion_date' => 'date',
        'survey_date' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }
}
