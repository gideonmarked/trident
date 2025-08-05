<?php
/**
 * Test script for login redirect functionality
 * 
 * This script tests the login redirect logic for different user types
 * Run this from the browser to see the results
 */

// Include WordPress
require_once('../../../wp-load.php');

// Check if user is logged in
if (!is_user_logged_in()) {
    echo "<h2>Login Redirect Test</h2>";
    echo "<p>You are not logged in. Please log in to test the redirect functionality.</p>";
    echo "<p><a href='/wp-login.php'>Go to wp-login.php</a> | <a href='/login'>Go to custom login</a></p>";
    exit;
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$has_trident_access = trident_user_has_access($current_user->ID);

echo "<h2>Login Redirect Test Results</h2>";
echo "<p><strong>Current User:</strong> " . $current_user->display_name . " (" . $current_user->user_login . ")</p>";
echo "<p><strong>User Roles:</strong> " . implode(', ', $user_roles) . "</p>";
echo "<p><strong>Has TRIDENT Access:</strong> " . ($has_trident_access ? 'Yes' : 'No') . "</p>";

// Test the redirect logic
$redirect_url = trident_login_redirect('', '', $current_user);
echo "<p><strong>Would Redirect To:</strong> " . $redirect_url . "</p>";

echo "<h3>Test Links:</h3>";
echo "<p><a href='/wp-login.php'>Test wp-login.php redirect</a></p>";
echo "<p><a href='/login'>Test custom login redirect</a></p>";
echo "<p><a href='/wp-admin'>Test wp-admin access</a></p>";
echo "<p><a href='/trident-admin'>Go to TRIDENT Admin</a></p>";

echo "<h3>Expected Behavior:</h3>";
if ($has_trident_access) {
    echo "<p style='color: green;'>✓ Should redirect to: " . home_url('/trident-admin') . "</p>";
    echo "<p style='color: orange;'>⚠ Accessing wp-admin should redirect to TRIDENT Admin</p>";
} else {
    echo "<p style='color: blue;'>✓ Should redirect to: " . admin_url() . "</p>";
    echo "<p style='color: green;'>✓ Can access wp-admin normally</p>";
}
?> 