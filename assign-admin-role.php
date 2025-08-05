<?php
/**
 * TRIDENT Admin User Creation Script
 * 
 * This script creates a default trident-admin user with credentials:
 * Username: trident-admin
 * Password: trident-admin
 * 
 * IMPORTANT: Delete this file after use for security reasons.
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Include WordPress
require_once('../../../wp-load.php');

// Check if user is logged in and is admin
if (!is_user_logged_in() || !current_user_can('administrator')) {
    die('Access denied. You must be logged in as an administrator.');
}

// Function to create default trident-admin user
function create_default_trident_admin() {
    // Check if the default user already exists
    $existing_user = get_user_by('login', 'trident-admin');
    
    if ($existing_user) {
        // Update existing user to ensure they have the trident-admin role
        $user = new WP_User($existing_user->ID);
        $user->set_role('trident-admin');
        return $existing_user;
    }
    
    // Create new user
    $user_id = wp_create_user('trident-admin', 'trident-admin', 'admin@trident.com');
    
    if (!is_wp_error($user_id)) {
        // Set user role
        $user = new WP_User($user_id);
        $user->set_role('trident-admin');
        
        // Update user display name and email
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => 'TRIDENT',
            'last_name' => 'Admin',
            'display_name' => 'TRIDENT Admin',
            'user_email' => 'admin@trident.com'
        ));
        
        return get_user_by('id', $user_id);
    } else {
        return false;
    }
}

// Create default trident-admin user
$default_user = create_default_trident_admin();

if ($default_user) {
    echo "<h2>✅ TRIDENT Admin User Created Successfully!</h2>";
    echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>Login Credentials:</h3>";
    echo "<p><strong>Username:</strong> <code>trident-admin</code></p>";
    echo "<p><strong>Password:</strong> <code>trident-admin</code></p>";
    echo "<p><strong>Email:</strong> <code>" . $default_user->user_email . "</code></p>";
    echo "<p><strong>Role:</strong> TRIDENT Admin</p>";
    echo "</div>";
    
    echo "<div style='background: #fef3c7; border: 1px solid #f59e0b; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>Important Links:</h3>";
    echo "<p><strong>Custom Login Page:</strong> <a href='" . home_url('/login') . "' target='_blank' style='color: #d97706; font-weight: bold;'>" . home_url('/login') . "</a></p>";
    echo "<p><strong>TRIDENT Admin Portal:</strong> <a href='" . home_url('/trident-admin') . "' target='_blank' style='color: #d97706; font-weight: bold;'>" . home_url('/trident-admin') . "</a></p>";
    echo "</div>";
    
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>⚠️ Security Notice:</h3>";
    echo "<p><strong>1.</strong> Please change the default password after first login.</p>";
    echo "<p><strong>2.</strong> Delete this file immediately after use.</p>";
    echo "<p><strong>3.</strong> Consider using a strong password for production.</p>";
    echo "</div>";
} else {
    echo "<h2>❌ Error Creating TRIDENT Admin User</h2>";
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 20px; border-radius: 8px;'>";
    echo "<p>There was an error creating the default admin user. Please check the error logs.</p>";
    echo "</div>";
}

// List all users with trident-admin role
echo "<h3>Current Users with trident-admin role:</h3>";
$trident_admins = get_users(array('role' => 'trident-admin'));
if (!empty($trident_admins)) {
    echo "<ul>";
    foreach ($trident_admins as $admin) {
        echo "<li><strong>{$admin->display_name}</strong> ({$admin->user_email}) - {$admin->user_login}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No users found with trident-admin role.</p>";
}
?> 