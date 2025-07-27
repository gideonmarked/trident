<?php
/**
 * TRIDENT Database Management
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Create custom database tables on theme activation
function trident_create_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // TRIDENT Products table
    $table_products = $wpdb->prefix . 'trident_products';
    $sql_products = "CREATE TABLE $table_products (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description text,
        price decimal(10,2) NOT NULL DEFAULT 0.00,
        image_url varchar(500),
        body_colors text,
        cap_colors text,
        boot_colors text,
        sizes text,
        stock_quantity int(11) DEFAULT 0,
        status varchar(20) DEFAULT 'active',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    // TRIDENT Orders table
    $table_orders = $wpdb->prefix . 'trident_orders';
    $sql_orders = "CREATE TABLE $table_orders (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        order_number varchar(50) NOT NULL,
        customer_id mediumint(9) NOT NULL,
        customer_name varchar(255) NOT NULL,
        customer_email varchar(255) NOT NULL,
        customer_phone varchar(50),
        customer_address text,
        total_amount decimal(10,2) NOT NULL DEFAULT 0.00,
        subtotal decimal(10,2) NOT NULL DEFAULT 0.00,
        tax_amount decimal(10,2) NOT NULL DEFAULT 0.00,
        shipping_amount decimal(10,2) NOT NULL DEFAULT 0.00,
        discount_amount decimal(10,2) NOT NULL DEFAULT 0.00,
        status varchar(20) DEFAULT 'pending',
        payment_method varchar(50),
        payment_status varchar(20) DEFAULT 'pending',
        shipping_method varchar(50),
        shipping_status varchar(20) DEFAULT 'pending',
        notes text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY order_number (order_number),
        KEY customer_id (customer_id),
        KEY status (status),
        KEY payment_status (payment_status),
        KEY created_at (created_at)
    ) $charset_collate;";
    
    // TRIDENT Order Items table
    $table_order_items = $wpdb->prefix . 'trident_order_items';
    $sql_order_items = "CREATE TABLE $table_order_items (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        order_id mediumint(9) NOT NULL,
        product_id mediumint(9) NOT NULL,
        product_name varchar(255) NOT NULL,
        product_sku varchar(100),
        product_image_url varchar(500),
        quantity int(11) NOT NULL DEFAULT 1,
        unit_price decimal(10,2) NOT NULL,
        total_price decimal(10,2) NOT NULL,
        selected_colors text,
        selected_size varchar(20),
        customization_notes text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY order_id (order_id),
        KEY product_id (product_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    dbDelta($sql_products);
    dbDelta($sql_orders);
    dbDelta($sql_order_items);
    
    // TRIDENT Banners table
    $table_banners = $wpdb->prefix . 'trident_banners';
    $sql_banners = "CREATE TABLE $table_banners (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        subtitle varchar(255),
        description text,
        price decimal(10,2) DEFAULT 0.00,
        image_url varchar(500) NOT NULL,
        status varchar(20) DEFAULT 'active',
        sort_order int(11) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    // TRIDENT Customers table
    $table_customers = $wpdb->prefix . 'trident_customers';
    $sql_customers = "CREATE TABLE $table_customers (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(255) NOT NULL,
        country varchar(100) NOT NULL,
        street_address_1 varchar(255) NOT NULL,
        street_address_2 varchar(255),
        city varchar(100) NOT NULL,
        state_province varchar(100) NOT NULL,
        zip_postal_code varchar(20) NOT NULL,
        phone_number varchar(50) NOT NULL,
        status varchar(20) DEFAULT 'active',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    dbDelta($sql_products);
    dbDelta($sql_orders);
    dbDelta($sql_order_items);
    dbDelta($sql_banners);
    dbDelta($sql_customers);
    
    // Add version option to track database schema
    update_option('trident_db_version', '1.0');
}

// Get all products
function trident_get_products($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => 'active',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'trident_products';
    
    $where_clause = "WHERE status = '" . esc_sql($args['status']) . "'";
    $order_clause = "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']);
    $limit_clause = $args['limit'] > 0 ? "LIMIT " . intval($args['limit']) : '';
    $offset_clause = $args['offset'] > 0 ? "OFFSET " . intval($args['offset']) : '';
    
    $sql = "SELECT * FROM $table_name $where_clause $order_clause $limit_clause $offset_clause";
    
    return $wpdb->get_results($sql);
}

// Get single product by ID
function trident_get_product($product_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $product_id);
    
    return $wpdb->get_row($sql);
}

// Insert new product
function trident_insert_product($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    
    $defaults = array(
        'name' => '',
        'description' => '',
        'price' => 0.00,
        'image_url' => '',
        'body_colors' => '',
        'cap_colors' => '',
        'boot_colors' => '',
        'sizes' => '',
        'stock_quantity' => 0,
        'status' => 'active'
    );
    
    $data = wp_parse_args($data, $defaults);
    
    // Convert arrays to JSON strings
    if (is_array($data['body_colors'])) {
        $data['body_colors'] = json_encode($data['body_colors']);
    }
    if (is_array($data['cap_colors'])) {
        $data['cap_colors'] = json_encode($data['cap_colors']);
    }
    if (is_array($data['boot_colors'])) {
        $data['boot_colors'] = json_encode($data['boot_colors']);
    }
    if (is_array($data['sizes'])) {
        $data['sizes'] = json_encode($data['sizes']);
    }
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

// Update product
function trident_update_product($product_id, $data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    
    // Convert arrays to JSON strings
    if (isset($data['body_colors']) && is_array($data['body_colors'])) {
        $data['body_colors'] = json_encode($data['body_colors']);
    }
    if (isset($data['cap_colors']) && is_array($data['cap_colors'])) {
        $data['cap_colors'] = json_encode($data['cap_colors']);
    }
    if (isset($data['boot_colors']) && is_array($data['boot_colors'])) {
        $data['boot_colors'] = json_encode($data['boot_colors']);
    }
    if (isset($data['sizes']) && is_array($data['sizes'])) {
        $data['sizes'] = json_encode($data['sizes']);
    }
    
    $result = $wpdb->update(
        $table_name,
        $data,
        array('id' => $product_id)
    );
    
    return $result !== false;
}

// Delete product
function trident_delete_product($product_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    
    return $wpdb->delete($table_name, array('id' => $product_id));
}

// Get product colors as array
function trident_get_product_colors($product, $color_type = 'body') {
    $colors_field = $color_type . '_colors';
    
    if (isset($product->$colors_field) && !empty($product->$colors_field)) {
        $colors = json_decode($product->$colors_field, true);
        return is_array($colors) ? $colors : array();
    }
    
    return array();
}

// Get product sizes as array
function trident_get_product_sizes($product) {
    if (isset($product->sizes) && !empty($product->sizes)) {
        $sizes = json_decode($product->sizes, true);
        return is_array($sizes) ? $sizes : array();
    }
    
    return array();
}

// Insert new order
function trident_insert_order($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $defaults = array(
        'order_number' => trident_generate_order_number(),
        'customer_id' => 0,
        'customer_name' => '',
        'customer_email' => '',
        'customer_phone' => '',
        'customer_address' => '',
        'total_amount' => 0.00,
        'subtotal' => 0.00,
        'tax_amount' => 0.00,
        'shipping_amount' => 0.00,
        'discount_amount' => 0.00,
        'status' => 'pending',
        'payment_method' => '',
        'payment_status' => 'pending',
        'shipping_method' => '',
        'shipping_status' => 'pending',
        'notes' => ''
    );
    
    $data = wp_parse_args($data, $defaults);
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

// Insert order item
function trident_insert_order_item($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_order_items';
    
    $defaults = array(
        'order_id' => 0,
        'product_id' => 0,
        'product_name' => '',
        'product_sku' => '',
        'product_image_url' => '',
        'quantity' => 1,
        'unit_price' => 0.00,
        'total_price' => 0.00,
        'selected_colors' => '',
        'selected_size' => '',
        'customization_notes' => ''
    );
    
    $data = wp_parse_args($data, $defaults);
    
    // Convert arrays to JSON strings
    if (is_array($data['selected_colors'])) {
        $data['selected_colors'] = json_encode($data['selected_colors']);
    }
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

// Get single order by ID
function trident_get_order($order_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $order_id);
    
    return $wpdb->get_row($sql);
}

// Update order
function trident_update_order($order_id, $data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $result = $wpdb->update(
        $table_name,
        $data,
        array('id' => $order_id)
    );
    
    return $result !== false;
}

// Get orders
function trident_get_orders($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => '',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $where_clause = '';
    if (!empty($args['status'])) {
        $where_clause = "WHERE status = '" . esc_sql($args['status']) . "'";
    }
    
    $order_clause = "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']);
    $limit_clause = $args['limit'] > 0 ? "LIMIT " . intval($args['limit']) : '';
    $offset_clause = $args['offset'] > 0 ? "OFFSET " . intval($args['offset']) : '';
    
    $sql = "SELECT * FROM $table_name $where_clause $order_clause $limit_clause $offset_clause";
    
    return $wpdb->get_results($sql);
}

// Get orders with customer details
function trident_get_orders_with_customers($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => '',
        'orderby' => 'o.created_at',
        'order' => 'DESC',
        'limit' => -1,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $orders_table = $wpdb->prefix . 'trident_orders';
    $customers_table = $wpdb->prefix . 'trident_customers';
    
    $where_clause = '';
    if (!empty($args['status'])) {
        $where_clause = "WHERE o.status = '" . esc_sql($args['status']) . "'";
    }
    
    $order_clause = "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']);
    $limit_clause = $args['limit'] > 0 ? "LIMIT " . intval($args['limit']) : '';
    $offset_clause = $args['offset'] > 0 ? "OFFSET " . intval($args['offset']) : '';
    
    $sql = "SELECT o.*, c.first_name, c.last_name, c.email, c.phone_number, 
                   c.street_address_1, c.street_address_2, c.city, c.state_province, 
                   c.zip_postal_code, c.country
            FROM $orders_table o
            LEFT JOIN $customers_table c ON o.customer_id = c.id
            $where_clause $order_clause $limit_clause $offset_clause";
    
    return $wpdb->get_results($sql);
}

// Get order with items
function trident_get_order_with_items($order_id) {
    global $wpdb;
    
    $orders_table = $wpdb->prefix . 'trident_orders';
    $items_table = $wpdb->prefix . 'trident_order_items';
    
    // Get order
    $order = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $orders_table WHERE id = %d",
        $order_id
    ));
    
    if (!$order) {
        return false;
    }
    
    // Get order items
    $order->items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $items_table WHERE order_id = %d",
        $order_id
    ));
    
    return $order;
}

// Get order with customer details
function trident_get_order_with_customer($order_id) {
    global $wpdb;
    
    $orders_table = $wpdb->prefix . 'trident_orders';
    $customers_table = $wpdb->prefix . 'trident_customers';
    $items_table = $wpdb->prefix . 'trident_order_items';
    
    // Get order with customer details
    $order = $wpdb->get_row($wpdb->prepare(
        "SELECT o.*, c.first_name, c.last_name, c.email, c.phone_number, 
                c.street_address_1, c.street_address_2, c.city, c.state_province, 
                c.zip_postal_code, c.country
         FROM $orders_table o
         LEFT JOIN $customers_table c ON o.customer_id = c.id
         WHERE o.id = %d",
        $order_id
    ));
    
    if (!$order) {
        return false;
    }
    
    // Get order items
    $order->items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $items_table WHERE order_id = %d",
        $order_id
    ));
    
    return $order;
}

// Generate unique order number
function trident_generate_order_number() {
    $prefix = 'TR';
    $date = date('Ymd');
    $random = strtoupper(substr(md5(uniqid()), 0, 6));
    
    return $prefix . $date . $random;
}

// Update order status
function trident_update_order_status($order_id, $status) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    return $wpdb->update(
        $table_name,
        array('status' => $status),
        array('id' => $order_id)
    );
}

// Update order payment status
function trident_update_order_payment_status($order_id, $payment_status) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    return $wpdb->update(
        $table_name,
        array('payment_status' => $payment_status),
        array('id' => $order_id)
    );
}

// Update order shipping status
function trident_update_order_shipping_status($order_id, $shipping_status) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    return $wpdb->update(
        $table_name,
        array('shipping_status' => $shipping_status),
        array('id' => $order_id)
    );
}

// Calculate order totals
function trident_calculate_order_totals($order_id) {
    global $wpdb;
    
    $items_table = $wpdb->prefix . 'trident_order_items';
    $orders_table = $wpdb->prefix . 'trident_orders';
    
    // Get order
    $order = trident_get_order($order_id);
    if (!$order) {
        return false;
    }
    
    // Calculate subtotal from items
    $subtotal = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(total_price) FROM $items_table WHERE order_id = %d",
        $order_id
    ));
    
    $subtotal = floatval($subtotal ?: 0);
    $tax_amount = floatval($order->tax_amount ?: 0);
    $shipping_amount = floatval($order->shipping_amount ?: 0);
    $discount_amount = floatval($order->discount_amount ?: 0);
    
    // Calculate total
    $total_amount = $subtotal + $tax_amount + $shipping_amount - $discount_amount;
    
    // Update order
    $update_data = array(
        'subtotal' => $subtotal,
        'total_amount' => $total_amount
    );
    
    return trident_update_order($order_id, $update_data);
}

// Get order by order number
function trident_get_order_by_number($order_number) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE order_number = %s", $order_number);
    
    return $wpdb->get_row($sql);
}

// Get orders by customer
function trident_get_orders_by_customer($customer_id, $args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => '',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $where_clause = "WHERE customer_id = " . intval($customer_id);
    if (!empty($args['status'])) {
        $where_clause .= " AND status = '" . esc_sql($args['status']) . "'";
    }
    
    $order_clause = "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']);
    $limit_clause = $args['limit'] > 0 ? "LIMIT " . intval($args['limit']) : '';
    $offset_clause = $args['offset'] > 0 ? "OFFSET " . intval($args['offset']) : '';
    
    $sql = "SELECT * FROM $table_name $where_clause $order_clause $limit_clause $offset_clause";
    
    return $wpdb->get_results($sql);
}

// Get order statistics
function trident_get_order_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $stats = array();
    
    // Total orders
    $stats['total_orders'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    
    // Total revenue
    $stats['total_revenue'] = $wpdb->get_var("SELECT SUM(total_amount) FROM $table_name WHERE status = 'completed'");
    
    // Pending orders
    $stats['pending_orders'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'");
    
    // Completed orders
    $stats['completed_orders'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'completed'");
    
    return $stats;
}

// Get product statistics
function trident_get_product_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    
    $stats = array();
    
    // Total products
    $stats['total_products'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
    
    // Total stock
    $stats['total_stock'] = $wpdb->get_var("SELECT SUM(stock_quantity) FROM $table_name WHERE status = 'active'");
    
    // Low stock products (less than 10)
    $stats['low_stock_products'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE stock_quantity < 10 AND status = 'active'");
    
    return $stats;
}

// Get all banners
function trident_get_banners($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => 'active',
        'orderby' => 'sort_order',
        'order' => 'ASC',
        'limit' => -1,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'trident_banners';
    
    $where_clause = "WHERE status = '" . esc_sql($args['status']) . "'";
    $order_clause = "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']);
    $limit_clause = $args['limit'] > 0 ? "LIMIT " . intval($args['limit']) : '';
    $offset_clause = $args['offset'] > 0 ? "OFFSET " . intval($args['offset']) : '';
    
    $sql = "SELECT * FROM $table_name $where_clause $order_clause $limit_clause $offset_clause";
    
    return $wpdb->get_results($sql);
}

// Get single banner by ID
function trident_get_banner($banner_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $banner_id);
    
    return $wpdb->get_row($sql);
}

// Insert new banner
function trident_insert_banner($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    $defaults = array(
        'title' => '',
        'subtitle' => '',
        'description' => '',
        'price' => 0.00,
        'image_url' => '',
        'status' => 'active',
        'sort_order' => 0
    );
    
    $data = wp_parse_args($data, $defaults);
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

// Update banner
function trident_update_banner($banner_id, $data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    $result = $wpdb->update(
        $table_name,
        $data,
        array('id' => $banner_id)
    );
    
    return $result !== false;
}

// Delete banner
function trident_delete_banner($banner_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    return $wpdb->delete($table_name, array('id' => $banner_id));
}

// Get banner statistics
function trident_get_banner_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    $stats = array();
    
    // Total banners
    $stats['total_banners'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
    
    // Active banners
    $stats['active_banners'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
    
    return $stats;
}

// Get all customers
function trident_get_customers($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => 'active',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'trident_customers';
    
    $where_clause = "WHERE status = '" . esc_sql($args['status']) . "'";
    $order_clause = "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']);
    $limit_clause = $args['limit'] > 0 ? "LIMIT " . intval($args['limit']) : '';
    $offset_clause = $args['offset'] > 0 ? "OFFSET " . intval($args['offset']) : '';
    
    $sql = "SELECT * FROM $table_name $where_clause $order_clause $limit_clause $offset_clause";
    
    return $wpdb->get_results($sql);
}

// Get single customer by ID
function trident_get_customer($customer_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_customers';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $customer_id);
    
    return $wpdb->get_row($sql);
}

// Get customer by email
function trident_get_customer_by_email($email) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_customers';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE email = %s", $email);
    
    return $wpdb->get_row($sql);
}

// Insert new customer
function trident_insert_customer($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_customers';
    
    $defaults = array(
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'country' => '',
        'street_address_1' => '',
        'street_address_2' => '',
        'city' => '',
        'state_province' => '',
        'zip_postal_code' => '',
        'phone_number' => '',
        'status' => 'active'
    );
    
    $data = wp_parse_args($data, $defaults);
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

// Update customer
function trident_update_customer($customer_id, $data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_customers';
    
    $result = $wpdb->update(
        $table_name,
        $data,
        array('id' => $customer_id)
    );
    
    return $result !== false;
}

// Delete customer
function trident_delete_customer($customer_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_customers';
    
    return $wpdb->delete($table_name, array('id' => $customer_id));
}

// Get or create customer (useful for orders)
function trident_get_or_create_customer($customer_data) {
    // Check if customer exists by email
    $existing_customer = trident_get_customer_by_email($customer_data['email']);
    
    if ($existing_customer) {
        // Update existing customer with new data
        trident_update_customer($existing_customer->id, $customer_data);
        return $existing_customer->id;
    } else {
        // Create new customer
        return trident_insert_customer($customer_data);
    }
}

// Get customer statistics
function trident_get_customer_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_customers';
    
    $stats = array();
    
    // Total customers
    $stats['total_customers'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
    
    // New customers this month
    $stats['new_customers_this_month'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    
    // Customers by country
    $stats['customers_by_country'] = $wpdb->get_results("SELECT country, COUNT(*) as count FROM $table_name WHERE status = 'active' GROUP BY country ORDER BY count DESC");
    
    return $stats;
}

// Search customers
function trident_search_customers($search_term, $args = array()) {
    global $wpdb;
    
    $defaults = array(
        'limit' => 10,
        'offset' => 0
    );
    
    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'trident_customers';
    
    $search_term = '%' . $wpdb->esc_like($search_term) . '%';
    
    $sql = $wpdb->prepare(
        "SELECT * FROM $table_name 
        WHERE status = 'active' 
        AND (first_name LIKE %s OR last_name LIKE %s OR email LIKE %s OR phone_number LIKE %s)
        ORDER BY created_at DESC
        LIMIT %d OFFSET %d",
        $search_term, $search_term, $search_term, $search_term,
        $args['limit'], $args['offset']
    );
    
    return $wpdb->get_results($sql);
}