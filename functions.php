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
        'supports'           => array('title', 'thumbnail'),
        'show_in_rest'       => true,
        'rewrite'            => array('slug' => 'projects'),
    );

    register_post_type('project', $args);
}

add_action('init', 'custom_portfolio_register_project_post_type');