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

    public function documents()
    {
        return $this->hasMany(ClientDocument::class, 'certification_id');
    }

    public static function getRequiredDocumentTypes($serviceType)
    {
        $map = [
            'ISO 9001' => ['Incorporation Certificate', 'GST Registration', 'Aadhar Card', 'Utility Bill'],
            'ISO 14001' => ['Incorporation Certificate', 'GST Registration', 'Environmental Policy'],
            'ISO 45001' => ['Incorporation Certificate', 'GST Registration', 'Safety Manual'],
            'ISO 27001' => ['Incorporation Certificate', 'GST Registration', 'IT Security Policy'],
            'CE Marking' => ['Product Technical File', 'Testing Report'],
            'BIS Certification' => ['Factory License', 'Manufacturing Process Flow', 'Test Equipment List'],
            'FSSAI' => ['Premises Proof', 'Food Safety Plan', 'Water Test Report'],
            'GMP' => ['Plant Layout', 'Quality Manual'],
            'Hallmark' => ['GST Registration', 'Address Proof'],
            'GST Registration' => ['Aadhar Card', 'PAN Card', 'Address Proof', 'Bank Statement']
        ];

        return $map[$serviceType] ?? ['Basic KYC Details'];
    }
}
