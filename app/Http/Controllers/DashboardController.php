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
        $stats = [
            'total_leads' => Lead::where('is_active', true)->count(),
            'pipeline_value' => Client::where('verification_status', '!=', 'completed')->sum('deal_amount'),
            'total_clients' => Client::count(),
            'active_certificates' => Certification::where('status', 'active')->count(),
            'completed_projects' => Client::where('verification_status', 'completed')->count(),
            'staff_members' => \App\Models\User::count(),
        ];

        $highValueDeals = Client::with('lead.assignee')
            ->orderBy('deal_amount', 'desc')
            ->take(5)
            ->get();

        $staffWorkload = \App\Models\User::whereIn('role', ['sales', 'admin'])
            ->withCount('assignedLeads')
            ->get();

        return view('dashboard', compact('stats', 'highValueDeals', 'staffWorkload'));
    }
}
