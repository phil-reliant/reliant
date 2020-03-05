<?php

/**
 * Add functionality for Hardware Catalog page
 * This should handle searching and browse by methods of travsersing catalog
 * Search is for products with matches againse name, manufacturer or serial number (sku)
 * Browse should let users select brand or product type then see further selections broken down by product family
 */
class ReliantHardwareFilters{

	function __construct(){
		add_action( 'init', array( $this, '_post_types' ) );

		//before updating post, save old information to determine which product caches to reset
		add_action( 'acf/save_post', array( $this, '_set_old_data' ), 5 );
		//update additional product relationship data on save
		add_action( 'acf/save_post', array( $this, '_update_filter_data' ) );
		add_action( "save_post_reliant_brand", array( $this, '_update_filter_data_reliant_brand' ) );
		add_action( "save_post_reliant_product_type", array( $this, '_update_filter_data_reliant_product_type' ) );

		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', array( $this, '_custom_wc_query_vars' ), 10, 2 );

		//add custom fields to graphql - https://docs.wpgraphql.com/getting-started/custom-fields-and-meta/
		add_action( 'graphql_register_types', array( $this, '_brand_product_cache' ) );
		add_action( 'graphql_register_types', array( $this, '_product_type_brand_cache' ) );
	}

	/**
	 * register our product cache with graphql , sending as json encoded string to save time defining every part of the array here
	 */
	function _brand_product_cache(){

		register_graphql_field( 'productBrand', 'productCache', [
			'type' => 'String',
			'description' => __( 'Brand products sorted by type and family', 'reliant' ),
			'resolve' => function( $post ) {
				$cache = get_post_meta( $post->ID, 'product_cache', true );
				return ! empty( $cache ) ? json_encode( $cache ) : '';
			}
		] );

	}

	/**
	 * register our brand cache with graphql , sending as json encoded string to save time defining every part of the array here
	 */
	function _product_type_brand_cache(){
		register_graphql_field( 'productFamilyType', 'brandCache', [
			'type' => 'String',
			'description' => __( 'Brand ids that contain families of this type', 'reliant' ),
			'resolve' => function( $post ) {
				$cache = get_post_meta( $post->ID, 'brand_cache', true );
				return ! empty( $cache ) ? json_encode( $cache ) : '';
			}
		] );
	}

	/**
	 * Add new post types needed to make hardware catalog filtering/browsing work based on mockup
	 * Need product brand
	 * Need product family
	 * Need product type
	 */
	function _post_types(){
		$post_types = array(
			'reliant_brand' => array(
				'single_label' => 'Brand',
				'plural_label' => 'Brands',
				'graphql_single_name' => 'productBrand',
				'graphql_plural_name' => 'productBrands',
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					//'revisions',
					'page-attributes',
				),
				'taxonomies' => array(),
				'rewrite' => array( 'slug' => 'brands', 'with_front' => false ),
				'publicly_queryable' => true,
				'has_archive' => false,
				'show_in_menu' => 'edit.php?post_type=product',
			),
			'reliant_brand_family' => array(
				'single_label' => 'Family',
				'plural_label' => 'Families',
				'graphql_single_name' => 'productFamily',
				'graphql_plural_name' => 'productFamilies',
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					//'revisions',
					'page-attributes',
				),
				'taxonomies' => array(),
				'rewrite' => array('slug' => 'family', 'with_front' => false ),
				'publicly_queryable' => true,
				'has_archive' => false,
				'show_in_menu' => 'edit.php?post_type=product',
			),
			'reliant_product_type' => array(
				'single_label' => 'Type',
				'plural_label' => 'Types',
				'graphql_single_name' => 'productFamilyType',
				'graphql_plural_name' => 'productFamilyTypes',
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					//'revisions',
					'page-attributes',
				),
				'taxonomies' => array(),
				'rewrite' => array('slug' => 'product-type', 'with_front' => false ),
				'publicly_queryable' => true,
				'has_archive' => false,
				'show_in_menu' => 'edit.php?post_type=product',
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

			$post_type += array(
				'labels' => $labels,
				'public' => true,
				'hierarchical' => false,
				'show_in_rest' => true,
				'show_in_graphql' => true,
			);

