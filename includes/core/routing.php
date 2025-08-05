<?php
/**
 * Custom Routing and Template Handling
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom rewrite rules for TRIDENT routes
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
 * Add custom query vars
 */
function trident_add_query_vars($vars) {
    $vars[] = 'trident_page';
    return $vars;
}
add_filter('query_vars', 'trident_add_query_vars');

/**
 * Handle custom page templates
 */
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

/**
 * Add custom post type for tumbler products if not exists
 */
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

/**
 * Add custom meta boxes for product details
 */
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