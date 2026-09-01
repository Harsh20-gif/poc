<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'certificate_name',
        'certificate_type',
        'issue_date',
        'expiry_date',
        'certificate_pdf_path',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function renewalLeads()
    {
        return $this->hasMany(Lead::class, 'renewed_from_certification_id');
    }
}
