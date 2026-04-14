<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header class="site-header">
    <div class="site-header__inner">
        <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>">
            Aliki Seferou
        </a>

        <nav class="site-nav" aria-label="Main navigation">
            <a href="<?php echo esc_url(home_url('/')); ?>">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
            <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer">Instagram</a>
        </nav>
    </div>
</header>