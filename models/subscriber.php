<?php

/**
 *
 * Process subscriber actions.
 *
 * @package Models
 * @subpackage Subscriber
 *
**/

// Namespace.
namespace WPSS\Models;

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Define class.
class Subscriber{

	/**
	 * validate_data
	 * NULLED.
	 *
	 * @access public
	 * @param $data  array  Post data from the form
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	public function validate_data($data){
		if(filter_var($data['emailaddress'], FILTER_VALIDATE_EMAIL)){
			// Email is valid. Check if user has been registered before.
			self::check_user($data);
		}
		else{
			// Output message.
			output(array(
				'type'     => 'error',
				'response' => WPSS_INVALID_EMAIL_ADDRESS,
				'field'    => 'wp_simple_subscriber[emailaddress]'
			));
		}
	}

	/**
	 * check_user
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	public function check_user($data){
		// Get all users.
		$users = get_posts(array(
			'posts_per_page' => -1,
			'post_type'      => WPSS_POST_TYPE_SLUG_1
		));
		// Add all email address to an array.
		$confirmed = array_map(function($user){
			return get_the_title($user->ID);
		}, $users);
		// Check array.
		if(!in_array($data['emailaddress'], $confirmed)){
			// User has not signed up before. Add new user..
			self::add_new_subscriber($data);
		}
		else{
			// Output message.
			output(array(
				'type'     => 'error',
				'response' => WPSS_DUPLICATED_EMAIL_ADDRESS,
				'field'    => 'wp_simple_subscriber[emailaddress]'
			));
		}
	}

	/**
	 * add_new_subscriber
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	public function add_new_subscriber($data){
		// Build data array.
		$post = array(
			'post_title'  => $data['emailaddress'],
			'post_type'   => WPSS_POST_TYPE_SLUG_1,
			'post_status' => 'publish'
		);
		// Insert post.
		$postID = wp_insert_post($post);
		// Check error.
        if(!is_wp_error($postID)){
        	// Update post meta.
			update_post_meta($postID, WPSS_META_PREFIX . 'firstname', (isset($data['firstname'])) ? $data['firstname'] : '-');
			update_post_meta($postID, WPSS_META_PREFIX . 'lastname', (isset($data['lastname'])) ? $data['lastname'] : '-');
			update_post_meta($postID, WPSS_META_PREFIX . 'status', 'Subscribed');
			update_post_meta($postID, WPSS_META_PREFIX . 'date', date('Y-m-d'));
			update_post_meta($postID, WPSS_META_PREFIX . 'ip_address', $_SERVER['REMOTE_ADDR']);
			// Output message.
			output(array(
				'type'     => 'success',
				'response' => WPSS_SUCCESSFULLY_ADDED,
				'field'    => 'wp_simple_subscriber[emailaddress]'
			));
        }
        else{
			// Output message.
			output(array(
				'type'     => 'error',
				'response' => WPSS_ERROR_ADDING,
				'field'    => 'wp_simple_subscriber[emailaddress]'
			));
        }
	}
}
