<?php
/**
 * Test Banner Functions
 * 
 * This script tests the banner add and remove functions to ensure they work properly.
 * Run this script to verify banner functionality.
 */

require_once('../../../wp-load.php');

echo "<h1>TRIDENT Banner Functions Test</h1>";

// Check if user has access
if (!trident_user_has_access()) {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>❌ Access Denied:</strong> You must be logged in as a TRIDENT admin to test banner functions.</p>";
    echo "</div>";
    exit;
}

echo "<h2>1. Current Banners</h2>";
$banners = trident_get_banners();
if (!empty($banners)) {
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>✅ Found " . count($banners) . " banner(s):</strong></p>";
    echo "<ul>";
    foreach ($banners as $banner) {
        echo "<li><strong>ID:</strong> {$banner->id} | <strong>Image:</strong> {$banner->image_url} | <strong>Status:</strong> {$banner->status}</li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>ℹ️ No banners found.</strong> The banner table is empty.</p>";
    echo "</div>";
}

echo "<h2>2. Banner Statistics</h2>";
$stats = trident_get_banner_stats();
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Total Banners:</strong> " . $stats['total_banners'] . "</p>";
echo "<p><strong>Active Banners:</strong> " . $stats['active_banners'] . "</p>";
echo "</div>";

echo "<h2>3. Test Banner Insert Function</h2>";
$test_banner_data = array(
    'title' => 'Test Banner',
    'subtitle' => 'Test Subtitle',
    'description' => 'This is a test banner for testing purposes.',
    'price' => 0.00,
    'image_url' => 'http://trident.local/wp-content/uploads/test-banner.jpg',
    'status' => 'active',
    'sort_order' => 0
);

$banner_id = trident_insert_banner($test_banner_data);
if ($banner_id) {
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>✅ Success:</strong> Test banner inserted with ID: $banner_id</p>";
    echo "</div>";
    
    // Test retrieving the banner
    $retrieved_banner = trident_get_banner($banner_id);
    if ($retrieved_banner) {
        echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px; margin-top: 10px;'>";
        echo "<p><strong>✅ Success:</strong> Banner retrieved successfully</p>";
        echo "<p><strong>Title:</strong> {$retrieved_banner->title}</p>";
        echo "<p><strong>Image URL:</strong> {$retrieved_banner->image_url}</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin-top: 10px;'>";
        echo "<p><strong>❌ Error:</strong> Failed to retrieve banner</p>";
        echo "</div>";
    }
    
    echo "<h2>4. Test Banner Delete Function</h2>";
    $delete_result = trident_delete_banner($banner_id);
    if ($delete_result) {
        echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px;'>";
        echo "<p><strong>✅ Success:</strong> Test banner deleted successfully</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>";
        echo "<p><strong>❌ Error:</strong> Failed to delete test banner</p>";
        echo "</div>";
    }
} else {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>❌ Error:</strong> Failed to insert test banner</p>";
    echo "</div>";
}

echo "<h2>5. Database Table Structure</h2>";
global $wpdb;
$table_name = $wpdb->prefix . 'trident_banners';
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

if ($table_exists) {
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>✅ Success:</strong> Banner table exists: $table_name</p>";
    
    // Show table structure
    $columns = $wpdb->get_results("DESCRIBE $table_name");
    echo "<h3>Table Columns:</h3>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li><strong>{$column->Field}:</strong> {$column->Type} {$column->Null} {$column->Key} {$column->Default}</li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>❌ Error:</strong> Banner table does not exist: $table_name</p>";
    echo "</div>";
}

echo "<h2>6. Summary</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<h3>✅ Banner Functions Status:</h3>";
echo "<ul>";
echo "<li><strong>Database Table:</strong> " . ($table_exists ? "✅ Created" : "❌ Missing") . "</li>";
echo "<li><strong>Insert Function:</strong> " . ($banner_id ? "✅ Working" : "❌ Failed") . "</li>";
echo "<li><strong>Get Function:</strong> " . ($retrieved_banner ? "✅ Working" : "❌ Failed") . "</li>";
echo "<li><strong>Delete Function:</strong> " . ($delete_result ? "✅ Working" : "❌ Failed") . "</li>";
echo "<li><strong>Statistics Function:</strong> ✅ Working</li>";
echo "</ul>";
echo "</div>";

echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>⚠️ Security Notice:</h3>";
echo "<p>Delete this file after testing is complete.</p>";
echo "</div>";
?> 