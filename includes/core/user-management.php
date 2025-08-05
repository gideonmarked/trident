<?php
/**
 * User Management and Access Control
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom role and create tables on theme activation
 */
function trident_theme_activation() {
    // Remove role if it exists to avoid conflicts
    remove_role('trident-admin');
    
    // Add the trident-admin role
    add_role('trident-admin', 'TRIDENT Admin', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'manage_trident_products' => true,
        'manage_trident_orders' => true,
        'manage_trident_banners' => true
    ));
    
    // Create database tables
    trident_create_tables();
    
    // Create default trident-admin user
    trident_create_default_admin_user();
}
add_action('after_switch_theme', 'trident_theme_activation');

/**
 * Add custom capabilities
 */
function trident_add_custom_capabilities() {
    $admin_role = get_role('administrator');
    if ($admin_role) {
        $admin_role->add_cap('manage_trident_products');
        $admin_role->add_cap('manage_trident_orders');
        $admin_role->add_cap('manage_trident_banners');
    }
}
add_action('init', 'trident_add_custom_capabilities');

/**
 * Check if user has TRIDENT admin access
 */
function trident_user_has_access($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    $user = get_userdata($user_id);
    
    // Check if user has trident-admin role or is administrator
    return in_array('trident-admin', $user->roles) || in_array('administrator', $user->roles);
}

/**
 * Redirect users without proper access
 */
function trident_check_admin_access() {
    if (is_page('trident-admin') || is_page('login')) {
        if (!is_user_logged_in()) {
            wp_redirect(home_url('/login'));
            exit;
        }
        
        if (!trident_user_has_access()) {
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('template_redirect', 'trident_check_admin_access');

/**
 * Custom login redirect based on user role
 */
function trident_login_redirect($redirect_to, $requested_redirect_to, $user) {
    // If a specific redirect was requested, respect it
    if (!empty($requested_redirect_to)) {
        return $requested_redirect_to;
    }
    
    // Check if user has TRIDENT admin access
    if (trident_user_has_access($user->ID)) {
        return home_url('/trident-admin');
    } else {
        // Non-TRIDENT admin users go to wp-admin
        return admin_url();
    }
}
add_filter('login_redirect', 'trident_login_redirect', 10, 3);

/**
 * Redirect TRIDENT admin users away from wp-admin to trident-admin
 */
function trident_admin_redirect() {
    // Only run this on admin pages
    if (!is_admin()) {
        return;
    }
    
    // Don't redirect on AJAX requests
    if (wp_doing_ajax()) {
        return;
    }
    
    // Don't redirect on admin-ajax.php
    if (strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') !== false) {
        return;
    }
    
    // Check if user is logged in and has TRIDENT admin access
    if (is_user_logged_in() && trident_user_has_access()) {
        // Get the current admin page
        $current_page = $_SERVER['REQUEST_URI'];
        
        // Don't redirect if they're already on the trident-admin page
        if (strpos($current_page, 'trident-admin') !== false) {
            return;
        }
        
        // Redirect to TRIDENT admin
        wp_redirect(home_url('/trident-admin'));
        exit;
    }
}
add_action('admin_init', 'trident_admin_redirect');

/**
 * Custom logout redirect
 */
function trident_logout_redirect($redirect_to, $requested_redirect_to, $user) {
    // If a specific redirect was requested, respect it
    if (!empty($requested_redirect_to)) {
        return $requested_redirect_to;
    }
    
    // Default logout redirect to custom login page
    return home_url('/login');
}
add_filter('logout_redirect', 'trident_logout_redirect', 10, 3);

/**
 * Create default trident-admin user
 */
function trident_create_default_admin_user() {
    // Check if the default user already exists
    $existing_user = get_user_by('login', 'trident-admin');
    
    if ($existing_user) {
        // Update existing user to ensure they have the trident-admin role
        $user = new WP_User($existing_user->ID);
        $user->set_role('trident-admin');
        return;
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
        
        // Log the creation
        error_log('TRIDENT: Default admin user created successfully');
    } else {
        // Log any errors
        error_log('TRIDENT: Error creating default admin user - ' . $user_id->get_error_message());
    }
} 