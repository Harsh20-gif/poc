@extends('layouts.app')
@section('title', 'Edit Client - ' . ($client->company_name ?? $client->client_name))
@section('subtitle', 'Update client information.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-dark">Client Details</h5>
            </div>
            
            <form action="{{ route('clients.update', $client) }}" method="POST" id="editClientForm">
                @csrf
                @method('PUT')
                <div class="card-body px-4 py-4">


                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label fw-medium text-dark">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $client->company_name) }}" required>
                            @error('company_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="owner" class="form-label fw-medium text-dark">Owner <span class="text-danger">*</span></label>
                            <input type="text" id="owner" name="owner" class="form-control @error('owner') is-invalid @enderror" value="{{ old('owner', $client->owner) }}" required>
                            @error('owner')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label fw-medium text-dark">Address</label>
                            <textarea id="address" name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $client->address) }}</textarea>
                            @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label fw-medium text-dark">City</label>
                            <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $client->city) }}">
                            @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label fw-medium text-dark">State</label>
                            <input type="text" id="state" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $client->state) }}">
                            @error('state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip" class="form-label fw-medium text-dark">Zip</label>
                            <input type="text" id="zip" name="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip', $client->zip) }}">
                            @error('zip')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label fw-medium text-dark">Country</label>
                            <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $client->country) }}">
                            @error('country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label fw-medium text-dark">Phone <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $client->phone) }}" required>
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="website" class="form-label fw-medium text-dark">Website</label>
                            <input type="url" id="website" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $client->website) }}">
                            @error('website')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vat_number" class="form-label fw-medium text-dark">VAT Number</label>
                            <input type="text" id="vat_number" name="vat_number" class="form-control @error('vat_number') is-invalid @enderror" value="{{ old('vat_number', $client->vat_number) }}">
                            @error('vat_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="client_group" class="form-label fw-medium text-dark">Client Group</label>
                            <select id="client_group" name="client_group" class="form-select @error('client_group') is-invalid @enderror">
                                <option value="">Select group</option>
                                <option value="Retail" {{ old('client_group', $client->client_group) === 'Retail' ? 'selected' : '' }}>Retail</option>
                                <option value="Corporate" {{ old('client_group', $client->client_group) === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                <option value="Government" {{ old('client_group', $client->client_group) === 'Government' ? 'selected' : '' }}>Government</option>
                            </select>
                            @error('client_group')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="currency" class="form-label fw-medium text-dark">Currency</label>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror">
                                <option value="INR" {{ old('currency', $client->currency) === 'INR' ? 'selected' : '' }}>INR</option>
                                <option value="USD" {{ old('currency', $client->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $client->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency', $client->currency) === 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                            @error('currency')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="currency_symbol" class="form-label fw-medium text-dark">Currency Symbol</label>
                            <input type="text" id="currency_symbol" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" value="{{ old('currency_symbol', $client->currency_symbol ?? '₹') }}">
                            @error('currency_symbol')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-end">
                    <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary text-dark border-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4" id="editClientSubmitBtn">
                        <span class="btn-text">Save Changes</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const currencySelect = document.getElementById('currency');
        const currencySymbolInput = document.getElementById('currency_symbol');
        const clientForm = document.getElementById('editClientForm');
        const submitBtn = document.getElementById('editClientSubmitBtn');

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
