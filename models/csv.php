<?php

/**
 *
 * Create CSV from post type data.
 *
 * @package Models
 * @subpackage CSV
 *
**/

// Namespace.
namespace WPSS\Models;

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Define class.
class CSV{

	/**
	 * init
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	public function init(){
		// Get the upload directory.
		$upload_dir = wp_upload_dir();
		// Set the CSV directory.
		$csv_dir = $upload_dir['path'] . WPSS_CSV_DIR;
		// Create a CSV directory.
		if(!file_exists($csv_dir)){
		    mkdir($csv_dir);
		}
		// Pick a filename and destination directory for the file.
		// Remember that the folder where you want to write the file has to be writable.
		$directory = get_bloginfo('name') . ' ' . WPSS_PLG_NAME . ' ' . date('Y-m-d H-i-s');
		$filename  = htmlspecialchars_decode($directory) . '.csv';
		$file      = $csv_dir . $filename;

		// Generate CSV file.
		self::generate($file, $filename);
	}

	/**
	 * generate
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
	public function generate($file, $filename){
		// Get the upload directory.
		$upload_dir = wp_upload_dir();
		// Actually create the file. The w+ parameter will wipe out and overwrite any existing file with the same name.
		$handle = fopen($file, 'w+');
		// Write the spreadsheet column titles / labels.
		fputcsv($handle,
			array(
				__('ID', 'WPSS'),
				__('Email Address', 'WPSS'),
				__('First Name', 'WPSS'),
				__('Last Name', 'WPSS'),
				__('Status', 'WPSS'),
				__('Signup Date', 'WPSS'),
				__('IP Address', 'WPSS')
			)
		);
		// Get all users.
		$users = get_posts(array(
			'posts_per_page' => -1,
			'post_type'      => WPSS_POST_TYPE_SLUG_1
		));
		foreach($users as $row){
			fputcsv($handle,
				array(
					$row->ID,
					$row->post_title,
					get_post_meta($row->ID, WPSS_META_PREFIX . 'first_name', true),
					get_post_meta($row->ID, WPSS_META_PREFIX . 'last_name', true),
					get_post_meta($row->ID, WPSS_META_PREFIX . 'status', true),
					get_post_meta($row->ID, WPSS_META_PREFIX . 'date', true),
					get_post_meta($row->ID, WPSS_META_PREFIX . 'ip_address', true)
				)
			);
		}
		// Finish writing the file
		fclose($handle);
		// Force download of file
		$csv_url = $upload_dir['url'] . WPSS_CSV_DIR . $filename;
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		readfile($csv_url);

		exit();
	}
}
