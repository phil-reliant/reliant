<?php
/**
 * Custom post types
 */

add_action( 'init', 'narwhal_register_post_types');

  // REGISTER POST TYPES
function narwhal_register_post_types() {
  $post_types = array(
   'reliant_testimonial' => array(
     'single_label' => 'Testimonial',
     'plural_label' => 'Testimonials',
     'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
     'taxonomies' => array('post_tag'),
     'rewrite' => array('slug' => 'testimonials', 'with_front' => false),
     'publicly_queryable' => true,
     'has_archive' => true,
     'menu_icon' => 'dashicons-media-text'
   )
  );
  foreach ($post_types as $machine_name => $post_type) {
    $labels = array(
      'name' => _x(ucwords($post_type['plural_label']), 'post type general name'),
      'singular_name' => _x(ucwords($post_type['single_label']), 'post type singular name'),
      'add_new' => _x('Add New ', ucwords($post_type['single_label'])),
      'add_new_item' => __('Add New '.ucwords($post_type['single_label'])),
      'edit_item' => __('Edit '.ucwords($post_type['single_label'])),
      'new_item' => __('New '.$post_type['single_label']),
      'view_item' => __('View '.ucwords($post_type['single_label'])),
      'all_items' => __('All '.ucwords($post_type['plural_label'])),
      'search_items' => __('Search '.$post_type['plural_label']),
      'not_found' =>  __('No '.$post_type['plural_label'].' found'),
      'not_found_in_trash' => __('No '.$post_type['plural_label'].' found in Trash'),
      'parent_item_colon' => ''
    );
    register_post_type( $machine_name,
      array(
        'labels' => $labels,
        'public' => true,
        'hierarchical' => false,
        'supports' => $post_type['supports'],
        'taxonomies' => $post_type['taxonomies'],
        'has_archive' => $post_type['has_archive'],
        'rewrite' => $post_type['rewrite'],
        'menu_icon' => $post_type['menu_icon'],
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'hierarchical' => true,
        'graphql_single_name' => $post_type['single_label'],
        'graphql_plural_name' => $post_type['plural_label']
      )
    );
  }
}