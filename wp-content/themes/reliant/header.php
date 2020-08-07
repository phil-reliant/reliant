<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package reliant
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
		window['_fs_debug'] = false;
		window['_fs_host'] = 'fullstory.com';
		window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
		window['_fs_org'] = '1KN6M';
		window['_fs_namespace'] = 'FS';
		(function(m,n,e,t,l,o,g,y){
			if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
			g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
			o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
			y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
			g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
			g.anonymize=function(){g.identify(!!0)};
			g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
			g.log = function(a,b){g("log",[a,b])};
			g.consent=function(a){g("consent",!arguments.length||a)};
			g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
			g.clearUserCookie=function(){};
			g._w={};y='XMLHttpRequest';g._w[y]=m[y];y='fetch';g._w[y]=m[y];
			if(m[y])m[y]=function(){return g._w[y].apply(this,arguments)};
			g._v="1.2.0";
		})(window,document,window['_fs_namespace'],'script','user');
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- Owlytica tags for Sitescout from mark@cowtan.net -->
<script type="text/javascript">var ssaUrl = 'https://' + 'pixel.sitescout.com/iap/ffb7b421e2f84bb0';new Image().src = ssaUrl; (function(d) { var syncUrl = 'https://' + 'pixel.sitescout.com/dmp/asyncPixelSync'; var iframe = d.createElement('iframe'); (iframe.frameElement || iframe).style.cssText = "width: 0; height: 0; border: 0;"; iframe.src = "javascript:false"; d.body.appendChild(iframe); var doc = iframe.contentWindow.document; doc.open().write('<body onload="window.location.href=\''+syncUrl+'\'">'); doc.close(); })(document); 
<?php if (is_page( array( 15258, 'Owlytica: IT Asset Maintenance Platform', 'owlytica'  ) ) ) : ?>
<script type="text/javascript">var ssaUrl = 'https://' + 'pixel.sitescout.com/iap/b0a1e3dc43f0d711';new Image().src = ssaUrl; (function(d) { var syncUrl = 'https://' + 'pixel.sitescout.com/dmp/asyncPixelSync'; var iframe = d.createElement('iframe'); (iframe.frameElement || iframe).style.cssText = "width: 0; height: 0; border: 0;"; iframe.src = "javascript:false"; d.body.appendChild(iframe); var doc = iframe.contentWindow.document; doc.open().write('<body onload="window.location.href=\''+syncUrl+'\'">'); doc.close(); })(document);
<?php endif; ?>
	 </script>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'reliant' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$reliant_description = get_bloginfo( 'description', 'display' );
			if ( $reliant_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $reliant_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'reliant' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
