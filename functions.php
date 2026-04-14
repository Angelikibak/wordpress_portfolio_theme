<?php

function custom_portfolio_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'custom_portfolio_theme_setup');

function custom_portfolio_theme_scripts() {
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
        'supports'           => array('title', 'editor', 'thumbnail'),
        'show_in_rest'       => true,
        'rewrite'            => array('slug' => 'projects'),
    );

    register_post_type('project', $args);
}

add_action('init', 'custom_portfolio_register_project_post_type');

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