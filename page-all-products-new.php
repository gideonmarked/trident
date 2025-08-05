<?php
/**
 * Template Name: All Products Page
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Start output buffering to capture content
ob_start();
?>

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
                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_2-150x150.webp" alt="Tumbler View 3">
                </div>
                <div class="trident-gallery-thumbnail">
                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_3-150x150.webp" alt="Tumbler View 4">
                </div>
            </div>
            
            <!-- Product Details -->
            <div class="trident-product-details">
                <h3 class="trident-product-name">32 oz Lightweight Wide Mouth Trail Series™</h3>
                <div class="trident-product-price">₱1,395</div>
                
                <div class="trident-product-options">
                    <div class="trident-option-group">
                        <label class="trident-option-label">Body Color</label>
                        <div class="trident-color-options">
                            <div class="trident-color-option active" data-color="yellow">
                                <div class="trident-color-swatch yellow"></div>
                                <span>Yellow</span>
                            </div>
                            <div class="trident-color-option" data-color="green">
                                <div class="trident-color-swatch green"></div>
                                <span>Green</span>
                            </div>
                            <div class="trident-color-option" data-color="black">
                                <div class="trident-color-swatch black" style="background: #1f2937;"></div>
                                <span>Black</span>
                            </div>
                            <div class="trident-color-option" data-color="mint">
                                <div class="trident-color-swatch mint"></div>
                                <span>Mint</span>
                            </div>
                            <div class="trident-color-option" data-color="gold">
                                <div class="trident-color-swatch gold"></div>
                                <span>Gold</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="trident-option-group">
                        <label class="trident-option-label">Cap Color</label>
                        <div class="trident-color-options">
                            <div class="trident-color-option active" data-color="yellow">
                                <div class="trident-color-swatch yellow"></div>
                                <span>Yellow</span>
                            </div>
                            <div class="trident-color-option" data-color="green">
                                <div class="trident-color-swatch green"></div>
                                <span>Green</span>
                            </div>
                            <div class="trident-color-option" data-color="black">
                                <div class="trident-color-swatch black" style="background: #1f2937;"></div>
                                <span>Black</span>
                            </div>
                            <div class="trident-color-option" data-color="mint">
                                <div class="trident-color-swatch mint"></div>
                                <span>Mint</span>
                            </div>
                            <div class="trident-color-option" data-color="gold">
                                <div class="trident-color-swatch gold"></div>
                                <span>Gold</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="trident-option-group">
                        <label class="trident-option-label">Boot Color</label>
                        <div class="trident-color-options">
                            <div class="trident-color-option active" data-color="yellow">
                                <div class="trident-color-swatch yellow"></div>
                                <span>Yellow</span>
                            </div>
                            <div class="trident-color-option" data-color="green">
                                <div class="trident-color-swatch green"></div>
                                <span>Green</span>
                            </div>
                            <div class="trident-color-option" data-color="black">
                                <div class="trident-color-swatch black" style="background: #1f2937;"></div>
                                <span>Black</span>
                            </div>
                            <div class="trident-color-option" data-color="mint">
                                <div class="trident-color-swatch mint"></div>
                                <span>Mint</span>
                            </div>
                            <div class="trident-color-option" data-color="gold">
                                <div class="trident-color-swatch gold"></div>
                                <span>Gold</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="trident-option-group">
                        <label class="trident-option-label">Size</label>
                        <div class="trident-size-options">
                            <div class="trident-size-option active" data-size="24oz">
                                <span>24oz</span>
                            </div>
                            <div class="trident-size-option" data-size="32oz">
                                <span>32oz</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="trident-option-group">
                        <label class="trident-option-label">Quantity</label>
                        <div class="trident-quantity-selector">
                            <button class="trident-quantity-btn" data-action="decrease">-</button>
                            <input type="number" class="trident-quantity-input" value="1" min="1" max="10">
                            <button class="trident-quantity-btn" data-action="increase">+</button>
                        </div>
                    </div>
                </div>
                
                <div class="trident-product-actions">
                    <button class="trident-customize-btn">CUSTOMIZE</button>
                    <button class="trident-checkout-btn">Check Out</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- All Products Grid -->
<section class="trident-all-products">
    <div class="trident-all-products-content">
        <h2 class="trident-all-products-title">ALL PRODUCTS</h2>
        
        <div class="trident-products-grid">
            <?php
            // Get all products from custom database
            $all_products = function_exists('trident_get_products') ? trident_get_products() : array();
            
            if (!empty($all_products)) {
                foreach ($all_products as $product) {
                    $colors = function_exists('trident_get_product_colors') ? trident_get_product_colors($product) : array();
                    $sizes = function_exists('trident_get_product_sizes') ? trident_get_product_sizes($product) : array();
                    ?>
                    <div class="trident-product-card">
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
                            <div class="trident-customize-row">
                                <button class="trident-customize-btn-small">CUSTOMIZE</button>
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
                for ($i = 0; $i < 8; $i++) {
                    ?>
                    <div class="trident-product-card">
                        <div class="trident-product-image">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tumbler-default.png" alt="32 oz Lightweight Wide Mouth Trail Series™" class="trident-product-image">
                        </div>
                        <div class="trident-product-name">32 oz Lightweight Wide Mouth Trail Series™</div>
                        <div class="trident-product-price">₱1,299.00</div>
                        <div class="trident-product-options">
                            <div class="trident-customize-row">
                                <button class="trident-customize-btn-small">CUSTOMIZE</button>
                                <div class="trident-color-swatches">
                                    <div class="trident-color-swatch green"></div>
                                    <div class="trident-color-swatch brown"></div>
                                    <div class="trident-color-swatch yellow"></div>
                                </div>
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

<?php
// Get the buffered content
$content = ob_get_clean();

// Render the page with common layout
trident_render_page($content, 'All Products');
?> 