<!-- New Lead Modal -->
<div class="modal fade" id="newLeadModal" tabindex="-1" aria-labelledby="newLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form action="{{ route('leads.store') }}" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="newLeadModalLabel">Add New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @csrf
                <!-- Add a hidden input to let the controller know we want to redirect back, not to leads.index -->
                <input type="hidden" name="redirect_back" value="1">
                <div class="modal-body p-4 text-start">
                    <h5 class="mb-3 fw-bold text-primary border-bottom pb-2">Contact Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Contact Person *</label>
                            <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}" required>
                            @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Company Name</label>
                            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}">
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" required>
                            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Alternate Mobile</label>
                            <input type="text" name="alternate_mobile" class="form-control @error('alternate_mobile') is-invalid @enderror" value="{{ old('alternate_mobile') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">City</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                        </div>
                    </div>

                    <h5 class="mb-3 fw-bold text-primary border-bottom pb-2">Lead Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Source *</label>
                            <select name="source" id="lead_source_select" class="form-select @error('source') is-invalid @enderror" required onchange="toggleCustomSource(this)">
                                <option value="">Select source</option>
                                @foreach(['Facebook', 'Instagram', 'Google', 'WhatsApp', 'Other'] as $src)
                                    <option value="{{ $src }}" {{ old('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                                @endforeach
                            </select>
                            @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            
                            <div class="mt-2 {{ old('source') === 'Other' ? '' : 'd-none' }}" id="custom_source_container">
                                <label class="form-label fw-medium small">Please specify source *</label>
                                <input type="text" name="source_custom" id="source_custom" class="form-control @error('source_custom') is-invalid @enderror" value="{{ old('source_custom') }}">
                                @error('source_custom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Assigned Staff</label>
                            <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                <option value="">-- Unassigned --</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>{{ $member->name }} ({{ ucfirst($member->role) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <h5 class="mb-3 fw-bold text-primary border-bottom pb-2">Interested Services</h5>
                    <div class="row g-3 mb-4">
                        @foreach($allServices as $service)
                            <div class="col-md-4 col-sm-6">
                                <label class="service-check-wrapper d-flex align-items-center w-100">
                                    <input type="checkbox" name="services[]" value="{{ $service }}" class="form-check-input service-checkbox me-2" {{ (is_array(old('services')) && in_array($service, old('services'))) ? 'checked' : '' }}>
                                    <span>{{ $service }}</span>
                                </label>
                            </div>
                        @endforeach
                        
                        <div class="col-12 mt-3">
                            <label class="form-label fw-medium text-muted small mb-1">Add Custom Service (comma separated)</label>
                            <input type="text" name="custom_services" class="form-control form-control-sm border-secondary" placeholder="e.g. ISO 22000, Custom Audit">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create Lead</button>
                </div>
        </form>
    </div>
</div>

<script>
function toggleCustomSource(select) {
    const container = document.getElementById('custom_source_container');
    const input = document.getElementById('source_custom');
    if (select.value === 'Other') {
        container.classList.remove('d-none');
        input.setAttribute('required', 'required');
    } else {
        container.classList.add('d-none');
        input.removeAttribute('required');
        input.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    @if(old('redirect_back'))
        var newLeadModal = new bootstrap.Modal(document.getElementById('newLeadModal'));
        newLeadModal.show();
    @endif
});
</script>
