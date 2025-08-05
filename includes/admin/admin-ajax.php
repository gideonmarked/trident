<?php
/**
 * Admin AJAX Handlers
 *
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add product AJAX handler
 */
function trident_add_product_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_product_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get form data
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_details = sanitize_textarea_field($_POST['product_details']);
    $size = sanitize_text_field($_POST['size']);

    // Get color data
    $body_colors = json_decode(stripslashes($_POST['body_colors']), true);
    $cap_colors = json_decode(stripslashes($_POST['cap_colors']), true);
    $boot_colors = json_decode(stripslashes($_POST['boot_colors']), true);

    // Validate required fields
    if (empty($product_name)) {
        wp_send_json_error('Product name is required');
    }

    if (empty($product_details)) {
        wp_send_json_error('Product details are required');
    }

    // Validate that at least one color is selected for each category
    if (empty($body_colors) || !is_array($body_colors)) {
        wp_send_json_error('At least one body color is required');
    }

    if (empty($cap_colors) || !is_array($cap_colors)) {
        wp_send_json_error('At least one cap color is required');
    }

    if (empty($boot_colors) || !is_array($boot_colors)) {
        wp_send_json_error('At least one boot color is required');
    }

    // Handle image upload
    $image_url = '';
    if (!empty($_FILES['product_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('product_image', 0);

        if (!is_wp_error($attachment_id)) {
            $image_url = wp_get_attachment_url($attachment_id);
        }
    }

    // Prepare product data
    $product_data = array(
        'name' => $product_name,
        'description' => $product_details,
        'price' => 1299.00, // Default price
        'image_url' => $image_url,
        'body_colors' => $body_colors,
        'cap_colors' => $cap_colors,
        'boot_colors' => $boot_colors,
        'sizes' => json_encode(array($size)),
        'stock_quantity' => 100,
        'status' => 'active'
    );

    // Insert product
    $product_id = trident_insert_product($product_data);

    if ($product_id) {
        wp_send_json_success(array(
            'message' => 'Product added successfully',
            'product_id' => $product_id
        ));
    } else {
        wp_send_json_error('Failed to add product');
    }
}
add_action('wp_ajax_trident_add_product', 'trident_add_product_ajax');

/**
 * Delete product AJAX handler
 */
function trident_delete_product_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_product_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    $product_id = intval($_POST['product_id']);

    if (trident_delete_product($product_id)) {
        wp_send_json_success('Product deleted successfully');
    } else {
        wp_send_json_error('Failed to delete product');
    }
}
add_action('wp_ajax_trident_delete_product', 'trident_delete_product_ajax');

/**
 * Add banner AJAX handler
 */
function trident_add_banner_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_banner_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get form data
    $title = sanitize_text_field($_POST['banner_name']); // Use banner_name field for title
    $description = sanitize_textarea_field($_POST['description']);
    $position = sanitize_text_field($_POST['position']);
    $link_url = esc_url_raw($_POST['link_url']);

    // Title is optional - no validation needed

    // Handle image upload
    $image_url = '';
    if (!empty($_FILES['banner_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('banner_image', 0);

        if (!is_wp_error($attachment_id)) {
            $image_url = wp_get_attachment_url($attachment_id);
        }
    }

    // Prepare banner data
    $banner_data = array(
        'title' => $title,
        'description' => $description,
        'image_url' => $image_url,
        'position' => $position,
        'link_url' => $link_url,
        'status' => 'active'
    );

    // Insert banner
    $banner_id = trident_insert_banner($banner_data);

    if ($banner_id) {
        wp_send_json_success(array(
            'message' => 'Banner added successfully',
            'banner_id' => $banner_id
        ));
    } else {
        wp_send_json_error('Failed to add banner');
    }
}
add_action('wp_ajax_trident_add_banner', 'trident_add_banner_ajax');

/**
 * Delete banner AJAX handler
 */
function trident_delete_banner_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_banner_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    $banner_id = intval($_POST['banner_id']);

    if (trident_delete_banner($banner_id)) {
        wp_send_json_success('Banner deleted successfully');
    } else {
        wp_send_json_error('Failed to delete banner');
    }
}
add_action('wp_ajax_trident_delete_banner', 'trident_delete_banner_ajax');

/**
 * Get dashboard statistics AJAX handler
 */
