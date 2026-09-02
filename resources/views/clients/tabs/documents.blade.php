<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold">Client Documents</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('documents.store', $client) }}" method="POST" enctype="multipart/form-data" class="mb-4 bg-light p-3 border rounded">
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
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $doc->document_type }}</h6>
                            <div class="mt-2">
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 12px;"><i class="bi bi-eye"></i> View</a>
                                @if($doc->original_filename)
                                    <span class="small text-muted ms-2">{{ $doc->original_filename }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2">
                            <span class="badge bg-{{ $doc->verification_status === 'verified' ? 'success' : ($doc->verification_status === 'rejected' ? 'danger' : 'warning text-dark') }}">{{ ucfirst($doc->verification_status) }}</span>
                            
                            @if(in_array(Auth::user()->role, ['admin', 'verifier']) && $doc->verification_status === 'pending')
                                <div class="d-flex gap-2">
                                    <form action="{{ route('documents.verify', $doc) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success px-3 py-0" style="font-size: 12px;">Verify</button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-danger px-3 py-0" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#rejectDocModal{{ $doc->id }}">Reject</button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($doc->verification_status === 'rejected')
                        <div class="alert alert-danger p-2 small mt-2 mb-0">Reason: {{ $doc->rejection_reason }}</div>
                    @endif
                        
                        <!-- Reject Doc Modal -->
                    @if(in_array(Auth::user()->role, ['admin', 'verifier']) && $doc->verification_status === 'pending')
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
