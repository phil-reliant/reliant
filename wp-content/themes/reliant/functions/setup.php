<?php
/****
 *
 * Theme Setup
 */

if (! function_exists('reliant_setup') ) :
function reliant_setup() {
	register_nav_menus( array(
		'header' => esc_html__( 'Header', 'reliant' ),
		'footer-menu-primary' => esc_html__('Landing Page Footer', 'reliant'),
		'footer-menu-secondary' => esc_html__('Footer Menu Secondary', 'reliant')
	) );
}
endif;
add_action('after_setup_theme', 'reliant_setup');

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(
		array(
			'page_title'      => __( 'Options' ),
			'menu_title'      => __( 'Options' ),
			'menu_slug'       => 'site-options',
			'capability'      => 'edit_posts',
			'show_in_graphql' => true
		)
	);
}

function gravity_form_choices( $field ){
	if( !class_exists( 'GFAPI' ) ){
		return $field;
	}

	$forms = GFAPI::get_forms( true, false );
	$field['choices'] = array();

	foreach( $forms as $form ){
		$field['choices'][$form['id']] = $form['title'];
	}

	return $field;
}

add_filter( 'acf/load_field/name=form_id', 'gravity_form_choices' );
add_filter( 'acf/load_field/name=tab_1_form_id', 'gravity_form_choices' );
add_filter( 'acf/load_field/name=tab_2_form_id', 'gravity_form_choices' );
