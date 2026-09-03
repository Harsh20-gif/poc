@extends('layouts.app')
@section('title', 'Edit Profile')
@section('subtitle', 'Manage your account settings and preferences')

@section('content')
<div class="row g-4 max-w-4xl mx-auto">
    <!-- Avatar Section -->
    <div class="col-12 col-md-4">
        <div class="card border-0 h-100" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-4 text-center">
                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4 d-flex justify-content-center">
                        @if($user->avatar_path)
                            <img src="{{ Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--bg-primary);">
                        @else
                            <div class="avatar-circle text-white shadow rounded-circle d-flex align-items-center justify-content-center mx-auto" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 120px; height: 120px; font-size: 48px; font-weight: bold; border: 4px solid var(--bg-primary);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label for="photo" class="form-label text-dark-custom fw-semibold small">Upload New Photo</label>
                        <input type="file" class="form-control form-control-sm @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" required>
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <button type="submit" class="btn btn-sm text-white w-100 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
                        <i class="bi bi-upload me-1"></i> Update Avatar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- Basic Info Form -->
            <div class="card border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="mb-0 fw-bold text-dark">Profile Information</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label text-dark-custom fw-semibold">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label text-dark-custom fw-semibold">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn text-white px-4 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="card border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="mb-0 fw-bold text-dark">Change Password</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label text-dark-custom fw-semibold">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label text-dark-custom fw-semibold">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-dark-custom fw-semibold">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark px-4 shadow-sm">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
