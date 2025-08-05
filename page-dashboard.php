<?php
/**
 * Template Name: Custom Dashboard
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Check if user is logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

$current_user = wp_get_current_user();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Dashboard - <?php bloginfo('name'); ?></title>
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
        }
        
        .dashboard-container {
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }
        
        .user-details h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-details p {
            font-size: 0.875rem;
            color: #9ca3af;
        }
        
        .sidebar-nav {
            padding: 0 2rem;
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
            background: #374151;
            color: white;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }
        
        .dashboard-header {
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
        
        .logout-btn {
            background: #dc2626;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .logout-btn:hover {
            background: #b91c1c;
        }
        
        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 0.5rem;
        }
        
        .card-description {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        /* Recent Activity */
        .activity-list {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            background: #fbbf24;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .activity-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        
        .activity-content p {
            font-size: 0.875rem;
            color: #6b7280;
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
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/trident-header-logo.png" alt="TRIDENT Logo">
                    </div>
                    <div class="sidebar-logo-text">TRIDENT</div>
                </div>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <h3><?php echo esc_html($current_user->display_name); ?></h3>
                        <p><?php echo esc_html($current_user->user_email); ?></p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="#" class="nav-link active">
                        <div class="nav-icon">📊</div>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon">🛍️</div>
                        Products
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon">📦</div>
                        Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon">👥</div>
                        Customers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon">📈</div>
                        Analytics
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon">⚙️</div>
                        Settings
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-header">
                <h1 class="page-title">Dashboard</h1>
                <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="logout-btn">Logout</a>
            </div>
            
            <!-- Dashboard Cards -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Total Sales</h3>
                    </div>
                    <div class="card-value">₱125,450</div>
                    <p class="card-description">+12% from last month</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Orders</h3>
                    </div>
                    <div class="card-value">1,234</div>
                    <p class="card-description">+8% from last month</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Customers</h3>
                    </div>
                    <div class="card-value">856</div>
                    <p class="card-description">+15% from last month</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Products</h3>
                    </div>
                    <div class="card-value">45</div>
                    <p class="card-description">Active products</p>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="activity-list">
                <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 600;">Recent Activity</h2>
                
                <div class="activity-item">
                    <div class="activity-icon">🛒</div>
                    <div class="activity-content">
                        <h4>New Order Received</h4>
                        <p>Order #1234 for Custom Tumbler - ₱1,395</p>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">👤</div>
                    <div class="activity-content">
                        <h4>New Customer Registration</h4>
                        <p>John Doe registered as a new customer</p>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <h4>Product Updated</h4>
                        <p>Limited Edition Tumbler stock updated</p>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">💰</div>
                    <div class="activity-content">
                        <h4>Payment Received</h4>
                        <p>Payment of ₱2,790 received for Order #1233</p>
                    </div>
                </div>
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