<?php
/**
 * Test file to check database tables
 */

// Load WordPress
require_once('../../../wp-load.php');

global $wpdb;

echo "<h1>Database Tables Check</h1>";

// Check if tables exist
$customers_table = $wpdb->prefix . 'trident_customers';
$orders_table = $wpdb->prefix . 'trident_orders';
$order_items_table = $wpdb->prefix . 'trident_order_items';

echo "<h2>Table Status:</h2>";
echo "<ul>";

// Check customers table
$customers_exists = $wpdb->get_var("SHOW TABLES LIKE '$customers_table'") == $customers_table;
echo "<li>Customers table ($customers_table): " . ($customers_exists ? '✅ EXISTS' : '❌ MISSING') . "</li>";

// Check orders table
$orders_exists = $wpdb->get_var("SHOW TABLES LIKE '$orders_table'") == $orders_table;
echo "<li>Orders table ($orders_table): " . ($orders_exists ? '✅ EXISTS' : '❌ MISSING') . "</li>";

// Check order items table
$order_items_exists = $wpdb->get_var("SHOW TABLES LIKE '$order_items_table'") == $order_items_table;
echo "<li>Order items table ($order_items_table): " . ($order_items_exists ? '✅ EXISTS' : '❌ MISSING') . "</li>";

echo "</ul>";

// If tables don't exist, try to create them
if (!$customers_exists || !$orders_exists || !$order_items_exists) {
    echo "<h2>Creating Missing Tables...</h2>";
    
    // Include the database creation function
    require_once('includes/database/database.php');
    
    // Call the table creation function
    trident_create_tables();
    
    echo "<p>Tables creation attempted. Please refresh this page to check again.</p>";
} else {
    echo "<h2>All Tables Exist! ✅</h2>";
    
    // Show table counts
    $customers_count = $wpdb->get_var("SELECT COUNT(*) FROM $customers_table");
    $orders_count = $wpdb->get_var("SELECT COUNT(*) FROM $orders_table");
    $order_items_count = $wpdb->get_var("SELECT COUNT(*) FROM $order_items_table");
    
    echo "<h3>Current Data:</h3>";
    echo "<ul>";
    echo "<li>Customers: $customers_count</li>";
    echo "<li>Orders: $orders_count</li>";
    echo "<li>Order Items: $order_items_count</li>";
    echo "</ul>";
}

echo "<p><a href='../wp-admin/admin.php?page=trident-admin&current_page=orders'>Back to Admin</a></p>";
?> 