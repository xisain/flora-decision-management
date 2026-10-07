if (document.documentElement.classList.contains('landing-scroll')) {
    const navigation = document.querySelector('.public-nav');

    if (navigation) {
        const updateNavigationHeight = () => {
            document.documentElement.style.setProperty(
                '--landing-nav-height', `${navigation.getBoundingClientRect().height}px`,
            );
        };

        updateNavigationHeight();
        if ('ResizeObserver' in window) {
            new ResizeObserver(updateNavigationHeight).observe(navigation);
        } else {
            window.addEventListener('resize', updateNavigationHeight);
        }
    }
}
