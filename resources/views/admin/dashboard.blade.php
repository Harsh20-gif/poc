@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar-nav')
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link active" href="{{ url('/admin/dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-people me-2"></i> Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-file-earmark-text me-2"></i> Content
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-gear me-2"></i> Settings
        </a>
    </li>
</ul>
@endsection

@section('content')
<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card stat-card h-100 p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Total Users</p>
                        <h3 class="fw-bold mb-0 text-dark-custom">1,248</h3>
                    </div>
                    <div class="icon-box bg-light-primary text-primary">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card h-100 p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Active Content</p>
                        <h3 class="fw-bold mb-0 text-dark-custom">342</h3>
                    </div>
                    <div class="icon-box bg-light-primary text-primary">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card h-100 p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Pending Reviews</p>
                        <h3 class="fw-bold mb-0 text-dark-custom">18</h3>
                    </div>
                    <div class="icon-box bg-light-primary text-primary">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-bold mb-0 text-dark-custom">Recent Content</h5>
            <button class="btn btn-sm btn-outline-primary fw-medium">View All</button>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless text-nowrap mb-0 align-middle">
                <thead>
                    <tr class="text-muted border-bottom">
                        <th class="fw-semibold pb-3">Title</th>
                        <th class="fw-semibold pb-3">Author</th>
                        <th class="fw-semibold pb-3">Status</th>
                        <th class="fw-semibold pb-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-bottom">
                        <td class="py-3"><span class="fw-medium text-dark-custom">Marketing Campaign Q4</span></td>
                        <td class="py-3 text-muted">Sarah Jenkins</td>
                        <td class="py-3"><span class="badge bg-warning text-dark rounded-pill px-3">Pending</span></td>
                        <td class="py-3 text-end"><a href="#" class="text-primary text-decoration-none">Review</a></td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-3"><span class="fw-medium text-dark-custom">Product Launch Specs</span></td>
                        <td class="py-3 text-muted">David Miller</td>
                        <td class="py-3"><span class="badge bg-success rounded-pill px-3">Approved</span></td>
                        <td class="py-3 text-end"><a href="#" class="text-primary text-decoration-none">View</a></td>
                    </tr>
                    <tr>
                        <td class="py-3"><span class="fw-medium text-dark-custom">Brand Guidelines 2025</span></td>
                        <td class="py-3 text-muted">Emily Chen</td>
                        <td class="py-3"><span class="badge bg-success rounded-pill px-3">Approved</span></td>
                        <td class="py-3 text-end"><a href="#" class="text-primary text-decoration-none">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
