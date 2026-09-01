@extends('layouts.app')
@section('title', 'Import Leads')
@section('subtitle', 'Upload a CSV file to bulk import leads')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Upload CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        <div class="form-text mt-2">
                            Please ensure your CSV has the following columns in order (with headers):<br>
                            <code>contact_person, company_name, mobile, alternate_mobile, email, city, source, services</code>
                            <br><br>
                            <em>Notes:</em>
                            <ul class="mb-0 text-muted">
                                <li><code>contact_person</code> and <code>mobile</code> are required.</li>
                                <li><code>services</code> should be pipe-separated, e.g., <code>ISO 9001|BIS Certification</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('leads.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-upload me-2"></i> Import Leads</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
