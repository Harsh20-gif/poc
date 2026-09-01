@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    @if(in_array(Auth::user()->role, ['admin', 'sales']))
        <div class="col-md-4 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Pending Leads</h6>
                    <h2 class="mb-0 fw-bold text-primary">{{ $stats['pending_leads'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">In Conversation</h6>
                    <h2 class="mb-0 fw-bold text-warning">{{ $stats['in_conversation_leads'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Renewal Leads</h6>
                    <h2 class="mb-0 fw-bold text-danger">{{ $stats['renewal_leads'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Certs Expiring (30 days)</h6>
                    <h2 class="mb-0 fw-bold text-danger">{{ $stats['certs_expiring_soon'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    @endif

    @if(in_array(Auth::user()->role, ['admin', 'verifier', 'sales']))
        <div class="col-md-4 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Clients</h6>
                    <h2 class="mb-0 fw-bold text-success">{{ $stats['total_clients'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    @endif

    @if(in_array(Auth::user()->role, ['admin', 'verifier']))
        <div class="col-md-4 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Pending Verifications</h6>
                    <h2 class="mb-0 fw-bold text-info">{{ $stats['pending_documents'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