function trident_get_dashboard_stats_ajax() {
    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    $stats = array(
        'products' => trident_get_product_stats(),
        'orders' => trident_get_order_stats(),
        'banners' => trident_get_banner_stats()
    );

    wp_send_json_success($stats);
}
add_action('wp_ajax_trident_get_dashboard_stats', 'trident_get_dashboard_stats_ajax');

/**
 * Generate dummy products AJAX handler
 */
function trident_generate_dummy_products_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_dummy_products_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get the last product ID to avoid redundancy
    global $wpdb;
    $table_products = $wpdb->prefix . 'trident_products';
    $last_id = $wpdb->get_var("SELECT MAX(id) FROM $table_products");
    $start_id = $last_id ? $last_id + 1 : 1;
    
    // Check if we already have 10 or more dummy products
    $existing_products = trident_get_products();
    if (count($existing_products) >= 10) {
        wp_send_json_error('You already have 10 or more products. Please clear some products first if you want to generate new ones.');
    }
    
    $dummy_products = array(
        array(
            'name' => 'Classic TRIDENT Tumbler',
            'description' => 'Our signature tumbler with premium insulation and sleek design. Perfect for hot and cold beverages.',
            'price' => 1299.00,
            'body_colors' => array('#1f2937', '#059669', '#dc2626', '#f59e0b', '#3b82f6'),
            'cap_colors' => array('#1f2937', '#059669', '#dc2626'),
            'boot_colors' => array('#1f2937', '#059669', '#dc2626'),
            'sizes' => array('20oz', '30oz', '40oz')
        ),
        array(
            'name' => 'Premium TRIDENT Tumbler',
            'description' => 'Upgraded version with enhanced insulation and premium materials. Ideal for outdoor adventures.',
            'price' => 1499.00,
            'body_colors' => array('#7c3aed', '#ec4899', '#f97316', '#10b981', '#6366f1'),
            'cap_colors' => array('#7c3aed', '#ec4899', '#f97316'),
            'boot_colors' => array('#7c3aed', '#ec4899', '#f97316'),
            'sizes' => array('20oz', '30oz', '40oz')
        ),
        array(
            'name' => 'TRIDENT Travel Tumbler',
            'description' => 'Compact and lightweight design perfect for travel. Fits in most cup holders.',
            'price' => 999.00,
            'body_colors' => array('#6b7280', '#84cc16', '#f43f5e', '#fbbf24', '#8b5cf6'),
            'cap_colors' => array('#6b7280', '#84cc16', '#f43f5e'),
            'boot_colors' => array('#6b7280', '#84cc16', '#f43f5e'),
            'sizes' => array('16oz', '20oz')
        ),
        array(
            'name' => 'TRIDENT Sports Tumbler',
            'description' => 'Designed for active lifestyles with enhanced grip and durability. Perfect for gym and sports.',
            'price' => 1199.00,
            'body_colors' => array('#ef4444', '#22c55e', '#3b82f6', '#f59e0b', '#8b5cf6'),
            'cap_colors' => array('#ef4444', '#22c55e', '#3b82f6'),
            'boot_colors' => array('#ef4444', '#22c55e', '#3b82f6'),
            'sizes' => array('24oz', '32oz')
        ),
        array(
            'name' => 'TRIDENT Office Tumbler',
            'description' => 'Professional design perfect for the workplace. Keeps beverages at optimal temperature.',
            'price' => 1099.00,
            'body_colors' => array('#374151', '#6b7280', '#9ca3af', '#d1d5db', '#f3f4f6'),
            'cap_colors' => array('#374151', '#6b7280', '#9ca3af'),
            'boot_colors' => array('#374151', '#6b7280', '#9ca3af'),
            'sizes' => array('20oz', '30oz')
        ),
        array(
            'name' => 'TRIDENT Adventure Tumbler',
            'description' => 'Rugged design for outdoor enthusiasts. Built to withstand extreme conditions.',
            'price' => 1399.00,
            'body_colors' => array('#92400e', '#78350f', '#451a03', '#dc2626', '#059669'),
            'cap_colors' => array('#92400e', '#78350f', '#451a03'),
            'boot_colors' => array('#92400e', '#78350f', '#451a03'),
            'sizes' => array('32oz', '40oz')
        ),
        array(
            'name' => 'TRIDENT Minimalist Tumbler',
            'description' => 'Clean and simple design with focus on functionality. Perfect for everyday use.',
            'price' => 899.00,
            'body_colors' => array('#ffffff', '#000000', '#6b7280', '#d1d5db'),
            'cap_colors' => array('#ffffff', '#000000', '#6b7280'),
            'boot_colors' => array('#ffffff', '#000000', '#6b7280'),
            'sizes' => array('16oz', '20oz')
        ),
        array(
            'name' => 'TRIDENT Family Tumbler',
            'description' => 'Large capacity tumbler perfect for families. Great for sharing beverages.',
            'price' => 1599.00,
            'body_colors' => array('#fbbf24', '#f59e0b', '#d97706', '#92400e', '#78350f'),
            'cap_colors' => array('#fbbf24', '#f59e0b', '#d97706'),
            'boot_colors' => array('#fbbf24', '#f59e0b', '#d97706'),
            'sizes' => array('40oz', '50oz')
        ),
        array(
            'name' => 'TRIDENT Student Tumbler',
            'description' => 'Affordable option for students. Reliable insulation at a great price.',
            'price' => 799.00,
            'body_colors' => array('#3b82f6', '#1d4ed8', '#1e40af', '#1e3a8a', '#172554'),
            'cap_colors' => array('#3b82f6', '#1d4ed8', '#1e40af'),
            'boot_colors' => array('#3b82f6', '#1d4ed8', '#1e40af'),
            'sizes' => array('16oz', '20oz')
        ),
        array(
            'name' => 'TRIDENT Premium Collection',
            'description' => 'Limited edition premium tumbler with exclusive colors and premium materials.',
            'price' => 1799.00,
            'body_colors' => array('#7c2d12', '#991b1b', '#7f1d1d', '#450a0a', '#dc2626'),
            'cap_colors' => array('#7c2d12', '#991b1b', '#7f1d1d'),
            'boot_colors' => array('#7c2d12', '#991b1b', '#7f1d1d'),
            'sizes' => array('20oz', '30oz', '40oz')
        )
    );
    
    $success_count = 0;
    foreach ($dummy_products as $index => $product_data) {
        $product_data['image_url'] = get_template_directory_uri() . '/assets/images/tumbler-default.png';
        $product_data['stock_quantity'] = 100;
        $product_data['status'] = 'active';
        
        // Use custom ID starting from the last ID + 1
        $custom_id = $start_id + $index;
        if (trident_insert_product_with_id($product_data, $custom_id)) {
            $success_count++;
        }
    }
    
    if ($success_count > 0) {
        wp_send_json_success(array(
            'message' => "Successfully generated $success_count dummy products",
            'count' => $success_count
        ));
    } else {
        wp_send_json_error('Failed to generate dummy products');
    }
}
add_action('wp_ajax_trident_generate_dummy_products', 'trident_generate_dummy_products_ajax');

