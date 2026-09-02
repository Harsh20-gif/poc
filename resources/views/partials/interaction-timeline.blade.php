<div class="card h-100">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark">Interaction Timeline</h6>
    </div>
    <div class="card-body">
        @if($showForm)
        <form action="{{ $submitUrl }}" method="POST" class="mb-4 pb-4 border-bottom">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium text-dark">Log New Interaction</label>
                <textarea name="remark" rows="3" class="form-control text-dark border-secondary" placeholder="What happened in this interaction?" required></textarea>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-medium small mb-1 text-dark">Next Follow-up Date (Optional)</label>
                    <input type="date" name="next_follow_up_date" class="form-control form-control-sm text-dark border-secondary">
                </div>
                <div class="col-md-6 text-end mt-3 mt-md-0">
                    <button type="submit" class="btn text-white" style="background-color: #0284c7;">Add Note</button>
                </div>
            </div>
        </form>
        @endif

        <!-- Timeline Filter -->
        <form method="GET" action="{{ route('leads.show', $lead) }}" class="mb-4 bg-light p-3 rounded border">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="timeline_search" class="form-control form-control-sm" placeholder="Search timeline..." value="{{ request('timeline_search') }}">
                </div>
                <div class="col-md-4">
                    <select name="timeline_type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="remark" {{ request('timeline_type') === 'remark' ? 'selected' : '' }}>Remarks</option>
                        <option value="status_change" {{ request('timeline_type') === 'status_change' ? 'selected' : '' }}>Status Changes</option>
                        <option value="assignment" {{ request('timeline_type') === 'assignment' ? 'selected' : '' }}>Assignments</option>
                        <option value="created" {{ request('timeline_type') === 'created' ? 'selected' : '' }}>Creation</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
                </div>
            </div>
        </form>

        <div class="timeline position-relative ps-4 border-start border-2 border-secondary border-opacity-25 ms-2">
            @forelse($interactions as $interaction)
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
                        </div>
                        <small class="text-muted">{{ $interaction->created_at->format('M d, Y h:ia') }}</small>
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
                    @if($interaction->next_follow_up_date)
                    <small class="fw-medium text-primary"><i class="bi bi-calendar-event me-1"></i> Follow-up scheduled for {{ \Carbon\Carbon::parse($interaction->next_follow_up_date)->format('M d, Y') }}</small>
                    @endif
                </div>
            @empty
                <p class="text-muted text-center py-4 ms-n4">No interactions logged yet.</p>
            @endforelse
        </div>
    </div>
</div>
