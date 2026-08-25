<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'certificate_number',
        'certificate_type',
        'issue_date',
        'expiry_date',
        'status',
        'document_url',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    /**
     * Get the lead that owns the certificate.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
