# TRIDENT Mini Cart System

A comprehensive, showable/hidable mini cart system for the TRIDENT WordPress theme. This system provides a modern, responsive shopping cart experience with AJAX functionality.

## Features

- **Floating Cart Button**: Always accessible cart toggle button
- **Header Cart Icon**: Cart icon in the site header with item count
- **AJAX Operations**: All cart operations happen without page reload
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Session-based Storage**: Cart data persists across page visits
- **Quantity Controls**: Easy quantity adjustment within the cart
- **Product Options Support**: Support for custom product options (size, color, etc.)
- **Modern UI**: Clean, modern design with smooth animations
- **Accessibility**: Full keyboard navigation and screen reader support

## Files Added

### Core Files
- `includes/components/mini-cart.php` - Main cart functionality
- `assets/js/mini-cart.js` - JavaScript for cart interactions
- `assets/css/mini-cart.css` - Styling for the mini cart
- `mini-cart-demo.php` - Demo template showing integration examples

### Modified Files
- `functions.php` - Added session support and component loading
- `header.php` - Added cart icon to header

## Quick Start

### 1. Basic Integration

The mini cart is automatically loaded and available on all pages. The cart icon will appear in the header and a floating cart button will be available.

### 2. Adding Products to Cart

Use the `trident_add_to_cart_button()` function to create add-to-cart buttons:

```php
<?php
// Simple add to cart button
trident_add_to_cart_button($product->id, 1, array(), 'Add to Cart');

// Add to cart with custom options
$options = array(
    'size' => '20oz',
    'color' => 'Classic Black'
);
trident_add_to_cart_button($product->id, 1, $options, 'Add with Options');
?>
```

### 3. Manual HTML Button

You can also create buttons manually:

```html
<button class="add-to-cart-btn btn btn-primary" 
        data-product-id="123" 
        data-quantity="1"
        data-options='{"size": "20oz", "color": "Black"}'>
    Add to Cart
</button>
```

### 4. Display Cart Information

```php
<?php
// Display cart count
echo trident_get_cart_count_display();

// Display cart total
echo trident_get_cart_total_display();
?>
```

## API Functions

### Cart Management

```php
// Add product to cart
trident_add_to_cart($product_id, $quantity, $options);

// Remove product from cart
trident_remove_from_cart($cart_key);

// Update cart item quantity
trident_update_cart_quantity($cart_key, $quantity);

// Get cart contents
$cart = trident_get_cart_contents();

// Get cart count
$count = trident_get_cart_count();

// Get cart total
$total = trident_get_cart_total();
```

### Display Functions

```php
// Render add to cart button
trident_add_to_cart_button($product_id, $quantity, $options, $button_text, $button_class);

// Get cart count for display
trident_get_cart_count_display();

// Get cart total for display
trident_get_cart_total_display();

// Render cart item HTML
trident_render_cart_item($cart_key, $item);
```

## JavaScript API

### Global Functions

```javascript
// Add product to cart
tridentAddToCart(productId, quantity, options);

// Open cart
tridentOpenCart();

// Close cart
tridentCloseCart();
```

### Events

The mini cart triggers custom events that you can listen to:

```javascript
// Listen for cart updates
$(document).on('cartUpdated', function(event, data) {
    console.log('Cart updated:', data);
});

// Listen for cart open
$(document).on('cartOpened', function() {
    console.log('Cart opened');
});

// Listen for cart close
$(document).on('cartClosed', function() {
    console.log('Cart closed');
});
```

## AJAX Endpoints

The mini cart provides several AJAX endpoints:

- `trident_add_to_cart` - Add product to cart
- `trident_remove_from_cart` - Remove product from cart
- `trident_update_cart_quantity` - Update item quantity
- `trident_get_cart_count` - Get cart count and total
- `trident_get_cart_contents` - Get full cart contents with HTML

## Styling Customization

### CSS Variables

