let toggle = document.getElementById('objectlist-toggle');

if (toggle) {
    toggle.addEventListener('click', function (event) {
        event.preventDefault();
        toggle.parentElement.classList.toggle('objectlist__collapsed');
    });
}