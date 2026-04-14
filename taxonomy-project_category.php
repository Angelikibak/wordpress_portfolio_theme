<?php get_header(); ?>

<?php
$current_term = get_queried_object();
?>

<main class="category-archive">

    <header class="category-archive__header">
        <h1 class="category-archive__title">
            <?php single_term_title(); ?>
        </h1>

        <a class="category-archive__back-link" href="<?php echo esc_url(home_url('/')); ?>">
            Back to Home
        </a>
    </header>

    <section class="category-archive__grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article class="category-archive__item">
                    <a href="<?php the_permalink(); ?>" class="category-archive__item-link">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php endif; ?>
                        <h2 class="category-archive__item-title"><?php the_title(); ?></h2>
                    </a>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No projects found in this category yet.</p>
        <?php endif; ?>
    </section>

</main>

<?php get_footer(); ?>