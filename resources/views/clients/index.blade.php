@extends('layouts.app')
@section('title', 'Clients Directory')
@section('subtitle', 'Manage all certified clients.')

@section('topbar_actions')
    <button class="btn btn-outline-secondary text-dark border-secondary me-2" onclick="window.location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
    </button>
    <button type="button" class="btn text-white border-0 shadow-sm me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8);" data-bs-toggle="modal" data-bs-target="#newLeadModal">
        <i class="bi bi-plus-lg me-1"></i> New Lead
    </button>
    <button type="button" class="btn btn-primary shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#newClientModal">
        <i class="bi bi-person-plus-fill me-1"></i> New Client
    </button>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-outline-secondary text-dark border-secondary"><i class="bi bi-person-plus"></i> Add Staff</a>
@endsection

@section('content')



@include('layouts.partials.stat_cards')

<div class="card mb-4 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
    <div class="card-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Certified CRM Clients</h5>
    </div>
    <div class="card-body px-4 pt-3 pb-4">
        <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-center mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control text-dark border-secondary rounded-pill" placeholder="Search Company or Client..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select border-secondary rounded-pill text-dark" onchange="this.form.submit()">
                    <option value="All" {{ request('status') === 'All' ? 'selected' : '' }}>All Status</option>
                    <option value="Verified" {{ request('status') === 'Verified' ? 'selected' : '' }}>Verified</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Survey Scheduled" {{ request('status') === 'Survey Scheduled' ? 'selected' : '' }}>Survey Scheduled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="client_group" class="form-select border-secondary rounded-pill text-dark" onchange="this.form.submit()">
                    <option value="All Groups" {{ request('client_group') === 'All Groups' ? 'selected' : '' }}>All Groups</option>
                    @foreach($clientGroups as $group)
                        <option value="{{ $group }}" {{ request('client_group') === $group ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role === 'admin')
            <div class="col-md-2">
                <select name="assigned_to" class="form-select border-secondary rounded-pill text-dark" onchange="this.form.submit()">
                    <option value="All" {{ request('assigned_to') === 'All' ? 'selected' : '' }}>All Staff</option>
                    @foreach($staff as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-auto ms-auto d-flex gap-2">
                <button type="submit" class="btn btn-secondary rounded-pill px-4">Filter</button>
                <a href="{{ route('clients.export', request()->all()) }}" class="btn btn-success rounded-pill px-4 text-white">
                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                </a>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0 text-dark">
                <thead style="border-bottom: 1px solid var(--card-border);">
                    <tr>
                        <th class="text-muted small text-uppercase pb-3">ID</th>
                        <th class="text-muted small text-uppercase pb-3">Company Name</th>
                        <th class="text-muted small text-uppercase pb-3">Contact Person</th>
                        <th class="text-muted small text-uppercase pb-3">Client Group</th>
                        <th class="text-muted small text-uppercase pb-3">Services</th>
                        <th class="text-muted small text-uppercase pb-3">Deal Amount</th>
                        <th class="text-muted small text-uppercase pb-3">Verification Status</th>
                        <th class="text-muted small text-uppercase pb-3">Active Certificates</th>
                        <th class="text-muted small text-uppercase pb-3">Assigned Staff</th>
                        <th class="text-muted small text-uppercase pb-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <td class="py-3 text-dark">#{{ $client->id }}</td>
                        <td class="py-3 fw-medium text-dark">
                            <a href="{{ route('clients.show', $client) }}" class="text-decoration-none text-primary">{{ $client->company_name ?? '—' }}</a>
                        </td>
                        <td class="py-3 text-dark">{{ $client->client_name }}</td>
                        <td class="py-3">
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">{{ $client->client_group ?? '—' }}</span>
                        </td>
                        <td class="py-3">
                            @php
                                $services = is_string($client->finalized_services) ? json_decode($client->finalized_services, true) : $client->finalized_services;
                            @endphp
                            @if(is_array($services) && count($services) > 0)
                                <span class="badge bg-secondary bg-opacity-10 text-dark fw-normal rounded-pill px-3">{{ $services[0] }}</span>
                                @if(count($services) > 1)
                                    <span class="badge bg-secondary bg-opacity-10 text-muted fw-normal rounded-pill ms-1">+{{ count($services) - 1 }} more</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-dark">
                            {{ $client->currency_symbol }}{{ number_format($client->deal_amount, 2) }}
                        </td>
                        <td class="py-3">
                            @if($client->verification_status === 'pending')
                                <span class="badge rounded-pill bg-secondary text-white">Pending</span>
                            @elseif($client->verification_status === 'in_progress')
                                <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">Survey Scheduled</span>
                            @elseif($client->verification_status === 'completed')
                                <span class="badge rounded-pill bg-primary text-white">Verified</span>
                            @else
                                <span class="badge rounded-pill bg-light text-dark">{{ ucfirst($client->verification_status) }}</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($client->active_certificates > 0)
                                <a href="{{ route('clients.show', $client) }}#certifications" class="badge rounded-pill bg-secondary text-white text-decoration-none px-3">
                                    {{ $client->active_certificates }}
                                </a>
                            @else
                                <span class="text-muted small">0</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($client->lead?->assignee)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 24px; height: 24px; font-size: 10px;">
                                        {{ strtoupper(substr($client->lead->assignee->name, 0, 1)) }}
                                    </div>
                                    <span class="text-dark small">{{ $client->lead->assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-end">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-secondary text-dark border-secondary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">No converted clients yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="newClientModal" tabindex="-1" aria-labelledby="newClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-2">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="newClientModalLabel">New Client</h5>
                    <p class="mb-0 text-muted small">Add a client directly without converting a lead.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST" id="newClientForm">
                @csrf
                <div class="modal-body px-4 pb-4">


                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label fw-medium text-dark">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required>
                            @error('company_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="owner" class="form-label fw-medium text-dark">Owner <span class="text-danger">*</span></label>
                            <input type="text" id="owner" name="owner" class="form-control @error('owner') is-invalid @enderror" value="{{ old('owner') }}" required>
                            @error('owner')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label fw-medium text-dark">Address</label>
                            <textarea id="address" name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label fw-medium text-dark">City</label>
                            <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                            @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label fw-medium text-dark">State</label>
                            <input type="text" id="state" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                            @error('state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip" class="form-label fw-medium text-dark">Zip</label>
                            <input type="text" id="zip" name="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip') }}">
                            @error('zip')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label fw-medium text-dark">Country</label>
                            <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}">
                            @error('country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label fw-medium text-dark">Phone <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="website" class="form-label fw-medium text-dark">Website</label>
                            <input type="url" id="website" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website') }}">
                            @error('website')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vat_number" class="form-label fw-medium text-dark">VAT Number</label>
                            <input type="text" id="vat_number" name="vat_number" class="form-control @error('vat_number') is-invalid @enderror" value="{{ old('vat_number') }}">
                            @error('vat_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="client_group" class="form-label fw-medium text-dark">Client Group</label>
                            <input list="client_groups_list" id="client_group" name="client_group" class="form-control @error('client_group') is-invalid @enderror" value="{{ old('client_group') }}" placeholder="Type or select group">
                            <datalist id="client_groups_list">
                                @foreach($clientGroups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                            </datalist>
                            @error('client_group')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="currency" class="form-label fw-medium text-dark">Currency</label>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror">
                                <option value="INR" {{ old('currency', 'INR') === 'INR' ? 'selected' : '' }}>INR</option>
                                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                            @error('currency')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="currency_symbol" class="form-label fw-medium text-dark">Currency Symbol</label>
                            <input type="text" id="currency_symbol" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" value="{{ old('currency_symbol', '₹') }}">
                            @error('currency_symbol')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary text-dark border-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="newClientSubmitBtn">
                        <span class="btn-text">Save Client</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('newClientModal');
        const currencySelect = document.getElementById('currency');
        const currencySymbolInput = document.getElementById('currency_symbol');
        const clientForm = document.getElementById('newClientForm');
        const submitBtn = document.getElementById('newClientSubmitBtn');

        const currencyMap = {
            INR: '₹',
            USD: '$',
            EUR: '€',
            GBP: '£'
        };

        function syncCurrencySymbol() {
            const selectedCurrency = currencySelect.value;
            currencySymbolInput.value = currencyMap[selectedCurrency] || currencySymbolInput.value || '₹';
        }

        if (currencySelect && currencySymbolInput) {
            currencySelect.addEventListener('change', syncCurrencySymbol);
            syncCurrencySymbol();
        }

        if (modalElement && {{ $errors->any() || session('client_modal') ? 'true' : 'false' }}) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        if (clientForm && submitBtn) {
            clientForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                const textNode = submitBtn.querySelector('.btn-text');
                const spinner = submitBtn.querySelector('.spinner-border');
                if (textNode) textNode.textContent = 'Saving...';
                if (spinner) spinner.classList.remove('d-none');
            });
        }
    });
</script>
@endsection
