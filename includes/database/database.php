<?php
/**
 * Database Table Creation
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create database tables
 */
function trident_create_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Products table
    $table_products = $wpdb->prefix . 'trident_products';
    $sql_products = "CREATE TABLE $table_products (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description text,
        price decimal(10,2) DEFAULT 0.00,
        image_url varchar(500),
        body_colors text,
        cap_colors text,
        boot_colors text,
        sizes text,
        stock_quantity int DEFAULT 0,
        status varchar(20) DEFAULT 'active',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    // Orders table
    $table_orders = $wpdb->prefix . 'trident_orders';
    $sql_orders = "CREATE TABLE $table_orders (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customer_id mediumint(9) NOT NULL,
        order_number varchar(50) NOT NULL,
        total_amount decimal(10,2) DEFAULT 0.00,
        status varchar(20) DEFAULT 'pending',
        payment_method varchar(50),
        shipping_address text,
        billing_address text,
        notes text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY order_number (order_number)
    ) $charset_collate;";
    
    // Order items table
    $table_order_items = $wpdb->prefix . 'trident_order_items';
    $sql_order_items = "CREATE TABLE $table_order_items (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        order_id mediumint(9) NOT NULL,
        product_id mediumint(9) NOT NULL,
        product_name varchar(255) NOT NULL,
        quantity int DEFAULT 1,
        price decimal(10,2) DEFAULT 0.00,
        color varchar(50),
        size varchar(20),
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY order_id (order_id),
        KEY product_id (product_id)
    ) $charset_collate;";
    
    // Banners table
    $table_banners = $wpdb->prefix . 'trident_banners';
    $sql_banners = "CREATE TABLE $table_banners (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description text,
        image_url varchar(500) NOT NULL,
        position varchar(50) DEFAULT 'hero',
        status varchar(20) DEFAULT 'active',
        start_date datetime,
        end_date datetime,
        link_url varchar(500),
        sort_order int DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    // Customers table
    $table_customers = $wpdb->prefix . 'trident_customers';
    $sql_customers = "CREATE TABLE $table_customers (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(255) NOT NULL,
        phone varchar(20),
        address text,
        city varchar(100),
        state varchar(100),
        zip_code varchar(20),
        country varchar(100) DEFAULT 'Philippines',
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
    update_option('trident_db_version', '1.0.0');
}