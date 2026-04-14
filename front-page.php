<?php
get_header();

$categories = get_terms(array(
    'taxonomy'   => 'project_category',
    'hide_empty' => true,
));

$default_category_slug = 'concerts';
$default_project = null;
$default_category = null;

if (!empty($categories) && !is_wp_error($categories)) {
    foreach ($categories as $category) {
        if ($category->slug === $default_category_slug) {
            $default_category = $category;
            break;
        }
    }

    if (!$default_category) {
        $default_category = $categories[0];
    }

    $default_projects = new WP_Query(array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'term_id',
                'terms'    => $default_category->term_id,
            ),
        ),
    ));

    $default_projects_data = array();

    if ($default_projects->have_posts()) {
        while ($default_projects->have_posts()) {
            $default_projects->the_post();

            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

            if ($image_url) {
                $default_projects_data[] = array(
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                    'image' => $image_url,
                );
            }
        }
        wp_reset_postdata();
    }

    if (!empty($default_projects_data)) {
        $default_project = $default_projects_data[0];
    }
}
?>

<main class="homepage-stage">

    <a
        class="homepage-stage__background-link"
        href="<?php echo $default_project ? esc_url($default_project['link']) : '#'; ?>"
        id="homepage-background-link"
        aria-label="<?php echo $default_project ? esc_attr($default_project['title']) : 'Open project'; ?>"
    >
        <div
            class="homepage-stage__background"
            id="homepage-background"
            <?php if ($default_project && !empty($default_project['image'])) : ?>
                style="background-image: url('<?php echo esc_url($default_project['image']); ?>');"
            <?php endif; ?>
        ></div>
    </a>

    <div class="homepage-stage__overlay">
        <div class="homepage-stage__categories">
            <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                    <?php
                    $category_projects = new WP_Query(array(
                        'post_type'      => 'project',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'project_category',
                                'field'    => 'term_id',
                                'terms'    => $category->term_id,
                            ),
                        ),
                    ));

                    $projects_data = array();

                    if ($category_projects->have_posts()) {
                        while ($category_projects->have_posts()) {
                            $category_projects->the_post();

                            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

                            if ($image_url) {
                                $projects_data[] = array(
                                    'title' => get_the_title(),
                                    'link'  => get_permalink(),
                                    'image' => $image_url,
                                );
                            }
                        }
                        wp_reset_postdata();
                    }
                    ?>
                    <a
                        class="homepage-stage__category-link <?php echo ($default_category && $category->term_id === $default_category->term_id) ? 'is-active' : ''; ?>"
                        href="<?php echo esc_url(get_term_link($category)); ?>"
                        data-projects="<?php echo esc_attr(wp_json_encode($projects_data)); ?>"
                    >
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</main>

<?php get_footer(); ?>