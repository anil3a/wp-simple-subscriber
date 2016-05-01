<?php

/**
 *
 * Listens for requests and calls appropriate model.
 *
 * @package Controllers
 * @subpackage Request
 *
**/

// Namespace.
namespace WPSS\Controllers;

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Define class.
class Request{

	/**
	 * __construct
	 * Constructor for this class.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.2.0
	**/
	public function __construct(){
		// Check index is set and passes nonce.
		if( (isset($_POST['wp_simple_subscriber']) && isset($_POST['wp_simple_subscriber_nonce'])) && wp_verify_nonce($_POST['wp_simple_subscriber_nonce'], 'do_forms') ){
		    // Init Subscriber.
		    $subscriber = new \WPSS\Models\Subscriber;
			// Send for validation.
			$subscriber->validate_data($_POST['wp_simple_subscriber']);
		}

		if(isset($_GET['plugin_action']) && $_GET['plugin_action'] == 'generate_csv'){
		    // Init CSV.
		    $csv = new \WPSS\Models\CSV;
		    $csv->init();
		}
	}
}
