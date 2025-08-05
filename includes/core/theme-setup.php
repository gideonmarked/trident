<?php
/**
 * Theme Setup and Initialization
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

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