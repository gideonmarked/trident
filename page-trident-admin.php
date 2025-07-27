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
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
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
        
        .color-swatches {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .color-swatch {
            width: 24px;
            height: 24px;
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
            border-radius: 8px;
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
            display: block;
        }
        
        .checkout-link:hover {
            color: #fbbf24;
        }
        
        /* Add Product Popup */
        .add-product-popup {
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
        
        .add-product-popup.open {
            right: 0;
        }
        
        .popup-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .close-btn:hover {
            background: #f3f4f6;
            color: #374151;
        }
        
        .popup-content {
            padding: 2rem;
        }
        
        .color-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .color-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .color-section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
        }
        
        .selected-color {
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            color: #fbbf24;
            background: #fef3c7;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
        }
        
        .color-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .color-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .color-radio {
            display: none;
        }
        
        .color-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .color-label:hover {
            transform: translateY(-2px);
        }
        
        .color-radio:checked + .color-label .color-swatch {
            border: 3px solid #fbbf24;
            transform: scale(1.1);
        }
        
        .color-swatch {
            width: 30px;
            height: 30px;
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
        }
        
        .color-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
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
        
        .color-swatch-large.yellow { background: #fbbf24; }
        .color-swatch-large.green { background: #10b981; }
        .color-swatch-large.black { background: #1f2937; }
        .color-swatch-large.mint { background: #34d399; }
        .color-swatch-large.gold { background: #f59e0b; }
        
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
                grid-template-columns: 1fr;
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
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
        
        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .banner-text {
            text-align: center;
        }
        
        .banner-subtitle {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .banner-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .banner-price {
            background: #8b5cf6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: inline-block;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .banner-description {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            opacity: 0.9;
            line-height: 1.4;
        }
        
        .banner-logos {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .logo-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .logo-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
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
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
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
            color: #6b7280;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
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
                    <button onclick="openAddProductPopup()" class="add-product-btn">
                        <span>+</span>
                        Add Product
                    </button>
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
                                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-200x300.webp" alt="TRIDENT Tumbler">
                                    <?php endif; ?>
                                    <a href="?page=edit-product&id=<?php echo $product_id; ?>" class="edit-link">Edit</a>
                                </div>
                                
                                <h3 class="product-name"><?php echo esc_html($product->name); ?></h3>
                                
                                <div class="color-swatches">
                                    <?php foreach ($body_colors as $color): ?>
                                        <div class="color-swatch <?php echo esc_attr($color); ?>"></div>
                                    <?php endforeach; ?>
                                    <?php if (empty($body_colors)): ?>
                                        <div class="color-swatch yellow"></div>
                                        <div class="color-swatch green"></div>
                                        <div class="color-swatch black"></div>
                                        <div class="color-swatch blue"></div>
                                        <div class="color-swatch brown"></div>
                                    <?php endif; ?>
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
                                
                                <button class="customize-btn">CUSTOMIZE</button>
                                <a href="#" class="checkout-link">Check Out</a>
                            </div>
                            <?php
                        }
                    } else {
                        // Fallback product cards
                        for ($i = 1; $i <= 9; $i++) {
                            ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/assets_task_01k15dbfvcfk7bnnd2c0hamdm9_1753602237_img_0-200x300.webp" alt="TRIDENT Tumbler">
                                    <a href="#" class="edit-link">Edit</a>
                                </div>
                                
                                <h3 class="product-name">32 oz Lightweight Wide Mouth Trail Series™</h3>
                                
                                <div class="color-swatches">
                                    <div class="color-swatch yellow"></div>
                                    <div class="color-swatch green"></div>
                                    <div class="color-swatch black"></div>
                                    <div class="color-swatch blue"></div>
                                    <div class="color-swatch brown"></div>
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
                                
                                <button class="customize-btn">CUSTOMIZE</button>
                                <a href="#" class="checkout-link">Check Out</a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <!-- Add Product Popup -->
                <div id="addProductPopup" class="add-product-popup">
                    <div class="popup-header">
                        <h2 class="popup-title">Add Product</h2>
                        <button onclick="closeAddProductPopup()" class="close-btn">&times;</button>
                    </div>
                    <div class="popup-content">
                        <form id="addProductForm">
                            <!-- Body Colors Section -->
                            <div class="color-section">
                                <div class="color-section-header">
                                    <h3 class="color-section-title">Body Color - 22oz</h3>
                                    <span class="selected-color" id="bodyColorDisplay">Yellow</span>
                                </div>
                                <div class="color-options">
                                    <div class="color-option">
                                        <input type="radio" id="body_yellow" name="body_color" value="yellow" class="color-radio" checked>
                                        <label for="body_yellow" class="color-label">
                                            <div class="color-swatch yellow"></div>
                                            <span>Yellow</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="body_green" name="body_color" value="green" class="color-radio">
                                        <label for="body_green" class="color-label">
                                            <div class="color-swatch green"></div>
                                            <span>Dark Green</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="body_black" name="body_color" value="black" class="color-radio">
                                        <label for="body_black" class="color-label">
                                            <div class="color-swatch black"></div>
                                            <span>Black</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="body_mint" name="body_color" value="mint" class="color-radio">
                                        <label for="body_mint" class="color-label">
                                            <div class="color-swatch mint"></div>
                                            <span>Mint</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="body_gold" name="body_color" value="gold" class="color-radio">
                                        <label for="body_gold" class="color-label">
                                            <div class="color-swatch gold"></div>
                                            <span>Gold</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cap Colors Section -->
                            <div class="color-section">
                                <div class="color-section-header">
                                    <h3 class="color-section-title">Cap Color - 22oz</h3>
                                    <span class="selected-color" id="capColorDisplay">Yellow</span>
                                </div>
                                <div class="color-options">
                                    <div class="color-option">
                                        <input type="radio" id="cap_yellow" name="cap_color" value="yellow" class="color-radio" checked>
                                        <label for="cap_yellow" class="color-label">
                                            <div class="color-swatch yellow"></div>
                                            <span>Yellow</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="cap_green" name="cap_color" value="green" class="color-radio">
                                        <label for="cap_green" class="color-label">
                                            <div class="color-swatch green"></div>
                                            <span>Dark Green</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="cap_black" name="cap_color" value="black" class="color-radio">
                                        <label for="cap_black" class="color-label">
                                            <div class="color-swatch black"></div>
                                            <span>Black</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="cap_mint" name="cap_color" value="mint" class="color-radio">
                                        <label for="cap_mint" class="color-label">
                                            <div class="color-swatch mint"></div>
                                            <span>Mint</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="cap_gold" name="cap_color" value="gold" class="color-radio">
                                        <label for="cap_gold" class="color-label">
                                            <div class="color-swatch gold"></div>
                                            <span>Gold</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Boot Colors Section -->
                            <div class="color-section">
                                <div class="color-section-header">
                                    <h3 class="color-section-title">Boot Color - 22oz</h3>
                                    <span class="selected-color" id="bootColorDisplay">Yellow</span>
                                </div>
                                <div class="color-options">
                                    <div class="color-option">
                                        <input type="radio" id="boot_yellow" name="boot_color" value="yellow" class="color-radio" checked>
                                        <label for="boot_yellow" class="color-label">
                                            <div class="color-swatch yellow"></div>
                                            <span>Yellow</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="boot_green" name="boot_color" value="green" class="color-radio">
                                        <label for="boot_green" class="color-label">
                                            <div class="color-swatch green"></div>
                                            <span>Dark Green</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="boot_black" name="boot_color" value="black" class="color-radio">
                                        <label for="boot_black" class="color-label">
                                            <div class="color-swatch black"></div>
                                            <span>Black</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="boot_mint" name="boot_color" value="mint" class="color-radio">
                                        <label for="boot_mint" class="color-label">
                                            <div class="color-swatch mint"></div>
                                            <span>Mint</span>
                                        </label>
                                    </div>
                                    <div class="color-option">
                                        <input type="radio" id="boot_gold" name="boot_color" value="gold" class="color-radio">
                                        <label for="boot_gold" class="color-label">
                                            <div class="color-swatch gold"></div>
                                            <span>Gold</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Details Section -->
                            <div class="product-details-section">
                                <h3 class="section-title">Product Details</h3>
                                
                                <!-- Size Selection -->
                                <div class="size-selection">
                                    <label class="size-label">Size:</label>
                                    <div class="size-buttons">
                                        <button type="button" class="size-btn active" data-size="24oz">24oz</button>
                                        <button type="button" class="size-btn" data-size="32oz">32oz</button>
                                    </div>
                                </div>
                                
                                <!-- Product Name -->
                                <div class="form-group">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" id="product_name" name="product_name" class="form-input" required>
                                </div>
                                
                                <!-- Product Details -->
                                <div class="form-group">
                                    <label for="product_details" class="form-label">Product Details</label>
                                    <textarea id="product_details" name="product_details" class="form-textarea" rows="4"></textarea>
                                </div>
                                
                                <!-- Product Image -->
                                <div class="form-group">
                                    <label for="product_image" class="form-label">Product Image</label>
                                    <input type="file" id="product_image" name="product_image" class="form-input" accept="image/*">
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="popup-actions">
                                <button type="button" onclick="closeAddProductPopup()" class="cancel-btn">Cancel</button>
                                <button type="submit" class="save-btn">Add Item</button>
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
                                    <div class="banner-overlay">
                                        <div class="banner-text">
                                            <div class="banner-subtitle"><?php echo esc_html($banner->subtitle); ?></div>
                                            <h2 class="banner-title"><?php echo esc_html($banner->title); ?></h2>
                                            <?php if ($banner->price > 0): ?>
                                                <div class="banner-price">₱<?php echo number_format($banner->price, 2); ?></div>
                                            <?php endif; ?>
                                            <div class="banner-description"><?php echo esc_html($banner->description); ?></div>
                                        </div>
                                        <div class="banner-logos">
                                            <div class="logo-item">
                                                <div class="logo-icon">🌳</div>
                                                <span>ecoexplorations</span>
                                            </div>
                                            <div class="logo-item">
                                                <div class="logo-icon">☕</div>
                                                <span>COFFEE BEAN TEA LEAF</span>
                                            </div>
                                            <div class="logo-item">
                                                <div class="logo-icon">🏔️</div>
                                                <span>Philippine Parks and Biodiversity</span>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div class="banner-overlay">
                                        <div class="banner-text">
                                            <div class="banner-subtitle">19th Anniversary Limited Edition Tumbler</div>
                                            <h2 class="banner-title">A TUMBLER FOR TOMORROW</h2>
                                            <div class="banner-price">₱1,395</div>
                                            <div class="banner-description">With every purchase, a native tree is adopted on your behalf in a reforestation site managed by Philippine Parks and Biodiversity and Eco Explorations.</div>
                                        </div>
                                        <div class="banner-logos">
                                            <div class="logo-item">
                                                <div class="logo-icon">🌳</div>
                                                <span>ecoexplorations</span>
                                            </div>
                                            <div class="logo-item">
                                                <div class="logo-icon">☕</div>
                                                <span>COFFEE BEAN TEA LEAF</span>
                                            </div>
                                            <div class="logo-item">
                                                <div class="logo-icon">🏔️</div>
                                                <span>Philippine Parks and Biodiversity</span>
                                            </div>
                                        </div>
                                    </div>
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
                
                <!-- Add Banner Modal -->
                <div id="addBannerModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add New Banner</h2>
                            <button class="close-btn" onclick="closeAddBannerModal()">&times;</button>
                        </div>
                        <div class="modal-body" style="position: relative;">
                            <!-- Loading Overlay -->
                            <div id="bannerLoadingOverlay" class="loading-overlay">
                                <div style="text-align: center;">
                                    <div class="loading-spinner"></div>
                                    <div class="loading-text">Uploading banner...</div>
                                </div>
                            </div>
                            
                            <form id="addBannerForm" method="post" enctype="multipart/form-data">
                                <div class="form-group" id="bannerFormGroup">
                                    <label for="banner_image">Banner Image</label>
                                    <input type="file" id="banner_image" name="banner_image" accept="image/*" required>
                                    <small style="color: #6b7280; margin-top: 0.25rem; display: block;">Upload an image for the hero section banner</small>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="cancel-btn" id="bannerCancelBtn" onclick="closeAddBannerModal()">Cancel</button>
                                    <button type="submit" class="save-btn" id="bannerSaveBtn">
                                        <span class="btn-text">Save & Continue</span>
                                        <div class="btn-spinner"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            <?php elseif ($current_page === 'orders'): ?>
                <div class="page-header">
                    <h1 class="page-title">Order History</h1>
                </div>
                <div style="background: white; padding: 2rem; border-radius: 12px; text-align: center;">
                    <p>Order history functionality coming soon...</p>
                </div>
                
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // Banner Modal Functions
        function openAddBannerModal() {
            document.getElementById('addBannerModal').style.display = 'block';
        }
        
        function closeAddBannerModal() {
            document.getElementById('addBannerModal').style.display = 'none';
            // Reset loading state when closing modal
            hideBannerLoading();
            // Reset form
            document.getElementById('addBannerForm').reset();
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
            
            overlay.classList.add('active');
            saveBtn.classList.add('loading');
            formGroup.classList.add('disabled');
            cancelBtn.disabled = true;
        }
        
        function hideBannerLoading() {
            const overlay = document.getElementById('bannerLoadingOverlay');
            const saveBtn = document.getElementById('bannerSaveBtn');
            const formGroup = document.getElementById('bannerFormGroup');
            const cancelBtn = document.getElementById('bannerCancelBtn');
            
            overlay.classList.remove('active');
            saveBtn.classList.remove('loading');
            formGroup.classList.remove('disabled');
            cancelBtn.disabled = false;
        }
        
        // Handle form submission
        document.getElementById('addBannerForm').addEventListener('submit', function(e) {
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
                    
                    closeAddBannerModal();
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
            
            // Add Product Popup functionality
            setupAddProductPopup();
        });
        
        // Add Product Popup Functions
        function openAddProductPopup() {
            const popup = document.getElementById('addProductPopup');
            if (popup) {
                popup.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeAddProductPopup() {
            const popup = document.getElementById('addProductPopup');
            if (popup) {
                popup.classList.remove('open');
                document.body.style.overflow = 'auto';
                // Reset form
                document.getElementById('addProductForm').reset();
                // Reset size buttons
                document.querySelectorAll('.size-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelector('.size-btn[data-size="24oz"]').classList.add('active');
                // Reset color displays
                document.getElementById('bodyColorDisplay').textContent = 'Yellow';
                document.getElementById('capColorDisplay').textContent = 'Yellow';
                document.getElementById('bootColorDisplay').textContent = 'Yellow';
            }
        }
        
        function setupAddProductPopup() {
            // Color selection handlers
            document.querySelectorAll('input[name="body_color"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const label = this.nextElementSibling.querySelector('span').textContent;
                    document.getElementById('bodyColorDisplay').textContent = label;
                });
            });
            
            document.querySelectorAll('input[name="cap_color"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const label = this.nextElementSibling.querySelector('span').textContent;
                    document.getElementById('capColorDisplay').textContent = label;
                });
            });
            
            document.querySelectorAll('input[name="boot_color"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const label = this.nextElementSibling.querySelector('span').textContent;
                    document.getElementById('bootColorDisplay').textContent = label;
                });
            });
            
            // Size button handlers
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Form submission handler
            document.getElementById('addProductForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const selectedSize = document.querySelector('.size-btn.active').dataset.size;
                formData.append('size', selectedSize);
                formData.append('action', 'trident_add_product');
                formData.append('nonce', '<?php echo wp_create_nonce('trident_product_nonce'); ?>');
                
                // Show loading state
                const saveBtn = document.querySelector('#addProductForm .save-btn');
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Adding...';
                saveBtn.disabled = true;
                
                // Submit form
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                    
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        successMessage.textContent = 'Product added successfully!';
                        document.body.appendChild(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            if (successMessage.parentNode) {
                                successMessage.parentNode.removeChild(successMessage);
                            }
                        }, 3000);
                        
                        closeAddProductPopup();
                        location.reload(); // Refresh page to show new product
                    } else {
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                        errorMessage.textContent = 'Error adding product: ' + data.data;
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
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                    
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                    errorMessage.textContent = 'Error adding product. Please try again.';
                    document.body.appendChild(errorMessage);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 5000);
                });
            });
            
            // Close popup when clicking outside
            document.getElementById('addProductPopup').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddProductPopup();
                }
            });
            
            // Close popup with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddProductPopup();
                }
            });
        }
    </script>
</body>
</html> 