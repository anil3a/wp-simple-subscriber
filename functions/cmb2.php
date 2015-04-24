<?php

/**
 *
 * Handles anything and everything to do with CMB2.
 *
 * @package Functions
 * @subpackage CMB2
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

if(file_exists(WPSS_PATH . 'vendors/cmb2/init.php')){
	require_once WPSS_PATH . 'vendors/cmb2/init.php';
}

/**
 * cmb2
 * Retrieves custom CMB2 data. Will return either custom post meta or meta from options page.
 *
 * Post META: cmb2_wpss('status', 56);
 * Options META: cmb2_wpss('message_1', 'plugin_options');
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function cmb2_wpss($key, $ID = null){
	if(!is_numeric($ID)){
		$options = get_option($ID);
		return (isset($options[WPSS_CMB2_PREFIX . $key])) ? $options[WPSS_CMB2_PREFIX . $key] : false;
	}
	elseif(is_numeric($ID)){
		return (get_post_meta($ID, WPSS_CMB2_PREFIX . $key)) ? get_post_meta($ID, WPSS_CMB2_PREFIX . $key, true) : false;
	}
}

/**
 * cmb2_wpss_product_metaboxes
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function cmb2_wpss_product_metaboxes(){
	global $metabox;

	// Posttype Options.
	$metabox['posttype_options'] = new_cmb2_box(array(
		'id'           => WPSS_CMB2_PREFIX . 'product_details',
		'title'        => __('Subscriber Additional Details', 'wpss'),
		'object_types' => array('subscribers'),
		'context'      => 'normal',
		'priority'     => 'default',
		'cmb_styles'   => false
	));

	// Posttype Options :: First Name
	$metabox['posttype_options']->add_field(array(
		'name' => __('First Name', 'wpss'),
		'desc' => __('', 'wpss'),
		'id'   => WPSS_CMB2_PREFIX . 'first_name',
		'type' => 'text'
	));
	// Posttype Options :: Last Name
	$metabox['posttype_options']->add_field(array(
		'name' => __('Last Name', 'wpss'),
		'desc' => __('', 'wpss'),
		'id'   => WPSS_CMB2_PREFIX . 'last_name',
		'type' => 'text'
	));
	// Posttype Options :: Last Name
	$metabox['posttype_options']->add_field(array(
		'name'    => __('Status', 'wpss'),
		'desc'    => __('', 'wpss'),
		'id'      => WPSS_CMB2_PREFIX . 'status',
		'type'    => 'select',
		'options' => array(
			'Subscribed' => __('Subscribed', 'wpss'),
			'Unsubscribed' => __('Unsubscribed', 'wpss')
		)
	));
	// Posttype Options :: Last Name
	$metabox['posttype_options']->add_field(array(
		'name' => __('Date Signed Up', 'wpss'),
		'desc' => __('', 'wpss'),
		'id'   => WPSS_CMB2_PREFIX . 'date',
		'type' => 'text_date'
	));


	/* ---------------------------------------------------------------- */


	// Plugin Options
	$metabox['plugin_options'] = new_cmb2_box(array(
		'name'    => __('Option Section Title', 'wpss'),
		'id'      => WPSS_CMB2_PREFIX . 'plugin_options',
		'hookup'  => false,
		'show_on' => array(
			'key'   => 'options-page',
			'value' => array('plugin_options')
		),
	));

	// Plugin Options :: Test
	$metabox['plugin_options']->add_field(array(
		'name' => __('Message: Invalid email address', 'wpss'),
		'desc' => __('', 'wpss'),
		'id'   => WPSS_CMB2_PREFIX . 'message_invalid_email_address',
		'type' => 'text'
	));
	$metabox['plugin_options']->add_field(array(
		'name' => __('Message: Duplicate email address', 'wpss'),
		'desc' => __('', 'wpss'),
		'id'   => WPSS_CMB2_PREFIX . 'message_duplicate_email_address',
		'type' => 'text'
	));
	$metabox['plugin_options']->add_field(array(
		'name' => __('Message: Successfully added to database', 'wpss'),
		'desc' => __('', 'wpss'),
		'id'   => WPSS_CMB2_PREFIX . 'message_successfully_added',
		'type' => 'text'
	));
}
add_action('cmb2_init', 'cmb2_wpss_product_metaboxes');

/**
 * cmb2_wpss_localise_date_format
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function cmb2_wpss_localise_date_format($l10n){
    $l10n['defaults']['date_picker']['dateFormat'] = 'dd-mm-yy';

    return $l10n;
}
add_filter('cmb2_localized_data', 'cmb2_wpss_localise_date_format');

/**
 * cmb2_wpss_options_layout
 * Modifies the result of cmb2_metabox_form().
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function cmb2_wpss_options_layout($form_format, $object_id, $cmb){
	global $metabox;

	foreach($metabox as $k=>$v){
		if(strpos($k, 'plugin_options') !== false){
			$layouts[] = $k;
		}
	}

    if(in_array($object_id, $layouts)){
    	$name = (!empty($cmb->meta_box['name'])) ? $cmb->meta_box['name'] : ucwords(str_replace('_', ' ', $object_id));
    	$form_format  = '';
    	$form_format .= '<div class="wrap metabox-wrap cf">';
    	$form_format .= '<h2>' . $name . '</h2>';
        $form_format .= '<form class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<div class="submit-wrap"><input type="submit" name="submit-cmb" value="' . __('Save Options', 'vecom') . '" class="button-primary"></div></form>';
    	$form_format .= '</div>';
    }

    return $form_format;
}
add_filter('cmb2_get_metabox_form_format', 'cmb2_wpss_options_layout', 10, 3);
