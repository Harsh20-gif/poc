<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold">Communication Timeline</h6>
    </div>
    <div class="card-body">
        @include('partials.interaction-timeline', [
            'interactions' => $client->lead->interactions ?? collect(),
            'showForm' => true,
            'submitUrl' => route('clients.interactions.store', $client)
        ])
    </div>
</div>
