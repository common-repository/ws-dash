<?php
/*
Plugin Name: KDC Dash
Plugin Author: deZine.Ninja (_KDC-Labs)
Description: KRAZY DEVIL CREATIONZ - Always a part of eYou : A client dashboard for KDC clients.
Version: 2.0
Plugin URI: https://kdc.in/
Donate link: https://rzp.io/l/kdclabs
Tags: KDC, customization, icon, font icon
Requires at least: 3.5.1
Tested up to: 5.0.1
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/ 
defined('ABSPATH') or die("No script kiddies please!");

// changing the login page URL
function ws_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url('<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/kdc-login-logo.png?ver=20181219');
            background-image: none, url('<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/kdc-login-logo.svg?ver=20181219');
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'ws_login_logo' );

// changing the login page URL
function ws_login_logo_url() {
    return 'https://kdc.in/';
}
add_filter( 'login_headerurl', 'ws_login_logo_url' );

// changing the login page URL hover text
function ws_login_logo_url_title() {
    return 'webWorks by: KDC';
}
add_filter( 'login_headertitle', 'ws_login_logo_url_title' );

// add login message
function ws_login_message() {
    return '<center style="padding: 0 10px;">KDC :. Assist<br/><strong><a href="tel:1800222532?call">1800 222 532</strong> | <a hre="mailto:assist@kdc.in">assist@kdc.in</a></center>';
}
add_filter( 'login_message', 'ws_login_message' );

// replace wp_generator
remove_action( 'wp_head', 'wp_generator' );
function ws_generator_meta_tag() { 
   echo '<meta name="generator" content="KDC : WP-'. get_bloginfo ( 'version' ) . '" />' . "\n";
}
add_action( 'wp_head', 'ws_generator_meta_tag' );

/*
 * WP ToolBar
 */

// function to run before menu is rendered
add_action( 'wp_before_admin_bar_render', 'preload_ws_toolbar' );

// function to add items to toolbar
add_action( 'admin_bar_menu', 'ws_toolbar' );

// remove WordPress items from toolbar
function preload_ws_toolbar() {
  global $wp_admin_bar;
  
  // remove WordPress logo
  $wp_admin_bar->remove_node('wp-logo');

}
 
// add custom items to toolbar
function ws_toolbar() {
  global $wp_admin_bar;
  
  // add a single link
  $wp_admin_bar->add_node( array(
  	    'id' => 'ws-site',
  	    'title' => '<img src="' . plugins_url( '', __FILE__ ) . '/assets/img/kdc-tb-icon.png?ver=20181219" />',
  	    'href' => 'https://kdc.in/',
  	    'meta' => array( 'target' => '_kdc', 'title' => 'KRAZY DEVIL CREATIONZ' )
      ) );
      
  // add a item to the right of the menu using parent: top-secondary
  $wp_admin_bar->add_node( array(
  	    'id' => 'ws-assist',
  	    'title' => 'KDC :. Support',
  	    'href' => 'https://kdc.support/',
  	    'meta' => array( 'target' => '_blank' ),		
    	'parent' => 'ws-site'
      ) );
  
  // add a sub-item
  $wp_admin_bar->add_node( array(
  	    'id' => 'ws-email',
  	    'title' => 'eMail: assist@kdc.in',
  	    'href' => 'mailto:assist@kdc.in',
  	    'parent' => 'ws-assist'
      ) );
  
  // add another sub-item
  $wp_admin_bar->add_node( array(
  	    'id' => 'ws-tf',
  	    'title' => 'IN: 1800 222 532',
  	    'href' => 'tel:1800222532?call',
  	    'meta' => array( 'title' => 'TollFree - INDIA' ),
  	    'parent' => 'ws-assist'
      ) );
  
  // add another sub-item
  $wp_admin_bar->add_node( array(
  	    'id' => 'ws-call',
  	    'title' => 'US: +1 (442) 333-4532',
  	    'meta' => array( 'title' => 'United States of America' ),
  	    'href' => 'tel:+14423334532?call',
  	    'parent' => 'ws-assist'
      ) );
}
// WP ToolBar //

/*
 * WS Dash site.js
 */
function ws_dash_site() {
	wp_register_script( 'ws_dash_site_js', 'https://s3.ap-south-1.amazonaws.com/kdc-wp-dash/site.js', false, '2.0', true );
	wp_enqueue_script( 'ws_dash_site_js' );
}
add_action( 'wp_enqueue_scripts', 'ws_dash_site' );
// WS Dash //

/*
 * WP ICON
 */
function ws_icon() {
  load_plugin_textdomain( 'ws_icon' );
}
add_action( 'init', 'ws_icon' );

function ws_icon_style() {
  wp_register_style( 'wsicon-css', plugins_url( '', __FILE__ ) . '/assets/css/ws-icon.css', array() );
}
add_action( 'wp_enqueue_scripts', 'ws_icon_style' );

function ws_icon_shortcode( $atts ) {
  wp_enqueue_style( 'wsicon-css' );
  extract( shortcode_atts( array( 'icon' => 'ws' ), $atts ) );
  return '<span class="ws-'.str_replace( 'ws-', '', $icon ).'"></span>';
}

add_shortcode( 'wsicon', 'ws_icon_shortcode' );
add_filter( 'wp_nav_menu_items', 'do_shortcode' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_title', 'do_shortcode' );

function wsicon_add_shortcode_to_title( $title ){
  return do_shortcode( $title );
}
add_filter( 'the_title', 'wsicon_add_shortcode_to_title' );
// WP ICON //