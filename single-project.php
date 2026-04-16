<?php get_header(); ?>

<main class="single-project">
    <div class="single-project__inner">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php
                $project_categories = get_the_terms(get_the_ID(), 'project_category');
                $project_category_link = '';
                $project_category_name = '';

                if ($project_categories && !is_wp_error($project_categories)) {
                    $primary_category = $project_categories[0];
                    $project_category_link = get_term_link($primary_category);
                    $project_category_name = $primary_category->name;
                }
                ?>

                <nav class="single-project__topbar" aria-label="Project navigation">
                    <a class="single-project__back-link" href="<?php echo esc_url(home_url('/')); ?>">
                        Back to Home
                    </a>

                    <?php if ($project_category_link && !is_wp_error($project_category_link)) : ?>
                        <a class="single-project__back-link" href="<?php echo esc_url($project_category_link); ?>">
                            Back to <?php echo esc_html($project_category_name); ?>
                        </a>
                    <?php endif; ?>
                </nav>

                <article class="single-project__article">
                    <header class="single-project__header">
                        <h1 class="single-project__title"><?php the_title(); ?></h1>
                    </header>

                    <div class="single-project__content">
                        <?php the_content(); ?>
                    </div>
                </article>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>