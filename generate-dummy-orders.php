<?php
/**
 * Generate Dummy Orders Script
 * 
 * This script creates dummy orders, customers, and order items for testing purposes.
 * Run this script once to populate the database with sample data.
 */

// Load WordPress
require_once('../../../wp-load.php');

// Ensure only admin can run this
if (!current_user_can('manage_options')) {
    die('Access denied. Only administrators can run this script.');
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

echo "<h1>Generating Dummy Orders...</h1>";

// Insert customers
$customer_ids = [];
foreach ($dummy_customers as $customer) {
    $result = $wpdb->insert(
        $wpdb->prefix . 'trident_customers',
        $customer,
        ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
    );
    
    if ($result) {
        $customer_ids[] = $wpdb->insert_id;
        echo "✓ Created customer: {$customer['first_name']} {$customer['last_name']}<br>";
    } else {
        echo "✗ Failed to create customer: {$customer['first_name']} {$customer['last_name']}<br>";
    }
}

// Insert orders
$order_ids = [];
foreach ($dummy_orders as $index => $order) {
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
        echo "✓ Created order: {$order['order_number']} - ₱{$order['total_amount']}<br>";
    } else {
        echo "✗ Failed to create order: {$order['order_number']}<br>";
    }
}

// Insert order items - exactly 20 items per order
foreach ($order_ids as $order_id) {
    // Create exactly 20 items for each order
    for ($i = 0; $i < 20; $i++) {
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
            echo "✓ Created order item: {$product['name']} x{$quantity} ({$color}, {$size})<br>";
        } else {
            echo "✗ Failed to create order item for order {$order_id}<br>";
        }
    }
}

echo "<h2>Dummy Orders Generation Complete!</h2>";
echo "<p>Created " . count($customer_ids) . " customers</p>";
echo "<p>Created " . count($order_ids) . " orders</p>";
echo "<p>Created multiple order items</p>";

// Show summary
$total_orders = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}trident_orders");
$total_customers = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}trident_customers");
$total_order_items = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}trident_order_items");

echo "<h3>Database Summary:</h3>";
echo "<ul>";
echo "<li>Total Customers: {$total_customers}</li>";
echo "<li>Total Orders: {$total_orders}</li>";
echo "<li>Total Order Items: {$total_order_items}</li>";
echo "</ul>";

echo "<p><a href='../wp-admin/admin.php?page=trident-admin&current_page=orders'>View Orders in Admin</a></p>";
?> 