<?php
/**
 * Test script to verify homepage banner functionality
 * Access this file directly to test banner display
 */

require_once('../../../wp-load.php');

// Check if user has access
if (!function_exists('trident_user_has_access') || !trident_user_has_access()) {
    echo '<h1>Access Denied</h1>';
    echo '<p>You must be logged in as a trident-admin or administrator to access this page.</p>';
    echo '<p><a href="' . home_url('/login') . '">Go to Login</a></p>';
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Homepage Banner Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .banner-item { margin: 10px 0; padding: 10px; background: #f9f9f9; border-radius: 4px; }
        .banner-image { max-width: 200px; max-height: 150px; border: 1px solid #ccc; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>Homepage Banner Test</h1>
    
    <div class="test-section">
        <h2>Database Functions Test</h2>
        <?php
        // Test if database functions exist
        if (function_exists('trident_get_banners')) {
            echo '<p class="success">✅ trident_get_banners() function exists</p>';
        } else {
            echo '<p class="error">❌ trident_get_banners() function does not exist</p>';
        }
        
        if (function_exists('trident_insert_banner')) {
            echo '<p class="success">✅ trident_insert_banner() function exists</p>';
        } else {
            echo '<p class="error">❌ trident_insert_banner() function does not exist</p>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>Current Banners in Database</h2>
        <?php
        if (function_exists('trident_get_banners')) {
            $banners = trident_get_banners();
            
            if (!empty($banners)) {
                echo '<p class="success">Found ' . count($banners) . ' banner(s) in database:</p>';
                foreach ($banners as $banner) {
                    echo '<div class="banner-item">';
                    echo '<strong>ID:</strong> ' . $banner->id . '<br>';
                    echo '<strong>Title:</strong> ' . ($banner->title ?: 'No title') . '<br>';
                    echo '<strong>Image URL:</strong> ' . $banner->image_url . '<br>';
                    echo '<strong>Status:</strong> ' . $banner->status . '<br>';
                    echo '<strong>Created:</strong> ' . $banner->created_at . '<br>';
                    echo '<img src="' . esc_url($banner->image_url) . '" alt="Banner" class="banner-image">';
                    echo '</div>';
                }
            } else {
                echo '<p class="info">No banners found in database. You can add banners through the admin portal.</p>';
            }
        } else {
            echo '<p class="error">Cannot retrieve banners - function does not exist</p>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>Homepage Integration Test</h2>
        <?php
        // Simulate the homepage banner logic
        $banners = function_exists('trident_get_banners') ? trident_get_banners() : array();
        
        if (!empty($banners)) {
            echo '<p class="success">✅ Homepage will display ' . count($banners) . ' uploaded banner(s)</p>';
            echo '<p class="info">The homepage will show these banners in the hero slider instead of the default images.</p>';
        } else {
            echo '<p class="info">ℹ️ Homepage will display default banners (no uploaded banners found)</p>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>Navigation</h2>
        <p><a href="<?php echo home_url('/'); ?>">View Homepage</a></p>
        <p><a href="<?php echo home_url('/trident-admin'); ?>">TRIDENT Admin Portal</a></p>
        <p><a href="<?php echo home_url('/trident-admin?page=banners'); ?>">Manage Banners</a></p>
    </div>
    
    <div class="test-section">
        <h2>Test Instructions</h2>
        <ol>
            <li>Go to <a href="<?php echo home_url('/trident-admin?page=banners'); ?>">Manage Banners</a></li>
            <li>Upload a new banner image</li>
            <li>Return to this test page to see the new banner</li>
            <li>Visit the <a href="<?php echo home_url('/'); ?>">homepage</a> to see the banner in the hero slider</li>
        </ol>
    </div>
</body>
</html> 