<?php
include_once 'container_metabox.php';




function yac_create_post_types($postType,$label, $postion, $icon, $customMetaBoxes='')
{

    $labels = array(
        'name' => __($label),
        'singular_name' => __($label),
        'add_new' => __("Add New $label"),
        'add_new_item' => __("Add New $label"),
        'edit_item' => __("Edit $label"),
        'new_item' => __("Add New $label"),
        'view_item' => __("View $label"),
        'search_items' => __("Search $label"),
        'not_found' => __("No $label found"),
        'not_found_in_trash' => __("No $label found in trash"),
    );
    $supports = array(
        'title',
    );
    $args = array(
        'labels' => $labels,
        'supports' => $supports,
        'public' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => $postType),
        'has_archive' => true,
        'menu_position' => $postion,
        'menu_icon' => $icon,
        'register_meta_box_cb' => $customMetaBoxes,
    );
    register_post_type($postType, $args);

}



// Creating Section Post Type
function yac_creating_all_post_types(){

    yac_create_post_types('package','Package', 11, 'dashicons-calendar-alt');
    yac_create_post_types('container','Container', 12, 'dashicons-calendar-alt', 'wporg_add_custom_box');
    yac_create_post_types('section','Section', 13, 'dashicons-calendar-alt');
    yac_create_post_types('subsection','Sub Section', 14, 'dashicons-calendar-alt');
    yac_create_post_types('advert','Advert', 15, 'dashicons-calendar-alt');
    yac_create_post_types('playstore','Play Store', 16, 'dashicons-calendar-alt');

}
add_action('init', 'yac_creating_all_post_types');







/*
function wporg_add_custom_box()
{
    $screens = ['container'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id', // Unique ID
            'Custom Meta Box Title', // Box title
            'wporg_custom_box_html', // Content callback, must be of type callable
            $screen // Post type
        );
    }
}
add_action('add_meta_boxes', 'wporg_add_custom_box');
*/
?>