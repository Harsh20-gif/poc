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

        <div class="timeline">
            @forelse($interactions as $interaction)
                <div class="timeline-item mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark">{{ $interaction->user->name ?? 'System' }}</span>
                        <small class="text-muted">{{ $interaction->created_at->format('M d, Y h:ia') }}</small>
                    </div>
                    <div class="bg-light p-3 rounded-3 text-dark mb-2 border border-secondary border-opacity-25">
                        {{ $interaction->remark }}
                    </div>
                    @if($interaction->next_follow_up_date)
                    <small class="fw-medium" style="color: #0284c7;"><i class="bi bi-calendar-event me-1"></i> Follow-up scheduled for {{ \Carbon\Carbon::parse($interaction->next_follow_up_date)->format('M d, Y') }}</small>
                    @endif
                </div>
            @empty
                <p class="text-muted text-center py-4">No interactions logged yet.</p>
            @endforelse
        </div>
    </div>
</div>
