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
    wp_localize_script('wpss-js', 'WPSS_add_assets_vars', array('pluginurl' => plugin_dir_url(__FILE__)));

    // JavaScript.

    // CSS.
    wp_enqueue_style('jquery-ui-1.11.4', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', false);
    wp_enqueue_style('wpss-css-dashboard', WPSS_URL . 'templates/admin/dist/css/dashboard.css', false);
    wp_enqueue_style('wpss-css-metabox', WPSS_URL . 'templates/admin/dist/css/metabox.css');
}
add_action('admin_enqueue_scripts', 'WPSS_add_assets', 999);
