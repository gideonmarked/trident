<?php
/**
 * Test TRIDENT Logo Display
 * 
 * This script tests if the TRIDENT logo is displaying correctly.
 */

require_once('../../../wp-load.php');

echo "<h1>TRIDENT Logo Test</h1>";

// Check if user has access
if (!trident_user_has_access()) {
    echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>❌ Access Denied:</strong> You must be logged in as a TRIDENT admin to test.</p>";
    echo "<p><a href='" . home_url('/login') . "'>Go to Login</a></p>";
    echo "</div>";
    exit;
}

$logo_url = get_template_directory_uri() . '/assets/images/trident-header-logo.png';

echo "<h2>1. Logo Information</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Logo URL:</strong> <code>$logo_url</code></p>";
echo "<p><strong>Template Directory:</strong> <code>" . get_template_directory_uri() . "</code></p>";
echo "<p><strong>File Path:</strong> <code>" . get_template_directory() . "/assets/images/trident-header-logo.png</code></p>";
echo "</div>";

echo "<h2>2. Logo Display Test</h2>";
echo "<div style='background: #f0fdf4; border: 1px solid #22c55e; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Logo Preview:</strong></p>";
echo "<div style='width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin: 10px 0;'>";
echo "<img src='$logo_url' alt='TRIDENT Logo' style='width: 100%; height: 100%; object-fit: contain;'>";
echo "</div>";
echo "</div>";

echo "<h2>3. Admin Portal Links</h2>";
echo "<div style='background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 8px;'>";
echo "<h3>Test the logo in these admin pages:</h3>";
echo "<ul>";
echo "<li><a href='" . home_url('/trident-admin') . "' target='_blank'>TRIDENT Admin Portal</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=products') . "' target='_blank'>Products Page</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=banners') . "' target='_blank'>Banners Page</a></li>";
echo "<li><a href='" . home_url('/trident-admin?page=orders') . "' target='_blank'>Orders Page</a></li>";
echo "</ul>";
echo "</div>";

echo "<h2>4. File Accessibility Test</h2>";
$file_exists = file_exists(get_template_directory() . '/assets/images/trident-header-logo.png');
$file_size = $file_exists ? filesize(get_template_directory() . '/assets/images/trident-header-logo.png') : 0;

echo "<div style='background: " . ($file_exists ? "#f0fdf4" : "#fef2f2") . "; border: 1px solid " . ($file_exists ? "#22c55e" : "#ef4444") . "; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>File Exists:</strong> " . ($file_exists ? "✅ Yes" : "❌ No") . "</p>";
if ($file_exists) {
    echo "<p><strong>File Size:</strong> " . number_format($file_size) . " bytes</p>";
    echo "<p><strong>Last Modified:</strong> " . date('Y-m-d H:i:s', filemtime(get_template_directory() . '/assets/images/trident-header-logo.png')) . "</p>";
}
echo "</div>";

echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>⚠️ Security Notice:</h3>";
echo "<p>Delete this file after testing is complete.</p>";
echo "</div>";
?> 