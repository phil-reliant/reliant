<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('nwicons_acf_plugin_narwhal_icons_settings') ) :

class nwicons_acf_plugin_narwhal_icons_settings {

	function __construct(){
		$this->setup_page();
		$this->setup_fields();

		add_filter( 'acf/update_value/key=narwal_icons_settings_icon_file', array( $this, 'parse_icon_file' ), 20, 3 );
	}

	function setup_page(){
		acf_add_options_sub_page(
			array(
				'page_title'      => __( 'Narwhal Icons' ),
				'menu_title'      => __( 'Icons' ),
				'menu_slug'       => 'narwhal-icons',
				'capability'      => 'manage_options',
				'parent_slug'	  => 'options-general.php',
			)
		);
	}

	function parse_icon_file( $value, $post_id, $field ){
		try {
			$json_file_id = $value;
			$json_file = get_attached_file( $json_file_id );
			$json = file_get_contents( $json_file );
			$json = json_decode($json);
			$choices = [];
			foreach ($json->icons as $icon) {
				$paths = [];
				$tags = [];
				if( isset( $icon->icon ) ){
					foreach ($icon->icon->paths as $path) {
						$paths[] = '<path d="' . $path . '" />';
					}
					foreach ($icon->icon->tags as $tag) {
						$tags[] = $tag;
					}
				}else{
					foreach ($icon->paths as $path) {
						$paths[] = '<path d="' . $path . '" />';
					}
					foreach ($icon->tags as $tag) {
						$tags[] = $tag;
					}
				}
				$svg = '';
				$svg = '<svg width="20" height="20" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
				$svg .=		implode($paths);
				$svg .='</svg>';
				$choices[implode($paths)] = implode( ', ', $tags );
			}

			update_option( 'narwal_icon_settings_icons_parsed', $choices, false );

		} catch (\Exception $e) {
		}

		return $value;
	}

	function setup_fields(){
		$stem = 'narwal_icons_settings_';
		acf_add_local_field_group(array (
			'key' => 'group',
			'title' => 'Settings',
			'fields' => array (
				array(
					'key' => $stem.'icon_file',
					'label' => 'Icon File',
					'name' => $stem.'icon_file',
					'type' => 'file',
					'instructions' => 'Upload your <a href="https://icomoon.io/" target="_blank">IcoMoon</a> formatted json file.',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'show_in_graphql' => 0,
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'narwhal-icons',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
		));
	}
}

$nwicons_settings = new nwicons_acf_plugin_narwhal_icons_settings();

endif;
