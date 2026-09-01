<header class="topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-light d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div>
            <h5 class="mb-0 fw-bold text-dark-custom">@yield('title', 'Dashboard')</h5>
            @hasSection('subtitle')
            <small class="text-muted">@yield('subtitle')</small>
            @endif
        </div>
    </div>
    <div class="d-flex align-items-center">
        <span class="badge bg-secondary me-3">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
        <span class="text-muted d-none d-sm-inline-block me-3 fw-medium">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
        <div class="avatar-circle bg-primary text-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>
