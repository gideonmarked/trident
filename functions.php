<?php
/**
 * TRIDENT Theme Functions
 * 
 * Main functions file that loads all theme modules
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('TRIDENT_VERSION', '1.0.0');
define('TRIDENT_THEME_DIR', get_template_directory());
define('TRIDENT_THEME_URI', get_template_directory_uri());

/**
 * Start session for cart functionality
 */
function trident_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'trident_start_session', 1);

/**
 * Load core theme files
 */
function trident_load_core_files() {
    // Core functionality
    require_once TRIDENT_THEME_DIR . '/includes/core/theme-setup.php';
    require_once TRIDENT_THEME_DIR . '/includes/core/user-management.php';
    require_once TRIDENT_THEME_DIR . '/includes/core/security.php';
    require_once TRIDENT_THEME_DIR . '/includes/core/routing.php';
}
add_action('after_setup_theme', 'trident_load_core_files');

/**
 * Load database files
 */
function trident_load_database_files() {
    // Database functionality
    require_once TRIDENT_THEME_DIR . '/includes/database/database.php';
    require_once TRIDENT_THEME_DIR . '/includes/database/products.php';
    require_once TRIDENT_THEME_DIR . '/includes/database/orders.php';
    require_once TRIDENT_THEME_DIR . '/includes/database/banners.php';
}
add_action('init', 'trident_load_database_files');

/**
 * Load admin files
 */
function trident_load_admin_files() {
    // Admin functionality
    require_once TRIDENT_THEME_DIR . '/includes/admin/admin-ajax.php';
}
add_action('init', 'trident_load_admin_files');

/**
 * Load component files
 */
function trident_load_component_files() {
    // Component functionality
    require_once TRIDENT_THEME_DIR . '/includes/components/color-picker.php';
    require_once TRIDENT_THEME_DIR . '/includes/components/mini-cart.php';
}
add_action('init', 'trident_load_component_files');

/**
 * Load frontend files
 */
function trident_load_frontend_files() {
    // Frontend functionality
    if (!is_admin()) {
        // Load frontend-specific files here
    }
}
add_action('init', 'trident_load_frontend_files');

/**
 * Generate dummy products for demonstration
 */
