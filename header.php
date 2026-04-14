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
        <a class="site-header__name" href="<?php echo esc_url(home_url('/')); ?>">
            Aliki Seferou
        </a>

        <nav class="site-header__nav" aria-label="Main navigation">
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>
    </div>
</header>