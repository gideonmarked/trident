<?php
/**
 * Mini Cart Component
 * 
 * Handles mini cart functionality for the TRIDENT theme
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize mini cart functionality
 */
function trident_init_mini_cart() {
    // Add cart icon to header
    add_action('wp_footer', 'trident_mini_cart_html');
    
    // Enqueue cart scripts and styles
    add_action('wp_enqueue_scripts', 'trident_enqueue_cart_assets');
    
    // Add AJAX handlers
    add_action('wp_ajax_trident_add_to_cart', 'trident_ajax_add_to_cart');
    add_action('wp_ajax_nopriv_trident_add_to_cart', 'trident_ajax_add_to_cart');
    add_action('wp_ajax_trident_remove_from_cart', 'trident_ajax_remove_from_cart');
    add_action('wp_ajax_nopriv_trident_remove_from_cart', 'trident_ajax_remove_from_cart');
    add_action('wp_ajax_trident_update_cart_quantity', 'trident_ajax_update_cart_quantity');
    add_action('wp_ajax_nopriv_trident_update_cart_quantity', 'trident_ajax_update_cart_quantity');
    add_action('wp_ajax_trident_get_cart_count', 'trident_ajax_get_cart_count');
    add_action('wp_ajax_nopriv_trident_get_cart_count', 'trident_ajax_get_cart_count');
    add_action('wp_ajax_trident_get_cart_contents', 'trident_ajax_get_cart_contents');
    add_action('wp_ajax_nopriv_trident_get_cart_contents', 'trident_ajax_get_cart_contents');
}
add_action('init', 'trident_init_mini_cart');

/**
 * Enqueue cart assets
 */
function trident_enqueue_cart_assets() {
    wp_enqueue_script(
        'trident-mini-cart',
        get_template_directory_uri() . '/assets/js/mini-cart.js',
        array('jquery'),
        TRIDENT_VERSION,
        true
    );
    
    wp_enqueue_style(
        'trident-mini-cart',
        get_template_directory_uri() . '/assets/css/mini-cart.css',
        array(),
        TRIDENT_VERSION
    );
    
    // Localize script for AJAX
    wp_localize_script('trident-mini-cart', 'trident_cart_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('trident_cart_nonce'),
        'currency' => '$',
        'strings' => array(
            'added_to_cart' => 'Product added to cart!',
            'removed_from_cart' => 'Product removed from cart!',
            'cart_updated' => 'Cart updated!',
            'error' => 'An error occurred. Please try again.'
        )
    ));
}

/**
 * Get cart contents from session
 */
function trident_get_cart_contents() {
    if (!isset($_SESSION['trident_cart'])) {
        $_SESSION['trident_cart'] = array();
    }
    return $_SESSION['trident_cart'];
}

/**
 * Add product to cart
 */
function trident_add_to_cart($product_id, $quantity = 1, $options = array()) {
    if (!isset($_SESSION['trident_cart'])) {
        $_SESSION['trident_cart'] = array();
    }
    
    $cart_key = $product_id . '_' . md5(serialize($options));
    
    if (isset($_SESSION['trident_cart'][$cart_key])) {
        $_SESSION['trident_cart'][$cart_key]['quantity'] += $quantity;
    } else {
        $_SESSION['trident_cart'][$cart_key] = array(
            'product_id' => $product_id,
            'quantity' => $quantity,
            'options' => $options,
            'added_at' => current_time('timestamp')
        );
    }
    
    return true;
}

/**
 * Remove product from cart
 */
function trident_remove_from_cart($cart_key) {
    if (isset($_SESSION['trident_cart'][$cart_key])) {
        unset($_SESSION['trident_cart'][$cart_key]);
        return true;
    }
    return false;
}

/**
 * Update cart item quantity
 */
function trident_update_cart_quantity($cart_key, $quantity) {
    if (isset($_SESSION['trident_cart'][$cart_key])) {
        if ($quantity <= 0) {
            unset($_SESSION['trident_cart'][$cart_key]);
        } else {
            $_SESSION['trident_cart'][$cart_key]['quantity'] = $quantity;
        }
        return true;
    }
    return false;
}