function trident_generate_dummy_products() {
    // Get the last product ID to avoid redundancy
    global $wpdb;
    $table_products = $wpdb->prefix . 'trident_products';
    $last_id = $wpdb->get_var("SELECT MAX(id) FROM $table_products");
    $start_id = $last_id ? $last_id + 1 : 1;
    
    // Check if we already have 10 or more dummy products
    $existing_products = trident_get_products();
    if (count($existing_products) >= 10) {
        return; // Don't create duplicates if we already have enough
    }
    
    $dummy_products = array(
        array(
            'name' => 'Classic TRIDENT Tumbler',
            'description' => 'Our signature tumbler with premium insulation and sleek design. Perfect for hot and cold beverages.',
            'price' => 1299.00,
            'body_colors' => array('#1f2937', '#059669', '#dc2626', '#f59e0b', '#3b82f6'),
            'cap_colors' => array('#1f2937', '#059669', '#dc2626'),
            'boot_colors' => array('#1f2937', '#059669', '#dc2626'),
            'sizes' => array('20oz', '30oz', '40oz')
        ),
        array(
            'name' => 'Premium TRIDENT Tumbler',
            'description' => 'Upgraded version with enhanced insulation and premium materials. Ideal for outdoor adventures.',
            'price' => 1499.00,
            'body_colors' => array('#7c3aed', '#ec4899', '#f97316', '#10b981', '#6366f1'),
            'cap_colors' => array('#7c3aed', '#ec4899', '#f97316'),
            'boot_colors' => array('#7c3aed', '#ec4899', '#f97316'),
            'sizes' => array('20oz', '30oz', '40oz')
        ),
        array(
            'name' => 'TRIDENT Travel Tumbler',
            'description' => 'Compact and lightweight design perfect for travel. Fits in most cup holders.',
            'price' => 999.00,
            'body_colors' => array('#6b7280', '#84cc16', '#f43f5e', '#fbbf24', '#8b5cf6'),
            'cap_colors' => array('#6b7280', '#84cc16', '#f43f5e'),
            'boot_colors' => array('#6b7280', '#84cc16', '#f43f5e'),
            'sizes' => array('16oz', '20oz')
        ),
        array(
            'name' => 'TRIDENT Sports Tumbler',
            'description' => 'Designed for active lifestyles with enhanced grip and durability. Perfect for gym and sports.',
            'price' => 1199.00,
            'body_colors' => array('#ef4444', '#22c55e', '#3b82f6', '#f59e0b', '#8b5cf6'),
            'cap_colors' => array('#ef4444', '#22c55e', '#3b82f6'),
            'boot_colors' => array('#ef4444', '#22c55e', '#3b82f6'),
            'sizes' => array('24oz', '32oz')
        ),
        array(
            'name' => 'TRIDENT Office Tumbler',
            'description' => 'Professional design perfect for the workplace. Keeps beverages at optimal temperature.',
            'price' => 1099.00,
            'body_colors' => array('#374151', '#6b7280', '#9ca3af', '#d1d5db', '#f3f4f6'),
            'cap_colors' => array('#374151', '#6b7280', '#9ca3af'),
            'boot_colors' => array('#374151', '#6b7280', '#9ca3af'),
            'sizes' => array('20oz', '30oz')
        ),
        array(
            'name' => 'TRIDENT Adventure Tumbler',
            'description' => 'Rugged design for outdoor enthusiasts. Built to withstand extreme conditions.',
            'price' => 1399.00,
            'body_colors' => array('#92400e', '#78350f', '#451a03', '#dc2626', '#059669'),
            'cap_colors' => array('#92400e', '#78350f', '#451a03'),
            'boot_colors' => array('#92400e', '#78350f', '#451a03'),
            'sizes' => array('32oz', '40oz')
        ),
        array(
            'name' => 'TRIDENT Minimalist Tumbler',
            'description' => 'Clean and simple design with focus on functionality. Perfect for everyday use.',
            'price' => 899.00,
            'body_colors' => array('#ffffff', '#000000', '#6b7280', '#d1d5db'),
            'cap_colors' => array('#ffffff', '#000000', '#6b7280'),
            'boot_colors' => array('#ffffff', '#000000', '#6b7280'),
            'sizes' => array('16oz', '20oz')
        ),
        array(
            'name' => 'TRIDENT Family Tumbler',
            'description' => 'Large capacity tumbler perfect for families. Great for sharing beverages.',
            'price' => 1599.00,
            'body_colors' => array('#fbbf24', '#f59e0b', '#d97706', '#92400e', '#78350f'),
            'cap_colors' => array('#fbbf24', '#f59e0b', '#d97706'),
            'boot_colors' => array('#fbbf24', '#f59e0b', '#d97706'),
            'sizes' => array('40oz', '50oz')
        ),
        array(
            'name' => 'TRIDENT Student Tumbler',
            'description' => 'Affordable option for students. Reliable insulation at a great price.',
            'price' => 799.00,
            'body_colors' => array('#3b82f6', '#1d4ed8', '#1e40af', '#1e3a8a', '#172554'),
            'cap_colors' => array('#3b82f6', '#1d4ed8', '#1e40af'),
            'boot_colors' => array('#3b82f6', '#1d4ed8', '#1e40af'),
            'sizes' => array('16oz', '20oz')
        ),
        array(
            'name' => 'TRIDENT Premium Collection',
            'description' => 'Limited edition premium tumbler with exclusive colors and premium materials.',
            'price' => 1799.00,
            'body_colors' => array('#7c2d12', '#991b1b', '#7f1d1d', '#450a0a', '#dc2626'),
            'cap_colors' => array('#7c2d12', '#991b1b', '#7f1d1d'),
            'boot_colors' => array('#7c2d12', '#991b1b', '#7f1d1d'),
            'sizes' => array('20oz', '30oz', '40oz')
        )
    );
    
    foreach ($dummy_products as $index => $product_data) {
        $product_data['image_url'] = get_template_directory_uri() . '/assets/images/tumbler-default.png';
        $product_data['stock_quantity'] = 100;
        $product_data['status'] = 'active';
        
        // Use custom ID starting from the last ID + 1
        $custom_id = $start_id + $index;
        trident_insert_product_with_id($product_data, $custom_id);
    }
}

/**
 * Theme activation hook
 */
function trident_theme_activation_hook() {
    // Create database tables
    if (function_exists('trident_create_tables')) {
        trident_create_tables();
    }

    // Create default admin user
    if (function_exists('trident_create_default_admin_user')) {
        trident_create_default_admin_user();
    }
    
    // Generate dummy products
    if (function_exists('trident_generate_dummy_products')) {
        trident_generate_dummy_products();
    }

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'trident_theme_activation_hook');

/**
 * Theme deactivation hook
 */
function trident_theme_deactivation_hook() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'trident_theme_deactivation_hook');

/**
 * Add custom image sizes
 */
function trident_add_image_sizes() {
    add_image_size('trident-hero', 1200, 600, true);
    add_image_size('trident-product', 400, 400, true);
    add_image_size('trident-thumbnail', 300, 200, true);
    add_image_size('trident-banner', 800, 400, true);
}
add_action('after_setup_theme', 'trident_add_image_sizes');

/**
 * Customize the login page
 */
function trident_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'trident_login_logo_url');

function trident_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'trident_login_logo_url_title');

/**
 * Add theme support for various features
 */
function trident_theme_support() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'trident_theme_support');

/**
 * Register navigation menus
 */
function trident_register_nav_menus() {
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'trident'),
        'footer'  => esc_html__('Footer Menu', 'trident'),
    ));
}
add_action('init', 'trident_register_nav_menus');







 