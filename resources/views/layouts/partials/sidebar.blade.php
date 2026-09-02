<!-- Sidebar (Desktop) -->
<aside class="sidebar sidebar-desktop d-none d-md-flex flex-column">
    <div class="logo-area d-flex align-items-center" style="border-bottom: 1px solid var(--card-border); cursor: pointer;" onclick="document.body.classList.toggle('sidebar-collapsed')">
        <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="sidebar-logo shadow-sm" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
        <h5 class="mb-0 fw-bold text-dark ms-2">Proof of Content</h5>
    </div>
    <div class="flex-grow-1 px-3 py-3 d-flex flex-column">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
        </a>
        
        @if(in_array(Auth::user()->role, ['admin', 'sales']))
        <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
            <i class="bi bi-funnel me-2"></i> <span>Leads</span>
        </a>
        <a href="{{ route('history') }}" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> <span>History</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'sales', 'verifier']))
        <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i> <span>Clients</span>
        </a>
        <a href="{{ route('certifications.index') }}" class="nav-link {{ request()->routeIs('certifications.*') ? 'active' : '' }}">
            <i class="bi bi-award me-2"></i> <span>Certificates</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'sales']))
        <a href="{{ route('renewals.index') }}" class="nav-link {{ request()->routeIs('renewals.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat me-2"></i> <span>Renewals</span>
        </a>
        @endif
        
        <div class="mt-auto pb-2 border-top border-secondary border-opacity-10 pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-muted logout-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-door-open me-2"></i> <span class="logout-text">Reset Session</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Offcanvas Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start sidebar-mobile" tabindex="-1" id="mobileSidebar" style="background-color: var(--card-bg) !important;">
    <div class="offcanvas-header logo-area" style="border-bottom: 1px solid var(--card-border);">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="sidebar-logo shadow-sm" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
            <h5 class="mb-0 fw-bold text-dark ms-2">Proof of Content</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div class="flex-grow-1 px-3 py-3 d-flex flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            
            @if(in_array(Auth::user()->role, ['admin', 'sales']))
            <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                <i class="bi bi-funnel me-2"></i> Leads
            </a>
            <a href="{{ route('history') }}" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
                <i class="bi bi-clock-history me-2"></i> History
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'sales', 'verifier']))
            <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i> Clients
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'sales']))
            <a href="{{ route('renewals.index') }}" class="nav-link {{ request()->routeIs('renewals.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat me-2"></i> Renewals
            </a>
            @endif
            
            <div class="mt-auto pb-2 border-top border-secondary border-opacity-10 pt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-muted logout-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-door-open me-2"></i> Reset Session
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