You can customize the appearance by overriding CSS variables:

```css
:root {
    --cart-primary-color: #3b82f6;
    --cart-secondary-color: #059669;
    --cart-danger-color: #ef4444;
    --cart-border-radius: 12px;
    --cart-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}
```

### Custom Classes

The mini cart uses semantic class names that you can override:

- `.mini-cart-overlay` - The cart overlay
- `.mini-cart-container` - The cart container
- `.mini-cart-item` - Individual cart items
- `.cart-toggle` - The floating cart button
- `.header-cart-toggle` - The header cart icon

## Integration Examples

### Product Card Integration

```php
<div class="product-card">
    <img src="<?php echo $product->image_url; ?>" alt="<?php echo $product->name; ?>">
    <h3><?php echo $product->name; ?></h3>
    <p class="price">$<?php echo number_format($product->price, 2); ?></p>
    
    <!-- Add to cart button -->
    <?php trident_add_to_cart_button($product->id, 1, array(), 'Add to Cart'); ?>
</div>
```

### Product with Options

```php
<div class="product-card">
    <h3><?php echo $product->name; ?></h3>
    
    <!-- Product options -->
    <select class="size-select">
        <option value="20oz">20oz</option>
        <option value="30oz">30oz</option>
        <option value="40oz">40oz</option>
    </select>
    
    <select class="color-select">
        <option value="black">Black</option>
        <option value="white">White</option>
        <option value="blue">Blue</option>
    </select>
    
    <!-- Add to cart with selected options -->
    <button class="add-to-cart-btn btn btn-primary" 
            data-product-id="<?php echo $product->id; ?>"
            data-quantity="1">
        Add to Cart
    </button>
</div>

<script>
jQuery(document).ready(function($) {
    $('.add-to-cart-btn').on('click', function() {
        const $btn = $(this);
        const productId = $btn.data('product-id');
        const quantity = $btn.data('quantity');
        const size = $btn.closest('.product-card').find('.size-select').val();
        const color = $btn.closest('.product-card').find('.color-select').val();
        
        const options = {
            size: size,
            color: color
        };
        
        tridentAddToCart(productId, quantity, options);
    });
});
</script>
```

### Cart Status Display

```php
<div class="cart-status">
    <span>Items: <?php echo trident_get_cart_count_display(); ?></span>
    <span>Total: <?php echo trident_get_cart_total_display(); ?></span>
    <button onclick="tridentOpenCart()">View Cart</button>
</div>
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Mobile Support

The mini cart is fully responsive and optimized for mobile devices:

- Touch-friendly interface
- Swipe gestures supported
- Optimized for small screens
- Fast loading on mobile networks

## Performance

- Lightweight JavaScript (minified: ~15KB)
- Efficient CSS (minified: ~8KB)
- Minimal server requests
- Optimized animations
- Lazy loading of cart contents

## Security

- CSRF protection with nonces
- Input sanitization
- SQL injection prevention
- XSS protection
- Session security

## Troubleshooting

### Cart Not Loading

1. Check if sessions are enabled in PHP
2. Verify that the mini cart component is loaded
3. Check browser console for JavaScript errors
4. Ensure jQuery is loaded

### Products Not Adding

1. Verify product ID exists in database
2. Check AJAX nonce is valid
3. Ensure product has required fields (name, price)
4. Check server error logs

### Styling Issues

1. Clear browser cache
2. Check CSS file is loading
3. Verify CSS specificity
4. Check for conflicting styles

## Demo

Visit `/mini-cart-demo.php` to see the mini cart in action with examples of all features.

## Support

For issues or questions about the mini cart system, please check:

1. This README file
2. The demo template (`mini-cart-demo.php`)
3. Browser console for errors
4. WordPress debug log

## Changelog

### Version 1.0.0
- Initial release
- Basic cart functionality
- AJAX operations
- Responsive design
- Session storage
- Product options support 