<?php get_header(); ?>

<main class="site-main home-page">

    <section class="work-gallery" aria-label="Portfolio projects">
        <div class="work-gallery__inner">

            <?php
            $projects_query = new WP_Query(array(
                'post_type'      => 'project',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ));
            ?>

            <?php if ($projects_query->have_posts()) : ?>
                <?php while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
                    <article class="work-item">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php endif; ?>

                            <h2><?php the_title(); ?></h2>
                        </a>
                    </article>
                <?php endwhile; ?>

                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p>No projects found yet.</p>
            <?php endif; ?>

        </div>
    </section>

    <section id="about" class="home-section">
        <div class="home-section__inner">
            <h2>About</h2>
            <p>
                Short photographer bio goes here. Keep it simple, personal, and minimal.
            </p>
        </div>
    </section>

    <section id="contact" class="home-section">
        <div class="home-section__inner">
            <h2>Contact</h2>
            <p>
                <a href="mailto:hello@example.com">hello@example.com</a>
            </p>
        </div>
    </section>

</main>

<?php get_footer(); ?>