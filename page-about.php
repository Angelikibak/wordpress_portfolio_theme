<?php get_header(); ?>

<main class="about-page-fullscreen">
    <div class="about-page-fullscreen__media">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full'); ?>
        <?php endif; ?>
    </div>

    <div class="about-page-fullscreen__content">
        <div class="about-page-fullscreen__content-inner">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <h1 class="about-page-fullscreen__title"><?php the_title(); ?></h1>

                    <div class="about-page-fullscreen__text">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>