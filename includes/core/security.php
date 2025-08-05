<?php
/**
 * Security Enhancements and Customizer
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
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