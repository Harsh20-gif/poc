<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'city',
        'state',
        'service_type',
        'status',
        'source',
        'expected_value',
        'notes',
        'follow_up_date',
        'assigned_to',
        'is_client',
        'client_since',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
            'expected_value' => 'decimal:2',
            'is_client' => 'boolean',
            'client_since' => 'datetime',
        ];
    }

    /**
     * Staff member assigned to this lead.
     */
    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    /**
     * Certificates issued for this lead.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
