<?php

/**
 * Add functionality for Hardware Catalog page
 * Search is for products with matches against name, manufacturer or serial number (sku)
 */
class ReliantHardwareSearch{
	function __construct(){

		//add custom fields to search query
		add_action( 'pre_get_posts', array( $this, '_maybe_alter_query' ) );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', array( $this, '_custom_wc_query_vars' ), 10, 2 );

	}

	/**
	 * If we are searching for a product, add some extra fields to search query
	 */
	function _maybe_alter_query( $query ){

		if ( !is_admin() && $query->is_search && ( isset( $query->query_vars['post_type'] ) && 'product' == $query->query_vars['post_type'] ) ) {
			add_filter( 'posts_join', array( $this, '_search_join', ) );
			add_filter( 'posts_where', array( $this, '_search_where', ) );
			add_filter( 'posts_distinct', array( $this, '_search_distinct', ) );
		}

		return $query;
	}

	/**
	 * join post meta table for sku and brand text
	 */
	function _search_join( $join ){
		global $wpdb;

		$join .=' LEFT JOIN '.$wpdb->postmeta. ' wpmetabrand ON '. $wpdb->posts . '.ID = wpmetabrand.post_id AND wpmetabrand.meta_key="brand_text" ';
		$join .=' LEFT JOIN '.$wpdb->postmeta. ' wpmetasku ON '. $wpdb->posts . '.ID = wpmetasku.post_id AND wpmetasku.meta_key="_sku" ';

		return $join;
	}

	/**
	 * add where clauses for sku and brand text
	 */
	function _search_where( $where ){
		global $wpdb;

		$where = preg_replace(
			"/\(\s*".$wpdb->posts.".post_content\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(".$wpdb->posts.".post_content LIKE $1) OR (wpmetabrand.meta_value LIKE $1) OR (wpmetasku.meta_value LIKE $1)",
			$where
		 );

		return $where;
	}

	/**
	 * This should not be needed since we are joining on specific meta keys, but just in case
	 */
	function _search_distinct( $where ){
		global $wpdb;

		return "DISTINCT";
	}

}

new ReliantHardwareSearch();
