<?php

/**
 *
 * options_plugin.php
 *
 * @package Templates
 * @subpackage Options :: Plugin
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

global $metabox;

foreach($metabox as $k=>$v){
	if(strpos($k, 'WPSS_plugin_options') !== false){
		cmb2_metabox_form(WPSS_CMB2_PREFIX . $k, $k);
	}
}
