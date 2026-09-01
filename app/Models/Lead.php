<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_person',
        'company_name',
        'mobile',
        'alternate_mobile',
        'email',
        'city',
        'source',
        'services',
        'status',
        'is_active',
        'deactivation_reason',
        'renewed_from_certification_id',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'services' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function interactions()
    {
        return $this->hasMany(LeadInteraction::class)->orderBy('created_at', 'desc');
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function renewalCertification()
    {
        return $this->belongsTo(Certification::class, 'renewed_from_certification_id');
    }
}
