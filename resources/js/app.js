import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Auto-activate Bootstrap tab if URL contains a #hash
    const hash = window.location.hash;
    if (hash) {
        const triggerEl = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (triggerEl) {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }

    // Update URL hash when clicking a tab
    const tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabElements.forEach(tab => {
        tab.addEventListener('shown.bs.tab', event => {
            const target = event.target.getAttribute('data-bs-target');
            if (target) {
                history.replaceState(null, null, target);
            }
        });
    });
});
