<?php

/**
 * Change yoast structured data site search url
 */
add_filter( 'wpseo_json_ld_search_url', 'reliant_search_url' );
function reliant_search_url( $content ){
	return home_url() . '/products?searchTerm={search_term_string}&submittedSearchText={search_term_string}';
}
