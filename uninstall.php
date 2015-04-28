<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
	wp_die('WP_UNINSTALL_PLUGIN undefined.');
}

global $wpdb;

// Delete plugin options.
delete_option('WPSS_plugin_options');
// Delete all posts under posttype.
$wpdb->query("DELETE pt, pm FROM $wpdb->posts pt JOIN $wpdb->postmeta pm ON pm.post_id = pt.id WHERE pt.post_type = 'wpss_subscribers'");

