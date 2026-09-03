<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
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
                            $statusClass = match($cert->status) {
                                'active' => 'bg-primary',
                                'expiring_soon' => 'text-danger border border-danger border-opacity-50" style="background-color: var(--brand-danger-light);',
                                'expired' => 'bg-danger text-white',
                                'renewal_triggered' => 'bg-secondary',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <div class="text-end">
                            <span class="badge {{ $statusClass }} mb-2 d-block">{{ ucfirst(str_replace('_', ' ', $cert->status)) }}</span>
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
