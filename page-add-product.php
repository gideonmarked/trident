<?php
/**
 * Template Name: Add Product
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Check if user is logged in and has TRIDENT admin access
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

if (!trident_user_has_access()) {
    wp_redirect(home_url());
    exit;
}

$current_user = wp_get_current_user();

// Handle form submission
$success_message = '';
$error_message = '';

if ($_POST && isset($_POST['add_product'])) {
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_price = sanitize_text_field($_POST['product_price']);
    $product_description = sanitize_textarea_field($_POST['product_description']);
    $body_colors = isset($_POST['body_colors']) ? $_POST['body_colors'] : array();
    $cap_colors = isset($_POST['cap_colors']) ? $_POST['cap_colors'] : array();
    $boot_colors = isset($_POST['boot_colors']) ? $_POST['boot_colors'] : array();
    $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : array();
    
    if (empty($product_name) || empty($product_price)) {
        $error_message = 'Please fill in all required fields.';
    } else {
        // Prepare product data for custom database
        $product_data = array(
            'name' => $product_name,
            'description' => $product_description,
            'price' => floatval($product_price),
            'body_colors' => $body_colors,
            'cap_colors' => $cap_colors,
            'boot_colors' => $boot_colors,
            'sizes' => $sizes,
            'stock_quantity' => 0,
            'status' => 'active'
        );
        
        // Handle image upload
        if (!empty($_FILES['product_image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            
            $attachment_id = media_handle_upload('product_image', 0);
            if (!is_wp_error($attachment_id)) {
                $image_url = wp_get_attachment_url($attachment_id);
                $product_data['image_url'] = $image_url;
            }
        }
        
        // Insert product into custom database table
        $product_id = trident_insert_product($product_data);
        
        if ($product_id) {
            $success_message = 'Product added successfully!';
            
            // Redirect to product list after 2 seconds
            header("refresh:2;url=" . home_url('/trident-admin?page=products'));
        } else {
            $error_message = 'Error creating product. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Add Product - TRIDENT Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
            position: relative;
        }
        
        /* Dark overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            pointer-events: none;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: #1f2937;
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            z-index: 10;
        }
        
        .sidebar-header {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid #374151;
            margin-bottom: 2rem;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            width: 100%;
        }
        
        .sidebar-logo-icon {
            width: 100%;
            height: 40px;
            display: flex;
            justify-content: center;
        }
        
        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-width: 100%;
            max-height: 40px;
        }
        
        .sidebar-logo-text {
            font-size: 1.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Hide text when logo icon exists */
        .sidebar-logo-icon img {
            display: block;
        }
        
        .sidebar-logo-icon img + .sidebar-logo-text {
            display: none;
        }
        
        /* Alternative approach: hide text when image is loaded */
        .sidebar-logo:has(.sidebar-logo-icon img) .sidebar-logo-text {
            display: none;
        }
        
        .sidebar-nav {
            padding: 0 2rem;
            flex: 1;
        }
        
        .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background: #fbbf24;
            color: #1f2937;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-footer {
            padding: 2rem;
            border-top: 1px solid #374151;
            margin-top: auto;
        }
        
        .logout-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .logout-link:hover {
            color: #fbbf24;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .back-btn {
            background: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .back-btn:hover {
            background: #4b5563;
        }
        
        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            max-width: 800px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: border-color 0.3s ease;
            background: white;
        }
        
        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #fbbf24;
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .color-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .color-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .color-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .color-label {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }
        
        .submit-btn {
            background: #fbbf24;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }
        
        .submit-btn:hover {
            background: #f59e0b;
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .message.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .message.error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .form-container {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/trident-header-logo.png" alt="TRIDENT Logo">
                    </div>
                    <div class="sidebar-logo-text">TRIDENT</div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="?page=products" class="nav-link">
                        <div class="nav-icon">📦</div>
                        Product List
                    </a>
                </div>
                <div class="nav-item">
                    <a href="?page=banner" class="nav-link">
                        <div class="nav-icon">🖼️</div>
                        Add Banner Photo
                    </a>
                </div>
                <div class="nav-item">
                    <a href="?page=orders" class="nav-link">
                        <div class="nav-icon">📋</div>
                        Order History
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="logout-link">Log Out</a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Add Product</h1>
                <a href="<?php echo home_url('/trident-admin?page=products'); ?>" class="back-btn">
                    ← Back to Products
                </a>
            </div>
            
            <div class="form-container">
                <?php if ($success_message): ?>
                    <div class="message success"><?php echo esc_html($success_message); ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="message error"><?php echo esc_html($error_message); ?></div>
                <?php endif; ?>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label" for="product_name">Product Name *</label>
                        <input type="text" id="product_name" name="product_name" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="product_price">Price (₱) *</label>
                        <input type="number" id="product_price" name="product_price" class="form-input" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="product_description">Description</label>
                        <textarea id="product_description" name="product_description" class="form-textarea" placeholder="Enter product description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Body Colors</label>
                        <div class="color-options">
                            <div class="color-option">
                                <input type="checkbox" id="body_yellow" name="body_colors[]" value="yellow" class="color-checkbox">
                                <label for="body_yellow" class="color-label">Yellow</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="body_green" name="body_colors[]" value="green" class="color-checkbox">
                                <label for="body_green" class="color-label">Green</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="body_black" name="body_colors[]" value="black" class="color-checkbox">
                                <label for="body_black" class="color-label">Black</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="body_mint" name="body_colors[]" value="mint" class="color-checkbox">
                                <label for="body_mint" class="color-label">Mint</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="body_gold" name="body_colors[]" value="gold" class="color-checkbox">
                                <label for="body_gold" class="color-label">Gold</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Cap Colors</label>
                        <div class="color-options">
                            <div class="color-option">
                                <input type="checkbox" id="cap_yellow" name="cap_colors[]" value="yellow" class="color-checkbox">
                                <label for="cap_yellow" class="color-label">Yellow</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="cap_green" name="cap_colors[]" value="green" class="color-checkbox">
                                <label for="cap_green" class="color-label">Green</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="cap_black" name="cap_colors[]" value="black" class="color-checkbox">
                                <label for="cap_black" class="color-label">Black</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="cap_mint" name="cap_colors[]" value="mint" class="color-checkbox">
                                <label for="cap_mint" class="color-label">Mint</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="cap_gold" name="cap_colors[]" value="gold" class="color-checkbox">
                                <label for="cap_gold" class="color-label">Gold</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Boot Colors</label>
                        <div class="color-options">
                            <div class="color-option">
                                <input type="checkbox" id="boot_yellow" name="boot_colors[]" value="yellow" class="color-checkbox">
                                <label for="boot_yellow" class="color-label">Yellow</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="boot_green" name="boot_colors[]" value="green" class="color-checkbox">
                                <label for="boot_green" class="color-label">Green</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="boot_black" name="boot_colors[]" value="black" class="color-checkbox">
                                <label for="boot_black" class="color-label">Black</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="boot_mint" name="boot_colors[]" value="mint" class="color-checkbox">
                                <label for="boot_mint" class="color-label">Mint</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="boot_gold" name="boot_colors[]" value="gold" class="color-checkbox">
                                <label for="boot_gold" class="color-label">Gold</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Available Sizes</label>
                        <div class="color-options">
                            <div class="color-option">
                                <input type="checkbox" id="size_24oz" name="sizes[]" value="24oz" class="color-checkbox">
                                <label for="size_24oz" class="color-label">24oz</label>
                            </div>
                            <div class="color-option">
                                <input type="checkbox" id="size_32oz" name="sizes[]" value="32oz" class="color-checkbox">
                                <label for="size_32oz" class="color-label">32oz</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="product_image">Product Image</label>
                        <input type="file" id="product_image" name="product_image" class="form-input" accept="image/*">
                    </div>
                    
                    <button type="submit" name="add_product" class="submit-btn">Add Product</button>
                </form>
            </div>
        </main>
    </div>
    
    <script>
        // Hide sidebar logo text when logo image exists
        document.addEventListener('DOMContentLoaded', function() {
            const logoIcon = document.querySelector('.sidebar-logo-icon img');
            const logoText = document.querySelector('.sidebar-logo-text');
            
            if (logoIcon && logoText) {
                // Check if image is loaded
                if (logoIcon.complete && logoIcon.naturalWidth > 0) {
                    logoText.style.display = 'none';
                } else {
                    // Wait for image to load
                    logoIcon.addEventListener('load', function() {
                        logoText.style.display = 'none';
                    });
                    
                    // Fallback: hide text if image fails to load after 1 second
                    setTimeout(function() {
                        if (logoIcon.complete && logoIcon.naturalWidth > 0) {
                            logoText.style.display = 'none';
                        }
                    }, 1000);
                }
            }
        });
    </script>
</body>
</html> 