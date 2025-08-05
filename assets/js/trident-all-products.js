// TRIDENT All Products Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('[TRIDENT] All Products page loaded');
    
    // Gallery thumbnail functionality
    const thumbnails = document.querySelectorAll('.trident-gallery-thumbnail');
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', () => {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumbnail
            thumbnail.classList.add('active');
            console.log('[TRIDENT] Gallery thumbnail clicked:', index);
        });
    });
    
    // Color swatch functionality
    const colorSwatches = document.querySelectorAll('.trident-color-swatch');
    colorSwatches.forEach(swatch => {
        swatch.addEventListener('click', () => {
            // Remove active class from siblings
            swatch.parentElement.querySelectorAll('.trident-color-swatch').forEach(s => {
                s.classList.remove('active');
            });
            // Add active class to clicked swatch
            swatch.classList.add('active');
            console.log('[TRIDENT] Color selected:', swatch.className);
        });
    });
    
    // Product card interactions
    document.querySelectorAll('.trident-product-card').forEach(card => {
        const checkoutBtn = card.querySelector('.checkout');
        
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => {
                console.log('[TRIDENT] Product checkout clicked');
                // Add your checkout logic here
            });
        }
    });
    
    // Main product buttons
    const customizeBtn = document.querySelector('.trident-customize-btn');
    const checkoutBtn = document.querySelector('.trident-checkout-btn');
    
    if (customizeBtn) {
        customizeBtn.addEventListener('click', () => {
            console.log('[TRIDENT] Customize clicked');
            // Add your customization logic here
        });
    }
    
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            console.log('[TRIDENT] Checkout clicked');
            // Add your checkout logic here
        });
    }
    
    // Newsletter form
    const newsletterForm = document.querySelector('.trident-footer-email');
    if (newsletterForm) {
        const submitBtn = newsletterForm.querySelector('button');
        const emailInput = newsletterForm.querySelector('input');
        
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const email = emailInput.value;
            if (email) {
                console.log('[TRIDENT] Newsletter subscription:', email);
                // Add your newsletter subscription logic here
                alert('Thank you for subscribing!');
                emailInput.value = '';
            }
        });
    }
    
    // Header scroll effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.trident-header');
        if (window.scrollY > 100) {
            header.style.background = 'rgba(55, 65, 81, 0.95)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.background = 'var(--trident-dark)';
            header.style.backdropFilter = 'none';
        }
    });
    
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}); 