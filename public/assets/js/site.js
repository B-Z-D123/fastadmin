(function () {
    'use strict';

    var header = document.getElementById('siteHeader');
    var toggle = document.getElementById('siteMenuToggle');
    var menu = document.getElementById('siteMenu');

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            var isOpen = menu.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        menu.addEventListener('click', function (event) {
            if (event.target && event.target.tagName === 'A') {
                menu.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    if (menu) {
        var anchorLinks = menu.querySelectorAll('a[href^="#"]');
        Array.prototype.forEach.call(anchorLinks, function (link) {
            link.addEventListener('click', function () {
                Array.prototype.forEach.call(anchorLinks, function (item) {
                    item.classList.remove('active');
                });
                link.classList.add('active');
            });
        });
    }

    if (header) {
        var onScroll = function () {
            if (window.scrollY > 8) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }
})();
