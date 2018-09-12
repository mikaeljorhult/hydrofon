let toggle = document.getElementById('resourcelist-toggle');

if (toggle) {
    toggle.addEventListener('click', function (event) {
        event.preventDefault();
        toggle.parentElement.classList.toggle('resourcelist__collapsed');
    });
}