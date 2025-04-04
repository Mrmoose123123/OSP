document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');

    // Check if both elements were found
    if (menuToggle && navbar) {
        menuToggle.addEventListener('click', () => {
            // Toggle the 'active' class on the navigation menu UL
            navbar.classList.toggle('active');

            // Toggle the 'active' class on the button for styling (e.g., X animation)
            menuToggle.classList.toggle('active');

            // Toggle ARIA attribute for accessibility
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', String(!isExpanded)); // Use String() for explicit conversion
        });

        // Optional: Close menu when a navigation link is clicked
        const navLinks = navbar.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                // Check if the mobile menu is currently active before closing
                if (navbar.classList.contains('active')) {
                    navbar.classList.remove('active');
                    menuToggle.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });

    } else {
        // Log an error if elements aren't found, helps debugging
        if (!menuToggle) console.error("Error: Menu toggle button (.menu-toggle) not found.");
        if (!navbar) console.error("Error: Navbar element (.navbar) not found.");
    }
});