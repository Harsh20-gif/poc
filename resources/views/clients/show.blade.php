@extends('layouts.app')
@section('title', 'Client Details')
@section('subtitle', $client->company_name ?: $client->client_name)

@section('content')

<!-- Header Stat Cards -->
<div class="row g-4 mb-4">
    <!-- Deal Amount -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Deal Amount</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(37, 99, 235, 0.1); color: #2563EB;">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ $client->currency_symbol }}{{ number_format($client->deal_amount, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Active Certificates -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Active Certs</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(37, 99, 235, 0.1); color: #2563EB;">
                        <i class="bi bi-award fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ $client->certifications->where('status', 'active')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Verified Documents -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Verified Docs</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(37, 99, 235, 0.1); color: #2563EB;">
                        <i class="bi bi-check-circle fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ $client->documents->where('verification_status', 'verified')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Documents -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important; cursor: pointer;" onclick="document.querySelector('#documents-tab').click()">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Pending Docs</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(100, 116, 139, 0.1); color: #64748B;">
                        <i class="bi bi-list-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ $client->documents->where('verification_status', 'pending')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<ul class="nav nav-tabs mb-4" id="clientTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-medium" id="client-info-tab" data-bs-toggle="tab" data-bs-target="#client-info" type="button" role="tab" aria-controls="client-info" aria-selected="true">Client Info</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-medium" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Documents</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-medium" id="certifications-tab" data-bs-toggle="tab" data-bs-target="#certifications" type="button" role="tab" aria-controls="certifications" aria-selected="false">Certifications</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-medium" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">Communication Timeline</button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="clientTabsContent">
    <div class="tab-pane fade show active" id="client-info" role="tabpanel" aria-labelledby="client-info-tab">
        @include('clients.tabs.client-info')
    </div>
    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
        @include('clients.tabs.documents')
    </div>
    <div class="tab-pane fade" id="certifications" role="tabpanel" aria-labelledby="certifications-tab">
        @include('clients.tabs.certifications')
    </div>
    <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
        @include('clients.tabs.timeline')
    </div>
</div>

<!-- Modals -->
@if(in_array(Auth::user()->role, ['admin', 'verifier']))
<!-- Update Verification Modal -->
<div class="modal fade" id="updateVerificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Update Client Verification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('clients.update_status', $client) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Verification Status</label>
                        <select name="verification_status" class="form-select" required>
                            <option value="pending" {{ $client->verification_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="scheduled" {{ $client->verification_status === 'scheduled' ? 'selected' : '' }}>Scheduled for Survey</option>
                            <option value="completed" {{ $client->verification_status === 'completed' ? 'selected' : '' }}>Completed / Approved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Survey Date (Optional)</label>
                        <input type="date" name="survey_date" class="form-control" value="{{ $client->survey_date ? $client->survey_date->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(in_array(Auth::user()->role, ['admin', 'sales', 'verifier']))
<!-- Issue Cert Modal -->
<div class="modal fade" id="issueCertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Issue Certification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('certifications.store', $client) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Certificate Type *</label>
                        <input list="certificate_types_list" name="certificate_type" class="form-control" placeholder="Select or type type" required>
                        <datalist id="certificate_types_list">
                            @foreach($certificateTypes as $type)
                                <option value="{{ $type }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specific Name / Standard *</label>
                        <input type="text" name="certificate_name" class="form-control" placeholder="e.g. ISO 9001:2015" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Issue Date *</label>
                            <input type="date" name="issue_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Expiry Date *</label>
                            <input type="date" name="expiry_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Certificate PDF (Optional)</label>
                        <input type="file" name="certificate_file" class="form-control" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Issue Certificate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Placeholder Upload Modal -->
<div class="modal fade" id="placeholderUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Upload Required Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="placeholderUploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Document Type</label>
                        <input type="text" id="placeholderDocType" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File *</label>
                        <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">Max size: 5MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPlaceholderUploadModal(docId, docType) {
        document.getElementById('placeholderDocType').value = docType;
        document.getElementById('placeholderUploadForm').action = "/documents/" + docId;
        var modal = new bootstrap.Modal(document.getElementById('placeholderUploadModal'));
        modal.show();
    }
</script>

@endsection
