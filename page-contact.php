<?php get_header(); ?>

<main class="contact-page-split">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article class="contact-page-split__article">

                <div class="contact-page-split__media">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('full'); ?>
                    <?php endif; ?>
                </div>

                <div class="contact-page-split__content">
                    <div class="contact-page-split__content-inner">
                        <h1 class="contact-page-split__title"><?php the_title(); ?></h1>

                        <div class="contact-page-split__text">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>

            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>