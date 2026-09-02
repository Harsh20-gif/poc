@extends('layouts.app')
@section('title', 'Global Activity History')
@section('subtitle', 'Track all activities across all leads')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Activity Log</h5>
            </div>
            <div class="card-body">
                <!-- Timeline Filter -->
                <form method="GET" action="{{ route('history') }}" class="mb-4 bg-light p-3 rounded border">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search activities..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="remark" {{ request('type') === 'remark' ? 'selected' : '' }}>Remarks</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="timeline position-relative ps-4 border-start border-2 border-secondary border-opacity-25 ms-2">
                    @forelse($activities as $interaction)
                        @php
                            $typeColors = [
                                'remark' => 'primary',
                                'status_change' => 'warning',
                                'assignment' => 'info',
                                'created' => 'success',
                            ];
                            $typeIcons = [
                                'remark' => 'bi-chat-left-text',
                                'status_change' => 'bi-arrow-repeat',
                                'assignment' => 'bi-person-check',
                                'created' => 'bi-star',
                            ];
                            $type = $interaction->type ?? 'remark';
                            $color = $typeColors[$type] ?? 'secondary';
                            $icon = $typeIcons[$type] ?? 'bi-record-circle';
                        @endphp
                        <div class="timeline-item mb-4 position-relative">
                            <!-- Timeline marker -->
                            <div class="position-absolute bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; left: -40px; top: 0; z-index: 1;">
                                <i class="bi {{ $icon }} small"></i>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <span class="fw-bold text-dark">{{ $interaction->user->name ?? 'System' }}</span>
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}-subtle ms-2 rounded-pill px-2" style="font-size: 0.7rem;">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                                    <span class="text-muted ms-2 small">on Lead: <a href="{{ route('leads.show', $interaction->lead_id) }}" class="text-decoration-none fw-medium">{{ $interaction->lead->contact_person }}</a></span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block mb-1">{{ $interaction->created_at->format('M d, Y h:ia') }}</small>
                                    <a href="{{ route('leads.show', $interaction->lead_id) }}" class="btn btn-sm btn-outline-primary py-0" style="font-size: 0.75rem;">View Lead</a>
                                </div>
                            </div>
                            <div class="bg-light p-3 rounded-3 text-dark mb-2 border border-secondary border-opacity-10 shadow-sm">
                                {{ $interaction->remark }}
                                
                                @if($interaction->details && is_array($interaction->details))
                                    <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 small text-muted">
                                        @foreach($interaction->details as $key => $val)
                                            @if(is_scalar($val))
                                                <span class="me-3"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $val }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4 ms-n4">No activities logged yet.</p>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
