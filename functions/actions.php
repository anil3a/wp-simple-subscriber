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


// Added AJAX handler Jquery
function so_enqueue_scripts(){
  wp_register_script( 'ajaxHandle', WPSS_URL . 'templates/admin/dist/js/ajaxrequest.js', array(), false, true );
  wp_enqueue_script( 'ajaxHandle' );
  wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
  wp_enqueue_style('WPSS-css-frontend', WPSS_URL . 'templates/admin/dist/css/frontend.css');
}
add_action( 'wp_enqueue_scripts', 'so_enqueue_scripts' );

// Added Ajax handler backend
function so_wp_ajax_function(){

    $subscriber = new \WPSS\Models\Subscriber;

    $subscriber->validate_data( 
                    array( 
                        'first_name'    => $_POST['wpss_firstname'],
                        'last_name'     => $_POST['wpss_lastname'],
                        'emailaddress'  => $_POST['wpss_emailaddress']
                    )
                );
      
    wp_die(); // ajax call must die to avoid trailing 0 in your response
}
add_action( 'wp_ajax_wpss_subscribe_action', 'so_wp_ajax_function' );
add_action( 'wp_ajax_nopriv_wpss_subscribe_action', 'so_wp_ajax_function' );

