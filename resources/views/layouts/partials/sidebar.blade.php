<!-- Sidebar (Desktop) -->
<aside class="sidebar sidebar-desktop d-none d-md-flex flex-column">
    <div class="logo-area d-flex align-items-center">
        <div class="badge fs-6 me-2 p-2 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #6366F1, #8B5CF6); color: white; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">C</div>
        <h5 class="mb-0 fw-bold text-white">Proof of Content</h5>
    </div>
    <div class="flex-grow-1 px-3 py-3 d-flex flex-column">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        
        @if(in_array(Auth::user()->role, ['admin', 'sales']))
        <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
            <i class="bi bi-funnel me-2"></i> Leads
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
        
        <div class="mt-auto pb-2 border-top border-secondary border-opacity-25 pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-muted logout-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-door-open me-2"></i> Reset Session
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Offcanvas Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start sidebar-mobile bg-dark" tabindex="-1" id="mobileSidebar" style="background-color: var(--dark) !important;">
    <div class="offcanvas-header logo-area">
        <div class="d-flex align-items-center">
            <div class="badge fs-6 me-2 p-2 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #6366F1, #8B5CF6); color: white; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">C</div>
            <h5 class="mb-0 fw-bold text-white">Proof of Content</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
            
            <div class="mt-auto pb-2 border-top border-secondary border-opacity-25 pt-3">
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
