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
 * Add custom body classes
 */
function trident_body_classes($classes) {
    // Add theme class
    $classes[] = 'trident-theme';

    // Add page template class
    if (is_page_template()) {
        $template = get_page_template_slug();
        $classes[] = 'template-' . str_replace('.php', '', $template);
    }

    // Add admin class for TRIDENT admin pages
    if (is_page('trident-admin') || is_page('login')) {
        $classes[] = 'trident-admin-page';
    }

    return $classes;
}
add_filter('body_class', 'trident_body_classes');

/**
 * Add custom query vars
 */
function trident_add_query_vars($vars) {
    $vars[] = 'trident_page';
    return $vars;
}
add_filter('query_vars', 'trident_add_query_vars');

/**
 * Add custom rewrite rules
 */
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

/**
 * Handle custom page templates
 */
function trident_template_redirect() {
    // Handle login page
    if (is_404() && strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
        include(get_template_directory() . '/page-templates/admin/login.php');
        exit;
    }

    // Handle trident-admin page
    if (strpos($_SERVER['REQUEST_URI'], '/trident-admin') !== false) {
        if (is_404() || !is_page()) {
            include(get_template_directory() . '/page-templates/admin/trident-admin.php');
            exit;
        }
    }
}
add_action('template_redirect', 'trident_template_redirect');

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
 * Enqueue scripts and styles
 */
function trident_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('trident-style', get_stylesheet_uri(), array(), TRIDENT_VERSION);

    // Enqueue Tailwind CSS
    wp_enqueue_style('tailwindcss', 'https://cdn.tailwindcss.com', array(), null);

    // Enqueue main JavaScript
    wp_enqueue_script('trident-script', TRIDENT_THEME_URI . '/assets/js/main.js', array('jquery'), TRIDENT_VERSION, true);

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
        wp_enqueue_style('trident-admin', TRIDENT_THEME_URI . '/assets/css/admin.css', array(), TRIDENT_VERSION);
        wp_enqueue_script('trident-admin', TRIDENT_THEME_URI . '/assets/js/admin.js', array('jquery'), TRIDENT_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'trident_admin_scripts');

/**
 * Conditional asset loading for specific page templates
 */
function trident_template_assets() {
    if (is_page_template('page-homepage.php')) {
        wp_enqueue_style('trident-homepage', TRIDENT_THEME_URI . '/assets/css/trident-homepage.css', array(), TRIDENT_VERSION);
        wp_enqueue_script('trident-homepage', TRIDENT_THEME_URI . '/assets/js/trident-homepage.js', array('jquery'), TRIDENT_VERSION, true);
    }

    if (is_page_template('page-all-products.php')) {
        wp_enqueue_style('trident-all-products', TRIDENT_THEME_URI . '/assets/css/trident-all-products.css', array(), TRIDENT_VERSION);
        wp_enqueue_script('trident-all-products', TRIDENT_THEME_URI . '/assets/js/trident-all-products.js', array('jquery'), TRIDENT_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'trident_template_assets');

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