			register_post_type( $machine_name, $post_type );
		}
	}

	/**
	 * set hidden data before our new custom information is saved
	 * setting previous selections for use with logic after new information saved
	 */
	function _set_old_data( $post_id ){
		$post_type = get_post_type( $post_id );
		switch( $post_type ){
			case 'reliant_brand_family':
			case 'product':
				$method = "_set_old_data_{$post_type}";
				$this->$method( $post_id );
			break;

			default:
				return;
			break;
		}
	}

	/**
	 * Families will need to act when their brand or type changes
	 */
	function _set_old_data_reliant_brand_family( $post_id ){
		$original_brand = (int)get_post_meta( $post_id, 'brand', true );
		update_post_meta( $post_id, 'previous_brand', $original_brand );

		$original_type = (int)get_post_meta( $post_id, 'type', true );
		update_post_meta( $post_id, 'previous_type', $original_type );
	}

	/**
	 * Products will need to act on product family changes
	 */
	function _set_old_data_product( $post_id ){
		$original_family = (int)get_post_meta( $post_id, 'family', true );
		update_post_meta( $post_id, 'previous_family', $original_family );
	}

	/**
	 * Handle data transformations after we have saved our custom fields
	 * Caching, hidden relationships
	 */
	function _update_filter_data( $post_id ){
		$post_type = get_post_type( $post_id );
		switch( $post_type ){
			case 'reliant_brand_family':
			case 'product':
				$method = "_update_filter_data_{$post_type}";
				$this->$method( $post_id );
			break;

			default:
				return;
			break;
		}
	}

	/**
	 * set child product's brand text
	 */
	function _update_filter_data_reliant_brand( $post_id ){
		global $wpdb;

		$args = [
			'post_type' => 'product',
			'posts_per_page' => -1,
			'orderby' => [
				'menu_order' => 'ASC',
				'title' => 'DESC',
			],
			'meta_query' => array(
				array(
					'key' => 'brand',
					'value' => $post_id,
					'compare' => '=',
				),
			),
			'fields' => 'ids',
		];

		$query = new WP_Query( $args );
		if( count( $query->posts ) > 0 ){
			$post_ids = implode( ', ', $query->posts );
			$SQL = $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value=%s WHERE meta_key='brand_text' AND post_id IN ({$post_ids})", get_the_title( $post_id ) );
			$wpdb->query( $SQL );
		}
	}

	/**
	 * Cache families associated to this brand
	 * cache brand cross referenced with type?
	 */
	function _update_product_brand_cache( $post_id ){
		$cache = [];
		$type_sorter = [];

		//pull all families and sort by type
		$args = [
			'post_type' => 'reliant_brand_family',
			'posts_per_page' => -1,
			'orderby' => [
				'menu_order' => 'ASC',
				'title' => 'DESC',
			],
			'meta_query' => array(
				array(
					'key' => 'brand',
					'value' => $post_id,
					'compare' => '=',
				),
			),
		];
		$query = new WP_Query( $args );
		while( $query->have_posts() ){
			$query->the_post();

			$type = get_post_meta( $query->post->ID, 'type', true );
			if( !isset( $type_sorter[$type] ) ){
				$type_sorter[$type] = [];
			}

			$type_sorter[$type][ $query->post->ID ] = [
				'name' => $query->post->post_title,
				'products' => get_post_meta( $query->post->ID, 'product_cache', true ),
			];
		}

		//get types in the correct order and fill based on families
		$args = [
			'post_type' => 'reliant_product_type',
			'posts_per_page' => -1,
			'orderby' => [
				'menu_order' => 'ASC',
				'title' => 'DESC',
			],
			'post__in' => array_keys( $type_sorter ),
		];
		$query = new WP_Query( $args );
		while( $query->have_posts() ){
			$query->the_post();
			if( !isset( $type_sorter[$query->post->ID] ) ){
				continue;
			}
			$cache[$query->post->ID] = [
				'name' => $query->post->post_title,
				'families' => $type_sorter[$query->post->ID],
			];
		}

		update_post_meta( $post_id, 'product_cache', $cache );
	}

	/**
	 * Saving product family post type
	 * If relationships have changed, update child products with new information
	 * update caches as appropriate
	 */
	function _update_filter_data_reliant_brand_family( $post_id ){
		global $wpdb;
		$statements = [];

		$products = new WP_Query( array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'fields' => 'ids',
		) );
		$post_ids = implode( ', ', $products->posts );

		//determine if brand has changed
		$previous_brand = get_post_meta( $post_id, 'previous_brand', true );
		$brand_id = get_post_meta( $post_id, 'brand', true );
		if( $previous_brand != $brand_id ){
			$this->_update_product_brand_cache( $brand_id );
			if( 0 != $previous_brand ){
				$this->_update_product_brand_cache( $previous_brand );
			}

			//update child product relationships
			$brand_object = get_post( $brand_id );
			$statements[] = $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value=%d WHERE meta_key='brand' AND post_id IN ({$post_ids})", $brand_id );
			$statements[] = $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value=%s WHERE meta_key='brand_text' AND post_id IN ({$post_ids})", $brand_object->post_title );
		}

		//determine if type has changed
		$previous_type = get_post_meta( $post_id, 'previous_type', true );
		$type_id = get_post_meta( $post_id, 'type', true );
		if( $previous_type != $type_id ){
			$this->_update_product_type_cache( $type_id );
			if( 0 != $previous_type ){
				$this->_update_product_type_cache( $previous_type );
			}

			//update child product relationships
			$statements[] = $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value=%d WHERE meta_key='product_type' AND post_id IN ({$post_ids})", $type_id );
		}

		//update database with our sql statements - make sure there were posts to update
		if( count( $products->posts ) > 0 && count( $statements ) > 0 ){
			foreach( $statements as $statement ){
				$wpdb->query( $statement );
			}
		}
	}

	/**
	 * Cache products associated to this product family
	 * using woocommerce query here to expose special product fields to query
	 * https://github.com/woocommerce/woocommerce/wiki/wc_get_products-and-WC_Product_Query
	 */
	function _update_product_family_cache( $post_id ){
		//published products, in stock that are visible within some part of site
		$args = [
			'product_family' => $post_id,
			'limit' => -1,
			'status' => 'publish',
			'visibility' => array( 'visible', 'catalog', 'search' ),
			'stock_status' => 'instock',
		];
		$query = new WC_Product_Query( $args );
		$products = $query->get_products();

		$cache = array();
		foreach( $products as $product ){
			$cache[] = array(
				'name' => $product->get_name(),
				'url' => get_permalink( $product->get_id() ),
			);
		}

		update_post_meta( $post_id, 'product_cache', $cache );

		$brand_id = get_post_meta( $post_id, 'brand', true );
		$this->_update_product_brand_cache( $brand_id );
	}

	/**
	 * Update any affected brand product caches
	 */
	function _update_filter_data_reliant_product_type( $post_id ){
		$brands = [];
		$families = new WP_Query( [
			'post_type' => 'reliant_brand_family',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'type',
					'value' => $post_id,
					'compare' => '=',
				),
			),
		] );
		while( $families->have_posts() ){
			$families->the_post();
			$brand = (int)get_post_meta( $families->post->ID, 'brand', true );
			if( 0 === $brand ){
				continue;
			}
			$brands[$brand] = $brand;
		}

		foreach( $brands as $brand ){
			$this->_update_product_brand_cache( $brand );
		}

	}

	/**
	 * build cache of brands that contain products of this type
	 */
	function _update_product_type_cache( $post_id ){
		$brands = [];
		$families = new WP_Query( [
			'post_type' => 'reliant_brand_family',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'type',
					'value' => $post_id,
					'compare' => '=',
				),
			),
		] );
		while( $families->have_posts() ){
			$families->the_post();
			$brand = (int)get_post_meta( $families->post->ID, 'brand', true );
			if( 0 === $brand ){
				continue;
			}
			$brands[$brand] = 1;
		}

		update_post_meta( $post_id, 'brand_cache', $brands );
	}

	/**
	 * product save, get brand and type from family relationship and save to product
	 * save brand title to meta field for search
	 * trigger browse by information caches to refresh
	 */
	function _update_filter_data_product( $post_id ){
		$previous_family = get_post_meta( $post_id, 'previous_family', true );
		$family_id = get_post_meta( $post_id, 'family', true );
		$brand = get_post_meta( $family_id, 'brand', true );

		$type = get_post_meta( $family_id, 'type', true );
		update_post_meta( $post_id, 'brand', $brand );
		update_post_meta( $post_id, 'product_type', $type );

		$brand_object = get_post( $brand );
		update_post_meta( $post_id, 'brand_text', $brand_object->post_title );

		if( $previous_family != $family_id ){
			$this->_update_product_family_cache( $family_id );
			if( 0 != $previous_family ){
				$this->_update_product_family_cache( $previous_family );
			}
		}
	}

	/**
	* Handle a custom 'customvar' query var to get products with the 'customvar' meta.
	* https://github.com/woocommerce/woocommerce/wiki/wc_get_products-and-WC_Product_Query
	* @param array $query - Args for WP_Query.
	* @param array $query_vars - Query vars from WC_Product_Query.
	* @return array modified $query
	*/
	function _custom_wc_query_vars( $query, $query_vars ){

		if ( ! empty( $query_vars['product_family'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'family',
				'value' => esc_attr( $query_vars['product_family'] ),
			);
		}

		return $query;
	}

}

new ReliantHardwareFilters();
