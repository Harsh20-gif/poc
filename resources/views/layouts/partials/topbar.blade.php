<header class="topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-secondary d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div>
            <h4 class="mb-0 fw-bold text-white">@yield('title', 'Dashboard')</h4>
            @hasSection('subtitle')
            <div class="text-muted small mt-1">@yield('subtitle')</div>
            @endif
        </div>
    </div>
    <div class="d-flex align-items-center">
        @hasSection('topbar_actions')
            <div class="me-4 d-none d-lg-flex">
                @yield('topbar_actions')
            </div>
        @endif
        
        <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-50 me-3">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
        <span class="text-muted d-none d-sm-inline-block me-3 fw-medium">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
        <div class="avatar-circle text-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6366F1, #8B5CF6); width: 35px; height: 35px; font-weight: bold;">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>
