// Small progressive-enhancement helpers. The app works fine without JS.

// Auto-dismiss flash alerts after a few seconds.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });
});