/**
 * Clear all products AJAX handler
 */
function trident_clear_all_products_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_clear_products_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    global $wpdb;
    $table_products = $wpdb->prefix . 'trident_products';
    
    // Delete all products
    $result = $wpdb->query("DELETE FROM $table_products");
    
    if ($result !== false) {
        wp_send_json_success(array(
            'message' => 'All products cleared successfully',
            'deleted_count' => $result
        ));
    } else {
        wp_send_json_error('Failed to clear products');
    }
}
add_action('wp_ajax_trident_clear_all_products', 'trident_clear_all_products_ajax');

/**
 * Generate dummy orders AJAX handler
 */
/**
 * Generate dummy orders with exactly 20 order items per order
 * Creates 20 orders, each with 20 order items for a total of 400 order items
 */
function trident_generate_dummy_orders_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_dummy_orders_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    global $wpdb;

    // Dummy customer data
    $dummy_customers = [
        ['first_name' => 'Maria', 'last_name' => 'Santos', 'email' => 'maria.santos@email.com', 'phone' => '09123456789', 'address' => '123 Rizal Street', 'city' => 'Manila', 'state' => 'Metro Manila', 'zip_code' => '1000'],
        ['first_name' => 'Juan', 'last_name' => 'Cruz', 'email' => 'juan.cruz@email.com', 'phone' => '09234567890', 'address' => '456 Bonifacio Avenue', 'city' => 'Quezon City', 'state' => 'Metro Manila', 'zip_code' => '1100'],
        ['first_name' => 'Ana', 'last_name' => 'Garcia', 'email' => 'ana.garcia@email.com', 'phone' => '09345678901', 'address' => '789 Mabini Road', 'city' => 'Makati', 'state' => 'Metro Manila', 'zip_code' => '1200'],
        ['first_name' => 'Pedro', 'last_name' => 'Martinez', 'email' => 'pedro.martinez@email.com', 'phone' => '09456789012', 'address' => '321 Aguinaldo Street', 'city' => 'Pasig', 'state' => 'Metro Manila', 'zip_code' => '1600'],
        ['first_name' => 'Carmen', 'last_name' => 'Lopez', 'email' => 'carmen.lopez@email.com', 'phone' => '09567890123', 'address' => '654 Luna Avenue', 'city' => 'Taguig', 'state' => 'Metro Manila', 'zip_code' => '1630'],
        ['first_name' => 'Roberto', 'last_name' => 'Reyes', 'email' => 'roberto.reyes@email.com', 'phone' => '09678901234', 'address' => '987 Del Pilar Street', 'city' => 'Caloocan', 'state' => 'Metro Manila', 'zip_code' => '1400'],
        ['first_name' => 'Isabel', 'last_name' => 'Flores', 'email' => 'isabel.flores@email.com', 'phone' => '09789012345', 'address' => '147 Roxas Boulevard', 'city' => 'Pasay', 'state' => 'Metro Manila', 'zip_code' => '1300'],
        ['first_name' => 'Miguel', 'last_name' => 'Gonzalez', 'email' => 'miguel.gonzalez@email.com', 'phone' => '09890123456', 'address' => '258 Quezon Avenue', 'city' => 'Marikina', 'state' => 'Metro Manila', 'zip_code' => '1800'],
        ['first_name' => 'Elena', 'last_name' => 'Torres', 'email' => 'elena.torres@email.com', 'phone' => '09901234567', 'address' => '369 Commonwealth Avenue', 'city' => 'Quezon City', 'state' => 'Metro Manila', 'zip_code' => '1120'],
        ['first_name' => 'Carlos', 'last_name' => 'Villanueva', 'email' => 'carlos.villanueva@email.com', 'phone' => '09012345678', 'address' => '741 EDSA', 'city' => 'Mandaluyong', 'state' => 'Metro Manila', 'zip_code' => '1550']
    ];

    // Dummy order data
    $dummy_orders = [
        ['order_number' => 'TRD-2024-001', 'total_amount' => 1395.00, 'status' => 'completed', 'payment_method' => 'Credit Card', 'notes' => 'Delivered successfully'],
        ['order_number' => 'TRD-2024-002', 'total_amount' => 2790.00, 'status' => 'processing', 'payment_method' => 'PayPal', 'notes' => 'Ready for shipping'],
        ['order_number' => 'TRD-2024-003', 'total_amount' => 1395.00, 'status' => 'pending', 'payment_method' => 'Cash on Delivery', 'notes' => 'Awaiting payment confirmation'],
        ['order_number' => 'TRD-2024-004', 'total_amount' => 4185.00, 'status' => 'completed', 'payment_method' => 'Bank Transfer', 'notes' => 'Delivered to office'],
        ['order_number' => 'TRD-2024-005', 'total_amount' => 1395.00, 'status' => 'cancelled', 'payment_method' => 'Credit Card', 'notes' => 'Customer requested cancellation'],
        ['order_number' => 'TRD-2024-006', 'total_amount' => 2790.00, 'status' => 'completed', 'payment_method' => 'PayPal', 'notes' => 'Gift order'],
        ['order_number' => 'TRD-2024-007', 'total_amount' => 1395.00, 'status' => 'processing', 'payment_method' => 'Credit Card', 'notes' => 'Express shipping requested'],
        ['order_number' => 'TRD-2024-008', 'total_amount' => 5580.00, 'status' => 'completed', 'payment_method' => 'Bank Transfer', 'notes' => 'Bulk order for company'],
        ['order_number' => 'TRD-2024-009', 'total_amount' => 1395.00, 'status' => 'pending', 'payment_method' => 'Cash on Delivery', 'notes' => 'New customer'],
        ['order_number' => 'TRD-2024-010', 'total_amount' => 2790.00, 'status' => 'completed', 'payment_method' => 'PayPal', 'notes' => 'Repeat customer'],
        ['order_number' => 'TRD-2024-011', 'total_amount' => 1395.00, 'status' => 'processing', 'payment_method' => 'Credit Card', 'notes' => 'Custom color request'],
        ['order_number' => 'TRD-2024-012', 'total_amount' => 4185.00, 'status' => 'completed', 'payment_method' => 'Bank Transfer', 'notes' => 'Family order'],
        ['order_number' => 'TRD-2024-013', 'total_amount' => 1395.00, 'status' => 'pending', 'payment_method' => 'PayPal', 'notes' => 'International shipping'],
        ['order_number' => 'TRD-2024-014', 'total_amount' => 2790.00, 'status' => 'completed', 'payment_method' => 'Credit Card', 'notes' => 'Wedding gift'],
        ['order_number' => 'TRD-2024-015', 'total_amount' => 1395.00, 'status' => 'processing', 'payment_method' => 'Cash on Delivery', 'notes' => 'Local delivery'],
        ['order_number' => 'TRD-2024-016', 'total_amount' => 6975.00, 'status' => 'completed', 'payment_method' => 'Bank Transfer', 'notes' => 'Corporate order'],
        ['order_number' => 'TRD-2024-017', 'total_amount' => 1395.00, 'status' => 'cancelled', 'payment_method' => 'Credit Card', 'notes' => 'Out of stock'],
        ['order_number' => 'TRD-2024-018', 'total_amount' => 2790.00, 'status' => 'completed', 'payment_method' => 'PayPal', 'notes' => 'Birthday gift'],
        ['order_number' => 'TRD-2024-019', 'total_amount' => 1395.00, 'status' => 'processing', 'payment_method' => 'Credit Card', 'notes' => 'Holiday order'],
        ['order_number' => 'TRD-2024-020', 'total_amount' => 4185.00, 'status' => 'completed', 'payment_method' => 'Bank Transfer', 'notes' => 'Anniversary gift']
    ];

    // Product data for order items
    $products = [
        ['name' => '32 oz Lightweight Wide Mouth Trail Series™', 'price' => 1395.00],
        ['name' => '24 oz Insulated Tumbler with Straw', 'price' => 1299.00],
        ['name' => '40 oz Wide Mouth Water Bottle', 'price' => 1499.00]
    ];

    $colors = function_exists('trident_get_available_colors') ? array_column(trident_get_available_colors(), 'name') : ['Yellow', 'Green', 'Black', 'Mint', 'Gold'];
    $sizes = ['24oz', '32oz', '40oz'];

    try {
        // Insert customers or get existing ones
        $customer_ids = [];
        foreach ($dummy_customers as $customer) {
            // Check if customer already exists
            $existing_customer = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}trident_customers WHERE email = %s",
                    $customer['email']
                )
            );
            
            if ($existing_customer) {
                // Use existing customer ID
                $customer_ids[] = $existing_customer->id;
            } else {
                // Insert new customer
                $result = $wpdb->insert(
                    $wpdb->prefix . 'trident_customers',
                    $customer,
                    ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
                );
                
                if ($result) {
                    $customer_ids[] = $wpdb->insert_id;
                }
            }
        }

        // Check if we have any customers
        if (empty($customer_ids)) {
            wp_send_json_error('No customers available for orders');
        }

        // Insert orders
        $order_ids = [];
        foreach ($dummy_orders as $index => $order) {
            // Check if order already exists
            $existing_order = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}trident_orders WHERE order_number = %s",
                    $order['order_number']
                )
            );
            
            if ($existing_order) {
                // Use existing order ID
                $order_ids[] = $existing_order->id;
            } else {
                $customer_id = $customer_ids[$index % count($customer_ids)];
                
                $order_data = array_merge($order, [
                    'customer_id' => $customer_id,
                    'shipping_address' => 'Same as billing address',
                    'billing_address' => 'Same as shipping address'
                ]);
                
                $result = $wpdb->insert(
                    $wpdb->prefix . 'trident_orders',
                    $order_data,
                    ['%d', '%s', '%f', '%s', '%s', '%s', '%s', '%s']
                );
                
                if ($result) {
                    $order_ids[] = $wpdb->insert_id;
                }
            }
        }

        // Insert order items - exactly 20 items per order
        $order_items_created = 0;
        $orders_with_items = 0;
        $target_items_per_order = 20;
        
        foreach ($order_ids as $order_id) {
            // Check if order already has items
            $existing_items = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}trident_order_items WHERE order_id = %d",
                    $order_id
                )
            );
            
            // Only create items if the order doesn't already have 20 items
            if ($existing_items < $target_items_per_order) {
                $items_to_create = $target_items_per_order - $existing_items;
                
                for ($i = 0; $i < $items_to_create; $i++) {
                    $product = $products[array_rand($products)];
                    $quantity = rand(1, 3);
                    $price = $product['price'];
                    $color = $colors[array_rand($colors)];
                    $size = $sizes[array_rand($sizes)];
                    
                    $order_item = [
                        'order_id' => $order_id,
                        'product_id' => rand(1, 10), // Assuming we have products with IDs 1-10
                        'product_name' => $product['name'],
                        'quantity' => $quantity,
                        'price' => $price,
                        'color' => $color,
                        'size' => $size
                    ];
                    
                    $result = $wpdb->insert(
                        $wpdb->prefix . 'trident_order_items',
                        $order_item,
                        ['%d', '%d', '%s', '%d', '%f', '%s', '%s']
                    );
                    
                    if ($result) {
                        $order_items_created++;
                    }
                }
                
                // Verify this order now has exactly 20 items
                $final_item_count = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT COUNT(*) FROM {$wpdb->prefix}trident_order_items WHERE order_id = %d",
                        $order_id
                    )
                );
                
                if ($final_item_count == $target_items_per_order) {
                    $orders_with_items++;
                }
            } else {
                // Order already has 20 items
                $orders_with_items++;
            }
        }

        wp_send_json_success(array(
            'message' => 'Dummy orders generated successfully with 20 items per order',
            'customers_available' => count($customer_ids),
            'orders_available' => count($order_ids),
            'orders_with_20_items' => $orders_with_items,
            'order_items_created' => $order_items_created,
            'target_items_per_order' => $target_items_per_order
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error generating dummy orders: ' . $e->getMessage());
    }
}
add_action('wp_ajax_trident_generate_dummy_orders', 'trident_generate_dummy_orders_ajax');

