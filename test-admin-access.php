<?php
/**
 * Test TRIDENT Admin Access
 * 
 * This script helps debug the trident-admin page access issues.
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>TRIDENT Admin Access Test</h1>";

// Check if user is logged in
if (!is_user_logged_in()) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>❌ Not Logged In:</strong> You need to be logged in to access the admin area.";
    echo "</div>";
    echo "<p><a href='" . home_url('/login') . "'>Go to Login Page</a></p>";
    exit;
}

// Check if user has access
if (!trident_user_has_access()) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>❌ Access Denied:</strong> You don't have permission to access the TRIDENT admin area.";
    echo "</div>";
    exit;
}

echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<strong>✅ Access Granted:</strong> You have permission to access the TRIDENT admin area.";
echo "</div>";

// Test rewrite rules
echo "<h2>Rewrite Rules Test</h2>";

$rewrite_rules = get_option('rewrite_rules');
$trident_rules = array_filter($rewrite_rules, function($rule, $pattern) {
    return strpos($pattern, 'trident') !== false;
}, ARRAY_FILTER_USE_BOTH);

if (empty($trident_rules)) {
    echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>⚠️ Warning:</strong> No TRIDENT rewrite rules found. Flushing rewrite rules...";
    echo "</div>";
    
    flush_rewrite_rules();
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>✅ Success:</strong> Rewrite rules flushed.";
    echo "</div>";
} else {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>✅ Found:</strong> " . count($trident_rules) . " TRIDENT rewrite rules.";
    echo "</div>";
}

// Test URLs
echo "<h2>Test URLs</h2>";
echo "<ul>";
echo "<li><a href='" . home_url('/trident-admin') . "' target='_blank'>TRIDENT Admin (Main)</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=banners') . "' target='_blank'>TRIDENT Admin (Banners)</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=products') . "' target='_blank'>TRIDENT Admin (Products)</a></li>";
echo "</ul>";

// Check if page exists
echo "<h2>Page Template Check</h2>";
$template_file = get_template_directory() . '/page-trident-admin.php';
if (file_exists($template_file)) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>✅ Template Found:</strong> page-trident-admin.php exists.";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>❌ Template Missing:</strong> page-trident-admin.php not found.";
    echo "</div>";
}

// Check current user info
echo "<h2>Current User Info</h2>";
$current_user = wp_get_current_user();
echo "<p><strong>Username:</strong> " . $current_user->user_login . "</p>";
echo "<p><strong>Display Name:</strong> " . $current_user->display_name . "</p>";
echo "<p><strong>Roles:</strong> " . implode(', ', $current_user->roles) . "</p>";

// Check if trident-admin page exists in WordPress
echo "<h2>WordPress Page Check</h2>";
$trident_page = get_page_by_path('trident-admin');
if ($trident_page) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>✅ Page Found:</strong> trident-admin page exists in WordPress (ID: " . $trident_page->ID . ")";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>⚠️ Page Missing:</strong> trident-admin page doesn't exist in WordPress. Creating it...";
    echo "</div>";
    
    // Create the page
    $page_data = array(
        'post_title'    => 'TRIDENT Admin',
        'post_name'     => 'trident-admin',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '[trident-admin]',
        'page_template' => 'page-trident-admin.php'
    );
    
    $page_id = wp_insert_post($page_data);
    if ($page_id) {
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>✅ Page Created:</strong> trident-admin page created successfully (ID: " . $page_id . ")";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>❌ Error:</strong> Failed to create trident-admin page.";
        echo "</div>";
    }
}

echo "<h2>Next Steps</h2>";
echo "<ol>";
echo "<li>Click the test URLs above to see if they work</li>";
echo "<li>If they still show 404, try visiting: <a href='" . home_url('/?flush_rewrites=1') . "' target='_blank'>Flush Rewrite Rules</a></li>";
echo "<li>Check your browser's developer console for JavaScript errors</li>";
echo "</ol>";

echo "<p><a href='" . home_url('/wp-admin/') . "'>← Back to WordPress Admin</a></p>";
?> 