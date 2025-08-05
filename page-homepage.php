<?php
/**
 * Template Name: Homepage
 * 
 * @package WordPress
 * @subpackage Trident
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="trident-homepage">
    <!-- Header -->
    <?php get_template_part('template-parts/trident/header'); ?>

    <!-- Hero Section -->
    <section class="trident-hero">
        <div class="trident-hero-slider">
            <?php
            // Get banners from database
            $banners = function_exists('trident_get_banners') ? trident_get_banners() : array();
            
            if (!empty($banners)) {
                // Display uploaded banners
                foreach ($banners as $index => $banner) {
                    $is_active = $index === 0 ? 'active' : '';
                    ?>
                    <div class="trident-slide <?php echo $is_active; ?>">
                        <img src="<?php echo esc_url($banner->image_url); ?>" alt="<?php echo esc_attr($banner->title ?: 'TRIDENT Banner'); ?>" class="trident-slide-image">
                    </div>
                    <?php
                }
            } else {
                // Fallback to default banners if no uploaded banners exist
                ?>
                <div class="trident-slide active">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/homepage-hero.png" alt="TRIDENT Hero" class="trident-slide-image">
                </div>
                <div class="trident-slide">
                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-683x1024.webp" alt="TRIDENT Tumbler" class="trident-slide-image">
                </div>
                <div class="trident-slide">
                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15asm55f3e9pgfmm2qsnzs3_1753599594_img_0-683x1024.webp" alt="TRIDENT Tumbler" class="trident-slide-image">
                </div>
                <?php
            }
            ?>
        </div>
        
        <!-- Hero Carousel Navigation -->
        <button class="trident-carousel-prev">‹</button>
        <button class="trident-carousel-next">›</button>
        
        <div class="trident-hero-carousel">
            <div class="trident-carousel-dots">
                <?php
                $total_slides = !empty($banners) ? count($banners) : 3;
                for ($i = 0; $i < $total_slides; $i++) {
                    $is_active = $i === 0 ? 'active' : '';
                    echo '<span class="trident-dot ' . $is_active . '"></span>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="trident-about">
        <div class="trident-about-content">
            <h2 class="trident-about-title">WHAT IS TRIDENT PH?</h2>
            <div class="trident-about-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
            </div>
        </div>
    </section>

    <!-- Customize Section -->
    <section class="trident-customize">
        <div class="trident-customize-background">
        </div>
        <div class="trident-customize-content">
            <h2 class="trident-customize-title">Your Cup, Your Style</h2>
            <h3 class="trident-customize-subtitle">Customize your flask now!</h3>
            <button class="trident-customize-btn">Click Here</button>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="trident-featured">
        <div class="trident-featured-content">
            <h2 class="trident-featured-title">FEATURED PRODUCTS</h2>
            
            <div class="trident-products-grid">
                <?php
                // Get products from custom database
                $featured_products = function_exists('trident_get_products') ? trident_get_products(4) : array();
                
                if (!empty($featured_products)) {
                    foreach ($featured_products as $product) {
                        $colors = function_exists('trident_get_product_colors') ? trident_get_product_colors($product) : array();
                        $sizes = function_exists('trident_get_product_sizes') ? trident_get_product_sizes($product) : array();
                        ?>
                        <div class="trident-product-card">
                            <div class="trident-product-pagination">(1/<?php echo count($featured_products); ?>)</div>
                            <div class="trident-product-image">
                                <?php if (!empty($product->image_url)): ?>
                                    <img src="<?php echo esc_url($product->image_url); ?>" alt="<?php echo esc_attr($product->name); ?>" class="trident-product-image">
                                <?php else: ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tumbler-default.png" alt="<?php echo esc_attr($product->name); ?>" class="trident-product-image">
                                <?php endif; ?>
                            </div>
                            <div class="trident-product-name"><?php echo esc_html($product->name); ?></div>
                            <div class="trident-product-price">₱<?php echo number_format($product->price, 2); ?></div>
                            <div class="trident-product-options">
                                <div class="trident-color-swatches">
                                    <?php
                                    if (!empty($colors['body_colors'])) {
                                        foreach (array_slice($colors['body_colors'], 0, 3) as $color) {
                                            echo '<div class="trident-color-swatch" style="background: ' . esc_attr($color) . '"></div>';
                                        }
                                    } else {
                                        // Fallback colors
                                        echo '<div class="trident-color-swatch green"></div>';
                                        echo '<div class="trident-color-swatch brown"></div>';
                                        echo '<div class="trident-color-swatch yellow"></div>';
                                    }
                                    ?>
                                </div>
                                <div class="trident-product-quantity">
                                    <select class="trident-quantity-select">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                </div>
                            </div>
                            <button class="trident-checkout-btn">Check Out</button>
                        </div>
                        <?php
                    }
                } else {
                    // Fallback product cards if no products exist
                    for ($i = 0; $i < 4; $i++) {
                        ?>
                        <div class="trident-product-card">
                            <div class="trident-product-pagination">(<?php echo ($i + 1); ?>/4)</div>
                            <div class="trident-product-image">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tumbler-default.png" alt="32 oz Lightweight Wide Mouth Trail Series™" class="trident-product-image">
                            </div>
                            <div class="trident-product-name">32 oz Lightweight Wide Mouth Trail Series™</div>
                            <div class="trident-product-price">₱1,299.00</div>
                            <div class="trident-product-options">
                                <div class="trident-color-swatches">
                                    <div class="trident-color-swatch green"></div>
                                    <div class="trident-color-swatch brown"></div>
                                    <div class="trident-color-swatch yellow"></div>
                                </div>
                                <div class="trident-product-quantity">
                                    <select class="trident-quantity-select">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                </div>
                            </div>
                            <button class="trident-checkout-btn">Check Out</button>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            
            <div class="trident-shop-all">
                <a href="<?php echo get_permalink(get_page_by_path('all-products')); ?>" class="trident-shop-all-btn">Shop All</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php get_template_part('template-parts/trident/footer'); ?>
</div>

<?php wp_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Carousel Functionality
    const slides = document.querySelectorAll('.trident-slide');
    const dots = document.querySelectorAll('.trident-dot');
    const prevBtn = document.querySelector('.trident-carousel-prev');
    const nextBtn = document.querySelector('.trident-carousel-next');
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    // Function to show slide
    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        slides[index].classList.add('active');
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        
        currentSlide = index;
    }
    
    // Next slide
    function nextSlide() {
        const next = (currentSlide + 1) % totalSlides;
        showSlide(next);
    }
    
    // Previous slide
    function prevSlide() {
        const prev = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(prev);
    }
    
    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });
    
    // Auto-advance slides every 5 seconds
    setInterval(nextSlide, 5000);
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        }
    });
});
</script>

</body>
</html> 