<?php
/**
 * TRIDENT Theme Functions
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Include database functions
require_once get_template_directory() . '/includes/database.php';

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add custom role and create tables on theme activation
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

// Add custom capabilities
function trident_add_custom_capabilities() {
    $admin_role = get_role('administrator');
    if ($admin_role) {
        $admin_role->add_cap('manage_trident_products');
        $admin_role->add_cap('manage_trident_orders');
        $admin_role->add_cap('manage_trident_banners');
    }
}
add_action('init', 'trident_add_custom_capabilities');

// Check if user has TRIDENT admin access
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

// Redirect users without proper access
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

// Custom login redirect based on user role
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

// Redirect TRIDENT admin users away from wp-admin to trident-admin
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

// Custom logout redirect
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
 * Theme setup
 */
function trident_setup() {
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

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'trident'),
        'footer'  => esc_html__('Footer Menu', 'trident'),
    ));

    // Add image sizes
    add_image_size('trident-hero', 1200, 600, true);
    add_image_size('trident-product', 400, 400, true);
    add_image_size('trident-thumbnail', 300, 200, true);
}
add_action('after_setup_theme', 'trident_setup');

/**
 * Enqueue scripts and styles
 */
function trident_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('trident-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue Tailwind CSS
    wp_enqueue_style('tailwindcss', 'https://cdn.tailwindcss.com', array(), null);
    
    // Enqueue main JavaScript
    wp_enqueue_script('trident-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('trident-script', 'trident_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('trident_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'trident_scripts');

/**
 * Enqueue admin scripts and styles
 */
function trident_admin_scripts($hook) {
    // Only load on specific admin pages if needed
    if (in_array($hook, array('post.php', 'post-new.php'))) {
        wp_enqueue_style('trident-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
        wp_enqueue_script('trident-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), '1.0.0', true);
    }
}
add_action('admin_enqueue_scripts', 'trident_admin_scripts');

/**
 * Conditional asset loading for specific page templates
 */
function trident_template_assets() {
    if (is_page_template('page-homepage.php')) {
        wp_enqueue_style('trident-homepage', get_template_directory_uri() . '/assets/css/trident-homepage.css', array(), '1.0.0');
        wp_enqueue_script('trident-homepage', get_template_directory_uri() . '/assets/js/trident-homepage.js', array('jquery'), '1.0.0', true);
    }
    
    if (is_page_template('page-all-products.php')) {
        wp_enqueue_style('trident-all-products', get_template_directory_uri() . '/assets/css/trident-all-products.css', array(), '1.0.0');
        wp_enqueue_script('trident-all-products', get_template_directory_uri() . '/assets/js/trident-all-products.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'trident_template_assets');

/**
 * Register widget areas
 */
function trident_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'trident'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'trident'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area', 'trident'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here.', 'trident'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'trident_widgets_init');

/**
 * Fallback menu function
 */
function trident_fallback_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . home_url('/') . '">Home</a></li>';
    echo '<li><a href="' . home_url('/about/') . '">About</a></li>';
    echo '<li><a href="' . home_url('/products/') . '">Products</a></li>';
    echo '<li><a href="' . home_url('/contact/') . '">Contact</a></li>';
    echo '</ul>';
}

/**
 * Custom excerpt length
 */
function trident_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'trident_excerpt_length');

/**
 * Custom excerpt more
 */
function trident_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'trident_excerpt_more');

/**
 * Add custom body classes
 */
function trident_body_classes($classes) {
    // Add page template class
    if (is_page_template()) {
        $template = get_page_template_slug();
        $classes[] = 'template-' . str_replace('.php', '', $template);
    }
    
    return $classes;
}
add_filter('body_class', 'trident_body_classes');

/**
 * Customize the login page
 */
function trident_login_logo() {
    echo '<style type="text/css">
        #login h1 a {
            background-image: url(' . get_template_directory_uri() . '/assets/images/logo.png) !important;
            background-size: contain !important;
            width: 300px !important;
            height: 100px !important;
        }
    </style>';
}
add_action('login_head', 'trident_login_logo');

/**
 * Security enhancements
 */
function trident_security_headers() {
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove unnecessary links
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'trident_security_headers');

/**
 * Add theme customizer options
 */
function trident_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('trident_hero', array(
        'title'    => __('Hero Section', 'trident'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('hero_title', array(
        'default'           => 'A TUMBLER FOR TOMORROW',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label'   => __('Hero Title', 'trident'),
        'section' => 'trident_hero',
        'type'    => 'text',
    ));
    
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => '19th Anniversary Limited Edition Tumbler',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label'   => __('Hero Subtitle', 'trident'),
        'section' => 'trident_hero',
        'type'    => 'text',
    ));
}
add_action('customize_register', 'trident_customize_register'); 

// Add custom post type for tumbler products if not exists
function trident_register_post_types() {
    if (!post_type_exists('tumbler_product')) {
        register_post_type('tumbler_product', array(
            'labels' => array(
                'name' => 'Tumbler Products',
                'singular_name' => 'Tumbler Product',
                'add_new' => 'Add New Product',
                'add_new_item' => 'Add New Tumbler Product',
                'edit_item' => 'Edit Tumbler Product',
                'new_item' => 'New Tumbler Product',
                'view_item' => 'View Tumbler Product',
                'search_items' => 'Search Tumbler Products',
                'not_found' => 'No tumbler products found',
                'not_found_in_trash' => 'No tumbler products found in trash'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'menu_icon' => 'dashicons-products',
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'show_in_rest' => true
        ));
    }
}
add_action('init', 'trident_register_post_types');

// Add custom meta boxes for product details
function trident_add_product_meta_boxes() {
    add_meta_box(
        'trident_product_details',
        'Product Details',
        'trident_product_details_callback',
        'tumbler_product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'trident_add_product_meta_boxes');

function trident_product_details_callback($post) {
    wp_nonce_field('trident_save_product_details', 'trident_product_nonce');
    
    $price = get_post_meta($post->ID, '_product_price', true);
    $colors = get_post_meta($post->ID, '_product_colors', true);
    if (!is_array($colors)) {
        $colors = array();
    }
    
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="product_price">Price (₱)</label></th>';
    echo '<td><input type="number" id="product_price" name="product_price" value="' . esc_attr($price) . '" step="0.01" min="0" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label>Available Colors</label></th>';
    echo '<td>';
    $color_options = function_exists('trident_get_available_colors') ? array_column(trident_get_available_colors(), 'class') : array('yellow', 'green', 'black', 'mint', 'gold');
    foreach ($color_options as $color) {
        $checked = in_array($color, $colors) ? 'checked' : '';
        echo '<label style="margin-right: 15px;"><input type="checkbox" name="product_colors[]" value="' . $color . '" ' . $checked . ' /> ' . ucfirst($color) . '</label>';
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>';
}

function trident_save_product_details($post_id) {
    if (!isset($_POST['trident_product_nonce']) || !wp_verify_nonce($_POST['trident_product_nonce'], 'trident_save_product_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['product_price'])) {
        update_post_meta($post_id, '_product_price', sanitize_text_field($_POST['product_price']));
    }
    
    if (isset($_POST['product_colors'])) {
        update_post_meta($post_id, '_product_colors', array_map('sanitize_text_field', $_POST['product_colors']));
    } else {
        update_post_meta($post_id, '_product_colors', array());
    }
}
add_action('save_post', 'trident_save_product_details');

// Add custom CSS for admin
function trident_admin_styles() {
    if (is_page('trident-admin') || is_page('login')) {
        echo '<style>
            body { background: #f8fafc !important; }
            .wp-admin { background: #f8fafc !important; }
        </style>';
    }
}
add_action('wp_head', 'trident_admin_styles');

// AJAX handlers for banner management
function trident_add_banner_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_banner_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check user permissions
    if (!trident_user_has_access()) {
        wp_die('Access denied');
    }
    
    // Handle image upload
    $image_url = '';
    if (!empty($_FILES['banner_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $attachment_id = media_handle_upload('banner_image', 0);
        if (!is_wp_error($attachment_id)) {
            $image_url = wp_get_attachment_url($attachment_id);
        } else {
            wp_send_json_error('Error uploading image: ' . $attachment_id->get_error_message());
        }
    } else {
        wp_send_json_error('Banner image is required');
    }
    
    // Prepare banner data
    $banner_data = array(
        'title' => '',
        'subtitle' => '',
        'description' => '',
        'price' => 0,
        'image_url' => $image_url,
        'status' => 'active',
        'sort_order' => 0
    );
    
    // Insert banner
    $banner_id = trident_insert_banner($banner_data);
    
    if ($banner_id) {
        wp_send_json_success('Banner added successfully');
    } else {
        wp_send_json_error('Error adding banner');
    }
}
add_action('wp_ajax_trident_add_banner', 'trident_add_banner_ajax');

function trident_delete_banner_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_banner_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check user permissions
    if (!trident_user_has_access()) {
        wp_die('Access denied');
    }
    
    $banner_id = intval($_POST['banner_id']);
    
    if ($banner_id <= 0) {
        wp_send_json_error('Invalid banner ID');
    }
    
    // Delete banner
    $result = trident_delete_banner($banner_id);
    
    if ($result) {
        wp_send_json_success('Banner deleted successfully');
    } else {
        wp_send_json_error('Error deleting banner');
    }
}
add_action('wp_ajax_trident_delete_banner', 'trident_delete_banner_ajax');

// Add custom rewrite rules for TRIDENT routes
function trident_add_rewrite_rules() {
    add_rewrite_rule(
        '^login/?$',
        'index.php?pagename=login',
        'top'
    );
    add_rewrite_rule(
        '^trident-admin/?$',
        'index.php?pagename=trident-admin',
        'top'
    );
    add_rewrite_rule(
        '^trident-admin/([^/]+)/?$',
        'index.php?pagename=trident-admin&page=$matches[1]',
        'top'
    );
}
add_action('init', 'trident_add_rewrite_rules');

// Add custom query vars
function trident_add_query_vars($vars) {
    $vars[] = 'trident_page';
    return $vars;
}
add_filter('query_vars', 'trident_add_query_vars');

// Handle custom page templates
function trident_template_redirect() {
    global $wp_query;
    
    // Handle login page
    if (is_404() && strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
        include(get_template_directory() . '/page-login.php');
        exit;
    }
    
    // Handle trident-admin page (including with parameters)
    if (strpos($_SERVER['REQUEST_URI'], '/trident-admin') !== false) {
        // Check if it's a 404 or if we need to handle it
        if (is_404() || !is_page()) {
            include(get_template_directory() . '/page-trident-admin.php');
            exit;
        }
    }
}
add_action('template_redirect', 'trident_template_redirect');

// Create default trident-admin user
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

// Product AJAX handler
function trident_add_product_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'trident_product_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    // Check user permissions
    if (!trident_user_has_access()) {
        wp_send_json_error('Access denied');
    }
    
    // Get form data
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_details = sanitize_textarea_field($_POST['product_details']);
    $size = sanitize_text_field($_POST['size']);
    
    // Get color data
    $body_colors = json_decode(stripslashes($_POST['body_colors']), true);
    $cap_colors = json_decode(stripslashes($_POST['cap_colors']), true);
    $boot_colors = json_decode(stripslashes($_POST['boot_colors']), true);
    
    // Validate required fields
    if (empty($product_name)) {
        wp_send_json_error('Product name is required');
    }
    
    if (empty($product_details)) {
        wp_send_json_error('Product details are required');
    }
    
    // Validate that at least one color is selected for each category
    if (empty($body_colors) || !is_array($body_colors)) {
        wp_send_json_error('At least one body color is required');
    }
    
    if (empty($cap_colors) || !is_array($cap_colors)) {
        wp_send_json_error('At least one cap color is required');
    }
    
    if (empty($boot_colors) || !is_array($boot_colors)) {
        wp_send_json_error('At least one boot color is required');
    }
    
    // Handle image upload
    $image_url = '';
    if (!empty($_FILES['product_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $attachment_id = media_handle_upload('product_image', 0);
        
        if (is_wp_error($attachment_id)) {
            wp_send_json_error('Error uploading image: ' . $attachment_id->get_error_message());
        }
        
        $image_url = wp_get_attachment_url($attachment_id);
    }
    
    // Prepare product data
    $product_data = array(
        'name' => $product_name,
        'description' => $product_details,
        'price' => 1299.00, // Default price
        'image_url' => $image_url,
        'body_colors' => $body_colors,
        'cap_colors' => $cap_colors,
        'boot_colors' => $boot_colors,
        'sizes' => json_encode(array($size)),
        'stock_quantity' => 100,
        'status' => 'active'
    );
    
    // Insert product into database
    $result = trident_insert_product($product_data);
    
    if ($result) {
        wp_send_json_success('Product added successfully');
    } else {
        wp_send_json_error('Error adding product to database');
    }
}
add_action('wp_ajax_trident_add_product', 'trident_add_product_ajax');

/**
 * Render TRIDENT page with common layout
 * 
 * @param string $content The page content
 * @param string $page_title Optional page title
 */
function trident_render_page($content, $page_title = '') {
    global $content;
    $content = $content;
    include get_template_directory() . '/template-parts/trident/layout.php';
} 