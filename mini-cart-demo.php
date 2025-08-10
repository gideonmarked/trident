<?php
/**
 * Mini Cart Demo Template
 * 
 * This file demonstrates how to integrate the mini cart functionality
 * with existing product templates in the TRIDENT theme.
 * 
 * @package WordPress
 * @subpackage Trident
 */

get_header(); ?>

<div class="mini-cart-demo">
    <div class="container">
        <h1>Mini Cart Demo</h1>
        <p>This page demonstrates the mini cart functionality. Try adding products to see the cart in action!</p>
        
        <!-- Cart Status Display -->
        <div class="cart-status">
            <p>Items in cart: <span id="demo-cart-count"><?php echo trident_get_cart_count_display(); ?></span></p>
            <p>Cart total: <span id="demo-cart-total"><?php echo trident_get_cart_total_display(); ?></span></p>
        </div>
        
        <!-- Product Grid with Add to Cart Buttons -->
        <div class="products-grid">
            <?php
            $products = function_exists('trident_get_products') ? trident_get_products(array('limit' => 6)) : array();
            
            if (!empty($products)) {
                foreach ($products as $product) {
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($product->image_url)): ?>
                                <img src="<?php echo esc_url($product->image_url); ?>" alt="<?php echo esc_attr($product->name); ?>">
                            <?php else: ?>
                                <div class="product-placeholder">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21,15 16,10 5,21"></polyline>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name"><?php echo esc_html($product->name); ?></h3>
                            <div class="product-price">$<?php echo number_format($product->price, 2); ?></div>
                            <div class="product-description">
                                <?php echo wp_trim_words($product->description, 15); ?>
                            </div>
                            
                            <!-- Quantity Selector -->
                            <div class="product-quantity">
                                <label for="quantity-<?php echo $product->id; ?>">Quantity:</label>
                                <select id="quantity-<?php echo $product->id; ?>" class="quantity-select">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <?php 
                            // Example 1: Simple add to cart button
                            trident_add_to_cart_button($product->id, 1, array(), 'Add to Cart', 'product-add-btn');
                            ?>
                            
                            <!-- Example 2: Add to cart with custom options -->
                            <?php
                            $custom_options = array(
                                'size' => '20oz',
                                'color' => 'Classic Black'
                            );
                            trident_add_to_cart_button($product->id, 1, $custom_options, 'Add with Options', 'product-add-options-btn');
                            ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Fallback demo products
                $demo_products = array(
                    array('id' => 1, 'name' => 'Classic TRIDENT Tumbler', 'price' => 1299.00, 'description' => 'Our signature tumbler with premium insulation and sleek design.'),
                    array('id' => 2, 'name' => 'Premium TRIDENT Bottle', 'price' => 899.00, 'description' => 'Lightweight and durable water bottle perfect for outdoor adventures.'),
                    array('id' => 3, 'name' => 'TRIDENT Travel Mug', 'price' => 699.00, 'description' => 'Perfect for your morning commute with excellent heat retention.'),
                );
                
                foreach ($demo_products as $product) {
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <div class="product-placeholder">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21,15 16,10 5,21"></polyline>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name"><?php echo esc_html($product['name']); ?></h3>
                            <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                            <div class="product-description">
                                <?php echo esc_html($product['description']); ?>
                            </div>
                            
                            <!-- Quantity Selector -->
                            <div class="product-quantity">
                                <label for="quantity-<?php echo $product['id']; ?>">Quantity:</label>
                                <select id="quantity-<?php echo $product['id']; ?>" class="quantity-select">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <button class="add-to-cart-btn btn btn-primary product-add-btn" 
                                    data-product-id="<?php echo $product['id']; ?>" 
                                    data-quantity="1">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <!-- Demo Instructions -->
        <div class="demo-instructions">
            <h2>How to Use the Mini Cart</h2>
            <ol>
                <li>Click the cart icon in the header or the floating cart button to open the mini cart</li>
                <li>Add products to your cart using the "Add to Cart" buttons</li>
                <li>View your cart contents in the mini cart overlay</li>
                <li>Update quantities or remove items directly from the mini cart</li>
                <li>Proceed to checkout when ready</li>
            </ol>
            
            <h3>Integration Examples</h3>
            <p><strong>Simple Add to Cart Button:</strong></p>
            <pre><code>&lt;?php trident_add_to_cart_button($product->id, 1, array(), 'Add to Cart'); ?&gt;</code></pre>
            
            <p><strong>Add to Cart with Options:</strong></p>
            <pre><code>&lt;?php 
$options = array('size' => '20oz', 'color' => 'Black');
trident_add_to_cart_button($product->id, 1, $options, 'Add with Options'); 
?&gt;</code></pre>
            
            <p><strong>Display Cart Count:</strong></p>
            <pre><code>&lt;?php echo trident_get_cart_count_display(); ?&gt;</code></pre>
            
            <p><strong>Display Cart Total:</strong></p>
            <pre><code>&lt;?php echo trident_get_cart_total_display(); ?&gt;</code></pre>
        </div>
    </div>
</div>

<style>
.mini-cart-demo {
    padding: 40px 0;
}

.mini-cart-demo .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.mini-cart-demo h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #111827;
}

.cart-status {
    background: #f3f4f6;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    text-align: center;
}

.cart-status p {
    margin: 5px 0;
    font-size: 16px;
    color: #374151;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.product-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.product-image {
    width: 100%;
    height: 200px;
    background: #f9fafb;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.product-placeholder {
    color: #9ca3af;
}

.product-name {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.product-price {
    font-size: 20px;
    font-weight: 700;
    color: #059669;
    margin-bottom: 10px;
}

.product-description {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 15px;
}

.product-quantity {
    margin-bottom: 15px;
}

.product-quantity label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 5px;
}

.quantity-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
}

.product-add-btn {
    width: 100%;
    margin-bottom: 8px;
}

.product-add-options-btn {
    width: 100%;
    background-color: #7c3aed;
}

.product-add-options-btn:hover {
    background-color: #6d28d9;
}

.demo-instructions {
    background: #f9fafb;
    padding: 30px;
    border-radius: 12px;
    border-left: 4px solid #3b82f6;
}

.demo-instructions h2 {
    color: #111827;
    margin-bottom: 15px;
}

.demo-instructions h3 {
    color: #374151;
    margin: 25px 0 15px 0;
}

.demo-instructions ol {
    padding-left: 20px;
    color: #4b5563;
}

.demo-instructions li {
    margin-bottom: 8px;
}

.demo-instructions p {
    margin-bottom: 10px;
    color: #4b5563;
}

.demo-instructions pre {
    background: #1f2937;
    color: #f9fafb;
    padding: 15px;
    border-radius: 6px;
    overflow-x: auto;
    margin: 10px 0;
}

.demo-instructions code {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 13px;
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .mini-cart-demo {
        padding: 20px 0;
    }
    
    .demo-instructions {
        padding: 20px;
    }
}
</style>

<script>
// Update cart status when cart changes
jQuery(document).ready(function($) {
    // Listen for cart updates
    $(document).on('cartUpdated', function() {
        // This event can be triggered when cart is updated
        // For now, we'll update manually
    });
    
    // Update cart status every 2 seconds (for demo purposes)
    setInterval(function() {
        $.ajax({
            url: trident_cart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'trident_get_cart_count',
                nonce: trident_cart_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#demo-cart-count').text(response.data.cart_count);
                    $('#demo-cart-total').text('$' + parseFloat(response.data.cart_total).toFixed(2));
                }
            }
        });
    }, 2000);
});
</script>

<?php get_footer(); ?> 