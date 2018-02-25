const impersonationForm = document.querySelector('.topbar-impersonation form');

// Only add event listener if form is available.
if (impersonationForm) {
    const impersonationSelect = impersonationForm.querySelector('select');

    // Automatically submit form if value is changed.
    impersonationSelect.addEventListener('change', function () {
        impersonationForm.submit();
    });
}