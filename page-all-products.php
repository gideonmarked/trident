<?php
/**
 * Template Name: All Products Page
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
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="trident-products-page">
    <!-- Header -->
    <?php get_template_part('template-parts/trident/header'); ?>

    <!-- Hero Section -->
    <section class="trident-hero">
        <div class="trident-hero-slider">
            <div class="trident-slide active">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/homepage-hero.png" alt="TRIDENT Hero" class="trident-slide-image">
            </div>
            <div class="trident-slide">
                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-683x1024.webp" alt="TRIDENT Tumbler" class="trident-slide-image">
            </div>
            <div class="trident-slide">
                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15asm55f3e9pgfmm2qsnzs3_1753599594_img_0-683x1024.webp" alt="TRIDENT Tumbler" class="trident-slide-image">
            </div>
        </div>
        <div class="trident-hero-content">
            <div class="trident-hero-text">
                <div class="trident-hero-subtitle">19th Anniversary Limited Edition Tumbler</div>
                <h1 class="trident-hero-title">A TUMBLER<br>FOR TOMORROW</h1>
                <div class="trident-hero-price">₱1,395</div>
                <p class="trident-hero-eco">
                    With every purchase, a native tree is adopted on your behalf in a reforestation site managed by Philippine Parks and Biodiversity and Eco Explorations.
                </p>
                <div class="trident-hero-logos">
                    <div class="trident-logo-item">
                        <div class="trident-logo-circle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2"/>
                                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <span>ecoexplorations</span>
                    </div>
                    <div class="trident-logo-item">
                        <div class="trident-logo-circle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L13.09 8.26L20 9L13.09 9.74L12 16L10.91 9.74L4 9L10.91 8.26L12 2Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <span>COFFEE BEAN<br>TEA LEAF</span>
                    </div>
                    <div class="trident-logo-item">
                        <div class="trident-logo-circle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor"/>
                                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <span>Philippine Parks<br>and Biodiversity</span>
                    </div>
                </div>
            </div>
            <div class="trident-hero-product">
                <div class="trident-tumbler-hero">
                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-683x1024.webp" alt="TRIDENT Tumbler" class="trident-hero-tumbler">
                    <div class="trident-tumbler-text">PASSION FOR COFFEE & TEA SINCE '63</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Summary Section -->
    <section class="trident-product-summary">
        <div class="trident-product-summary-content">
            <h2 class="trident-product-summary-title">PRODUCT SUMMARY</h2>
            
            <div class="trident-product-layout">
                <!-- Product Gallery -->
                <div class="trident-product-gallery">
                    <div class="trident-gallery-thumbnail active">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-150x150.webp" alt="Tumbler View 1">
                    </div>
                    <div class="trident-gallery-thumbnail">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_1-150x150.webp" alt="Tumbler View 2">
                    </div>
                    <div class="trident-gallery-thumbnail">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15asm55f3e9pgfmm2qsnzs3_1753599594_img_0-150x150.webp" alt="Tumbler View 3">
                    </div>
                </div>
                
                <!-- Main Product Image -->
                <div class="trident-main-image">
                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-683x1024.webp" alt="TRIDENT Tumbler" class="trident-main-tumbler">
                </div>
                
                <!-- Product Details -->
                <div class="trident-product-details">
                    <h3 class="trident-product-name">32 oz Lightweight Wide Mouth Trail Series™</h3>
                    <p class="trident-product-description">Insulated tumbler with cap and straw.</p>
                    
                    <div class="trident-color-options">
                        <div class="trident-color-swatch yellow active"></div>
                        <div class="trident-color-swatch black"></div>
                        <div class="trident-color-swatch green"></div>
                        <div class="trident-color-swatch teal"></div>
                        <div class="trident-color-swatch orange"></div>
                    </div>
                    
                    <div class="trident-product-price">₱1,299.00</div>
                    
                    <div class="trident-product-quantity">
                        <label>Quantity:</label>
                        <select class="trident-quantity-select">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                    </div>
                    
                    <div class="trident-product-buttons">
                        <button class="trident-product-btn customize">Customize</button>
                        <button class="trident-product-btn checkout">Check Out</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="trident-featured">
        <div class="trident-featured-content">
            <h2 class="trident-featured-title">FEATURED PRODUCTS</h2>
            
            <div class="trident-products-grid">
                <?php
                // Get tumbler products for the featured section
                $featured_products = get_posts(array(
                    'post_type' => 'tumbler_product',
                    'posts_per_page' => 6,
                    'post_status' => 'publish'
                ));
                
                if (!empty($featured_products)) {
                    foreach ($featured_products as $product) {
                        $product_id = $product->ID;
                        ?>
                        <div class="trident-product-card">
                            <div class="trident-product-pagination">(1/10)</div>
                            <div class="trident-product-image">
                                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-200x300.webp" alt="<?php echo esc_attr($product->post_title); ?>" class="trident-product-image">
                            </div>
                            <div class="trident-product-name"><?php echo esc_html($product->post_title); ?></div>
                            <div class="trident-product-price">₱1,299.00</div>
                            <div class="trident-product-options">
                                <button class="trident-customize-btn-small">CUSTOMIZE</button>
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
                } else {
                    // Fallback product cards if no products exist
                    for ($i = 0; $i < 6; $i++) {
                        ?>
                        <div class="trident-product-card">
                            <div class="trident-product-pagination">(1/10)</div>
                            <div class="trident-product-image">
                                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15asm55f3e9pgfmm2qsnzs3_1753599594_img_0-200x300.webp" alt="32 oz Lightweight Wide Mouth Trail Series™" class="trident-product-image">
                            </div>
                            <div class="trident-product-name">32 oz Lightweight Wide Mouth Trail Series™</div>
                            <div class="trident-product-price">₱1,299.00</div>
                            <div class="trident-product-options">
                                <button class="trident-customize-btn-small">CUSTOMIZE</button>
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
        </div>
    </section>

    <!-- Footer -->
    <?php get_template_part('template-parts/trident/footer'); ?>
</div>

<?php wp_footer(); ?>
</body>
</html> 