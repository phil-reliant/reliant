<?php
/**
 * Custom post types
 */

add_action( 'init', 'narwhal_register_post_types');

  // REGISTER POST TYPES
function narwhal_register_post_types() {
	$post_types = array(
		'reliant_case_study' => array(
			'single_label' => 'Case Study',
			'plural_label' => 'Case Studies',
			'single_graphql_label' => 'caseStudy',
			'plural_graphql_label' => 'caseStudies',
			'supports' => array('title', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies' => array('category', 'post_tag'),
			'rewrite' => array('slug' => 'case-studies', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-portfolio'
		),
		'reliant_infographic' => array(
			'single_label' => 'Infographic',
			'plural_label' => 'Infographics',
			'single_graphql_label' => 'infographic',
			'plural_graphql_label' => 'infographics',
			'supports' => array('title', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies' => array('category', 'post_tag'),
			'rewrite' => array('slug' => 'infographics', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-analytics'
		),
		'reliant_podcast' => array(
			'single_label' => 'Podcast',
			'plural_label' => 'Podcasts',
			'single_graphql_label' => 'podcast',
			'plural_graphql_label' => 'podcasts',
			'supports' => array('title', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies' => array('category', 'post_tag'),
			'rewrite' => array('slug' => 'podcasts', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-media-audio'
		),
		'reliant_testimonial' => array(
			'single_label' => 'Testimonial',
			'plural_label' => 'Testimonials',
			'single_graphql_label' => 'testimonial',
			'plural_graphql_label' => 'testimonials',
			'supports' => array('title', 'thumbnail', 'revisions'),
			'taxonomies' => array('post_tag'),
			'rewrite' => array('slug' => 'testimonials', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-awards'
		),
		'reliant_video' => array(
			'single_label' => 'Video',
			'plural_label' => 'Videos',
			'single_graphql_label' => 'video',
			'plural_graphql_label' => 'videos',
			'supports' => array('title', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies' => array('category', 'post_tag'),
			'rewrite' => array('slug' => 'videos', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-format-video'
		),
		'reliant_white_paper' => array(
			'single_label' => 'White Paper',
			'plural_label' => 'White Papers',
			'single_graphql_label' => 'whitePaper',
			'plural_graphql_label' => 'whitePapers',
			'supports' => array('title', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies' => array('category', 'post_tag'),
			'rewrite' => array('slug' => 'white-papers', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-media-document'
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
        'graphql_single_name' => $post_type['single_graphql_label'],
        'graphql_plural_name' => $post_type['plural_graphql_label']
      )
    );
  }
}
