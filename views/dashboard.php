<?php

/**
 *
 * Fetches template applies data.
 *
 * @package Views
 * @subpackage Dashboard
 *
**/

// Namespace.
namespace WPSS\Views;

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Define class.
class Dashboard{

	/**
	 * fetch
	 * Returns correct template and applies data.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	public static function fetch($page){
		// Set template.
		$template = self::template($page);
		// Get view.
		if($template){
			include($template);
		}
	}

	/**
	 * template
	 * Returns the correct template.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	private static function template($page){
		// Get file.
		$page = str_replace('WPSS_', '', $page);
		// Set tpl.
		$tpl = WPSS_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $page . '.php';
		// Check for template.
		if(file_exists($tpl)){
			// Set template.
			return $tpl;
		}

		return false;
	}
}
