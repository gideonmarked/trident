<?php
/**
 * TRIDENT Theme Version Update Script
 * 
 * This script helps update the theme version across all files.
 * Run this script to update version numbers for cache busting.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // Load WordPress if not already loaded
    if (!file_exists('../../../wp-load.php')) {
        die('WordPress not found. Please run this script from the theme directory.');
    }
    require_once('../../../wp-load.php');
}

// Get current version from functions.php
$functions_file = __DIR__ . '/functions.php';
$current_version = '1.0.0';

if (file_exists($functions_file)) {
    $content = file_get_contents($functions_file);
    if (preg_match("/define\('TRIDENT_VERSION',\s*'([^']+)'\);/", $content, $matches)) {
        $current_version = $matches[1];
    }
}

echo "<h1>TRIDENT Theme Version Management</h1>";
echo "<p><strong>Current Version:</strong> {$current_version}</p>";

// Handle version update
if (isset($_POST['new_version']) && !empty($_POST['new_version'])) {
    $new_version = sanitize_text_field($_POST['new_version']);
    
    // Update functions.php
    $content = file_get_contents($functions_file);
    $content = preg_replace("/define\('TRIDENT_VERSION',\s*'[^']+'\);/", "define('TRIDENT_VERSION', '{$new_version}');", $content);
    
    if (file_put_contents($functions_file, $content)) {
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>Success!</strong> Version updated to {$new_version}";
        echo "</div>";
        
        // Update style.css
        $style_file = __DIR__ . '/style.css';
        if (file_exists($style_file)) {
            $style_content = file_get_contents($style_file);
            $style_content = preg_replace("/Version:\s*[0-9.]+/", "Version: {$new_version}", $style_content);
            file_put_contents($style_file, $style_content);
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<strong>Success!</strong> style.css version updated to {$new_version}";
            echo "</div>";
        }
        
        $current_version = $new_version;
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>Error!</strong> Could not update version.";
        echo "</div>";
    }
}

// Show version update form
echo "<form method='post' style='margin: 20px 0;'>";
echo "<label for='new_version'><strong>New Version:</strong></label><br>";
echo "<input type='text' id='new_version' name='new_version' value='{$current_version}' style='padding: 8px; margin: 10px 0; width: 200px;'>";
echo "<br><button type='submit' style='background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Update Version</button>";
echo "</form>";

// Show file information
echo "<h2>File Information</h2>";
$js_files = [
    '/assets/js/main.js',
    '/assets/js/admin.js',
    '/assets/js/trident-homepage.js',
    '/assets/js/trident-all-products.js'
];

$css_files = [
    '/style.css',
    '/assets/css/admin.css',
    '/assets/css/trident-homepage.css',
    '/assets/css/trident-all-products.css'
];

echo "<h3>JavaScript Files</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>File</th><th>Last Modified</th><th>Version</th></tr>";

foreach ($js_files as $file) {
    $full_path = get_template_directory() . $file;
    if (file_exists($full_path)) {
        $mtime = filemtime($full_path);
        $version = trident_get_file_version($file);
        echo "<tr>";
        echo "<td>{$file}</td>";
        echo "<td>" . date('Y-m-d H:i:s', $mtime) . "</td>";
        echo "<td>{$version}</td>";
        echo "</tr>";
    }
}
echo "</table>";

echo "<h3>CSS Files</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>File</th><th>Last Modified</th><th>Version</th></tr>";

foreach ($css_files as $file) {
    $full_path = get_template_directory() . $file;
    if (file_exists($full_path)) {
        $mtime = filemtime($full_path);
        $version = trident_get_file_version($file);
        echo "<tr>";
        echo "<td>{$file}</td>";
        echo "<td>" . date('Y-m-d H:i:s', $mtime) . "</td>";
        echo "<td>{$version}</td>";
        echo "</tr>";
    }
}
echo "</table>";

echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<strong>Note:</strong> File versions are automatically generated based on file modification time. ";
echo "This ensures cache busting when files are updated.";
echo "</div>";

echo "<p><a href='" . home_url('/wp-admin/') . "'>← Back to WordPress Admin</a></p>";
?> 