<?php
/**
 * Banners Database Functions
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get banners from database
 */
function trident_get_banners($args = array()) {
    global $wpdb;
    
    $defaults = array(
        'status' => 'active',
        'orderby' => 'created_at',
        'order' => 'DESC',
        'limit' => -1,
        'position' => ''
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $table_name = $wpdb->prefix . 'trident_banners';
    $sql = "SELECT * FROM $table_name WHERE status = %s";
    $params = array($args['status']);
    
    if (!empty($args['position'])) {
        $sql .= " AND position = %s";
        $params[] = $args['position'];
    }
    
    $sql .= " ORDER BY {$args['orderby']} {$args['order']}";
    
    if ($args['limit'] > 0) {
        $sql .= " LIMIT " . intval($args['limit']);
    }
    
    $sql = $wpdb->prepare($sql, $params);
    
    return $wpdb->get_results($sql);
}

/**
 * Get single banner by ID
 */
function trident_get_banner($banner_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $banner_id);
    
    return $wpdb->get_row($sql);
}

/**
 * Insert new banner
 */
function trident_insert_banner($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    $defaults = array(
        'title' => '',
        'description' => '',
        'image_url' => '',
        'position' => 'hero',
        'status' => 'active',
        'start_date' => '',
        'end_date' => '',
        'link_url' => '',
        'sort_order' => 0
    );
    
    $data = wp_parse_args($data, $defaults);
    
    $result = $wpdb->insert($table_name, $data);
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

/**
 * Update banner
 */
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

/**
 * Delete banner
 */
function trident_delete_banner($banner_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    return $wpdb->delete($table_name, array('id' => $banner_id));
}

/**
 * Get active banners for a specific position
 */
function trident_get_active_banners($position = 'hero') {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    $current_date = current_time('Y-m-d H:i:s');
    
    $sql = $wpdb->prepare(
        "SELECT * FROM $table_name 
         WHERE status = 'active' 
         AND position = %s 
         AND (start_date = '' OR start_date <= %s)
         AND (end_date = '' OR end_date >= %s)
         ORDER BY sort_order ASC, created_at DESC",
        $position,
        $current_date,
        $current_date
    );
    
    return $wpdb->get_results($sql);
}

/**
 * Get banner statistics
 */
function trident_get_banner_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    $stats = array(
        'total' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
        'active' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'"),
        'inactive' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'inactive'"),
        'hero' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE position = 'hero'"),
        'sidebar' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE position = 'sidebar'"),
        'footer' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE position = 'footer'")
    );
    
    return $stats;
}

/**
 * Update banner sort order
 */
function trident_update_banner_order($banner_ids) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'trident_banners';
    
    foreach ($banner_ids as $index => $banner_id) {
        $wpdb->update(
            $table_name,
            array('sort_order' => $index),
            array('id' => $banner_id)
        );
    }
    
    return true;
} 