/**
 * Get cart total
 */
function trident_get_cart_total() {
    $cart = trident_get_cart_contents();
    $total = 0;
    
    foreach ($cart as $cart_key => $item) {
        $product = trident_get_product($item['product_id']);
        if ($product) {
            $total += $product->price * $item['quantity'];
        }
    }
    
    return $total;
}

/**
 * Get cart count
 */
function trident_get_cart_count() {
    $cart = trident_get_cart_contents();
    $count = 0;
    
    foreach ($cart as $item) {
        $count += $item['quantity'];
    }
    
    return $count;
}

/**
 * AJAX: Add to cart
 */
function trident_ajax_add_to_cart() {
    check_ajax_referer('trident_cart_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $options = isset($_POST['options']) ? $_POST['options'] : array();
    
    if ($product_id && $quantity > 0) {
        $result = trident_add_to_cart($product_id, $quantity, $options);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Product added to cart!',
                'cart_count' => trident_get_cart_count(),
                'cart_total' => trident_get_cart_total()
            ));
        }
    }
    
    wp_send_json_error('Failed to add product to cart');
}

/**
 * AJAX: Remove from cart
 */
function trident_ajax_remove_from_cart() {
    check_ajax_referer('trident_cart_nonce', 'nonce');
    
    $cart_key = sanitize_text_field($_POST['cart_key']);
    
    if ($cart_key) {
        $result = trident_remove_from_cart($cart_key);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Product removed from cart!',
                'cart_count' => trident_get_cart_count(),
                'cart_total' => trident_get_cart_total()
            ));
        }
    }
    
    wp_send_json_error('Failed to remove product from cart');
}

/**
 * AJAX: Update cart quantity
 */
function trident_ajax_update_cart_quantity() {
    check_ajax_referer('trident_cart_nonce', 'nonce');
    
    $cart_key = sanitize_text_field($_POST['cart_key']);
    $quantity = intval($_POST['quantity']);
    
    if ($cart_key && $quantity >= 0) {
        $result = trident_update_cart_quantity($cart_key, $quantity);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Cart updated!',
                'cart_count' => trident_get_cart_count(),
                'cart_total' => trident_get_cart_total()
            ));
        }
    }
    
    wp_send_json_error('Failed to update cart');
}

/**
 * AJAX: Get cart count
 */
function trident_ajax_get_cart_count() {
    check_ajax_referer('trident_cart_nonce', 'nonce');
    
    wp_send_json_success(array(
        'cart_count' => trident_get_cart_count(),
        'cart_total' => trident_get_cart_total()
    ));
}

/**
 * AJAX: Get cart contents
 */
function trident_ajax_get_cart_contents() {
    check_ajax_referer('trident_cart_nonce', 'nonce');
    
    $cart = trident_get_cart_contents();
    $html = '';
    $items = array();
    
    if (!empty($cart)) {
        foreach ($cart as $cart_key => $item) {
            $item_html = trident_render_cart_item($cart_key, $item);
            if ($item_html) {
                $html .= $item_html;
                $items[] = $item;
            }
        }
    }
    
    wp_send_json_success(array(
        'items' => $items,
        'html' => $html,
        'cart_count' => trident_get_cart_count(),
        'cart_total' => trident_get_cart_total()
    ));
}

/**
 * Render mini cart HTML
 */
