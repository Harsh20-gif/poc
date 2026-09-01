@extends('layouts.app')
@section('title', 'Add Staff Member')
@section('subtitle', 'Create a new user account for staff')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card mb-4 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-header border-0 pb-0 pt-4 px-4">
                <h5 class="mb-0 fw-bold text-dark">Staff Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.staff.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Full Name *</label>
                        <input type="text" name="name" class="form-control text-dark border-secondary @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Email Address *</label>
                        <input type="email" name="email" class="form-control text-dark border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Role *</label>
                        <select name="role" class="form-select text-dark border-secondary @error('role') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="sales" {{ old('role') == 'sales' ? 'selected' : '' }}>Sales</option>
                            <option value="verifier" {{ old('role') == 'verifier' ? 'selected' : '' }}>Verifier</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Password *</label>
                        <input type="password" name="password" class="form-control text-dark border-secondary @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control text-dark border-secondary" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top border-secondary border-opacity-25">
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary text-dark border-secondary">Cancel</a>
                        <button type="submit" class="btn text-white px-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">Create Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
