@extends('layouts.app')

@section('title', 'User Dashboard')

@section('sidebar-nav')
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link active" href="{{ url('/user/dashboard') }}">
            <i class="bi bi-house me-2"></i> Home
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-file-earmark-plus me-2"></i> My Content
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-person me-2"></i> Profile
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="card p-2 border-0 shadow-sm">
    <div class="card-body py-5 text-center">
        <div class="icon-box bg-light-primary text-primary mx-auto mb-4" style="width: 72px; height: 72px;">
            <i class="bi bi-file-earmark-richtext fs-1"></i>
        </div>
        <h3 class="fw-bold mb-3 text-dark-custom">Welcome to Proof of Content</h3>
        <p class="text-muted mb-4 max-w-md mx-auto fs-5">This is your personal workspace. You can start creating and managing your content from here.</p>
        <button class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Create New Content</button>
    </div>
</div>
@endsection