/**
 * Get single product AJAX handler
 */
function trident_get_product_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_product_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get product ID
    $product_id = intval($_POST['product_id']);
    
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
    }

    // Get product data
    $product = trident_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error('Product not found');
    }

    // Return product data
    wp_send_json_success($product);
}
add_action('wp_ajax_trident_get_product', 'trident_get_product_ajax');

/**
 * Update product AJAX handler
 */
function trident_update_product_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_product_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get form data
    $product_id = intval($_POST['product_id']);
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_details = sanitize_textarea_field($_POST['product_details']);
    $product_price = floatval($_POST['product_price']);
    
    // Get color data
    $body_colors = isset($_POST['body_colors']) ? stripslashes($_POST['body_colors']) : '';
    $cap_colors = isset($_POST['cap_colors']) ? stripslashes($_POST['cap_colors']) : '';
    $boot_colors = isset($_POST['boot_colors']) ? stripslashes($_POST['boot_colors']) : '';

    // Validate required fields
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
    }

    if (empty($product_name)) {
        wp_send_json_error('Product name is required');
    }

    if (empty($product_details)) {
        wp_send_json_error('Product details are required');
    }

    if ($product_price <= 0) {
        wp_send_json_error('Product price must be greater than 0');
    }

    // Validate color data
    if (empty($body_colors)) {
        wp_send_json_error('At least one body color is required');
    }

    if (empty($cap_colors)) {
        wp_send_json_error('At least one cap color is required');
    }

    if (empty($boot_colors)) {
        wp_send_json_error('At least one boot color is required');
    }

    // Handle image upload if new image is provided
    $image_url = '';
    if (!empty($_FILES['product_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('product_image', 0);

        if (!is_wp_error($attachment_id)) {
            $image_url = wp_get_attachment_url($attachment_id);
        }
    }

    // Prepare product data
    $product_data = array(
        'name' => $product_name,
        'description' => $product_details,
        'price' => $product_price,
        'body_colors' => $body_colors,
        'cap_colors' => $cap_colors,
        'boot_colors' => $boot_colors
    );

    // Add image URL if new image was uploaded
    if (!empty($image_url)) {
        $product_data['image_url'] = $image_url;
    }

    // Update product
    $result = trident_update_product($product_id, $product_data);

    if ($result) {
        wp_send_json_success(array(
            'message' => 'Product updated successfully',
            'product_id' => $product_id
        ));
    } else {
        wp_send_json_error('Failed to update product');
    }
}
add_action('wp_ajax_trident_update_product', 'trident_update_product_ajax'); 