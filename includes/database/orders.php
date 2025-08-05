<?php
/**
 * Orders Database Functions
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get orders from database
 */
function trident_get_orders($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => '',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1,
        'customer_id' => null
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $table_name = $wpdb->prefix . 'trident_orders';
    $sql = "SELECT * FROM $table_name WHERE 1=1";
    $params = array();
    
    if (!empty($args['status'])) {
        $sql .= " AND status = %s";
        $params[] = $args['status'];
    }
    
    if ($args['customer_id']) {
        $sql .= " AND customer_id = %d";
        $params[] = $args['customer_id'];
    }
    
    $sql .= " ORDER BY {$args['orderby']} {$args['order']}";
    
    if ($args['limit'] > 0) {
        $sql .= " LIMIT " . intval($args['limit']);
    }
    
    if (!empty($params)) {
        $sql = $wpdb->prepare($sql, $params);
    }
    
    return $wpdb->get_results($sql);
}

/**
 * Get single order by ID
 */
function trident_get_order($order_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $order_id);
    
    return $wpdb->get_row($sql);
}

/**
 * Insert new order
 */
function trident_insert_order($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $defaults = array(
        'customer_id' => 0,
        'order_number' => '',
        'total_amount' => 0.00,
        'status' => 'pending',
        'payment_method' => '',
        'shipping_address' => '',
        'billing_address' => '',
        'notes' => ''
    );
    
    $data = wp_parse_args($data, $defaults);
    
    // Generate order number if not provided
    if (empty($data['order_number'])) {
        $data['order_number'] = trident_generate_order_number();
    }
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

/**
 * Update order
 */
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

/**
 * Delete order
 */
function trident_delete_order($order_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    return $wpdb->delete($table_name, array('id' => $order_id));
}

/**
 * Generate unique order number
 */
function trident_generate_order_number() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    do {
        $order_number = 'TRD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE order_number = %s",
            $order_number
        ));
    } while ($exists > 0);
    
    return $order_number;
}

/**
 * Get order statistics
 */
function trident_get_order_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_orders';
    
    $stats = array(
        'total' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
        'pending' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'"),
        'processing' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'processing'"),
        'completed' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'completed'"),
        'cancelled' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'cancelled'"),
        'total_revenue' => $wpdb->get_var("SELECT SUM(total_amount) FROM $table_name WHERE status = 'completed'")
    );
    
    return $stats;
}

/**
 * Get order items
 */
function trident_get_order_items($order_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_order_items';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE order_id = %d", $order_id);
    
    return $wpdb->get_results($sql);
}

/**
 * Add item to order
 */
function trident_add_order_item($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_order_items';
    
    $defaults = array(
        'order_id' => 0,
        'product_id' => 0,
        'product_name' => '',
        'quantity' => 1,
        'price' => 0.00,
        'color' => '',
        'size' => ''
    );
    
    $data = wp_parse_args($data, $defaults);
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
} 