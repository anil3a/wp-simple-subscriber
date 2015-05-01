<?php

/**
 *
 * WordPress channels.
 *
 * @package Functions
 * @subpackage Channels
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

/**
 * WPSS_create_channels
 * Creates the post types.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_create_channels(){
	$args = array(
		array(
			'name'     => WPSS_POST_TYPE_NAME_1,
			'singular' => WPSS_POST_TYPE_SING_1,
			'label'    => WPSS_PLG_NAME,
			'slug'     => WPSS_POST_TYPE_SLUG_1,
			'args'     => array(
				'query_var'            => strtolower(WPSS_POST_TYPE_SLUG_1), // Sets the query_var key for this post type
				'public'               => false, // Controls how the type is visible to authors
				'publicly_queryable'   => false, // Whether queries can be performed on the front end as part of parse_request()
				'show_ui'              => true, // Whether to generate a default UI for managing this post type in the admin
				'show_in_menu'         => true, // Where to show the post type in the admin menu. show_ui must be true
				'show_in_nav_menus'    => false, // Whether post_type is available for selection in navigation menus
				'show_in_admin_bar'    => false, // Whether to make this post type available in the WordPress admin bar
				'exclude_from_search'  => true, // Whether to exclude posts with this post type from front end search results
				'capability_type'      => 'post', // The string to use to build the read, edit, and delete capabilities
				'has_archive'          => false, // Enables post type archives. Will use $post_type as archive slug by default
				'hierarchical'         => false, // Whether the post type is hierarchical (e.g. page)
				'menu_position'        => 99, // The position in the menu order the post type should appear. show_in_menu must be true
				'can_export'           => true, // Can this post_type be exported
				'capabilities'         => array(), // An array of the capabilities for this post type
				'supports'             => array('title'), // An alias for calling add_post_type_support() directly
				'taxonomies'           => array(), // An array of registered taxonomies like category or post_tag that will be used with this post type
				'register_meta_box_cb' => 'WPSS_create_channel_metaboxes' // Provide a callback function that will be called when setting up the meta boxes for the edit form
			)
		)
	);
	new \WPSS\Controllers\Posttypes($args);
}
add_action('after_setup_theme', 'WPSS_create_channels');

/**
 * WPSS_cpt_wpsssubscribers_set_column_headers
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cpt_wpsssubscribers_set_column_headers($columns){
	$columns = array(
		'cb'       => '<input type="checkbox">',
		'title'    => __('Email Address', 'WPSS'),
		'name'     => __('Name', 'WPSS'),
		'status'   => __('Status', 'WPSS'),
		'signedup' => __('Date', 'WPSS'),
		'ip'       => __('IP Address', 'WPSS')
	);

    return $columns;
}
add_filter('manage_wpsssubscribers_posts_columns', 'WPSS_cpt_wpsssubscribers_set_column_headers');

/**
 * WPSS_cpt_wpsssubscribers_sortable
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cpt_wpsssubscribers_sortable($columns){
	$columns = array(
		'title'    => 'title',
		'name'     => 'name',
		'status'   => 'status',
		'signedup' => 'signedup',
		'ip'       => 'ip'
	);

	return $columns;
}
add_filter('manage_edit-wpsssubscribers_sortable_columns', 'WPSS_cpt_wpsssubscribers_sortable');

/**
 * WPSS_cpt_wpsssubscribers_set_column_data
 * NULLED.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_cpt_wpsssubscribers_set_column_data($column, $post_id){
	switch($column){
		case 'name' :
			$first = (WPSS_option('firstname', $post_id)) ? WPSS_option('firstname', $post_id) : '';
			$last  = (WPSS_option('lastname', $post_id)) ? ' ' . WPSS_option('lastname', $post_id) : '';
			echo $first . $last;
		break;
		case 'status' :
			echo WPSS_option('status', $post_id);
		break;
		case 'signedup' :
			echo date('d M Y', strtotime(WPSS_option('date', $post_id)));
		break;
		case 'ip' :
			echo get_post_meta($post_id, WPSS_META_PREFIX . 'ip_address', true);
		break;
	}
}
add_action('manage_wpsssubscribers_posts_custom_column', 'WPSS_cpt_wpsssubscribers_set_column_data', 10, 2);

/**
 * WPSS_add_export_button
 * NULLED
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_add_export_button($views){
    $views['my-button'] = '<a href="' . admin_url('edit.php?post_type=subscribers&plugin_action=generate_csv') . '" class="button-primary" style="margin:5px" onclick="return confirm(\'This will generate and download a CSV. Would you like to proceed?\')">' . __('Export as CSV', 'WPSS') . '</a>';

    return $views;
}
add_filter('views_edit-wpsssubscribers', 'WPSS_add_export_button');
