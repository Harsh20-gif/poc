<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    
    @if (session('success'))
        <div class="toast align-items-center text-bg-primary border-0 shadow-sm" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4500">
            <div class="d-flex">
                <div class="toast-body fw-medium">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4500">
            <div class="d-flex">
                <div class="toast-body fw-medium">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="toast align-items-center bg-danger bg-opacity-10 text-danger border border-danger shadow-sm" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4500">
            <div class="d-flex">
                <div class="toast-body fw-medium text-dark">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000">
            <div class="d-flex">
                <div class="toast-body fw-medium">
                    <i class="bi bi-exclamation-circle me-2"></i>Please fix the following errors:<br>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

</div>

<!-- JS Template for dynamic toasts (AJAX) -->
<div id="dynamic-toast-template" class="d-none">
    <div class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4500">
        <div class="d-flex">
            <div class="toast-body fw-medium">
                <i class="toast-icon me-2"></i><span class="toast-message"></span>
            </div>
            <button type="button" class="btn-close me-2 m-auto toast-close-btn" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast-container .toast:not(#dynamic-toast-template .toast)'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(toast => toast.show());
    });

    // Global helper for AJAX toasts
    window.showToast = function(type, message) {
        const container = document.querySelector('.toast-container');
        if (!container) return;
        
        const template = document.querySelector('#dynamic-toast-template .toast').cloneNode(true);
        const icon = template.querySelector('.toast-icon');
        const msgSpan = template.querySelector('.toast-message');
        const closeBtn = template.querySelector('.toast-close-btn');
        
        msgSpan.innerHTML = message;
        
        if (type === 'success') {
            template.classList.add('text-bg-primary', 'shadow-sm');
            template.classList.remove('border-0');
            icon.classList.add('bi', 'bi-check-circle');
            closeBtn.classList.add('btn-close-white');
        } else if (type === 'error') {
            template.classList.add('text-bg-danger');
            icon.classList.add('bi', 'bi-x-circle');
            closeBtn.classList.add('btn-close-white');
        } else if (type === 'warning') {
            template.classList.add('bg-danger', 'bg-opacity-10', 'text-danger', 'border', 'border-danger', 'shadow-sm');
            template.classList.remove('border-0');
            icon.classList.add('bi', 'bi-exclamation-triangle');
            template.querySelector('.toast-body').classList.add('text-dark');
        }
        
        container.appendChild(template);
        const toastInstance = new bootstrap.Toast(template);
        toastInstance.show();
        
        template.addEventListener('hidden.bs.toast', function () {
            template.remove();
        });
    }
</script>
