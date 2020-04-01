<?php

/*
Plugin Name: Advanced Custom Fields: Narwhal Icons
Description: Custom icon picker based on svg json files
Version: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('nwicons_acf_plugin_narwhal_icons') ) :

class nwicons_acf_plugin_narwhal_icons {

	// vars
	var $settings;


	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/

	function __construct() {

		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		add_filter( 'upload_mimes', array( $this, 'json_mime_type' ), 1, 1 );

		add_action( 'acf/init', array( $this, 'include_settings' ) );

		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field')); // v4

		//graphql
		add_filter( 'wpgraphql_acf_supported_fields', array( $this, 'support_graph_ql' ) );
		add_filter( 'wpgraphql_acf_register_graphql_field', array( $this, 'set_graph_ql_type' ), 20, 4 );
	}

	/**
	 * Add json to allowed mimetypes
	 * Note that the icomoon files seem to be text/plain rather than application/json
	 */
	function json_mime_type( $mime_types ){
		$mime_types['json'] = 'text/plain';
		return $mime_types;
	}

	/**
	 * includes class that sets up icon upload page
	 */
	function include_settings(){
		include_once('acf-narwhal-settings.php');
	}

	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/

	function include_field( $version = false ) {

		// support empty $version
		if( !$version ) $version = 4;


		// load NARWHALICONS
		load_plugin_textdomain( 'NARWHALICONS', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );


		// include
		include_once('fields/class-nwicons-acf-field-narwhal-icons-v' . $version . '.php');
	}

	/**
	 * add field to list of fields allowed for graphql
	 */
	function support_graph_ql( $fields ){
		$fields[] = 'narwhal_icons';
		return $fields;
	}

	/**
	 * set graphql return type, value is a group of paths, but basically a string
	 */
	function set_graph_ql_type( $field_config, $type_name, $field_name, $config ){
		$acf_field = isset( $config['acf_field'] ) ? $config['acf_field'] : null;
		$acf_type  = isset( $acf_field['type'] ) ? $acf_field['type'] : null;

		if( $acf_type != 'narwhal_icons' ){
			return $field_config;
		}

		$field_config['type'] = 'String';
		return $field_config;
	}
}


// initialize
new nwicons_acf_plugin_narwhal_icons();


// class_exists check
endif;

?>
