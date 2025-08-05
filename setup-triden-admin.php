<?php
/**
 * TRIDENT Admin Setup Script
 * 
 * This script sets up the TRIDENT admin user and ensures the theme is properly configured.
 * Run this script once to set up the TRIDENT admin system.
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Include WordPress
require_once('../../../wp-load.php');

echo "<h1>TRIDENT Admin Setup</h1>";

// 1. Check if theme is active
$current_theme = wp_get_theme();
echo "<h2>1. Theme Status</h2>";
echo "<p><strong>Current Theme:</strong> " . $current_theme->get('Name') . "</p>";
echo "<p><strong>Theme Directory:</strong> " . $current_theme->get_stylesheet() . "</p>";

if ($current_theme->get_stylesheet() !== 'trident') {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>⚠️ Warning:</strong> TRIDENT theme is not active. Please activate it in WordPress admin.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>✅ Success:</strong> TRIDENT theme is active.</p>";
    echo "</div>";
}

// 2. Create trident-admin role
echo "<h2>2. Creating TRIDENT Admin Role</h2>";
remove_role('trident-admin');
$role = add_role('trident-admin', 'TRIDENT Admin', array(
    'read' => true,
    'edit_posts' => true,
    'delete_posts' => true,
    'publish_posts' => true,
    'upload_files' => true,
    'manage_trident_products' => true,
    'manage_trident_orders' => true,
    'manage_trident_banners' => true
));

if ($role) {
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>✅ Success:</strong> TRIDENT Admin role created.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>❌ Error:</strong> Failed to create TRIDENT Admin role.</p>";
    echo "</div>";
}

// 3. Create default admin user
echo "<h2>3. Creating Default Admin User</h2>";
$existing_user = get_user_by('login', 'trident-admin');

if ($existing_user) {
    echo "<p>User 'trident-admin' already exists. Updating role...</p>";
    $user = new WP_User($existing_user->ID);
    $user->set_role('trident-admin');
    $user_id = $existing_user->ID;
} else {
    echo "<p>Creating new user 'trident-admin'...</p>";
    $user_id = wp_create_user('trident-admin', 'trident-admin', 'admin@trident.com');
    
    if (!is_wp_error($user_id)) {
        $user = new WP_User($user_id);
        $user->set_role('trident-admin');
        
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => 'TRIDENT',
            'last_name' => 'Admin',
            'display_name' => 'TRIDENT Admin',
            'user_email' => 'admin@trident.com'
        ));
    }
}

if (!is_wp_error($user_id)) {
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>✅ Success:</strong> TRIDENT Admin user created/updated.</p>";
    echo "<p><strong>Username:</strong> trident-admin</p>";
    echo "<p><strong>Password:</strong> trident-admin</p>";
    echo "<p><strong>Email:</strong> admin@trident.com</p>";
    echo "</div>";
} else {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>❌ Error:</strong> " . $user_id->get_error_message() . "</p>";
    echo "</div>";
}

// 4. Create database tables
echo "<h2>4. Creating Database Tables</h2>";
if (function_exists('trident_create_tables')) {
    trident_create_tables();
    echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>✅ Success:</strong> Database tables created.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p><strong>❌ Error:</strong> trident_create_tables function not found.</p>";
    echo "</div>";
}

// 5. Test login
echo "<h2>5. Login Information</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<h3>Login Credentials:</h3>";
echo "<p><strong>Username:</strong> <code>trident-admin</code></p>";
echo "<p><strong>Password:</strong> <code>trident-admin</code></p>";
echo "<p><strong>Email:</strong> <code>admin@trident.com</code></p>";
echo "</div>";

echo "<div style='background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<h3>Important Links:</h3>";
echo "<p><strong>Custom Login Page:</strong> <a href='" . home_url('/login') . "' target='_blank'>" . home_url('/login') . "</a></p>";
echo "<p><strong>TRIDENT Admin Portal:</strong> <a href='" . home_url('/trident-admin') . "' target='_blank'>" . home_url('/trident-admin') . "</a></p>";
echo "<p><strong>WordPress Admin:</strong> <a href='" . admin_url() . "' target='_blank'>" . admin_url() . "</a></p>";
echo "</div>";

// 6. List all users with trident-admin role
echo "<h2>6. Current TRIDENT Admin Users</h2>";
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

echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>⚠️ Security Notice:</h3>";
echo "<p><strong>1.</strong> Please change the default password after first login.</p>";
echo "<p><strong>2.</strong> Delete this file after successful setup.</p>";
echo "<p><strong>3.</strong> Consider using a strong password for production.</p>";
echo "</div>";
?> 