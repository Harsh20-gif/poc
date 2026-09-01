@extends('layouts.app')
@section('title', 'Client Profile')
@section('subtitle', $client->company_name ?: $client->client_name)

@section('content')
<div class="row g-4">
    <!-- Left Column: Client Details & Certifications -->
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Client Overview</h6>
                @if(in_array(Auth::user()->role, ['admin', 'verifier']))
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateVerificationModal">Update Verification</button>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block">Client Name</small>
                        <span class="fw-medium">{{ $client->client_name }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block">Company</small>
                        <span>{{ $client->company_name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block">Deal Amount</small>
                        <span class="fw-bold text-success">₹{{ number_format($client->deal_amount, 2) }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block">Verification Status</small>
                        <span class="badge bg-{{ $client->verification_status === 'completed' ? 'success' : ($client->verification_status === 'pending' ? 'warning text-dark' : 'info text-dark') }}">
                            {{ ucfirst($client->verification_status) }}
                        </span>
                        @if($client->survey_date)
                            <small class="d-block text-muted mt-1">Survey: {{ $client->survey_date->format('M d, Y') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Certifications & Document History</h6>
                @if(in_array(Auth::user()->role, ['admin', 'sales', 'verifier']))
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#issueCertModal">Issue Certificate</button>
                @endif
            </div>
            <div class="card-body bg-light bg-opacity-50">
                @forelse($client->certifications as $cert)
                    <div class="card mb-3 border shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $cert->certificate_type }}</h5>
                                    <div class="text-muted small">{{ $cert->certificate_name }}</div>
                                </div>
                                @php
                                    $cBadge = match($cert->status) {
                                        'active' => 'bg-success',
                                        'expiring_soon' => 'bg-warning text-dark',
                                        'expired' => 'bg-danger',
                                        'renewal_triggered' => 'bg-secondary',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <div class="text-end">
                                    <span class="badge {{ $cBadge }} mb-2 d-block">{{ ucfirst(str_replace('_', ' ', $cert->status)) }}</span>
                                    @if($cert->certificate_pdf_path)
                                        <a href="{{ Storage::url($cert->certificate_pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-pdf"></i> View PDF</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3 small">
                                <div class="col-6"><strong>Issued:</strong> {{ $cert->issue_date->format('M d, Y') }}</div>
                                <div class="col-6"><strong>Expires:</strong> {{ $cert->expiry_date->format('M d, Y') }}</div>
                            </div>
                            
                            <h6 class="fw-bold small text-muted text-uppercase mb-2">Required Documents</h6>
                            <ul class="list-group list-group-flush border rounded">
                                @foreach($cert->documents as $doc)
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                        <div>
                                            <span class="fw-medium small">{{ $doc->document_type }}</span>
                                            @if($doc->file_path)
                                                <br><a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1 py-0 px-2" style="font-size: 11px;"><i class="bi bi-eye"></i> View</a>
                                            @endif
                                        </div>
                                        <div>
                                            @if($doc->file_path)
                                                <span class="badge bg-{{ $doc->verification_status === 'verified' ? 'success' : ($doc->verification_status === 'rejected' ? 'danger' : 'warning text-dark') }}">{{ ucfirst($doc->verification_status) }}</span>
                                            @else
                                                <span class="badge bg-danger">Missing</span>
                                                <button class="btn btn-sm btn-outline-primary ms-2 py-0 px-2" style="font-size: 11px;" onclick="openPlaceholderUploadModal({{ $doc->id }}, '{{ $doc->document_type }}')">Upload</button>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-3 text-muted">No certificates issued yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Documents -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Client Documents</h6>
            </div>
            <div class="card-body bg-light bg-opacity-50">
                <form action="{{ route('documents.store', $client) }}" method="POST" enctype="multipart/form-data" class="mb-4 bg-white p-3 border rounded shadow-sm">
                    @csrf
                    <h6 class="fw-bold mb-3 small text-muted text-uppercase">Upload New Document</h6>
                    <div class="mb-2">
                        <select name="document_type" class="form-select form-select-sm" required>
                            <option value="">Select Document Type</option>
                            <option value="GST Certificate">GST Certificate</option>
                            <option value="Pan Card">Pan Card</option>
                            <option value="Aadhar Card">Aadhar Card</option>
                            <option value="Utility Bill">Utility Bill</option>
                            <option value="Incorporation Certificate">Incorporation Certificate</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="document_file" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">Upload Document</button>
                </form>

                <div class="d-flex flex-column gap-3">
                    @forelse($client->documents as $doc)
                        <div class="bg-white p-3 border rounded shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $doc->document_type }}</h6>
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1 py-0 px-2" style="font-size: 12px;"><i class="bi bi-eye"></i> View</a>
                                    @if($doc->original_filename)
                                        <span class="small text-muted ms-2">{{ $doc->original_filename }}</span>
                                    @endif
                                </div>
                                <span class="badge bg-{{ $doc->verification_status === 'verified' ? 'success' : ($doc->verification_status === 'rejected' ? 'danger' : 'warning text-dark') }}">{{ ucfirst($doc->verification_status) }}</span>
                            </div>
                            
                            @if($doc->verification_status === 'rejected')
                                <div class="alert alert-danger p-2 small mt-2 mb-0">Reason: {{ $doc->rejection_reason }}</div>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin', 'verifier']) && $doc->verification_status === 'pending')
                                <div class="d-flex gap-2 mt-3 pt-2 border-top">
                                    <form action="{{ route('documents.verify', $doc) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button class="btn btn-sm btn-success w-100">Verify</button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-danger flex-fill" data-bs-toggle="modal" data-bs-target="#rejectDocModal{{ $doc->id }}">Reject</button>
                                </div>
                                
                                <!-- Reject Doc Modal -->
                                <div class="modal fade" id="rejectDocModal{{ $doc->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Reject Document</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('documents.reject', $doc) }}" method="POST">
                                                @csrf
                                                <div class="modal-body p-3">
                                                    <label class="form-label">Reason for rejection *</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-muted small py-3">No documents uploaded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Client Interaction Timeline -->
        <div class="mt-4">
            @include('partials.interaction-timeline', [
                'interactions' => $client->lead->interactions ?? collect(),
                'showForm' => true,
                'submitUrl' => route('clients.interactions.store', $client)
            ])
        </div>
    </div>
</div>

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
                        <select name="certificate_type" class="form-select" required>
                            <option value="">Select Type</option>
                            @foreach(['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 27001', 'CE Marking', 'BIS Certification', 'FSSAI', 'GMP', 'Hallmark'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
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
