<?php
/**
 * Common Layout Template for TRIDENT Pages
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Get the page title
$page_title = isset($page_title) ? $page_title : get_the_title();
$page_title = $page_title ? $page_title . ' - TRIDENT' : 'TRIDENT';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="trident-page">
    <!-- Header -->
    <?php get_template_part('template-parts/trident/header'); ?>

    <!-- Main Content -->
    <main class="trident-main">
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <?php get_template_part('template-parts/trident/footer'); ?>
</div>

<?php wp_footer(); ?>
</body>
</html> 