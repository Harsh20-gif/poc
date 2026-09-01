<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'certification_id',
        'document_type',
        'file_path',
        'original_filename',
        'verification_status',
        'verified_by',
        'rejection_reason',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }
}
