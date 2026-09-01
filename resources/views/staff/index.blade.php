@extends('layouts.app')
@section('title', 'Staff Directory')
@section('subtitle', 'Manage CRM staff members and their roles.')

@section('topbar_actions')
    <a href="{{ route('admin.staff.create') }}" class="btn text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
        <i class="bi bi-person-plus me-1"></i> Add Staff
    </a>
@endsection

@section('content')

<div class="card mb-4 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
    <div class="card-header border-0 pb-0 pt-4 px-4">
        <h5 class="mb-0 fw-bold text-dark">All Staff Members</h5>
    </div>
    <div class="card-body px-4 pt-3 pb-4">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0 text-dark">
                <thead style="border-bottom: 1px solid var(--card-border);">
                    <tr>
                        <th class="text-muted small text-uppercase pb-3">Name</th>
                        <th class="text-muted small text-uppercase pb-3">Email</th>
                        <th class="text-muted small text-uppercase pb-3">Role</th>
                        <th class="text-muted small text-uppercase pb-3">Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <td class="py-3 fw-medium text-dark">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="background: linear-gradient(135deg, #0f172a, #334155); width: 32px; height: 32px; font-size: 14px;">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                {{ $member->name }}
                            </div>
                        </td>
                        <td class="py-3 text-dark">{{ $member->email }}</td>
                        <td class="py-3">
                            <span class="badge bg-secondary bg-opacity-10 text-dark fw-normal rounded-pill px-3">{{ ucfirst($member->role) }}</span>
                        </td>
                        <td class="py-3 text-dark">{{ $member->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No staff members found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $staff->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
