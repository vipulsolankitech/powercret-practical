<?php
/**
 * Plugin Name: Testimonail
 * Plugin URI:  http://localhost/	
 * Description: plugin to create custom post type for testimonial	
 * Version:     1.0.0
 * Author:      vipul	
 * Author URI:  https://vipulsolankitech.com/
 * License:     GPL2
 */
if (!defined('ABSPATH')) {
    die("You Can't access this path directoly"); // Exit if accessed directly
}

//below is function for creating the Testimonial custom post type.

function myplugin_register_testimonial_cpt() {

    $labels = array(
        'name'               => 'Testimonial',
        'singular_name'      => 'Testimonial',
        'menu_name'          => 'Testimonials',
        'name_admin_bar'     => 'Testimonial',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Testimonial',
        'new_item'           => 'New Testimonial',
        'edit_item'          => 'Edit Testimonial',
        'view_item'          => 'View Testimonial',
        'all_items'          => 'All Testimonial',
        'search_items'       => 'Search Testimonial',
        'not_found'          => 'No Testimonial found.',
        'not_found_in_trash' => 'No Testimonial found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonial'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest'       => true, // enables Gutenberg editor
    );

    register_post_type('testimonial', $args);
}

add_action('init', 'myplugin_register_testimonial_cpt');

include __DIR__ . '/inc/custom_fields.php';
//include __DIR__ . '/inc/code_debug.php';

