// Auto-dismiss Alpine alert divs (those with x-data="{ v: true }") after 5s.
// Alpine handles the fade via x-transition; we just flip the flag.
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        document.querySelectorAll('[x-data*="v: true"]').forEach(function (el) {
            if (el._x_dataStack) {
                el._x_dataStack[0].v = false;
            }
        });
    }, 5000);
});
