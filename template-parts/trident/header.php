<!-- TRIDENT Header -->
<header class="trident-header">
    <div class="trident-header-content">
        <a href="<?php echo home_url(); ?>" class="trident-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/trident-header-logo.png" alt="TRIDENT" class="trident-logo-image">
            <span class="trident-logo-text">TRIDENT</span>
        </a>
        <nav class="trident-nav">
            <a href="<?php echo home_url(); ?>">Home</a>
            <a href="#about">About</a>
            <a href="<?php echo get_permalink(get_page_by_path('all-products')); ?>">All Products</a>
            <a href="#search">Search</a>
        </nav>
        <a href="#checkout" class="trident-checkout-btn">Check Out</a>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoImage = document.querySelector('.trident-logo-image');
    const logoText = document.querySelector('.trident-logo-text');
    
    if (logoImage && logoText) {
        // Check if image is loaded and has dimensions
        if (logoImage.complete && logoImage.naturalWidth > 0) {
            logoText.classList.add('hidden');
        } else {
            // Wait for image to load
            logoImage.addEventListener('load', function() {
                logoText.classList.add('hidden');
            });
            
            // Fallback: hide text after a short delay if image doesn't load
            setTimeout(function() {
                if (logoImage.complete && logoImage.naturalWidth > 0) {
                    logoText.classList.add('hidden');
                }
            }, 1000);
        }
    }
});
</script> 