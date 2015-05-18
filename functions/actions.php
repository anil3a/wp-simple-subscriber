<?php

/**
 *
 * WordPress action hooks.
 *
 * @package Functions
 * @subpackage Action Hooks
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

/**
 * WPSS_add_assets
 * NULLED
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_add_assets(){
    // JavaScript.
    wp_enqueue_script('WPSS-jquery-ui-1.11.4', WPSS_URL . 'templates/admin/dist/js/jquery-ui.js', false);
    wp_enqueue_script('WPSS-js-dashboard', WPSS_URL . 'templates/admin/dist/js/dashboard.js', false);

    // CSS.
    wp_enqueue_style('WPSS-jquery-ui-1.11.4', WPSS_URL . 'templates/admin/dist/css/jquery-ui.css', false);
    wp_enqueue_style('WPSS-css-dashboard', WPSS_URL . 'templates/admin/dist/css/dashboard.css', false);
    wp_enqueue_style('WPSS-css-metabox', WPSS_URL . 'templates/admin/dist/css/metabox.css');

    // Localize.
    wp_localize_script('WPSS-js-dashboard', 'dashboard', array(
		'pluginurl'   => plugin_dir_url(__FILE__),
		'date_format' => get_option('date_format')
    ));
}
add_action('admin_enqueue_scripts', 'WPSS_add_assets', 999);
