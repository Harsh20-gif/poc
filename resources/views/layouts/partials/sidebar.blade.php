<!-- Sidebar (Desktop) -->
<aside class="sidebar sidebar-desktop d-none d-md-flex flex-column">
    <div class="logo-area d-flex align-items-center">
        <div class="badge bg-primary fs-6 me-2 p-2 rounded-3 shadow-sm">PoC</div>
        <h5 class="mb-0 fw-bold text-white">Proof of Content</h5>
    </div>
    <div class="flex-grow-1 px-3 py-3">
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        
        @if(in_array(Auth::user()->role, ['admin', 'sales']))
        <a href="{{ route('leads.index') }}" class="nav-link">
            <i class="bi bi-funnel me-2"></i> Leads
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'sales', 'verifier']))
        <a href="{{ route('clients.index') }}" class="nav-link">
            <i class="bi bi-building me-2"></i> Clients
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'sales']))
        <a href="{{ route('renewals.index') }}" class="nav-link">
            <i class="bi bi-arrow-repeat me-2"></i> Renewals
        </a>
        @endif
    </div>
    <div class="px-3 pb-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link text-danger logout-link border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- Offcanvas Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start sidebar-mobile bg-dark" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header logo-area">
        <div class="d-flex align-items-center">
            <div class="badge bg-primary fs-6 me-2 p-2 rounded-3 shadow-sm">PoC</div>
            <h5 class="mb-0 fw-bold text-white">Proof of Content</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div class="flex-grow-1 px-3 py-3">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            
            @if(in_array(Auth::user()->role, ['admin', 'sales']))
            <a href="{{ route('leads.index') }}" class="nav-link">
                <i class="bi bi-funnel me-2"></i> Leads
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'sales', 'verifier']))
            <a href="{{ route('clients.index') }}" class="nav-link">
                <i class="bi bi-building me-2"></i> Clients
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'sales']))
            <a href="{{ route('renewals.index') }}" class="nav-link">
                <i class="bi bi-arrow-repeat me-2"></i> Renewals
            </a>
            @endif
        </div>
        <div class="px-3 pb-4 pt-3 border-top border-secondary opacity-75">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-danger logout-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
