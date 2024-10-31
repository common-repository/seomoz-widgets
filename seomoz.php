<?php
/*
Plugin Name: SEOmoz Linkscape Stats
Description: Adds SEO related widgets and dashboard elements, powered by the SEOmo Linkscape API.
Version: 1.0.9
Author: nstielau
Author URI: http://seomoz.org
*/

$plugin_dir = WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ) , "" , plugin_basename(__FILE__) );
set_include_path( $plugin_dir . 'lib' . PATH_SEPARATOR . get_include_path() );
include_once( 'seomoz.php' );

///////////////////////////////////////////////
// Constants
//////////////////////////////////////////////

if( !defined( 'SEOMOZ_CACHE_GROUP') )
	define( 'SEOMOZ_CACHE_GROUP', 'seomoz_cache' );


///////////////////////////////////////////////
// Widgets
//////////////////////////////////////////////

require( 'seomoz_widget_heart.php' );
require( 'seomoz_widget_links.php' );


///////////////////////////////////////////////
// Options
//////////////////////////////////////////////

$seomoz_options = array( 'access_id'=>'' , 'secret_key'=>'' );
add_option( 'seomoz_options' , $seomoz_options , '' , 'no' );


///////////////////////////////////////////////
// Dashboard Panel
//////////////////////////////////////////////

/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup' , 'seomoz_dashboard_setup' );

/**
 * add Dashboard Widget via function wp_add_dashboard_widget()
 */
function seomoz_dashboard_setup() {
	wp_add_dashboard_widget( 'seomoz_dashboard_inbound_links' , __( 'SEOmoz Inbound Links','seomoz' ) , 'seomoz_dashboard_inbound_links' , 'seomoz_dashboard_inbound_links_control');
	wp_add_dashboard_widget( 'seomoz_dashboard_top_pages' , __( 'SEOmoz Top Pages','seomoz' ) , 'seomoz_dashboard_top_pages' , 'seomoz_dashboard_top_pages_control');
}

/**
 * Content of Inbound Links Dashboard-Widget
 */
function seomoz_dashboard_inbound_links() {
	include( 'seomoz_dashboard_inbound_links.php' );
}

/**
 * The dashboard panel config callback for Inbound Links
 */
function seomoz_dashboard_inbound_links_control() {
	if ( !$widget_options = get_option( 'seomoz_options' ) )
		$widget_options = array();

	if ( !isset($widget_options['dashboard_inbound_links']) )
		$widget_options['dashboard_inbound_links'] = array();

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['dashboard_inbound_links']) ) {
		$number = (int) stripslashes($_POST['dashboard_inbound_links']['links']);
		if ( $number < 1 || $number > 100 )
			$number = 25;
		$widget_options['dashboard_inbound_links']['links'] = $number;
		update_option( 'seomoz_options', $widget_options );
	}

	$number = isset( $widget_options['dashboard_inbound_links']['links'] ) ? (int) $widget_options['dashboard_inbound_links']['links'] : '';

	echo '<p><label for="links-number">' . __('Number of links to show:') . '</label>';
	echo '<input id="links-number" name="dashboard_inbound_links[links]" type="text" value="' . $number . '" size="3" /> <small>' . __( '(at most 100)' ) . '</small></p>';
}


/**
 * Content of Top Pages Dashboard-Widget
 */
function seomoz_dashboard_top_pages() {
	include( 'seomoz_dashboard_top_pages.php' );
}


/**
 * The dashboard panel config callback for Top Pages
 */
function seomoz_dashboard_top_pages_control() {
	if ( !$widget_options = get_option( 'seomoz_options' ) )
		$widget_options = array();

	if ( !isset($widget_options['dashboard_top_pages']) )
		$widget_options['dashboard_top_pages'] = array();

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['dashboard_top_pages']) ) {
		$number = (int) stripslashes($_POST['dashboard_top_pages']['pages']);
		if ( $number < 1 || $number > 100 )
			$number = 25;
		$widget_options['dashboard_top_pages']['pages'] = $number;
		update_option( 'seomoz_options', $widget_options );
	}

	$number = isset( $widget_options['dashboard_top_pages']['pages'] ) ? (int) $widget_options['dashboard_top_pages']['pages'] : '';

	echo '<p><label for="pages-number">' . __('Number of pages to show:') . '</label>';
	echo '<input id="pages-number" name="dashboard_top_pages[pages]" type="text" value="' . $number . '" size="3" /> <small>' . __( '(at most 100)' ) . '</small></p>';
}


///////////////////////////////////////////////
// Settings Page
//////////////////////////////////////////////

add_action( 'admin_menu' , 'seomoz_settings_menu' );

function seomoz_settings_menu() {
	// Add a menu item to the 'Settings' menu
	add_options_page( 'SEOmoz Options' , 'SEOmoz Widgets' , 'manage_options' , 'seomoz' , 'seomoz_options' );
}

function seomoz_options() {
	if ( !current_user_can( 'manage_options' ) )	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// Load the admin content
	include( 'seomoz_settings.php' );
}

?>