<?php
/**
 * Test Script to Demonstrate Time-Based Versioning
 * 
 * This script shows how the versioning system works with file modification times.
 */

// Load WordPress
require_once('../../../wp-load.php');

// Get the versioning function
function trident_get_file_version($file_path) {
    $file_path = get_template_directory() . $file_path;
    if (file_exists($file_path)) {
        return filemtime($file_path);
    }
    return '1.0.0';
}

echo "<h1>TRIDENT Theme - Time-Based Versioning Demo</h1>";

// JavaScript files
$js_files = [
    '/assets/js/main.js',
    '/assets/js/admin.js',
    '/assets/js/trident-homepage.js',
    '/assets/js/trident-all-products.js'
];

// CSS files
$css_files = [
    '/style.css',
    '/assets/css/admin.css',
    '/assets/css/trident-homepage.css',
    '/assets/css/trident-all-products.css'
];

echo "<h2>JavaScript Files - Version Numbers (Unix Timestamps)</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th style='padding: 10px;'>File</th>";
echo "<th style='padding: 10px;'>Last Modified</th>";
echo "<th style='padding: 10px;'>Version Number</th>";
echo "<th style='padding: 10px;'>Human Readable</th>";
echo "</tr>";

foreach ($js_files as $file) {
    $full_path = get_template_directory() . $file;
    if (file_exists($full_path)) {
        $mtime = filemtime($full_path);
        $version = trident_get_file_version($file);
        $human_time = date('Y-m-d H:i:s', $mtime);
        
        echo "<tr>";
        echo "<td style='padding: 10px;'>{$file}</td>";
        echo "<td style='padding: 10px;'>{$human_time}</td>";
        echo "<td style='padding: 10px; font-family: monospace;'>{$version}</td>";
        echo "<td style='padding: 10px;'>{$human_time}</td>";
        echo "</tr>";
    }
}
echo "</table>";

echo "<h2>CSS Files - Version Numbers (Unix Timestamps)</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th style='padding: 10px;'>File</th>";
echo "<th style='padding: 10px;'>Last Modified</th>";
echo "<th style='padding: 10px;'>Version Number</th>";
echo "<th style='padding: 10px;'>Human Readable</th>";
echo "</tr>";

foreach ($css_files as $file) {
    $full_path = get_template_directory() . $file;
    if (file_exists($full_path)) {
        $mtime = filemtime($full_path);
        $version = trident_get_file_version($file);
        $human_time = date('Y-m-d H:i:s', $mtime);
        
        echo "<tr>";
        echo "<td style='padding: 10px;'>{$file}</td>";
        echo "<td style='padding: 10px;'>{$human_time}</td>";
        echo "<td style='padding: 10px; font-family: monospace;'>{$version}</td>";
        echo "<td style='padding: 10px;'>{$human_time}</td>";
        echo "</tr>";
    }
}
echo "</table>";

echo "<h2>How It Works</h2>";
echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>✅ Time-Based Versioning System</h3>";
echo "<ul>";
echo "<li><strong>Automatic Updates:</strong> Every time you modify a file, its version number changes automatically</li>";
echo "<li><strong>Cache Busting:</strong> Browsers will download the new version because the URL changes</li>";
echo "<li><strong>Unix Timestamps:</strong> Version numbers are Unix timestamps (seconds since 1970)</li>";
echo "<li><strong>No Manual Work:</strong> You don't need to manually update version numbers</li>";
echo "</ul>";
echo "</div>";

echo "<h2>Example URLs Generated</h2>";
echo "<div style='background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<p><strong>Before:</strong> <code>/wp-content/themes/trident/assets/js/main.js</code></p>";
echo "<p><strong>After:</strong> <code>/wp-content/themes/trident/assets/js/main.js?ver=" . trident_get_file_version('/assets/js/main.js') . "</code></p>";
echo "</div>";

echo "<h2>Testing Version Changes</h2>";
echo "<p>To test that versioning works:</p>";
echo "<ol>";
echo "<li>Edit any JavaScript or CSS file</li>";
echo "<li>Save the file</li>";
echo "<li>Refresh this page to see the new version number</li>";
echo "<li>The version number will be different (higher) than before</li>";
echo "</ol>";

echo "<p><a href='" . home_url('/wp-admin/') . "'>← Back to WordPress Admin</a></p>";
?> 