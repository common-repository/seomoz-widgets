<?php
/**
 * Various functions used throughout the plugin.
 *
 */
// Returns to get the URL of the base directory of this plugin.
function seomoz_get_plugin_base_url() {
	return ( WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ) , "" , plugin_basename(__FILE__) ) );
}

// Returns an Authenticator used for authenticating different API calls.
function seomoz_get_authenticator() {
	$seomoz_options = get_option( 'seomoz_options' );
	$authenticator = new Authenticator();
	$authenticator->setAccessID( $seomoz_options['access_id'] );
	$authenticator->setSecretKey( $seomoz_options['secret_key'] );
	return $authenticator;
}

// Parses and returns the domain of the Wordpress blog.
function seomoz_get_wordpress_domain() {
	$url = get_bloginfo( 'wpurl' );
	return parse_url( $url , PHP_URL_HOST );
}

// Makes an API call to retrieve the inbound links to the specified domain.
// Uses the wp_cache* functions.
function seomoz_get_inbound_domain_links( $domain ) {
	$key = "inbound_domain_links_$domain";
	$inbound_links = wp_cache_get( $key , SEOMOZ_CACHE_GROUP );
	if ( false == $inbound_links ) {
		$linksService = new LinksService( seomoz_get_authenticator() );
		$inbound_links = $linksService->getLinks( $domain , LINKS_SCOPE_PAGE_TO_DOMAIN , null , LINKS_SORT_PAGE_AUTHORITY , LINKS_COL_URL , 0 , 100 );
		if ( false === $inbound_links ) {
			// API Error
			return false;
		}
		wp_cache_set( $key , $inbound_links , SEOMOZ_CACHE_GROUP );
	}
	return $inbound_links;
}

// Makes an API call to retrieve teh top pages for the specified subdomain.
// Uses the wp_cache* functions
function seomoz_get_top_pages( $subdomain ) {
	$key = "top_pages_$subdomain";
	$top_pages = wp_cache_get( $key , SEOMOZ_CACHE_GROUP );
	if ( false == $top_pages ) {
		$topPagesService = new TopPagesService( seomoz_get_authenticator() );
		$top_pages = $topPagesService->getTopPages( $subdomain , TOPPAGES_COL_URL + TOPPAGES_COL_TITLE , 0 , 100 );
		if ( false === $top_pages ) {
			// API Error
			return false;
		}
		wp_cache_set( $key , $top_pages , SEOMOZ_CACHE_GROUP );
	}
	return $top_pages;
}

function seomoz_test_auth($key, $secret) {
	$authenticator = new Authenticator();
	$authenticator->setAccessID( $key );
	$authenticator->setSecretKey( $secret );
	$linksService = new LinksService( $authenticator );
	$test_result = $linksService->getLinks( "seomoz.org" , LINKS_SCOPE_DOMAIN_TO_PAGE , null , LINKS_SORT_DOMAIN_AUTHORITY , LINKS_COL_URL , 0 , 1 );
	return $test_result != false;
}

// Returns the URL of the currently viewed page
function seomoz_current_url() {
	$pageURL = 'http';
	if ( $_SERVER["HTTPS"] == "on" ) { $pageURL .= "s"; }
	$pageURL .= "://";
	if ( $_SERVER["SERVER_PORT"] != "80" ) {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
?>