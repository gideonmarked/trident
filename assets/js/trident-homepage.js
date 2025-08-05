// TRIDENT Homepage JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('[TRIDENT] Homepage loaded');
    
    // Hero Carousel Functionality
    initHeroCarousel();
    
    // Tumbler Preview Interactions
    initTumblerPreviews();
    
    // Product Card Interactions
    initProductCards();
    
    // Newsletter Form
    initNewsletterForm();
    
    // Header Scroll Effect
    initHeaderScrollEffect();
    
    // Smooth Scrolling
    initSmoothScrolling();
});

// Hero Carousel
function initHeroCarousel() {
    const slides = document.querySelectorAll('.trident-slide');
    const dots = document.querySelectorAll('.trident-dot');
    const prevBtn = document.querySelector('.trident-carousel-prev');
    const nextBtn = document.querySelector('.trident-carousel-next');
    let currentSlide = 0;
    
    if (slides.length === 0) return;
    
    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => slide.classList.remove('active'));
        
        // Show current slide
        if (slides[index]) {
            slides[index].classList.add('active');
        }
        
        // Remove active class from all dots
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current dot
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        
        currentSlide = index;
        console.log('[TRIDENT] Hero carousel slide:', index);
    }
    
    // Dot click events
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
        });
    });
    
    // Previous button
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            const newIndex = currentSlide > 0 ? currentSlide - 1 : slides.length - 1;
            showSlide(newIndex);
        });
    }
    
    // Next button
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const newIndex = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
            showSlide(newIndex);
        });
    }
    
    // Auto-advance carousel
    setInterval(() => {
        const newIndex = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
        showSlide(newIndex);
    }, 5000);
}

// Tumbler Preview Interactions
function initTumblerPreviews() {
    const tumblerPreviews = document.querySelectorAll('.trident-tumbler-preview');
    
    tumblerPreviews.forEach((preview, index) => {
        preview.addEventListener('click', () => {
            console.log('[TRIDENT] Tumbler preview clicked:', index + 1);
            
            // Remove active class from all previews
            tumblerPreviews.forEach(p => p.style.transform = '');
            
            // Add active effect to clicked preview
            preview.style.transform = 'translateY(-10px) scale(1.1)';
            
            // Reset after animation
            setTimeout(() => {
                preview.style.transform = '';
            }, 300);
        });
        
        // Hover effects
        preview.addEventListener('mouseenter', () => {
            preview.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        preview.addEventListener('mouseleave', () => {
            preview.style.transform = '';
        });
    });
}

// Product Card Interactions
function initProductCards() {
    // Product card interactions
    document.querySelectorAll('.trident-product-card').forEach(card => {
        const customizeBtn = card.querySelector('.customize');
        const checkoutBtn = card.querySelector('.checkout');
        
        if (customizeBtn) {
            customizeBtn.addEventListener('click', () => {
                console.log('[TRIDENT] Product customize clicked');
                // Add your customization logic here
            });
        }
        
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => {
                console.log('[TRIDENT] Product checkout clicked');
                // Add your checkout logic here
            });
        }
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
    
    // Quantity selector functionality
    const quantitySelects = document.querySelectorAll('.trident-quantity-select');
    quantitySelects.forEach(select => {
        select.addEventListener('change', () => {
            console.log('[TRIDENT] Quantity changed:', select.value);
        });
    });
}

// Newsletter Form
function initNewsletterForm() {
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
}

// Header Scroll Effect
function initHeaderScrollEffect() {
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
}

// Smooth Scrolling
function initSmoothScrolling() {
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
}

// Customize Button
document.addEventListener('DOMContentLoaded', function() {
    const customizeBtn = document.querySelector('.trident-customize-btn');
    if (customizeBtn) {
        customizeBtn.addEventListener('click', () => {
            console.log('[TRIDENT] Customize button clicked');
            // Add your customization logic here
            // Could redirect to a customization page or open a modal
        });
    }
    
    // Shop All Button
    const shopAllBtn = document.querySelector('.trident-shop-all-btn');
    if (shopAllBtn) {
        shopAllBtn.addEventListener('click', () => {
            console.log('[TRIDENT] Shop All button clicked');
            // Navigation is handled by the href attribute
        });
    }
}); 