function trident_mini_cart_html() {
    ?>
    <!-- Mini Cart Overlay -->
    <div id="mini-cart-overlay" class="mini-cart-overlay">
        <div class="mini-cart-container">
            <div class="mini-cart-header">
                <h3>Shopping Cart</h3>
                <button class="mini-cart-close" aria-label="Close cart">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <div class="mini-cart-content">
                <div id="mini-cart-items" class="mini-cart-items">
                    <!-- Cart items will be loaded here via AJAX -->
                </div>
                
                <div class="mini-cart-empty" id="mini-cart-empty" style="display: none;">
                    <div class="empty-cart-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </div>
                    <p>Your cart is empty</p>
                    <a href="<?php echo esc_url(home_url('/all-products')); ?>" class="btn btn-primary">Start Shopping</a>
                </div>
            </div>
            
            <div class="mini-cart-footer" id="mini-cart-footer" style="display: none;">
                <div class="mini-cart-total">
                    <span>Total:</span>
                    <span id="mini-cart-total">$0.00</span>
                </div>
                <div class="mini-cart-actions">
                    <a href="<?php echo esc_url(home_url('/checkout')); ?>" class="btn btn-primary btn-checkout">Checkout</a>
                    <button class="btn btn-secondary btn-view-cart" onclick="window.location.href='<?php echo esc_url(home_url('/cart')); ?>'">View Cart</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cart Toggle Button -->
    <div class="cart-toggle-wrapper">
        <button id="cart-toggle" class="cart-toggle" aria-label="Open shopping cart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="cart-count" id="cart-count">0</span>
        </button>
    </div>
    <?php
}

/**
 * Add cart icon to header
 */
function trident_add_cart_icon_to_header() {
    ?>
    <div class="header-cart">
        <button id="header-cart-toggle" class="header-cart-toggle" aria-label="Open shopping cart">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="header-cart-count" id="header-cart-count">0</span>
        </button>
    </div>
    <?php
}

/**
 * Render cart item HTML
 */
function trident_render_cart_item($cart_key, $item) {
    $product = trident_get_product($item['product_id']);
    if (!$product) return '';
    
    $price = $product->price;
    $total = $price * $item['quantity'];
    
    ob_start();
    ?>
    <div class="mini-cart-item" data-cart-key="<?php echo esc_attr($cart_key); ?>">
        <div class="cart-item-image">
            <?php if (!empty($product->image_url)) : ?>
                <img src="<?php echo esc_url($product->image_url); ?>" alt="<?php echo esc_attr($product->name); ?>">
            <?php else : ?>
                <div class="cart-item-placeholder">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21,15 16,10 5,21"></polyline>
                    </svg>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="cart-item-details">
            <h4 class="cart-item-title"><?php echo esc_html($product->name); ?></h4>
            <div class="cart-item-price">$<?php echo number_format($price, 2); ?></div>
            
            <?php if (!empty($item['options'])) : ?>
                <div class="cart-item-options">
                    <?php foreach ($item['options'] as $option_name => $option_value) : ?>
                        <span class="cart-item-option"><?php echo esc_html($option_name); ?>: <?php echo esc_html($option_value); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="cart-item-quantity">
                <button class="quantity-btn quantity-minus" data-action="decrease">-</button>
                <input type="number" class="quantity-input" value="<?php echo esc_attr($item['quantity']); ?>" min="1" max="99">
                <button class="quantity-btn quantity-plus" data-action="increase">+</button>
            </div>
        </div>
        
        <div class="cart-item-total">
            $<?php echo number_format($total, 2); ?>
        </div>
        
        <button class="cart-item-remove" aria-label="Remove item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render add to cart button
 */
function trident_add_to_cart_button($product_id, $quantity = 1, $options = array(), $button_text = 'Add to Cart', $button_class = '') {
    $default_class = 'add-to-cart-btn btn btn-primary';
    $button_class = $default_class . ' ' . $button_class;
    
    $data_attributes = array(
        'data-product-id' => $product_id,
        'data-quantity' => $quantity
    );
    
    if (!empty($options)) {
        $data_attributes['data-options'] = json_encode($options);
    }
    
    $data_string = '';
    foreach ($data_attributes as $key => $value) {
        $data_string .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    
    ?>
    <button class="<?php echo esc_attr($button_class); ?>"<?php echo $data_string; ?>>
        <?php echo esc_html($button_text); ?>
    </button>
    <?php
}

/**
 * Get cart count for display
 */
function trident_get_cart_count_display() {
    return trident_get_cart_count();
}

/**
 * Get cart total for display
 */
function trident_get_cart_total_display() {
    return '$' . number_format(trident_get_cart_total(), 2);
} 