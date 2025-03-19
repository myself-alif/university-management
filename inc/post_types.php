<?php

function register_post_types()
{
    register_post_type('event', array(
        'has_archive' => true,
        'capability_type' => 'event',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'public' => true,
        'menu_icon' => 'dashicons-calendar',
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => "Add New Event",
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        )
    ));
    register_post_type('program', array(
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array("title"),
        'public' => true,
        'menu_icon' => 'dashicons-awards',
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => "Add New Program",
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        )
    ));
    register_post_type('professor', array(
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'public' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => "Add New Professor",
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        )
    ));

    register_post_type('note', array(
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'supports' => array('title', 'editor'),
        'show_in_rest' => true,
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => "Add New Note",
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        )
    ));
    register_post_type('like', array(
        'supports' => array('title'),
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-heart',
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => "Add New Like",
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like'
        )
    ));
    register_post_type('slide', array(
        'supports' => array('title'),
        'public' => true,
        'show_ui' => true,
        'menu_icon' => 'dashicons-slides',
        'labels' => array(
            'name' => 'Slides',
            'add_new_item' => "Add New Slide",
            'edit_item' => 'Edit Slide',
            'all_items' => 'All Slides',
            'singular_name' => 'Slide'
        )
    ));
}
add_action("init", "register_post_types");
