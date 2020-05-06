<?php

add_action('graphql_register_types', function () {
    register_graphql_field('Page', 'pageTemplate', [
        'type' => 'String',
        'description' => 'WordPress Page Template',
        'resolve' => function ($page) {
            return get_page_template_slug($page->pageId);
        },
    ]);
});

/**
 * Add graphql query to get 301 redirects based on uri and plugin WP Simple 301 Redirects
 */
add_action( 'graphql_register_types', 'register_redirects_type' );
function register_redirects_type() {
	register_graphql_field( 'RootQuery', 'getRedirect', [
		'type' => 'String',
		'description' => __( 'Get a redirect', 'reliant' ),
		'args'        => [
			'uri' => [
				'type'        => 'String',
				'description' => sprintf( __( 'Get the redirect by its uri', 'reliant' ) ),
			],
		],
		'resolve' => function( $source, $args, $context, $info ) {
			$ret = '';
			$request = $args['uri'];
			$clean_request = rtrim( $args['uri'], '/' );
			$redirects = (array)get_option( '301_redirects' );

			//quick checks based on exact match
			if( isset( $redirects[ $request ] ) ){
				$ret = $redirects[ $request ];
			}elseif( isset( $redirects[ $clean_request ] ) ){
				$ret = $redirects[ $clean_request ];
			}else{
				//wildcard checks - loosely based on same logic in plugin file
				$wildcard = get_option( '301_redirects_wildcard' );
				if( $wildcard ){
					foreach( $redirects as $match => $redirect ){
						$match = str_replace('*','(.*)',$match);
						$pattern = '/^' . str_replace( '/', '\/', rtrim( $match, '/' ) ) . '/';
						$redirect = str_replace('*','$1',$redirect);
						$output = preg_replace($pattern, $redirect, $clean_request);
						if ( $output !== $clean_request && $output !== $request ) {
							// pattern matched, perform redirect
							$ret = $output;
						}
					}
				}
			}

			return $ret;
		},
	] );
}
