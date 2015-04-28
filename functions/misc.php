<?php

/**
 *
 * Misc functions.
 *
 * @package Functions
 * @subpackage Misc
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

/**
 * output
 * Outputs based on environment.
 *
 * @since 1.0.0
 * @version 1.0.0
**/
if(!function_exists('output')){
    function output($input, $print = false){
        if(isset($_REQUEST['ajaxrequest'])){
            header('Content-Type: application/json');
            print json_encode($input);
            exit();
        }
        else{
        	if($print){
        		print_r($input);
        	}
        	else{
            	return $input;
        	}
        }
    }
}
