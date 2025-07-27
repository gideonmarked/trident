<?php
/**
 * Test script to verify sidebar logo functionality
 * Access this file directly to test logo display
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
    <title>Sidebar Logo Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .logo-test { display: flex; align-items: center; gap: 10px; margin: 10px 0; padding: 10px; background: #f9f9f9; border-radius: 4px; }
        .logo-icon { width: 200px; height: 40px; border: 1px solid #ccc; }
        .logo-text { font-size: 1.5rem; font-weight: 900; text-transform: uppercase; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>Sidebar Logo Test</h1>
    
    <div class="test-section">
        <h2>Logo File Check</h2>
        <?php
        $logo_path = get_template_directory() . '/assets/images/trident-header-logo.png';
        $logo_url = get_template_directory_uri() . '/assets/images/trident-header-logo.png';
        
        if (file_exists($logo_path)) {
            echo '<p class="success">✅ Logo file exists at: ' . $logo_path . '</p>';
            echo '<p class="info">Logo URL: ' . $logo_url . '</p>';
            
            $file_size = filesize($logo_path);
            echo '<p class="info">File size: ' . number_format($file_size) . ' bytes</p>';
            
            $image_info = getimagesize($logo_path);
            if ($image_info) {
                echo '<p class="success">✅ Valid image file: ' . $image_info[0] . 'x' . $image_info[1] . ' pixels</p>';
            } else {
                echo '<p class="error">❌ Invalid image file</p>';
            }
        } else {
            echo '<p class="error">❌ Logo file not found at: ' . $logo_path . '</p>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>Logo Display Test</h2>
        <p>Below is how the sidebar logo should appear:</p>
        
        <div class="logo-test">
            <div class="logo-icon">
                <img src="<?php echo $logo_url; ?>" alt="TRIDENT Logo" style="width: 100%; height: 100%; object-fit: contain; max-width: 100%;">
            </div>
            <div class="logo-text" id="testLogoText">TRIDENT</div>
        </div>
        
        <p class="info">The text "TRIDENT" should be hidden when the logo image loads successfully.</p>
    </div>
    
    <div class="test-section">
        <h2>JavaScript Test</h2>
        <p>Testing logo text hiding functionality...</p>
        <div id="jsTestResult">JavaScript test will run when page loads...</div>
    </div>
    
    <div class="test-section">
        <h2>Navigation</h2>
        <p><a href="<?php echo home_url('/trident-admin'); ?>">TRIDENT Admin Portal</a></p>
        <p><a href="<?php echo home_url('/trident-admin?page=products'); ?>">Products Page</a></p>
        <p><a href="<?php echo home_url('/trident-admin?page=banners'); ?>">Banners Page</a></p>
        <p><a href="<?php echo home_url('/dashboard'); ?>">Dashboard Page</a></p>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoIcon = document.querySelector('.logo-icon img');
            const logoText = document.getElementById('testLogoText');
            const jsTestResult = document.getElementById('jsTestResult');
            
            if (logoIcon && logoText) {
                // Check if image is loaded
                if (logoIcon.complete && logoIcon.naturalWidth > 0) {
                    logoText.style.display = 'none';
                    jsTestResult.innerHTML = '<p class="success">✅ Logo image loaded successfully, text hidden</p>';
                } else {
                    // Wait for image to load
                    logoIcon.addEventListener('load', function() {
                        logoText.style.display = 'none';
                        jsTestResult.innerHTML = '<p class="success">✅ Logo image loaded successfully, text hidden</p>';
                    });
                    
                    logoIcon.addEventListener('error', function() {
                        jsTestResult.innerHTML = '<p class="error">❌ Logo image failed to load</p>';
                    });
                    
                    // Fallback: check after 1 second
                    setTimeout(function() {
                        if (logoIcon.complete && logoIcon.naturalWidth > 0) {
                            logoText.style.display = 'none';
                            jsTestResult.innerHTML = '<p class="success">✅ Logo image loaded successfully, text hidden (fallback)</p>';
                        } else {
                            jsTestResult.innerHTML = '<p class="warning">⚠️ Logo image not loaded after 1 second</p>';
                        }
                    }, 1000);
                }
            } else {
                jsTestResult.innerHTML = '<p class="error">❌ Logo elements not found</p>';
            }
        });
    </script>
</body>
</html> 