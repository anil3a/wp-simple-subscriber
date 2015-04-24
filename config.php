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
// CMB2 Prefix.
define('WPSS_CMB2_PREFIX', '_wpss_cmb2_');

// 1. Load autoloader.
require_once(WPSS_PATH . 'autoloader.php');
// 2. Misc functions.
require_once(WPSS_PATH . 'functions/misc.php');
// 3. Load actions.
require_once(WPSS_PATH . 'functions/actions.php');
// 4. Load channels.
require_once(WPSS_PATH . 'functions/channels.php');
// 5. Load shortcode.
require_once(WPSS_PATH . 'functions/shortcode.php');
// 6. Load CMB2 actions & filters.
require_once(WPSS_PATH . 'functions/cmb2.php');

// Plugin specific options.
define('WPSS_CSV_DIR', '/wp-simple-subscriber-csv/');
define('WPSS_INVALID_EMAIL_ADDRESS',
	(cmb2_wpss('message_invalid_email_address', 'plugin_options')) ? cmb2_wpss('message_invalid_email_address', 'plugin_options') : __('Email address isn\'t valid!', 'wpss')
);
define('WPSS_DUPLICATED_EMAIL_ADDRESS',
	(cmb2_wpss('message_duplicate_email_address', 'plugin_options')) ? cmb2_wpss('message_duplicate_email_address', 'plugin_options') : __('Email address is already in our database!', 'wpss')
);
define('WPSS_SUCCESSFULLY_ADDED',
	(cmb2_wpss('message_successfully_added', 'plugin_options')) ? cmb2_wpss('message_successfully_added', 'plugin_options') : __('Email successfully added to the database.', 'wpss')
);
define('WPSS_ERROR_ADDING', __('There was an error adding this email to the database. Please try again.', 'wpss'));
