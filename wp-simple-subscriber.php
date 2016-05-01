<?php

/*
Plugin Name: WP Simple Subscriber
Plugin URI: https://github.com/sdellow/wp-simple-subscriber
Version: 1.2.0
Description: Allows you to collect subscribers via a simple form (in a shortcode) or your own custom form. This plugin only collects data it does not  provide a way to send out newsletters. All data is exportable as a CSV file for use with mainstream services like Campaign Monitor, Dot Mailer etc.
Author: Stew Dellow
Contributor: Anil Prajapati
Author URI: https://hellostew.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: wpss
*/

// Prevent direct access.
if(!defined('WPINC')){die;}

/**
 * init_wp_simple_subscribers
 * Init the plugin.
 *
 * @param null
 * @return null
 * @since 1.2.0
 * @version 1.0.0
**/
function init_wp_simple_subscribers(){
    // Do version checks.
    vc_wp_simple_subscribers('WP Simple Subscriber', 'wp-simple-subscriber');
    // Require config.
    require_once(plugin_dir_path(__FILE__) . 'config.php');
    // Init Dashboard.
    new \WPSS\Controllers\Dashboard;
}
add_action('plugins_loaded', 'init_wp_simple_subscribers');

/**
 * listen_wp_simple_subscribers
 * Listens to form post.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function listen_wp_simple_subscribers(){
    // Init Request.
    new \WPSS\Controllers\Request;
}
add_action('wp', 'listen_wp_simple_subscribers');

/**
 * vc_wp_simple_subscribers
 * Do version checks.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function vc_wp_simple_subscribers($name, $slug){
    // Version variables
    $required_php_version = '5.5'; $required_wp_version = '3.8';

    // Version checks
    if(version_compare(PHP_VERSION, $required_php_version, '<') || version_compare(get_bloginfo('version'), $required_wp_version, '<')){
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        deactivate_plugins(basename(__FILE__), true);

        if(isset($_GET['action']) && ($_GET['action'] == 'activate' || $_GET['action'] == 'error_scrape') && $_GET['plugin'] == $slug){
            die(__($name . ' requires PHP version ' . $required_php_version . ' or greater and WordPress ' . $required_wp_version . ' or greater.'));
        }
    }
}

/**
 * Activation
 *
 * @since 1.0.0
 * @version 1.0.0
**/
if(file_exists(plugin_dir_path(__FILE__) . 'controllers/activation.php')){
    require_once(plugin_dir_path(__FILE__) . 'controllers/activation.php');
    register_activation_hook(__FILE__, array('WPSS_Activation', 'activate'));
}
