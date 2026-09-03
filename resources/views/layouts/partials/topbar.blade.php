<header class="topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-secondary d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        @php
            $backMap = [
                'leads.show' => 'leads.index',
                'leads.create' => 'leads.index',
                'leads.edit' => 'leads.index',
                'leads.import.form' => 'leads.index',
                'clients.show' => 'clients.index',
                'clients.edit' => 'clients.index',
                'admin.staff.create' => 'admin.staff.index',
            ];
            $currentRoute = request()->route() ? request()->route()->getName() : '';
        @endphp

        @if(array_key_exists($currentRoute, $backMap))
            <a href="{{ route($backMap[$currentRoute]) }}" class="btn btn-sm btn-light border shadow-sm me-3" title="Go Back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        @endif
        <div>
            <h4 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h4>
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
        
        <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 me-3">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
        <span class="text-muted d-none d-sm-inline-block me-3 fw-medium">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                @if(auth()->user()->avatar_path)
                    <img src="{{ Storage::url(auth()->user()->avatar_path) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 35px; height: 35px; object-fit: cover;">
                @else
                    <div class="avatar-circle text-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 35px; height: 35px; font-weight: bold;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="profileDropdown" style="min-width: 250px;">
                <li class="px-3 py-2 text-center border-bottom mb-2">
                    <div class="mb-2 d-flex justify-content-center">
                        @if(auth()->user()->avatar_path)
                            <img src="{{ Storage::url(auth()->user()->avatar_path) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="avatar-circle text-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mx-auto" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 60px; height: 60px; font-weight: bold; font-size: 24px;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                    <div class="small text-muted mb-2">{{ auth()->user()->email }}</div>
                    <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
                </li>
                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Edit Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
