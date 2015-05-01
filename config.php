<?php

/**
 *
 * Configuration and settings.
 *
 * @package Index
 * @subpackage Config
 *
**/

// Prevent direct.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Plugin version.
define('WPSS_PLG_VERSION', '1.0.0');
// Plugin name.
define('WPSS_PLG_NAME', 'WP Simple Subscriber');
// Plugin directory.
define('WPSS_PATH', plugin_dir_path(__FILE__));
// Plugin URL.
define('WPSS_URL', plugin_dir_url(__FILE__));

// Plugin Options
define('WPSS_POST_TYPE_NAME_1', __('Subscribers', 'WPPN'));
define('WPSS_POST_TYPE_SING_1', __('Subscriber', 'WPPN'));
define('WPSS_POST_TYPE_SLUG_1', 'wpsssubscribers');

// Meta Prefix.
define('WPSS_META_PREFIX', '_WPSS_');

// 1. Load autoloader.
require_once(WPSS_PATH . 'autoloader.php');
// 2. Misc functions.
require_once(WPSS_PATH . 'functions/misc.php');
// 3. Load actions.
require_once(WPSS_PATH . 'functions/actions.php');
// 4. Load metaboxes.
require_once(WPSS_PATH . 'functions/metaboxes.php');
// 5. Load channels.
require_once(WPSS_PATH . 'functions/channels.php');
// 6. Load shortcode.
require_once(WPSS_PATH . 'functions/shortcode.php');

// Plugin specific options.
define('WPSS_CSV_DIR', '/wp-simple-subscriber-csv/');
// define('WPSS_INVALID_EMAIL_ADDRESS',
// 	(WPSS_cmb2('message_invalid_email_address', 'plugin_options')) ? WPSS_cmb2('message_invalid_email_address', 'plugin_options') : __('Email address isn\'t valid!', 'WPSS')
// );
// define('WPSS_DUPLICATED_EMAIL_ADDRESS',
// 	(WPSS_cmb2('message_duplicate_email_address', 'plugin_options')) ? WPSS_cmb2('message_duplicate_email_address', 'plugin_options') : __('Email address is already in our database!', 'WPSS')
// );
// define('WPSS_SUCCESSFULLY_ADDED',
// 	(WPSS_cmb2('message_successfully_added', 'plugin_options')) ? WPSS_cmb2('message_successfully_added', 'plugin_options') : __('Email successfully added to the database.', 'WPSS')
// );
// define('WPSS_ERROR_ADDING', __('There was an error adding this email to the database. Please try again.', 'WPSS'));
