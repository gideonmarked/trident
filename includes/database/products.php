<?php
/**
 * Products Database Functions
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get products from database
 */
function trident_get_products($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => 'active',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $table_name = $wpdb->prefix . 'trident_products';
    $sql = "SELECT * FROM $table_name WHERE status = %s ORDER BY {$args['orderby']} {$args['order']}";
    
    if ($args['limit'] > 0) {
        $sql .= " LIMIT " . intval($args['limit']);
    }
    
    $sql = $wpdb->prepare($sql, $args['status']);
    
    return $wpdb->get_results($sql);
}

/**
 * Get single product by ID
 */
function trident_get_product($product_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $product_id);
    
    return $wpdb->get_row($sql);
}

/**
 * Insert new product
 */
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

/**
 * Insert new product with custom ID (for dummy products)
 */
function trident_insert_product_with_id($data, $custom_id = null) {
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
    
    // If custom ID is provided, use it
    if ($custom_id !== null) {
        $data['id'] = $custom_id;
        $result = $wpdb->insert($table_name, $data);
    } else {
        $result = $wpdb->insert($table_name, $data);
    }
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

/**
 * Update product
 */
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

/**
 * Delete product
 */
function trident_delete_product($product_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    
    return $wpdb->delete($table_name, array('id' => $product_id));
}

/**
 * Get product colors as array
 */
function trident_get_product_colors($product, $color_type = 'body') {
    $colors_field = $color_type . '_colors';
    
    if (isset($product->$colors_field) && !empty($product->$colors_field)) {
        $colors = json_decode($product->$colors_field, true);
        return is_array($colors) ? $colors : array();
    }
    
    return array();
}

/**
 * Get product sizes as array
 */
function trident_get_product_sizes($product) {
    if (isset($product->sizes) && !empty($product->sizes)) {
        $sizes = json_decode($product->sizes, true);
        return is_array($sizes) ? $sizes : array();
    }
    
    return array();
}

/**
 * Get available colors for products
 */
function trident_get_available_colors() {
    return array(
        array('name' => 'White', 'color' => '#ffffff', 'class' => 'white'),
        array('name' => 'Black', 'color' => '#000000', 'class' => 'black'),
        array('name' => 'Gray', 'color' => '#6b7280', 'class' => 'gray'),
        array('name' => 'Light Gray', 'color' => '#d1d5db', 'class' => 'light-gray'),
        array('name' => 'Yellow', 'color' => '#fbbf24', 'class' => 'yellow'),
        array('name' => 'Dark Green', 'color' => '#059669', 'class' => 'dark-green'),
        array('name' => 'Dark Black', 'color' => '#1f2937', 'class' => 'dark-black'),
        array('name' => 'Mint', 'color' => '#10b981', 'class' => 'mint'),
        array('name' => 'Gold', 'color' => '#f59e0b', 'class' => 'gold'),
        array('name' => 'Blue', 'color' => '#3b82f6', 'class' => 'blue'),
        array('name' => 'Red', 'color' => '#ef4444', 'class' => 'red'),
        array('name' => 'Purple', 'color' => '#8b5cf6', 'class' => 'purple')
    );
}

/**
 * Get product statistics
 */
function trident_get_product_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_products';
    
    $stats = array(
        'total' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
        'active' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'"),
        'inactive' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'inactive'"),
        'low_stock' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE stock_quantity <= 10 AND stock_quantity > 0"),
        'out_of_stock' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE stock_quantity = 0")
    );
    
    return $stats;
} 