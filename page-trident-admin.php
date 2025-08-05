<?php
/**
 * Template Name: TRIDENT Admin Portal
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

// Ensure database tables exist
if (function_exists('trident_create_tables')) {
    trident_create_tables();
}

$current_user = wp_get_current_user();
$current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'products';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>TRIDENT Admin - <?php bloginfo('name'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        * {
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
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
            align-items: center;
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
            font-family: 'Montserrat', sans-serif;
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
            font-family: 'Montserrat', sans-serif;
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
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .generate-dummy-btn {
            background: #10b981;
            color: white;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .generate-dummy-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        
        .clear-products-btn {
            background: #ef4444;
            color: white;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .clear-products-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
        
        .add-product-btn {
            background: #fbbf24;
            color: white;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .add-product-btn:hover {
            background: #f59e0b;
        }
        
        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            position: relative;
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: #f3f4f6;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .edit-link {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            color: #374151;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .product-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .customize-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .color-swatches {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        
        .color-swatch:hover {
            border-color: #fbbf24;
        }
        
        .color-swatch.yellow { background: #fbbf24; }
        .color-swatch.green { background: #10b981; }
        .color-swatch.black { background: #1f2937; }
        .color-swatch.blue { background: #3b82f6; }
        .color-swatch.brown { background: #92400e; }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .product-price {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quantity-input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
        }
        
        .customize-btn {
            background: #fbbf24;
            color: white;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 20px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .customize-btn:hover {
            background: #f59e0b;
        }
        
        .checkout-link {
            color: #374151;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
        }
        
        .checkout-link:hover {
            color: #fbbf24;
        }
        

        

        
        /* Color Picker Popup Styles */
        .color-picker-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }
        
        .color-picker-popup.open {
            display: flex;
        }
        
        .color-picker-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .color-picker-header {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .color-picker-header h3 {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .color-picker-body {
            padding: 2rem;
        }
        
        .color-picker-section {
            margin-bottom: 2rem;
        }
        
        .color-picker-section h4 {
            margin: 0 0 1rem 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
        }
        
        .predefined-colors {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
        }
        
        .predefined-color {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .predefined-color:hover {
            background: #f3f4f6;
            transform: scale(1.05);
        }
        
        .predefined-color.selected {
            background: #fef3c7;
            border: 2px solid #fbbf24;
        }
        
        .predefined-color .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
        }
        
        .predefined-color span {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-align: center;
        }
        
        .custom-color-input {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .custom-color-input input[type="color"] {
            width: 60px;
            height: 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .custom-color-input input[type="text"] {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
        }
        
        .custom-color-input input[type="text"]:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
        }
        
        .color-picker-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        .add-color-action-btn {
            background: #fbbf24;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .add-color-action-btn:hover {
            background: #f59e0b;
        }
        
        .color-label:hover {
            transform: translateY(-2px);
        }
        
        .color-radio:checked + .color-label .color-swatch {
            border: 3px solid #fbbf24;
            transform: scale(1.1);
        }
        
        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .color-swatch.yellow { background: #fbbf24; }
        .color-swatch.green { background: #10b981; }
        .color-swatch.black { background: #1f2937; }
        .color-swatch.mint { background: #6ee7b7; }
        .color-swatch.gold { background: #f59e0b; }
        
        .product-details-section {
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.5rem;
        }
        
        .size-selection {
            margin-bottom: 1.5rem;
        }
        
        .size-label {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.75rem;
        }
        
        .size-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .size-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #fbbf24;
            background: white;
            color: #374151;
            border-radius: 20px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .size-btn.active {
            background: #fbbf24;
            color: white;
        }
        
        .size-btn:hover {
            background: #fbbf24;
            color: white;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #fbbf24;
        }
        
        .popup-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .popup-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .popup-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .popup-content {
            padding: 2rem;
        }
        
        .color-section {
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
        }
        
        .color-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1px solid #707070;
            padding-bottom: 0.5rem;
        }
        
        .color-section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .selected-color {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .color-swatches-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .color-swatch-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .color-swatch-large {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        
        .color-swatch-large.selected {
            border-color: #fbbf24;
        }
        
        .color-swatch-large.white { background: #ffffff; border: 2px solid #e5e7eb; }
        .color-swatch-large.black { background: #000000; }
        .color-swatch-large.gray { background: #6b7280; }
        .color-swatch-large.light-gray { background: #d1d5db; }
        .color-swatch-large.yellow { background: #fbbf24; }
        .color-swatch-large.dark-green { background: #059669; }
        .color-swatch-large.dark-black { background: #1f2937; }
        .color-swatch-large.mint { background: #10b981; }
        .color-swatch-large.gold { background: #f59e0b; }
        .color-swatch-large.blue { background: #3b82f6; }
        .color-swatch-large.red { background: #ef4444; }
        .color-swatch-large.purple { background: #8b5cf6; }
        
        .color-swatch-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
        }
        
        .section-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 1rem 0;
        }
        
        .product-details-section {
            margin-bottom: 2rem;
        }
        
        .product-details-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .size-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .size-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #fbbf24;
            border-radius: 8px;
            background: white;
            color: #1f2937;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .size-btn.active {
            background: #fbbf24;
            color: white;
        }
        
        .popup-form-group {
            margin-bottom: 1.5rem;
        }
        
        .popup-form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .popup-form-input,
        .popup-form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: border-color 0.3s ease;
            background: white;
        }
        
        .popup-form-input:focus,
        .popup-form-textarea:focus {
            outline: none;
            border-color: #fbbf24;
        }
        
        .popup-form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .popup-actions {
            display: flex;
            gap: 1rem;
            padding: 2rem;
            border-top: 1px solid #e5e7eb;
            position: sticky;
            bottom: 0;
            background: white;
        }
        
        .cancel-btn {
            flex: 1;
            padding: 1rem;
            border: 2px solid #1f2937;
            border-radius: 8px;
            background: white;
            color: #1f2937;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cancel-btn:hover {
            background: #f3f4f6;
        }
        
        .add-item-btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            background: #fbbf24;
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .add-item-btn:hover {
            background: #f59e0b;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .overlay.open {
            opacity: 1;
            visibility: visible;
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
            
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .customize-row {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
            
            .banner-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1rem;
            }
            
            .banner-preview {
                height: 200px;
            }
            
            .add-product-popup {
                width: 100%;
                right: -100%;
            }
        }
        
        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
            
            .banner-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 0 0.5rem;
            }
            
            .banner-preview {
                height: 180px;
            }
            
            .banner-overlay {
                padding: 1rem;
            }
            
            .banner-title {
                font-size: 1.25rem;
            }
            
            .remove-banner-btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }
            
            .banner-logos {
                gap: 0.5rem;
            }
            
            .logo-item {
                font-size: 0.625rem;
            }
        }
        
        /* Banner Grid */
        .banner-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .banner-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .banner-preview {
            position: relative;
            height: 250px;
            overflow: hidden;
            border-radius: 8px 8px 0 0;
        }
        
        .banner-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-width: 100%;
            object-position: center;
        }
        

        
        .remove-banner-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .remove-banner-btn:hover {
            background: #dc2626;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-header h2 {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        /* Loading States */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        /* Full Page Loading Overlay using ::after */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        body.loading::after,
        body.popup-open::after {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        
        /* Loading content positioned absolutely */
        .loading-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            text-align: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        body.loading .loading-content {
            opacity: 1;
            visibility: visible;
        }
        
        /* Hide loading content when popup is open (we only want the dark background) */
        body.popup-open .loading-content {
            opacity: 0;
            visibility: hidden;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #fbbf24;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-top: 1rem;
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .save-btn {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .save-btn.loading {
            background: #9ca3af;
            cursor: not-allowed;
        }
        
        .save-btn.loading .btn-text {
            opacity: 0;
        }
        
        .save-btn .btn-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            opacity: 0;
            visibility: hidden;
        }
        
        .save-btn.loading .btn-spinner {
            opacity: 1;
            visibility: visible;
        }
        
        .form-group.disabled {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .close-btn:hover {
            background: #f3f4f6;
            color: #374151;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            color: #374151;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 1.5rem 2rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .cancel-btn {
            background: #f3f4f6;
            color: #374151;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .cancel-btn:hover {
            background: #e5e7eb;
        }
        
        .save-btn {
            background: #fbbf24;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .save-btn:hover {
            background: #f59e0b;
        }
        
        /* Add Banner Popup Styles */
        .add-banner-popup {
            position: fixed;
            top: 0;
            right: -500px;
            width: 500px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .add-banner-popup.open {
            right: 0;
        }
        
        /* File Upload Area Styles */
        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            background: #f9fafb;
        }
        
        .file-upload-area:hover {
            border-color: #f59e0b;
            background: #fef3c7;
        }
        
        .file-upload-area.dragover {
            border-color: #f59e0b;
            background: #fef3c7;
            transform: scale(1.02);
        }
        
        .upload-content {
            pointer-events: none;
        }
        
        .upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        
        .upload-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin: 0 0 0.5rem 0;
        }
        
        .upload-subtext {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        
        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            pointer-events: auto;
        }
        
        .file-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .file-preview img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .file-info {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .file-info span {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }
        
        .remove-file-btn {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }
        
        .remove-file-btn:hover {
            background: #dc2626;
        }
        
        /* Orders Table Styles */
        .orders-table-container {
            margin-top: 2rem;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .orders-table-wrapper {
            overflow-x: auto;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Montserrat', sans-serif;
        }
        
        .orders-table thead {
            background: #374151;
            color: white;
        }
        
        .orders-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .orders-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s;
        }
        
        .orders-table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        .orders-table tbody tr:last-child {
            border-bottom: none;
        }
        
        .orders-table td {
            padding: 1rem;
            vertical-align: top;
            font-size: 0.875rem;
            color: #374151;
        }
        
        .orders-table td:first-child {
            font-weight: 600;
            color: #111827;
        }
        
        .no-product {
            color: #9ca3af;
            font-style: italic;
        }
        
        .delete-order-btn {
            background: none;
            border: none;
            color: #ef4444;
            text-decoration: underline;
            cursor: pointer;
            font-size: 0.875rem;
            padding: 0;
            font-family: inherit;
        }
        
        .delete-order-btn:hover {
            color: #dc2626;
        }
        
        .export-btn {
            background: #fbbf24;
            color: #374151;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            font-family: 'Montserrat', sans-serif;
        }
        
        .export-btn:hover {
            background: #f59e0b;
        }
        
        .table-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding: 1rem 0;
        }
        
        .cancel-btn {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Montserrat', sans-serif;
        }
        
        .cancel-btn:hover {
            background: #e5e7eb;
        }
        
        .save-continue-btn {
            background: #fbbf24;
            color: #374151;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Montserrat', sans-serif;
        }
        
        .save-continue-btn:hover {
            background: #f59e0b;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            border: 2px dashed #d1d5db;
        }
        
        .empty-icon {
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        

    </style>
</head>
<body>
    <!-- Loading Content -->
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text">Adding product...</div>
    </div>
    
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
                    <a href="?page=products" class="nav-link <?php echo $current_page === 'products' ? 'active' : ''; ?>">
                        Product List
                    </a>
                </div>
                <div class="nav-item">
                    <a href="?page=banners" class="nav-link <?php echo $current_page === 'banners' ? 'active' : ''; ?>">
                        Add Banner Photo
                    </a>
                </div>
                <div class="nav-item">
                    <a href="?page=orders" class="nav-link <?php echo $current_page === 'orders' ? 'active' : ''; ?>">
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
            <?php if ($current_page === 'products'): ?>
                <div class="page-header">
                    <h1 class="page-title">Product List</h1>
                    <div class="header-actions">
                        <button onclick="clearAllProducts()" class="clear-products-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 6H5H21M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Clear All Products
                        </button>
                        <button onclick="generateDummyProducts()" class="generate-dummy-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Generate Dummy Products
                        </button>
                    <button onclick="openAddProductPopup()" class="add-product-btn">
                        <span>+</span>
                        Add Product
                    </button>
                    </div>
                </div>
                
                <div class="product-grid">
                    <?php
                    // Get products from custom database table
                    $products = trident_get_products();
                    
                    if (!empty($products)) {
                        foreach ($products as $product) {
                            $product_id = $product->id;
                            $body_colors = trident_get_product_colors($product, 'body');
                            ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <?php if (!empty($product->image_url)): ?>
                                        <img src="<?php echo esc_url($product->image_url); ?>" alt="<?php echo esc_attr($product->name); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tumbler-default.png" alt="TRIDENT Tumbler">
                                    <?php endif; ?>
                                    <a href="#" class="edit-link" onclick="openEditProductPopup(<?php echo $product_id; ?>)">Edit</a>
                                </div>
                                
                                <h3 class="product-name"><?php echo esc_html($product->name); ?></h3>
                                
                                <div class="customize-row">
                                <div class="color-swatches">
                                    <?php foreach ($body_colors as $color): ?>
                                        <div class="color-swatch <?php echo esc_attr($color); ?>" style="background: <?php echo esc_attr($color); ?>;"></div>
                                    <?php endforeach; ?>
                                    <?php if (empty($body_colors)): ?>
                                        <div class="color-swatch yellow" style="background: #fbbf24;"></div>
                                        <div class="color-swatch green" style="background: #10b981;"></div>
                                        <div class="color-swatch black" style="background: #1f2937;"></div>
                                        <div class="color-swatch blue" style="background: #3b82f6;"></div>
                                        <div class="color-swatch brown" style="background: #92400e;"></div>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="product-footer">
                                    <div class="product-price">₱<?php echo number_format($product->price, 2); ?></div>
                                    <div class="quantity-selector">
                                        <select class="quantity-input">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Fallback product cards
                        for ($i = 1; $i <= 9; $i++) {
                            ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tumbler-default.png" alt="TRIDENT Tumbler">
                                    <a href="#" class="edit-link" onclick="openEditProductPopup(0)">Edit</a>
                                </div>
                                
                                <h3 class="product-name">32 oz Lightweight Wide Mouth Trail Series™</h3>
                                
                                <div class="customize-row">
                                <div class="color-swatches">
                                    <div class="color-swatch yellow" style="background: #fbbf24;"></div>
                                    <div class="color-swatch green" style="background: #10b981;"></div>
                                    <div class="color-swatch black" style="background: #1f2937;"></div>
                                    <div class="color-swatch blue" style="background: #3b82f6;"></div>
                                    <div class="color-swatch brown" style="background: #92400e;"></div>
                                    </div>
                                </div>
                                
                                <div class="product-footer">
                                    <div class="product-price">₱1,299.00</div>
                                    <div class="quantity-selector">
                                        <select class="quantity-input">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <?php include get_template_directory() . '/template-parts/add-product-popup.php'; ?>
                
                <!-- Edit Product Popup -->
                <div id="editProductPopup" class="add-product-popup">
                    <div class="popup-header">
                        <h2 class="popup-title">Edit Product</h2>
                        <button onclick="closeEditProductPopup()" class="close-btn">&times;</button>
                    </div>
                    <div class="popup-content">
                        <!-- Loading Overlay -->
                        <div id="editProductLoadingOverlay" class="loading-overlay">
                            <div style="text-align: center;">
                                <div class="loading-spinner"></div>
                                <div class="loading-text">Loading product...</div>
                            </div>
                        </div>
                        
                        <form id="editProductForm" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="edit_product_id" name="product_id">
                            <input type="hidden" id="edit_body_colors" name="body_colors" value="">
                            <input type="hidden" id="edit_cap_colors" name="cap_colors" value="">
                            <input type="hidden" id="edit_boot_colors" name="boot_colors" value="">
                            
                            <div class="form-group" id="editProductFormGroup">
                                <label for="edit_product_name" class="form-label">Product Name</label>
                                <input type="text" id="edit_product_name" name="product_name" class="form-input" placeholder="Enter product name..." required>
                                <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Give your product a descriptive name</small>
                            </div>
                            
                            <div class="form-group" id="editProductDetailsFormGroup">
                                <label for="edit_product_details" class="form-label">Product Details</label>
                                <textarea id="edit_product_details" name="product_details" class="form-textarea" placeholder="Enter product details..." required></textarea>
                                <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Describe your product features and specifications</small>
                            </div>
                            
                            <div class="form-group" id="editProductPriceFormGroup">
                                <label for="edit_product_price" class="form-label">Price (₱)</label>
                                <input type="number" id="edit_product_price" name="product_price" class="form-input" placeholder="0.00" step="0.01" min="0" required>
                                <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Set the product price in Philippine Peso</small>
                            </div>
                            
                            <div class="form-group" id="editProductImageFormGroup">
                                <label for="edit_product_image" class="form-label">Product Image</label>
                                <div class="file-upload-area" id="editProductUploadArea">
                                    <div class="upload-content">
                                        <div class="upload-icon">📁</div>
                                        <p class="upload-text">Drag and drop your image here</p>
                                        <p class="upload-subtext">or click to browse files</p>
                                        <input type="file" id="edit_product_image" name="product_image" accept="image/*" class="file-input">
                                    </div>
                                    <div class="file-preview" id="editProductFilePreview" style="display: none;">
                                        <img id="editProductPreviewImage" src="" alt="Preview">
                                        <div class="file-info">
                                            <span id="editProductFileName"></span>
                                            <button type="button" class="remove-file-btn" onclick="removeEditProductFile()">×</button>
                                        </div>
                                    </div>
                                </div>
                                <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Upload an image for your product (optional)</small>
                            </div>
                            
                                                            <!-- Color Options Section -->
                                <div class="section-divider"></div>
                                <div class="product-details-section">
                                    <h3 class="product-details-title">Color Options</h3>
                                    
                                    <?php
                                    // Get available colors from database
                                    $available_colors = function_exists('trident_get_available_colors') ? trident_get_available_colors() : array();
                                    
                                    // Debug: Log the function status and colors
                                    error_log('trident_get_available_colors function exists: ' . (function_exists('trident_get_available_colors') ? 'YES' : 'NO'));
                                    error_log('Available colors count: ' . count($available_colors));
                                    if (!empty($available_colors)) {
                                        error_log('First color: ' . print_r($available_colors[0], true));
                                    }
                                    
                                    // Fallback to hardcoded colors if function doesn't exist
                                    if (empty($available_colors)) {
                                        error_log('Using fallback colors');
                                        $available_colors = array(
                                            array('name' => 'White', 'color' => '#ffffff', 'class' => 'white'),
                                            array('name' => 'Black', 'color' => '#000000', 'class' => 'black'),
                                            array('name' => 'Gray', 'color' => '#6b7280', 'class' => 'gray'),
                                            array('name' => 'Light Gray', 'color' => '#d1d5db', 'class' => 'light-gray'),
                                            array('name' => 'Yellow', 'color' => '#fbbf24', 'class' => 'yellow'),
                                            array('name' => 'Dark Green', 'color' => '#059669', 'class' => 'dark-green'),
                                            array('name' => 'Dark Black', 'color' => '#1f2937', 'class' => 'dark-black'),
                                            array('name' => 'Mint', 'color' => '#10b981', 'class' => 'mint'),
                                            array('name' => 'Gold', 'color' => '#f59e0b', 'class' => 'gold'),
                                            array('name' => 'Blue', 'color' => '#3b82f6', 'class' => 'blue'),
                                            array('name' => 'Red', 'color' => '#ef4444', 'class' => 'red'),
                                            array('name' => 'Purple', 'color' => '#8b5cf6', 'class' => 'purple')
                                        );
                                    }
                                    ?>
                                    

                                
                                <!-- Body Colors -->
                                <div class="color-section">
                                    <h4 class="color-section-title">Body Colors</h4>
                                    <div class="color-swatches-row" id="editBodyColorsRow">
                                        <?php if (empty($available_colors)): ?>
                                            <p style="color: red;">No colors available from database!</p>
                                        <?php else: ?>
                                            <?php foreach ($available_colors as $color): ?>
                                            <div class="color-swatch-item">
                                                <div class="color-swatch-large <?php echo esc_attr($color['class']); ?>" 
                                                     data-color="<?php echo esc_attr($color['color']); ?>" 
                                                     data-name="<?php echo esc_attr($color['name']); ?>"></div>
                                                <span class="color-swatch-label"><?php echo esc_html($color['name']); ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Cap Colors -->
                                <div class="color-section">
                                    <h4 class="color-section-title">Cap Colors</h4>
                                    <div class="color-swatches-row" id="editCapColorsRow">
                                        <?php if (empty($available_colors)): ?>
                                            <p style="color: red;">No colors available from database!</p>
                                        <?php else: ?>
                                            <?php foreach ($available_colors as $color): ?>
                                            <div class="color-swatch-item">
                                                <div class="color-swatch-large <?php echo esc_attr($color['class']); ?>" 
                                                     data-color="<?php echo esc_attr($color['color']); ?>" 
                                                     data-name="<?php echo esc_attr($color['name']); ?>"></div>
                                                <span class="color-swatch-label"><?php echo esc_html($color['name']); ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Boot Colors -->
                                <div class="color-section">
                                    <h4 class="color-section-title">Boot Colors</h4>
                                    <div class="color-swatches-row" id="editBootColorsRow">
                                        <?php if (empty($available_colors)): ?>
                                            <p style="color: red;">No colors available from database!</p>
                                        <?php else: ?>
                                            <?php foreach ($available_colors as $color): ?>
                                            <div class="color-swatch-item">
                                                <div class="color-swatch-large <?php echo esc_attr($color['class']); ?>" 
                                                     data-color="<?php echo esc_attr($color['color']); ?>" 
                                                     data-name="<?php echo esc_attr($color['name']); ?>"></div>
                                                <span class="color-swatch-label"><?php echo esc_html($color['name']); ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Size Options -->
                                <div class="size-options">
                                    <h4 class="color-section-title">Size</h4>
                                    <div class="size-btn" data-size="24oz">24oz</div>
                                    <div class="size-btn" data-size="32oz">32oz</div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="popup-actions">
                                <button type="button" onclick="closeEditProductPopup()" class="cancel-btn">Cancel</button>
                                <button type="submit" class="save-btn" id="editProductSaveBtn">
                                    <span class="btn-text">Update Product</span>
                                    <div class="btn-spinner"></div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
            <?php elseif ($current_page === 'banners'): ?>
                <div class="page-header">
                    <h1 class="page-title">Add Banner Photo</h1>
                    <button class="add-product-btn" onclick="openAddBannerModal()">
                        <span>+</span>
                        Add Image
                    </button>
                </div>
                
                <div class="banner-grid">
                    <?php
                    // Get banners from custom database table
                    $banners = trident_get_banners();
                    
                    if (!empty($banners)) {
                        foreach ($banners as $banner) {
                            ?>
                            <div class="banner-card" data-banner-id="<?php echo $banner->id; ?>">
                                <div class="banner-preview">
                                    <img src="<?php echo esc_url($banner->image_url); ?>" alt="<?php echo esc_attr($banner->title); ?>">
                                    <button class="remove-banner-btn" onclick="removeBanner(<?php echo $banner->id; ?>)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 6H5H21M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Show sample banners
                        for ($i = 1; $i <= 2; $i++) {
                            ?>
                            <div class="banner-card">
                                <div class="banner-preview">
                                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-200x300.webp" alt="Sample Banner">
                                    <button class="remove-banner-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 6H5H21M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <!-- Add Banner Popup -->
                <div id="addBannerPopup" class="add-banner-popup">
                    <div class="popup-header">
                        <h2 class="popup-title">Add New Banner</h2>
                        <button onclick="closeAddBannerPopup()" class="close-btn">&times;</button>
                        </div>
                    <div class="popup-content">
                            <!-- Loading Overlay -->
                            <div id="bannerLoadingOverlay" class="loading-overlay">
                                <div style="text-align: center;">
                                    <div class="loading-spinner"></div>
                                    <div class="loading-text">Uploading banner...</div>
                                </div>
                            </div>
                            
                            <form id="addBannerForm" method="post" enctype="multipart/form-data">
                                <div class="form-group" id="bannerFormGroup">
                                <label for="banner_name" class="form-label">Banner Name</label>
                                <input type="text" id="banner_name" name="banner_name" class="form-input" placeholder="Enter banner name...">
                                <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Give your banner a name for easy identification</small>
                            </div>
                            
                            <div class="form-group" id="bannerImageFormGroup">
                                <label for="banner_image" class="form-label">Upload Photo</label>
                                <div class="file-upload-area" id="bannerUploadArea">
                                    <div class="upload-content">
                                        <div class="upload-icon">📁</div>
                                        <p class="upload-text">Drag and drop your image here</p>
                                        <p class="upload-subtext">or click to browse files</p>
                                        <input type="file" id="banner_image" name="banner_image" accept="image/*" class="file-input" required>
                                    </div>
                                    <div class="file-preview" id="bannerFilePreview" style="display: none;">
                                        <img id="bannerPreviewImage" src="" alt="Preview">
                                        <div class="file-info">
                                            <span id="bannerFileName"></span>
                                            <button type="button" class="remove-file-btn" onclick="removeBannerFile()">×</button>
                                        </div>
                                    </div>
                                </div>
                                    <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Upload an image for the hero section banner</small>
                                </div>
                            
                            <!-- Action Buttons -->
                            <div class="popup-actions">
                                <button type="button" onclick="closeAddBannerPopup()" class="cancel-btn">Cancel</button>
                                    <button type="submit" class="save-btn" id="bannerSaveBtn">
                                        <span class="btn-text">Save & Continue</span>
                                        <div class="btn-spinner"></div>
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
                
            <?php elseif ($current_page === 'orders'): ?>
                <div class="page-header">
                    <h1 class="page-title">Order History</h1>
                    <div class="page-actions">
                        <button onclick="exportOrders()" class="export-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Export Excel
                        </button>
                        <button onclick="generateDummyOrders()" class="generate-dummy-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Generate Dummy Orders
                        </button>
                    </div>
                </div>
                
                <div class="orders-table-container">
                    <?php
                    global $wpdb;
                    $table_orders = $wpdb->prefix . 'trident_orders';
                    $table_customers = $wpdb->prefix . 'trident_customers';
                    $table_order_items = $wpdb->prefix . 'trident_order_items';
                    
                    // Get orders with customer information and first order item
                    $orders = $wpdb->get_results("
                        SELECT o.*, c.first_name, c.last_name, c.email, c.phone,
                               oi.product_name, oi.color, oi.size
                        FROM $table_orders o
                        LEFT JOIN $table_customers c ON o.customer_id = c.id
                        LEFT JOIN (
                            SELECT oi1.order_id, oi1.product_name, oi1.color, oi1.size
                            FROM $table_order_items oi1
                            INNER JOIN (
                                SELECT order_id, MIN(id) as min_id
                                FROM $table_order_items
                                GROUP BY order_id
                            ) oi2 ON oi1.order_id = oi2.order_id AND oi1.id = oi2.min_id
                        ) oi ON o.id = oi.order_id
                        ORDER BY o.created_at DESC
                        LIMIT 50
                    ");
                    
                    if ($orders): ?>
                        <div class="orders-table-wrapper">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>ID No.</th>
                                        <th>Full Name</th>
                                        <th>Product Type</th>
                                        <th>Contact No.</th>
                                        <th>Email Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?php echo str_pad($order->id, 4, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo esc_html($order->first_name . ' ' . $order->last_name); ?></td>
                                            <td>
                                                <?php if ($order->product_name): ?>
                                                    <?php echo esc_html($order->size . ' ' . $order->color); ?><br>
                                                    <small><?php echo esc_html($order->product_name); ?></small>
                                                <?php else: ?>
                                                    <span class="no-product">No product details</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo esc_html($order->phone); ?></td>
                                            <td><?php echo esc_html($order->email); ?></td>
                                            <td>
                                                <button class="delete-order-btn" onclick="deleteOrder(<?php echo $order->id; ?>)">Delete User</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h3>No Orders Yet</h3>
                            <p>Generate some dummy orders to see the order history in action.</p>
                            <button onclick="generateDummyOrders()" class="generate-dummy-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Generate Dummy Orders
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="table-actions">
                    <button class="cancel-btn">Cancel</button>
                    <button class="save-continue-btn">Save & Continue</button>
                </div>
                
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // Banner Popup Functions
        function openAddBannerModal() {
            const popup = document.getElementById('addBannerPopup');
            if (popup) {
                popup.classList.add('open');
                document.body.style.overflow = 'hidden';
                // Show dark overlay when popup opens
                document.body.classList.add('popup-open');
            }
        }
        
        function closeAddBannerPopup() {
            const popup = document.getElementById('addBannerPopup');
            if (popup) {
                popup.classList.remove('open');
                document.body.style.overflow = 'auto';
                // Hide dark overlay when popup closes
                document.body.classList.remove('popup-open');
                // Reset loading state when closing popup
            hideBannerLoading();
            // Reset form
                resetBannerForm();
            }
        }
        
        // Keep the old function name for backward compatibility
        function closeAddBannerModal() {
            closeAddBannerPopup();
        }
        
        // Banner file upload functions
        function handleBannerFileSelect(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bannerPreviewImage').src = e.target.result;
                    document.getElementById('bannerFileName').textContent = file.name;
                    document.getElementById('bannerFilePreview').style.display = 'flex';
                    document.querySelector('.upload-content').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }
        
        function removeBannerFile() {
            document.getElementById('banner_image').value = '';
            document.getElementById('bannerFilePreview').style.display = 'none';
            document.querySelector('.upload-content').style.display = 'block';
        }
        
        function resetBannerForm() {
            document.getElementById('banner_name').value = '';
            document.getElementById('banner_image').value = '';
            document.getElementById('bannerFilePreview').style.display = 'none';
            document.querySelector('.upload-content').style.display = 'block';
        }
        
        function clearAllProducts() {
            if (confirm('This will delete ALL products from the database. This action cannot be undone. Are you sure you want to continue?')) {
                // Show loading state
                const button = document.querySelector('.clear-products-btn');
                const originalText = button.innerHTML;
                button.innerHTML = '<div class="loading-spinner"></div> Clearing...';
                button.disabled = true;
                
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=trident_clear_all_products&nonce=<?php echo wp_create_nonce('trident_clear_products_nonce'); ?>'
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        successMessage.textContent = 'All products cleared successfully!';
                        document.body.appendChild(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            if (successMessage.parentNode) {
                                successMessage.parentNode.removeChild(successMessage);
                            }
                        }, 3000);
                        
                        // Reload page to show empty state
                        location.reload();
                    } else {
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        errorMessage.textContent = 'Error clearing products: ' + data.data;
                        document.body.appendChild(errorMessage);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            if (errorMessage.parentNode) {
                                errorMessage.parentNode.removeChild(errorMessage);
                            }
                        }, 5000);
                    }
                })
                .catch(error => {
                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error clearing products. Please try again.';
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                });
            }
        }
        
        function generateDummyProducts() {
            if (confirm('This will generate 10 dummy products. Are you sure you want to continue?')) {
                // Show loading state
                const button = document.querySelector('.generate-dummy-btn');
                const originalText = button.innerHTML;
                button.innerHTML = '<div class="loading-spinner"></div> Generating...';
                button.disabled = true;
                
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=trident_generate_dummy_products&nonce=<?php echo wp_create_nonce('trident_dummy_products_nonce'); ?>'
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        successMessage.textContent = 'Dummy products generated successfully!';
                        document.body.appendChild(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            if (successMessage.parentNode) {
                                successMessage.parentNode.removeChild(successMessage);
                            }
                        }, 3000);
                        
                        // Reload page to show new products
                        location.reload();
                    } else {
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        errorMessage.textContent = 'Error generating dummy products: ' + data.data;
                        document.body.appendChild(errorMessage);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            if (errorMessage.parentNode) {
                                errorMessage.parentNode.removeChild(errorMessage);
                            }
                        }, 5000);
                    }
                })
                .catch(error => {
                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error generating dummy products. Please try again.';
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                });
            }
        }
        
        function generateDummyOrders() {
            if (confirm('This will generate 20 dummy orders with customers and order items. Are you sure you want to continue?')) {
                // Show loading state
                const button = document.querySelectorAll('.generate-dummy-btn')[0]; // Get the first button in orders page
                const originalText = button.innerHTML;
                button.innerHTML = '<div class="loading-spinner"></div> Generating...';
                button.disabled = true;
                
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=trident_generate_dummy_orders&nonce=<?php echo wp_create_nonce('trident_dummy_orders_nonce'); ?>'
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        
                        // Create detailed success message
                        const message = `Dummy orders generated successfully! 
                        ${data.data.orders_available} orders with ${data.data.target_items_per_order} items each. 
                        ${data.data.order_items_created} new items created.`;
                        
                        successMessage.textContent = message;
                        document.body.appendChild(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            if (successMessage.parentNode) {
                                successMessage.parentNode.removeChild(successMessage);
                            }
                        }, 3000);
                        
                        // Reload page to show new orders
                        location.reload();
                    } else {
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        errorMessage.textContent = 'Error generating dummy orders: ' + data.data;
                        document.body.appendChild(errorMessage);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            if (errorMessage.parentNode) {
                                errorMessage.parentNode.removeChild(errorMessage);
                            }
                        }, 5000);
                    }
                })
                .catch(error => {
                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error generating dummy orders. Please try again.';
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                });
            }
        }
        
        function viewOrderDetails(orderId) {
            // For now, just show an alert with the order ID
            // This can be expanded to show a detailed modal or navigate to a detail page
            alert('Viewing order details for Order ID: ' + orderId + '\n\nThis feature will be implemented in the next update.');
        }
        
        function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                // Show loading state
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Deleting...';
                button.disabled = true;
                
                // Here you would typically make an AJAX call to delete the order
                // For now, just show a success message
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    successMessage.textContent = 'Order deleted successfully!';
                    document.body.appendChild(successMessage);
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        if (successMessage.parentNode) {
                            successMessage.parentNode.removeChild(successMessage);
                        }
                    }, 3000);
                    
                    // Reload page to reflect changes
                    location.reload();
                }, 1000);
            }
        }
        
        function exportOrders() {
            // Show loading state
            const button = document.querySelector('.export-btn');
            const originalText = button.innerHTML;
            button.innerHTML = '<div class="loading-spinner"></div> Exporting...';
            button.disabled = true;
            
            // Simulate export process
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                successMessage.textContent = 'Orders exported to Excel successfully!';
                document.body.appendChild(successMessage);
                
                // Remove success message after 3 seconds
                setTimeout(() => {
                    if (successMessage.parentNode) {
                        successMessage.parentNode.removeChild(successMessage);
                    }
                }, 3000);
            }, 2000);
        }
        
        function removeBanner(bannerId) {
            if (confirm('Are you sure you want to remove this banner?')) {
                // Find the banner card and show loading state
                const bannerCard = document.querySelector('[data-banner-id="' + bannerId + '"]');
                if (bannerCard) {
                    bannerCard.style.opacity = '0.6';
                    bannerCard.style.pointerEvents = 'none';
                }
                
                // Send AJAX request to delete banner
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=trident_delete_banner&banner_id=' + bannerId + '&nonce=<?php echo wp_create_nonce('trident_banner_nonce'); ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove banner card from DOM
                        if (bannerCard) {
                            bannerCard.remove();
                        }
                        
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        successMessage.textContent = 'Banner removed successfully!';
                        document.body.appendChild(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            if (successMessage.parentNode) {
                                successMessage.parentNode.removeChild(successMessage);
                            }
                        }, 3000);
                    } else {
                        // Reset banner card state
                        if (bannerCard) {
                            bannerCard.style.opacity = '1';
                            bannerCard.style.pointerEvents = 'auto';
                        }
                        
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        errorMessage.textContent = 'Error removing banner: ' + data.data;
                        document.body.appendChild(errorMessage);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            if (errorMessage.parentNode) {
                                errorMessage.parentNode.removeChild(errorMessage);
                            }
                        }, 5000);
                    }
                })
                .catch(error => {
                    // Reset banner card state
                    if (bannerCard) {
                        bannerCard.style.opacity = '1';
                        bannerCard.style.pointerEvents = 'auto';
                    }
                    
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error removing banner. Please try again.';
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                });
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addBannerModal');
            if (event.target === modal) {
                closeAddBannerModal();
            }
        }
        
        // Loading state functions
        function showBannerLoading() {
            const overlay = document.getElementById('bannerLoadingOverlay');
            const saveBtn = document.getElementById('bannerSaveBtn');
            const formGroup = document.getElementById('bannerFormGroup');
            const cancelBtn = document.getElementById('bannerCancelBtn');
            
            if (overlay) overlay.classList.add('active');
            if (saveBtn) saveBtn.classList.add('loading');
            if (formGroup) formGroup.classList.add('disabled');
            if (cancelBtn) cancelBtn.disabled = true;
        }
        
        function hideBannerLoading() {
            const overlay = document.getElementById('bannerLoadingOverlay');
            const saveBtn = document.getElementById('bannerSaveBtn');
            const formGroup = document.getElementById('bannerFormGroup');
            const cancelBtn = document.getElementById('bannerCancelBtn');
            
            if (overlay) overlay.classList.remove('active');
            if (saveBtn) saveBtn.classList.remove('loading');
            if (formGroup) formGroup.classList.remove('disabled');
            if (cancelBtn) cancelBtn.disabled = false;
        }
        
        // Handle form submission
        const addBannerForm = document.getElementById('addBannerForm');
        if (addBannerForm) {
            addBannerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            showBannerLoading();
            
            const formData = new FormData(this);
            formData.append('action', 'trident_add_banner');
            formData.append('nonce', '<?php echo wp_create_nonce('trident_banner_nonce'); ?>');
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading state
                hideBannerLoading();
                
                if (data.success) {
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    successMessage.textContent = 'Banner added successfully!';
                    document.body.appendChild(successMessage);
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        if (successMessage.parentNode) {
                            successMessage.parentNode.removeChild(successMessage);
                        }
                    }, 3000);
                    
                    closeAddBannerPopup();
                    location.reload(); // Refresh page to show new banner
                } else {
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error adding banner: ' + data.data;
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                }
            })
            .catch(error => {
                // Hide loading state
                hideBannerLoading();
                
                console.error('Error:', error);
                
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                errorMessage.textContent = 'Error adding banner. Please try again.';
                document.body.appendChild(errorMessage);
                
                // Remove error message after 5 seconds
                setTimeout(() => {
                    if (errorMessage.parentNode) {
                        errorMessage.parentNode.removeChild(errorMessage);
                    }
                }, 5000);
            });
        });
        }
        
        // Shared utility functions for file uploads (global scope)
        function preventDefaultsShared(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlightShared(e, uploadArea) {
            uploadArea.classList.add('dragover');
        }
        
        function unhighlightShared(e, uploadArea) {
            uploadArea.classList.remove('dragover');
        }
        
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
            
            // Banner popup event listeners
            const bannerPopup = document.getElementById('addBannerPopup');
            if (bannerPopup) {
                // Close popup when clicking outside
                bannerPopup.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeAddBannerPopup();
                    }
                });
            }
            
            // Close popup with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddBannerPopup();
                }
            });
            
            // Banner file upload drag and drop
            const bannerUploadArea = document.getElementById('bannerUploadArea');
            const bannerFileInput = document.getElementById('banner_image');
            
            if (bannerUploadArea && bannerFileInput) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    bannerUploadArea.addEventListener(eventName, preventDefaults, false);
                    document.body.addEventListener(eventName, preventDefaults, false);
                });
                
                // Highlight drop area when item is dragged over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    bannerUploadArea.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    bannerUploadArea.addEventListener(eventName, unhighlight, false);
                });
                
                // Handle dropped files
                bannerUploadArea.addEventListener('drop', handleDrop, false);
                
                // Handle file input change
                bannerFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        handleBannerFileSelect(file);
                    }
                });
            }
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            function highlight(e) {
                bannerUploadArea.classList.add('dragover');
            }
            
            function unhighlight(e) {
                bannerUploadArea.classList.remove('dragover');
            }
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        bannerFileInput.files = files;
                        handleBannerFileSelect(file);
                    } else {
                        alert('Please select an image file.');
                    }
                }
            }
        });
        
        // Edit Product Popup Functions
        function openEditProductPopup(productId) {
            const popup = document.getElementById('editProductPopup');
            if (popup) {
                popup.classList.add('open');
                document.body.style.overflow = 'hidden';
                document.body.classList.add('popup-open');
                
                // Set product ID
                document.getElementById('edit_product_id').value = productId;
                
                // Load product data if it's a real product (not fallback)
                if (productId > 0) {
                    loadProductData(productId);
                } else {
                    // Reset form for fallback product
                    resetEditProductForm();
                }
            }
        }
        
        function closeEditProductPopup() {
            const popup = document.getElementById('editProductPopup');
            if (popup) {
                popup.classList.remove('open');
                document.body.style.overflow = 'auto';
                document.body.classList.remove('popup-open');
                hideEditProductLoading();
                resetEditProductForm();
            }
        }
        
        function loadProductData(productId) {
            showEditProductLoading();
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=trident_get_product&product_id=' + productId + '&nonce=<?php echo wp_create_nonce('trident_product_nonce'); ?>'
            })
            .then(response => response.json())
            .then(data => {
                hideEditProductLoading();
                
                if (data.success) {
                    const product = data.data;
                    
                    // Populate form fields
                    document.getElementById('edit_product_id').value = product.id || '';
                    document.getElementById('edit_product_name').value = product.name || '';
                    document.getElementById('edit_product_details').value = product.description || '';
                    document.getElementById('edit_product_price').value = product.price || '';
                    
                    // Load color data
                    loadEditProductColors(product);
                    
                    // Show existing image if available
                    if (product.image_url) {
                        document.getElementById('editProductPreviewImage').src = product.image_url;
                        document.getElementById('editProductFileName').textContent = 'Current Image';
                        document.getElementById('editProductFilePreview').style.display = 'flex';
                        document.querySelector('#editProductUploadArea .upload-content').style.display = 'none';
                    }
                } else {
                    console.error('Error loading product:', data.data);
                    alert('Error loading product data. Please try again.');
                }
            })
            .catch(error => {
                hideEditProductLoading();
                console.error('Error:', error);
                alert('Error loading product data. Please try again.');
            });
        }
        
        function resetEditProductForm() {
            document.getElementById('edit_product_id').value = '';
            document.getElementById('edit_product_name').value = '';
            document.getElementById('edit_product_details').value = '';
            document.getElementById('edit_product_price').value = '';
            document.getElementById('edit_product_image').value = '';
            document.getElementById('editProductFilePreview').style.display = 'none';
            document.querySelector('#editProductUploadArea .upload-content').style.display = 'block';
            
            // Reset color selections
            resetEditProductColors();
        }
        
        function loadEditProductColors(product) {
            // Load body colors
            if (product.body_colors) {
                try {
                    const bodyColors = JSON.parse(product.body_colors);
                    if (Array.isArray(bodyColors)) {
                        setEditColorSelections('editBodyColorsRow', bodyColors);
                        document.getElementById('edit_body_colors').value = product.body_colors;
                    }
                } catch (e) {
                    console.error('Error parsing body colors:', e);
                }
            }
            
            // Load cap colors
            if (product.cap_colors) {
                try {
                    const capColors = JSON.parse(product.cap_colors);
                    if (Array.isArray(capColors)) {
                        setEditColorSelections('editCapColorsRow', capColors);
                        document.getElementById('edit_cap_colors').value = product.cap_colors;
                    }
                } catch (e) {
                    console.error('Error parsing cap colors:', e);
                }
            }
            
            // Load boot colors
            if (product.boot_colors) {
                try {
                    const bootColors = JSON.parse(product.boot_colors);
                    if (Array.isArray(bootColors)) {
                        setEditColorSelections('editBootColorsRow', bootColors);
                        document.getElementById('edit_boot_colors').value = product.boot_colors;
                    }
                } catch (e) {
                    console.error('Error parsing boot colors:', e);
                }
            }
        }
        
        function setEditColorSelections(containerId, selectedColors) {
            const container = document.getElementById(containerId);
            if (!container) return;
            
            // Reset all selections
            container.querySelectorAll('.color-swatch-large').forEach(swatch => {
                swatch.classList.remove('selected');
            });
            
            // Set selected colors by matching hex values
            selectedColors.forEach(colorHex => {
                const swatch = container.querySelector(`[data-color="${colorHex}"]`);
                if (swatch) {
                    swatch.classList.add('selected');
                } else {
                    console.log('Color swatch not found for:', colorHex);
                }
            });
        }
        
        function resetEditProductColors() {
            // Reset all color selections
            ['editBodyColorsRow', 'editCapColorsRow', 'editBootColorsRow'].forEach(containerId => {
                const container = document.getElementById(containerId);
                if (container) {
                    container.querySelectorAll('.color-swatch-large').forEach(swatch => {
                        swatch.classList.remove('selected');
                    });
                }
            });
            
            // Reset hidden inputs
            document.getElementById('edit_body_colors').value = '';
            document.getElementById('edit_cap_colors').value = '';
            document.getElementById('edit_boot_colors').value = '';
        }
        
        // Initialize edit product color selection
        document.addEventListener('DOMContentLoaded', function() {
            // Body colors selection
            const editBodyColorsRow = document.getElementById('editBodyColorsRow');
            if (editBodyColorsRow) {
                editBodyColorsRow.addEventListener('click', function(e) {
                    if (e.target.classList.contains('color-swatch-large')) {
                        toggleEditColorSelection(e.target, 'edit_body_colors');
                    }
                });
            }
            
            // Cap colors selection
            const editCapColorsRow = document.getElementById('editCapColorsRow');
            if (editCapColorsRow) {
                editCapColorsRow.addEventListener('click', function(e) {
                    if (e.target.classList.contains('color-swatch-large')) {
                        toggleEditColorSelection(e.target, 'edit_cap_colors');
                    }
                });
            }
            
            // Boot colors selection
            const editBootColorsRow = document.getElementById('editBootColorsRow');
            if (editBootColorsRow) {
                editBootColorsRow.addEventListener('click', function(e) {
                    if (e.target.classList.contains('color-swatch-large')) {
                        toggleEditColorSelection(e.target, 'edit_boot_colors');
                    }
                });
            }
        });
        
        function toggleEditColorSelection(swatch, hiddenInputId) {
            swatch.classList.toggle('selected');
            updateEditColorHiddenInput(hiddenInputId);
        }
        
        function updateEditColorHiddenInput(hiddenInputId) {
            const hiddenInput = document.getElementById(hiddenInputId);
            const containerId = hiddenInputId.replace('edit_', '').replace('_colors', 'ColorsRow');
            const container = document.getElementById(containerId);
            
            if (container) {
                const selectedColors = [];
                container.querySelectorAll('.color-swatch-large.selected').forEach(swatch => {
                    selectedColors.push(swatch.getAttribute('data-name'));
                });
                
                hiddenInput.value = JSON.stringify(selectedColors);
            }
        }
        
        function showEditProductLoading() {
            const overlay = document.getElementById('editProductLoadingOverlay');
            const saveBtn = document.getElementById('editProductSaveBtn');
            const formGroup = document.getElementById('editProductFormGroup');
            
            if (overlay) overlay.classList.add('active');
            if (saveBtn) saveBtn.classList.add('loading');
            if (formGroup) formGroup.classList.add('disabled');
        }
        
        function hideEditProductLoading() {
            const overlay = document.getElementById('editProductLoadingOverlay');
            const saveBtn = document.getElementById('editProductSaveBtn');
            const formGroup = document.getElementById('editProductFormGroup');
            
            if (overlay) overlay.classList.remove('active');
            if (saveBtn) saveBtn.classList.remove('loading');
            if (formGroup) formGroup.classList.remove('disabled');
        }
        
        function removeEditProductFile() {
            document.getElementById('edit_product_image').value = '';
            document.getElementById('editProductFilePreview').style.display = 'none';
            document.querySelector('#editProductUploadArea .upload-content').style.display = 'block';
        }
        
        // Handle edit product form submission
        const editProductForm = document.getElementById('editProductForm');
        if (editProductForm) {
            editProductForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                showEditProductLoading();
                
                const formData = new FormData(this);
                formData.append('action', 'trident_update_product');
                formData.append('nonce', '<?php echo wp_create_nonce('trident_product_nonce'); ?>');
                
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    hideEditProductLoading();
                    
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        successMessage.textContent = 'Product updated successfully!';
                        document.body.appendChild(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            if (successMessage.parentNode) {
                                successMessage.parentNode.removeChild(successMessage);
                            }
                        }, 3000);
                        
                        closeEditProductPopup();
                        location.reload(); // Refresh page to show updated product
                    } else {
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        errorMessage.textContent = 'Error updating product: ' + data.data;
                        document.body.appendChild(errorMessage);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            if (errorMessage.parentNode) {
                                errorMessage.parentNode.removeChild(errorMessage);
                            }
                        }, 5000);
                    }
                })
                .catch(error => {
                    hideEditProductLoading();
                    
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error updating product. Please try again.';
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                });
            });
        }
        
        // Edit product popup event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const editProductPopup = document.getElementById('editProductPopup');
            if (editProductPopup) {
                // Close popup when clicking outside
                editProductPopup.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditProductPopup();
                    }
                });
            }
            
            // Close edit popup with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEditProductPopup();
                }
            });
            
            // Edit product file upload drag and drop
            const editProductUploadArea = document.getElementById('editProductUploadArea');
            const editProductFileInput = document.getElementById('edit_product_image');
            
            if (editProductUploadArea && editProductFileInput) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    editProductUploadArea.addEventListener(eventName, preventDefaultsShared, false);
                    document.body.addEventListener(eventName, preventDefaultsShared, false);
                });
                
                // Highlight drop area when item is dragged over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    editProductUploadArea.addEventListener(eventName, (e) => highlightShared(e, editProductUploadArea), false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    editProductUploadArea.addEventListener(eventName, (e) => unhighlightShared(e, editProductUploadArea), false);
                });
                
                // Handle dropped files
                editProductUploadArea.addEventListener('drop', handleEditProductDrop, false);
                
                // Handle file input change
                editProductFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        handleEditProductFileSelect(file);
                    }
                });
            }
            
            function handleEditProductFileSelect(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('editProductPreviewImage').src = e.target.result;
                        document.getElementById('editProductFileName').textContent = file.name;
                        document.getElementById('editProductFilePreview').style.display = 'flex';
                        document.querySelector('#editProductUploadArea .upload-content').style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            }
            
            function handleEditProductDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        editProductFileInput.files = files;
                        handleEditProductFileSelect(file);
                    } else {
                        alert('Please select an image file.');
                    }
                }
            }
        });
    </script>
    
        <?php trident_render_color_picker(); ?>

</body>
</html> 