<?php
/**
 * Debug script to check admin user status
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h2>TRIDENT Login Debug</h2>";

// Check if user exists by email
$user = get_user_by('email', 'trident-admin@trident.com');
if ($user) {
    echo "<p><strong>✅ User found:</strong></p>";
    echo "<ul>";
    echo "<li>ID: " . $user->ID . "</li>";
    echo "<li>Username: " . $user->user_login . "</li>";
    echo "<li>Email: " . $user->user_email . "</li>";
    echo "<li>Display Name: " . $user->display_name . "</li>";
    echo "<li>Roles: " . implode(', ', $user->roles) . "</li>";
    echo "</ul>";
    
    // Test password
    $test_password = 'admin';
    if (wp_check_password($test_password, $user->user_pass, $user->ID)) {
        echo "<p><strong>✅ Password 'admin' is correct!</strong></p>";
    } else {
        echo "<p><strong>❌ Password 'admin' is incorrect!</strong></p>";
        echo "<p>Stored password hash: " . $user->user_pass . "</p>";
    }
    
    // Check if user has trident-admin role
    if (trident_user_has_access($user->ID)) {
        echo "<p><strong>✅ User has TRIDENT admin access</strong></p>";
    } else {
        echo "<p><strong>❌ User does NOT have TRIDENT admin access</strong></p>";
    }
    
} else {
    echo "<p><strong>❌ User not found!</strong></p>";
    echo "<p>Attempting to create user...</p>";
    
    // Try to create the user
    trident_create_default_admin_user();
    
    // Check again
    $user = get_user_by('email', 'trident-admin@trident.com');
    if ($user) {
        echo "<p><strong>✅ User created successfully!</strong></p>";
        echo "<ul>";
        echo "<li>ID: " . $user->ID . "</li>";
        echo "<li>Username: " . $user->user_login . "</li>";
        echo "<li>Email: " . $user->user_email . "</li>";
        echo "<li>Roles: " . implode(', ', $user->roles) . "</li>";
        echo "</ul>";
    } else {
        echo "<p><strong>❌ Failed to create user!</strong></p>";
    }
}

// Test the login function
echo "<h3>Testing Login Function</h3>";
$email = 'trident-admin@trident.com';
$password = 'admin';

$test_user = get_user_by('email', $email);
if ($test_user) {
    if (wp_check_password($password, $test_user->user_pass, $test_user->ID)) {
        echo "<p><strong>✅ Login test successful!</strong></p>";
        echo "<p>You should be able to log in with:</p>";
        echo "<ul>";
        echo "<li>Email: trident-admin@trident.com</li>";
        echo "<li>Password: admin</li>";
        echo "</ul>";
    } else {
        echo "<p><strong>❌ Login test failed - password incorrect</strong></p>";
    }
} else {
    echo "<p><strong>❌ Login test failed - user not found</strong></p>";
}

echo "<p><a href='/login'>Go to Login Page</a></p>";
?> 