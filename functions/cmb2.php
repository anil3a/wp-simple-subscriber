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
 * Post META: WPSS_cmb2('status', 56);
 * Options META: WPSS_cmb2('message_1', 'WPSS_plugin_options');
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cmb2($key, $ID = null){
	if(!is_numeric($ID)){
		$options = get_option($ID);
		return (isset($options[WPSS_CMB2_PREFIX . $key])) ? $options[WPSS_CMB2_PREFIX . $key] : false;
	}
	elseif(is_numeric($ID)){
		return (get_post_meta($ID, WPSS_CMB2_PREFIX . $key)) ? get_post_meta($ID, WPSS_CMB2_PREFIX . $key, true) : false;
	}
}

/**
 * WPSS_cmb2_product_metaboxes
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cmb2_product_metaboxes(){
	global $metabox;

	// Posttype Options.
	$metabox['WPSS_posttype_options'] = new_cmb2_box(array(
		'id'           => WPSS_CMB2_PREFIX . 'product_details',
		'title'        => __('Subscriber Additional Details', 'WPSS'),
		'object_types' => array('WPSS_subscribers'),
		'context'      => 'normal',
		'priority'     => 'default',
		'cmb_styles'   => false
	));

	// Posttype Options :: First Name
	$metabox['WPSS_posttype_options']->add_field(array(
		'name' => __('First Name', 'WPSS'),
		'desc' => __('', 'WPSS'),
		'id'   => WPSS_CMB2_PREFIX . 'first_name',
		'type' => 'text'
	));
	// Posttype Options :: Last Name
	$metabox['WPSS_posttype_options']->add_field(array(
		'name' => __('Last Name', 'WPSS'),
		'desc' => __('', 'WPSS'),
		'id'   => WPSS_CMB2_PREFIX . 'last_name',
		'type' => 'text'
	));
	// Posttype Options :: Last Name
	$metabox['WPSS_posttype_options']->add_field(array(
		'name'    => __('Status', 'WPSS'),
		'desc'    => __('', 'WPSS'),
		'id'      => WPSS_CMB2_PREFIX . 'status',
		'type'    => 'select',
		'options' => array(
			'Subscribed'   => __('Subscribed', 'WPSS'),
			'Unsubscribed' => __('Unsubscribed', 'WPSS')
		)
	));
	// Posttype Options :: Last Name
	$metabox['WPSS_posttype_options']->add_field(array(
		'name' => __('Date Signed Up', 'WPSS'),
		'desc' => __('', 'WPSS'),
		'id'   => WPSS_CMB2_PREFIX . 'date',
		'type' => 'text_date'
	));


	/* ---------------------------------------------------------------- */


	// Plugin Options
	$metabox['WPSS_plugin_options'] = new_cmb2_box(array(
		'name'    => __('Option Section Title', 'WPSS'),
		'id'      => WPSS_CMB2_PREFIX . 'WPSS_plugin_options',
		'hookup'  => false,
		'show_on' => array(
			'key'   => 'options-page',
			'value' => array('WPSS_plugin_options')
		),
	));

	// Plugin Options :: Test
	$metabox['WPSS_plugin_options']->add_field(array(
		'name' => __('Message: Invalid email address', 'WPSS'),
		'desc' => __('', 'WPSS'),
		'id'   => WPSS_CMB2_PREFIX . 'message_invalid_email_address',
		'type' => 'text'
	));
	$metabox['WPSS_plugin_options']->add_field(array(
		'name' => __('Message: Duplicate email address', 'WPSS'),
		'desc' => __('', 'WPSS'),
		'id'   => WPSS_CMB2_PREFIX . 'message_duplicate_email_address',
		'type' => 'text'
	));
	$metabox['WPSS_plugin_options']->add_field(array(
		'name' => __('Message: Successfully added to database', 'WPSS'),
		'desc' => __('', 'WPSS'),
		'id'   => WPSS_CMB2_PREFIX . 'message_successfully_added',
		'type' => 'text'
	));
}
add_action('cmb2_init', 'WPSS_cmb2_product_metaboxes');

/**
 * WPSS_cmb2_localise_date_format
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cmb2_localise_date_format($l10n){
    $l10n['defaults']['date_picker']['dateFormat'] = 'dd-mm-yy';

    return $l10n;
}
add_filter('cmb2_localized_data', 'WPSS_cmb2_localise_date_format');

/**
 * WPSS_cmb2_options_layout
 * Modifies the result of cmb2_metabox_form().
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cmb2_options_layout($form_format, $object_id, $cmb){
	global $metabox;

	foreach($metabox as $k=>$v){
		if(strpos($k, 'WPSS_plugin_options') !== false){
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
add_filter('cmb2_get_metabox_form_format', 'WPSS_cmb2_options_layout', 10, 3);
