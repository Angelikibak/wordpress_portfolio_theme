<?php get_header(); ?>

<main class="site-main single-project">

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article class="single-project__article">

                <header class="single-project__header">
                    <h1 class="single-project__title"><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-project__featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>

            </article>
        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>