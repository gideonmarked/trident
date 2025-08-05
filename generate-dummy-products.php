<?php
/**
 * Generate Dummy Products Page
 * 
 * This file can be accessed directly to generate dummy products
 * Access via: /wp-content/themes/trident/generate-dummy-products.php
 */

// Load WordPress
require_once '../../../wp-config.php';

// Check if user is logged in and has admin capabilities
if (!current_user_can('manage_options')) {
    wp_die('Access denied. You need administrator privileges.');
}

// Load theme functions
require_once 'functions.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Dummy Products - TRIDENT Theme</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #6b7280;
        }
        .button {
            background: #10b981;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
        .button:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        .button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        .result {
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid;
        }
        .result.success {
            background: #f0fdf4;
            border-color: #10b981;
            color: #065f46;
        }
        .result.error {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }
        .result.info {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #1e40af;
        }
        .product-list {
            margin-top: 1rem;
        }
        .product-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #3b82f6;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Generate Dummy Products</h1>
            <p>This will create 10 sample products for the TRIDENT theme</p>
        </div>

        <?php
        // Handle form submission
        if (isset($_POST['generate_products']) && wp_verify_nonce($_POST['nonce'], 'generate_dummy_products')) {
            echo '<div class="result info">Processing...</div>';
            
            // Check if function exists
            if (function_exists('trident_generate_dummy_products')) {
                // Check if products already exist
                if (function_exists('trident_get_products')) {
                    $existing_products = trident_get_products();
                    if (!empty($existing_products)) {
                        echo '<div class="result error">Products already exist in the database. Please clear existing products first.</div>';
                    } else {
                        // Generate dummy products
                        trident_generate_dummy_products();
                        
                        // Check results
                        $products = trident_get_products();
                        if (!empty($products)) {
                            echo '<div class="result success">';
                            echo '<h3>✅ Successfully generated ' . count($products) . ' dummy products!</h3>';
                            echo '<div class="product-list">';
                            echo '<h4>Products created:</h4>';
                            foreach ($products as $product) {
                                echo '<div class="product-item">';
                                echo '<strong>' . esc_html($product->name) . '</strong> - ₱' . number_format($product->price, 2);
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<div class="result error">Failed to generate products. Please check the database connection.</div>';
                        }
                    }
                } else {
                    echo '<div class="result error">Function trident_get_products not found. Please check theme installation.</div>';
                }
            } else {
                echo '<div class="result error">Function trident_generate_dummy_products not found. Please check theme installation.</div>';
            }
        }
        ?>

        <form method="post">
            <?php wp_nonce_field('generate_dummy_products', 'nonce'); ?>
            <button type="submit" name="generate_products" class="button">
                🚀 Generate Dummy Products
            </button>
        </form>

        <a href="<?php echo admin_url('admin.php?page=trident-admin&tab=products'); ?>" class="back-link">
            ← Back to TRIDENT Admin
        </a>
    </div>
</body>
</html> 