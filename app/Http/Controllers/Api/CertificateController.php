<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index(Request $request)
    {
        $certificates = Certificate::with(['lead.assignedStaff'])
            ->latest()
            ->get();

        return response()->json([
            'total' => $certificates->count(),
            'certificates' => $certificates->map(fn($c) => [
                'id' => $c->id,
                'certificate_number' => $c->certificate_number,
                'certificate_type' => $c->certificate_type,
                'issue_date' => $c->issue_date->toDateString(),
                'expiry_date' => $c->expiry_date->toDateString(),
                'status' => $c->status,
                'document_url' => $c->document_url,
                'lead' => $c->lead ? [
                    'id' => $c->lead->id,
                    'company_name' => $c->lead->company_name,
                    'contact_person' => $c->lead->contact_person,
                    'assigned_to' => $c->lead->assignedStaff ? [
                        'id' => $c->lead->assignedStaff->id,
                        'name' => $c->lead->assignedStaff->name,
                    ] : null,
                ] : null,
                'created_at' => $c->created_at->toDateTimeString(),
            ]),
        ]);
    }
}
