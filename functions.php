<?php

function custom_portfolio_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'custom_portfolio_theme_setup');

function custom_portfolio_theme_scripts() {
    if (is_page('about') || is_page_template('page-about.php')) {
        wp_enqueue_style(
            'custom-portfolio-kedebideri-font',
            'https://fonts.googleapis.com/css2?family=Kedebideri&display=swap',
            array(),
            null
        );
    }

    wp_enqueue_style(
        'custom-portfolio-theme-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'custom_portfolio_theme_scripts');

function custom_portfolio_register_project_post_type() {
    $labels = array(
        'name'                  => 'Projects',
        'singular_name'         => 'Project',
        'menu_name'             => 'Projects',
        'name_admin_bar'        => 'Project',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Project',
        'new_item'              => 'New Project',
        'edit_item'             => 'Edit Project',
        'view_item'             => 'View Project',
        'all_items'             => 'All Projects',
        'search_items'          => 'Search Projects',
        'not_found'             => 'No projects found.',
        'not_found_in_trash'    => 'No projects found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-format-gallery',
        'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'capability_type'    => array('project', 'projects'),
        'map_meta_cap'       => true,
        'show_in_rest'       => true,
        'rewrite'            => array('slug' => 'projects'),
    );

    register_post_type('project', $args);
}

add_action('init', 'custom_portfolio_register_project_post_type');

function custom_portfolio_add_project_role_caps() {
    $roles = array('administrator', 'editor');
    $caps = array(
        'edit_project',
        'read_project',
        'delete_project',
        'edit_projects',
        'edit_others_projects',
        'publish_projects',
        'read_private_projects',
        'delete_projects',
        'delete_private_projects',
        'delete_published_projects',
        'delete_others_projects',
        'edit_private_projects',
        'edit_published_projects',
        'create_projects',
    );

    foreach ($roles as $role_name) {
        $role = get_role($role_name);

        if (!$role) {
            continue;
        }

        foreach ($caps as $cap) {
            $role->add_cap($cap);
        }
    }
}

add_action('admin_init', 'custom_portfolio_add_project_role_caps');

function custom_portfolio_register_project_category_taxonomy() {
    $labels = array(
        'name'              => 'Project Categories',
        'singular_name'     => 'Project Category',
        'search_items'      => 'Search Project Categories',
        'all_items'         => 'All Project Categories',
        'edit_item'         => 'Edit Project Category',
        'update_item'       => 'Update Project Category',
        'add_new_item'      => 'Add New Project Category',
        'new_item_name'     => 'New Project Category Name',
        'menu_name'         => 'Project Categories',
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'project-category'),
    );

    register_taxonomy('project_category', array('project'), $args);
}

add_action('init', 'custom_portfolio_register_project_category_taxonomy');

function custom_portfolio_theme_enqueue_homepage_script() {
    if (is_front_page()) {
        wp_enqueue_script(
            'custom-portfolio-homepage-stage',
            get_template_directory_uri() . '/assets/js/homepage-stage.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'custom_portfolio_theme_enqueue_homepage_script');

function custom_portfolio_show_all_projects_in_category($query) {
    if (
        !is_admin() &&
        $query->is_main_query() &&
        is_tax('project_category')
    ) {
        $query->set('posts_per_page', -1);
        $query->set('orderby', 'menu_order title');
        $query->set('order', 'ASC');
    }
}

add_action('pre_get_posts', 'custom_portfolio_show_all_projects_in_category');

function custom_portfolio_project_category_orderby($orderby, $query) {
    global $wpdb;

    if (
        !is_admin() &&
        $query->is_main_query() &&
        $query->is_tax('project_category')
    ) {
        return "CASE WHEN {$wpdb->posts}.menu_order = 0 THEN 1 ELSE 0 END ASC, {$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC";
    }

    return $orderby;
}

add_filter('posts_orderby', 'custom_portfolio_project_category_orderby', 10, 2);

function custom_portfolio_add_project_order_box() {
    add_meta_box(
        'custom_portfolio_project_order',
        'Project Order',
        'custom_portfolio_render_project_order_box',
        'project',
        'side',
        'default'
    );
}

add_action('add_meta_boxes_project', 'custom_portfolio_add_project_order_box');

function custom_portfolio_render_project_order_box($post) {
    wp_nonce_field('custom_portfolio_save_project_order', 'custom_portfolio_project_order_nonce');
    ?>
    <p>
        <label for="custom_portfolio_project_order_field">Display order</label>
    </p>
    <input
        type="number"
        id="custom_portfolio_project_order_field"
        name="custom_portfolio_project_order"
        value="<?php echo esc_attr($post->menu_order); ?>"
        min="0"
        step="1"
        style="width: 100%;"
    >
    <p class="description">Use 1, 2, 3... to control the order inside project category pages. Leave 0 to place it after ordered projects.</p>
    <?php
}

function custom_portfolio_save_project_order($post_id) {
    if (
        !isset($_POST['custom_portfolio_project_order_nonce']) ||
        !wp_verify_nonce($_POST['custom_portfolio_project_order_nonce'], 'custom_portfolio_save_project_order')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'project') {
        return;
    }

    if (!isset($_POST['custom_portfolio_project_order'])) {
        return;
    }

    $project_order = max(0, absint($_POST['custom_portfolio_project_order']));

    remove_action('save_post_project', 'custom_portfolio_save_project_order');

    wp_update_post(array(
        'ID'         => $post_id,
        'menu_order' => $project_order,
    ));

    add_action('save_post_project', 'custom_portfolio_save_project_order');
}

add_action('save_post_project', 'custom_portfolio_save_project_order');

function custom_portfolio_add_project_category_order_field() {
    ?>
    <div class="form-field term-order-wrap">
        <label for="custom_portfolio_project_category_order">Category Order</label>
        <input
            type="number"
            id="custom_portfolio_project_category_order"
            name="custom_portfolio_project_category_order"
            value="0"
            min="0"
            step="1"
        >
        <p>Use 1, 2, 3... to control the homepage category order. Leave 0 to place it after ordered categories.</p>
    </div>
    <?php
}

add_action('project_category_add_form_fields', 'custom_portfolio_add_project_category_order_field');

function custom_portfolio_edit_project_category_order_field($term) {
    $category_order = get_term_meta($term->term_id, 'custom_portfolio_project_category_order', true);
    ?>
    <tr class="form-field term-order-wrap">
        <th scope="row">
            <label for="custom_portfolio_project_category_order">Category Order</label>
        </th>
        <td>
            <input
                type="number"
                id="custom_portfolio_project_category_order"
                name="custom_portfolio_project_category_order"
                value="<?php echo esc_attr($category_order !== '' ? $category_order : 0); ?>"
                min="0"
                step="1"
            >
            <p class="description">Use 1, 2, 3... to control the homepage category order. Leave 0 to place it after ordered categories.</p>
        </td>
    </tr>
    <?php
}

add_action('project_category_edit_form_fields', 'custom_portfolio_edit_project_category_order_field');

function custom_portfolio_save_project_category_order($term_id) {
    if (!isset($_POST['custom_portfolio_project_category_order'])) {
        return;
    }

    update_term_meta(
        $term_id,
        'custom_portfolio_project_category_order',
        max(0, absint($_POST['custom_portfolio_project_category_order']))
    );
}

add_action('created_project_category', 'custom_portfolio_save_project_category_order');
add_action('edited_project_category', 'custom_portfolio_save_project_category_order');

function custom_portfolio_sort_project_categories_by_order($categories) {
    if (empty($categories) || is_wp_error($categories)) {
        return $categories;
    }

    usort($categories, function($first_category, $second_category) {
        $first_order = absint(get_term_meta($first_category->term_id, 'custom_portfolio_project_category_order', true));
        $second_order = absint(get_term_meta($second_category->term_id, 'custom_portfolio_project_category_order', true));

        if ($first_order === $second_order) {
            return strcasecmp($first_category->name, $second_category->name);
        }

        if ($first_order === 0) {
            return 1;
        }

        if ($second_order === 0) {
            return -1;
        }

        return $first_order <=> $second_order;
    });

    return $categories;
}
