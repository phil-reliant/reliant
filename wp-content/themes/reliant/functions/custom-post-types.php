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
        ),
        'reliant_guides' => array(
			'single_label' => 'Guide',
			'plural_label' => 'Guides',
			'single_graphql_label' => 'Guide',
			'plural_graphql_label' => 'Guides',
			'supports' => array('title', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies' => array('category', 'post_tag'),
			'rewrite' => array('slug' => 'guides', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-portfolio'
		),
		'reliant_eol_product' => array(
			'single_label' => 'EOL Product',
			'plural_label' => 'EOL Products',
			'single_graphql_label' => 'eolProduct',
			'plural_graphql_label' => 'eolProducts',
			'supports' => array( 'title', ),
			'taxonomies' => array(),
			'rewrite' => array('slug' => 'eol-product', 'with_front' => false),
			'publicly_queryable' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-palmtree'
		),
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

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'st-music-park' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'st-music-park' ),
		'menu_name'                  => __( 'Categories', 'st-music-park' ),
		'all_items'                  => __( 'All Items', 'st-music-park' ),
		'parent_item'                => __( 'Parent Item', 'st-music-park' ),
		'parent_item_colon'          => __( 'Parent Item:', 'st-music-park' ),
		'new_item_name'              => __( 'New Item Name', 'st-music-park' ),
		'add_new_item'               => __( 'Add New Item', 'st-music-park' ),
		'edit_item'                  => __( 'Edit Item', 'st-music-park' ),
		'update_item'                => __( 'Update Item', 'st-music-park' ),
		'view_item'                  => __( 'View Item', 'st-music-park' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'st-music-park' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'st-music-park' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'st-music-park' ),
		'popular_items'              => __( 'Popular Items', 'st-music-park' ),
		'search_items'               => __( 'Search Items', 'st-music-park' ),
		'not_found'                  => __( 'Not Found', 'st-music-park' ),
		'no_terms'                   => __( 'No items', 'st-music-park' ),
		'items_list'                 => __( 'Items list', 'st-music-park' ),
		'items_list_navigation'      => __( 'Items list navigation', 'st-music-park' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
		'show_in_graphql' => true,
		'graphql_single_name' => 'EOLCategory',
		'graphql_plural_name' => 'EOLCategories',
	);

	register_taxonomy( 'eol_category', array( 'reliant_eol_product' ), $args );
}
/*
 * Duplicate code
 * This field is managed by ACF in live database.
 *
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_5f2d5997a0c79',
		'title' => 'Single Guide Details',
		'fields' => array(
			array(
				'key' => 'field_5f2d5997b7f63',
				'label' => 'Guide Url',
				'name' => 'guide_url',
				'type' => 'url',
				'instructions' => 'The URL for the guide',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'show_in_graphql' => 1,
				'default_value' => '',
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'reliant_guides',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_graphql' => 1,
		'graphql_field_name' => 'guide_details',
	));
	
	endif;*/
