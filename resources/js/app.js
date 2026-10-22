document.addEventListener('DOMContentLoaded', () => {

    // ========================================
    // MOBILE HAMBURGER MENU — SINGLE HANDLER
    // ========================================
    const menuButton = document.querySelector('.site-header .mobile-nav-toggle');
    const navigation = document.querySelector('.site-header #primary-navigation');
    const header = document.querySelector('.site-header');
    const mobileQuery = window.matchMedia('(max-width: 900px)');

    if (menuButton && navigation && header) {
        function setMenuState(open) {
            const isOpen = mobileQuery.matches && Boolean(open);

            navigation.classList.toggle('is-open', isOpen);
            menuButton.setAttribute('aria-expanded', String(isOpen));
            menuButton.setAttribute(
                'aria-label',
                isOpen ? 'Close navigation menu' : 'Open navigation menu'
            );
            menuButton.setAttribute(
                'title',
                isOpen ? 'Close menu' : 'Open menu'
            );
        }

        menuButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
            setMenuState(!isOpen);
        });

        navigation.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (mobileQuery.matches) {
                    setMenuState(false);
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (
                mobileQuery.matches &&
                menuButton.getAttribute('aria-expanded') === 'true' &&
                !header.contains(event.target)
            ) {
                setMenuState(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setMenuState(false);
            }
        });

        function resetMenu() {
            setMenuState(false);
        }

        window.addEventListener('resize', resetMenu);
        if (typeof mobileQuery.addEventListener === 'function') {
            mobileQuery.addEventListener('change', resetMenu);
        } else if (typeof mobileQuery.addListener === 'function') {
            mobileQuery.addListener(resetMenu);
        }

        setMenuState(false);
    }

    // ========================================
    // HERO SLIDER
    // ========================================
    const hero = document.querySelector('#hero-slider');

    // Slider na ho toh sirf slider skip karo.
    if (!hero) {
        return;
    }

    const slides = Array.from(
        hero.querySelectorAll('.home-hero-slide')
    );

    const prevButton = hero.querySelector('.hero-slider-prev');
    const nextButton = hero.querySelector('.hero-slider-next');

    const dots = Array.from(
        hero.querySelectorAll('.hero-slider-dot')
    );

    if (slides.length === 0) {
        return;
    }

    let currentIndex = slides.findIndex(function (slide) {
        return slide.classList.contains('is-active');
    });

    if (currentIndex < 0) {
        currentIndex = 0;
    }

    let timer = null;

    function showSlide(index) {
        currentIndex = (index + slides.length) % slides.length;

        slides.forEach(function (slide, i) {
            const active = i === currentIndex;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', String(!active));
        });

        dots.forEach(function (dot, i) {
            const active = i === currentIndex;
            dot.classList.toggle('active', active);

            if (active) {
                dot.setAttribute('aria-current', 'true');
            } else {
                dot.removeAttribute('aria-current');
            }
        });
    }

    function stopAutoSlide() {
        if (timer !== null) {
            clearInterval(timer);
            timer = null;
        }
    }

    function startAutoSlide() {
        stopAutoSlide();

        if (slides.length > 1) {
            timer = setInterval(function () {
                showSlide(currentIndex + 1);
            }, 3000);
        }
    }

    function changeSlide(index) {
        showSlide(index);
        startAutoSlide();
    }

    prevButton?.addEventListener('click', function () {
        changeSlide(currentIndex - 1);
    });

    nextButton?.addEventListener('click', function () {
        changeSlide(currentIndex + 1);
    });

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            changeSlide(index);
        });
    });

    hero.addEventListener('mouseenter', stopAutoSlide);
    hero.addEventListener('mouseleave', startAutoSlide);

    showSlide(currentIndex);
    startAutoSlide();
});
