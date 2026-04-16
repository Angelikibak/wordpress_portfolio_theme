<?php get_header(); ?>

<main class="site-page">
    <div class="site-page__inner">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article class="site-page__article">
                    <header class="site-page__header">
                        <h1 class="site-page__title"><?php the_title(); ?></h1>
                    </header>

                    <div class="site-page__content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>