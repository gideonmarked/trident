<?php
/**
 * Flush Rewrite Rules and Test TRIDENT Routes
 */

require_once('../../../wp-load.php');

echo "<h1>TRIDENT Route Testing</h1>";

// Flush rewrite rules
flush_rewrite_rules();

echo "<h2>1. Flushed Rewrite Rules</h2>";
echo "<p>✅ Rewrite rules have been flushed.</p>";

echo "<h2>2. Testing Routes</h2>";

// Test login route
echo "<h3>Testing /login route:</h3>";
$login_url = home_url('/login');
echo "<p><strong>URL:</strong> <a href='$login_url' target='_blank'>$login_url</a></p>";

// Test trident-admin route
echo "<h3>Testing /trident-admin route:</h3>";
$admin_url = home_url('/trident-admin');
echo "<p><strong>URL:</strong> <a href='$admin_url' target='_blank'>$admin_url</a></p>";

echo "<h2>3. Login Credentials</h2>";
echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>Username:</strong> trident-admin</p>";
echo "<p><strong>Password:</strong> trident-admin</p>";
echo "<p><strong>Email:</strong> admin@trident.com</p>";
echo "</div>";

echo "<h2>4. Next Steps</h2>";
echo "<ol>";
echo "<li>Click on the login URL above</li>";
echo "<li>Use the credentials to log in</li>";
echo "<li>You should be redirected to the TRIDENT admin portal</li>";
echo "<li>If it doesn't work, try accessing the admin portal directly</li>";
echo "</ol>";

echo "<div style='background: #fef2f2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>⚠️ Security Notice:</h3>";
echo "<p>Delete this file after testing is complete.</p>";
echo "</div>";
?> 