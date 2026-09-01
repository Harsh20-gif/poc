document.addEventListener('DOMContentLoaded', function() {
    // Password toggle
    const toggleBtns = document.querySelectorAll('.toggle-password');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Hide';
            } else {
                input.type = 'password';
                this.textContent = 'Show';
            }
        });
    });

    // Service checkbox highlight logic
    const serviceChecks = document.querySelectorAll('.service-checkbox');
    serviceChecks.forEach(check => {
        // Initial state
        if(check.checked) {
            check.closest('.service-check-wrapper').classList.add('checked');
        }
        
        // On change
        check.addEventListener('change', function() {
            if(this.checked) {
                this.closest('.service-check-wrapper').classList.add('checked');
            } else {
                this.closest('.service-check-wrapper').classList.remove('checked');
            }
        });
    });

    // Sidebar active link logic
    const currentUrl = window.location.href;
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
        if(currentUrl.startsWith(link.href) && link.href !== window.location.origin + '/' && link.href !== window.location.origin + '/logout') {
            link.classList.add('active');
        } else if (currentUrl === link.href) {
            link.classList.add('active');
        }
    });

    // Generic Data Confirm Handler
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            if(!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });
});
