<?php
/**
 * Temporary Flush Rewrite Rules Page
 * Access this via: https://trident.local/wp-content/themes/trident/flush-rewrites-temp.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is logged in and has admin access
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    wp_die('Access denied. You must be logged in as an administrator.');
}

echo "<h1>TRIDENT Rewrite Rules Flush</h1>";

// Flush rewrite rules
flush_rewrite_rules();

echo "<h2>✅ Rewrite Rules Flushed Successfully!</h2>";
echo "<p>The custom routes should now work properly.</p>";

echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='" . home_url('/trident-admin') . "' target='_blank'>TRIDENT Admin Portal</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=banners') . "' target='_blank'>Banners Page</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=products') . "' target='_blank'>Products Page</a></li>";
echo "<li><a href='" . home_url('/login') . "' target='_blank'>Login Page</a></li>";
echo "</ul>";

echo "<h2>Login Credentials:</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Username:</strong> trident-admin</p>";
echo "<p><strong>Password:</strong> trident-admin</p>";
echo "</div>";

echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>⚠️ Important:</h3>";
echo "<p>Delete this file after you've confirmed the routes are working!</p>";
echo "</div>";
?> 