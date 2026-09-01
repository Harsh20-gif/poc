<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [];

        if (in_array($user->role, ['admin', 'sales'])) {
            $stats['pending_leads'] = Lead::where('status', 'pending')->where('is_active', true)->count();
            $stats['in_conversation_leads'] = Lead::where('status', 'in_conversation')->where('is_active', true)->count();
            $stats['renewal_leads'] = Lead::where('status', 'renewal')->where('is_active', true)->count();
            $stats['total_clients'] = Client::count();
            $stats['certs_expiring_soon'] = Certification::where('status', 'expiring_soon')->count();
        }

        if (in_array($user->role, ['admin', 'verifier'])) {
            $stats['total_clients'] = Client::count();
            $stats['pending_documents'] = ClientDocument::where('verification_status', 'pending')->count();
        }

        return view('dashboard', compact('stats'));
    }
}
