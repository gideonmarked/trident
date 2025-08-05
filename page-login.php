<?php
/**
 * Template Name: Custom Login
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Redirect if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

// Handle login form submission
$login_error = '';
if ($_POST && isset($_POST['trident_login'])) {
    $creds = array(
        'user_login'    => sanitize_text_field($_POST['email']),
        'user_password' => $_POST['password'],
        'remember'      => isset($_POST['remember'])
    );
    
    $user = wp_signon($creds, false);
    
    if (is_wp_error($user)) {
        $login_error = 'Invalid email or password. Please try again.';
    } else {
        // Use the same redirect logic as wp-login.php
        if (trident_user_has_access($user->ID)) {
            wp_redirect(home_url('/trident-admin'));
        } else {
            wp_redirect(admin_url());
        }
        exit;
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
    <title>Login - <?php bloginfo('name'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('<?php echo get_template_directory_uri(); ?>/assets/images/login bg.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        

        
        /* Starburst effects */
        .starburst {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #FFD700;
            border-radius: 50%;
            animation: twinkle 3s infinite;
        }
        
        .starburst::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 1px;
            height: 20px;
            background: linear-gradient(to bottom, #FFD700, transparent);
        }
        
        .starburst:nth-child(1) { top: 15%; left: 25%; animation-delay: 0s; }
        .starburst:nth-child(2) { top: 25%; right: 30%; animation-delay: 1s; }
        .starburst:nth-child(3) { top: 60%; left: 15%; animation-delay: 2s; }
        .starburst:nth-child(4) { top: 70%; right: 20%; animation-delay: 0.5s; }
        .starburst:nth-child(5) { top: 85%; left: 40%; animation-delay: 1.5s; }
        
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }
        
        .trident-logo {
            position: relative;
            z-index: 10;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .trident-logo-icon {
            width: 100%;
            max-width: 300px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .trident-logo-icon img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        
        .trident-logo-text {
            color: white;
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            font-size: 1rem;
            color: #6b7280;
            font-weight: 400;
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
            text-align: left;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: border-color 0.3s ease;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #fbbf24;
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 1.2rem;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .remember-checkbox {
            width: 16px;
            height: 16px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .remember-label {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }
        
        .forgot-password {
            font-size: 0.875rem;
            color: #3b82f6;
            text-decoration: underline;
            font-weight: 500;
        }
        
        .login-btn {
            width: 100%;
            padding: 1rem;
            background: #fbbf24;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .login-btn:hover {
            background: #f59e0b;
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            text-align: center;
        }
        
        @media (max-width: 480px) {
            .login-card {
                margin: 1rem;
                padding: 2rem;
            }
            
            .trident-logo-text {
                font-size: 1.5rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Starburst effects -->
    <div class="starburst"></div>
    <div class="starburst"></div>
    <div class="starburst"></div>
    <div class="starburst"></div>
    <div class="starburst"></div>
    
    <!-- TRIDENT Logo -->
    <div class="trident-logo">
        <div class="trident-logo-icon">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/trident-footer-logo.png" alt="TRIDENT Logo">
        </div>
    </div>
    
    <!-- Login Card -->
    <div class="login-card">
        <?php if ($login_error): ?>
            <div class="error-message"><?php echo esc_html($login_error); ?></div>
        <?php endif; ?>
        
        <div class="login-header">
            <h1 class="login-title">Welcome Back!</h1>
            <p class="login-subtitle">We missed you! Please enter your details</p>
        </div>
        
        <form method="post" action="">
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Enter your Email Address" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your Password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
                </div>
            </div>
            
            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                    <label for="remember" class="remember-label">Remember Me</label>
                </div>
                <a href="<?php echo wp_lostpassword_url(); ?>" class="forgot-password">Forgot Password?</a>
            </div>
            
            <button type="submit" name="trident_login" class="login-btn">Log In</button>
        </form>
    </div>
    
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }
    </script>
</body>
</html> 