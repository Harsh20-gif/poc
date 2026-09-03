@extends('layouts.app')
@section('title', 'Add New Lead')
@section('subtitle', 'Manually enter a new lead into the CRM')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('leads.store') }}" method="POST">
                    @csrf
                    
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
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" required>
                            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                            <input list="sources_list" name="source" class="form-control @error('source') is-invalid @enderror" value="{{ old('source') }}" placeholder="Select or type source" required>
                            <datalist id="sources_list">
                                @foreach($sources as $src)
                                    <option value="{{ $src }}">
                                @endforeach
                            </datalist>
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

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('leads.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
