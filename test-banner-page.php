<?php
/**
 * Test Banner Page Functionality
 * 
 * This script tests if the banner page is working correctly in the TRIDENT admin portal.
 */

require_once('../../../wp-load.php');

echo "<h1>TRIDENT Banner Page Test</h1>";

// Check if user has access
if (!trident_user_has_access()) {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>❌ Access Denied:</strong> You must be logged in as a TRIDENT admin to test.</p>";
    echo "<p><a href='" . home_url('/login') . "'>Go to Login</a></p>";
    echo "</div>";
    exit;
}

echo "<h2>1. Banner Functions Test</h2>";

// Test banner functions
$banners = trident_get_banners();
$stats = trident_get_banner_stats();

echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Banner Functions Status:</strong></p>";
echo "<ul>";
echo "<li><strong>trident_get_banners():</strong> " . (function_exists('trident_get_banners') ? "✅ Available" : "❌ Missing") . "</li>";
echo "<li><strong>trident_get_banner_stats():</strong> " . (function_exists('trident_get_banner_stats') ? "✅ Available" : "❌ Missing") . "</li>";
echo "<li><strong>trident_insert_banner():</strong> " . (function_exists('trident_insert_banner') ? "✅ Available" : "❌ Missing") . "</li>";
echo "<li><strong>trident_delete_banner():</strong> " . (function_exists('trident_delete_banner') ? "✅ Available" : "❌ Missing") . "</li>";
echo "</ul>";
echo "</div>";

echo "<h2>2. Current Banners</h2>";
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

echo "<h2>3. Banner Statistics</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Total Banners:</strong> " . $stats['total_banners'] . "</p>";
echo "<p><strong>Active Banners:</strong> " . $stats['active_banners'] . "</p>";
echo "</div>";

echo "<h2>4. Banner Page Links</h2>";
echo "<div style='background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 8px;'>";
echo "<h3>Test these banner page URLs:</h3>";
echo "<ul>";
echo "<li><a href='" . home_url('/trident-admin?page=banners') . "' target='_blank'>Banners Page (with ?page=banners)</a></li>";
echo "<li><a href='" . home_url('/trident-admin') . "' target='_blank'>Main Admin Portal</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=products') . "' target='_blank'>Products Page</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=orders') . "' target='_blank'>Orders Page</a></li>";
echo "</ul>";
echo "</div>";

echo "<h2>5. AJAX Endpoints Test</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>AJAX Endpoints Status:</strong></p>";
echo "<ul>";
echo "<li><strong>Add Banner:</strong> " . (has_action('wp_ajax_trident_add_banner') ? "✅ Hooked" : "❌ Not Hooked") . "</li>";
echo "<li><strong>Delete Banner:</strong> " . (has_action('wp_ajax_trident_delete_banner') ? "✅ Hooked" : "❌ Not Hooked") . "</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Route Testing</h2>";
$test_urls = array(
    '/trident-admin' => 'Main Admin Portal',
    '/trident-admin?page=banners' => 'Banners Page',
    '/trident-admin?page=products' => 'Products Page',
    '/trident-admin?page=orders' => 'Orders Page'
);

echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Route Status:</strong></p>";
foreach ($test_urls as $url => $description) {
    $full_url = home_url($url);
    echo "<p><strong>$description:</strong> <a href='$full_url' target='_blank'>$full_url</a></p>";
}
echo "</div>";

echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>⚠️ Security Notice:</h3>";
echo "<p>Delete this file after testing is complete.</p>";
echo "</div>";
?> 