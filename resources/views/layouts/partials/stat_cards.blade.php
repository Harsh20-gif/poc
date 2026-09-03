<!-- Stat Cards Row -->
<div class="row g-4 mb-4">
    <!-- Total Leads -->
    <div class="col-md-4 col-lg-2">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); box-shadow: var(--card-shadow); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Total Leads</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 32px; height: 32px;">
                        <i class="bi bi-funnel-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ number_format($stats['total_leads'] ?? 0) }}</h3>
            </div>
        </div>
    </div>
    
    <!-- Pipeline Value -->
    <div class="col-md-4 col-lg-2">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); box-shadow: var(--card-shadow); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Pipeline</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(37, 99, 235, 0.1); color: #2563EB;">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">${{ number_format(($stats['pipeline_value'] ?? 0) / 1000, 1) }}k</h3>
            </div>
        </div>
    </div>

    <!-- Total Clients -->
    <div class="col-md-4 col-lg-2">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); box-shadow: var(--card-shadow); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Clients</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(37, 99, 235, 0.1); color: #2563EB;">
                        <i class="bi bi-buildings fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ number_format($stats['total_clients'] ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Active Certificates -->
    <div class="col-md-4 col-lg-2">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); box-shadow: var(--card-shadow); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Active Certs</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(37, 99, 235, 0.1); color: #2563EB;">
                        <i class="bi bi-patch-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ number_format($stats['active_certificates'] ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Completed Projects -->
    <div class="col-md-4 col-lg-2">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); box-shadow: var(--card-shadow); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Completed</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(20, 184, 166, 0.1); color: #14B8A6;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ number_format($stats['completed_projects'] ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Staff Members -->
    <div class="col-md-4 col-lg-2">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); box-shadow: var(--card-shadow); border: 1px solid var(--card-border) !important;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Staff</span>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(100, 116, 139, 0.1); color: #64748B;">
                        <i class="bi bi-people fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-2 text-dark">{{ number_format($stats['staff_members'] ?? 0) }}</h3>
            </div>
        </div>
    </div>
</div>
