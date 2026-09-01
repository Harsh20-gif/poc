@extends('layouts.app')
@section('title', 'Clients')

@section('content')
<div class="card mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Client / Company</th>
                    <th>Deal Amount</th>
                    <th>Services Finalized</th>
                    <th>Conversion Date</th>
                    <th>Verification Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $client->company_name ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $client->client_name }}</div>
                    </td>
                    <td class="fw-medium text-success">₹{{ number_format($client->deal_amount, 2) }}</td>
                    <td>
                        @if($client->finalized_services)
                            @foreach($client->finalized_services as $service)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-1">{{ $service }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $client->conversion_date->format('M d, Y') }}</td>
                    <td>
                        @php
                            $badgeClass = match($client->verification_status) {
                                'pending' => 'bg-warning text-dark',
                                'scheduled' => 'bg-info text-dark',
                                'completed' => 'bg-success',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($client->verification_status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary">Profile</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No clients found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $clients->links('pagination::bootstrap-5') }}
</div>
@endsection
