<?php

/**
 *
 * WordPress metaboxes.
 *
 * @package Functions
 * @subpackage Metaboxes
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}


/**
 * WPSS_option
 * Retrieves custom plugin data. Will return either custom post meta or meta from options page.
 *
 * Post META: WPSS_option('status', 56);
 * Options META: WPSS_option('message_1', 'WPSS_plugin_options');
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_option($key, $ID = false){
	// Post Meta.
	if($ID){
		return (get_post_meta($ID, WPSS_META_PREFIX . $key)) ? get_post_meta($ID, WPSS_META_PREFIX . $key, true) : false;
	}
	// WordPress Option.
	else{
		return get_option(WPSS_META_PREFIX . $key);
	}
}


/**
 * add_metabox_classes
 * Add classes to meta boxes.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function add_metabox_classes($classes){
    $classes[] = 'WPSS_post_box';

    return $classes;
}
add_filter('postbox_classes_wpsssubscribers_WPSS_subscriber_additional_details', 'add_metabox_classes');


/**
 * WPSS_create_channel_metaboxes
 * Add meta boxes.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_create_channel_metaboxes(){
    add_meta_box(
    	'WPSS_subscriber_additional_details', // Unique ID.
    	__('Subscriber Additional Details', 'WPSS'), // Title of meta box.
    	'WPSS_subscriber_additional_details_content', // Callback for metabox content.
    	WPSS_POST_TYPE_SLUG_1, // Page / Custom Post Type.
    	'normal', // Context.
    	'default' // Priority.
    );
}


/**
 * WPSS_subscriber_additional_details_content
 * Displays meta box content.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_subscriber_additional_details_content(){
	global $post;

	?>
	<div class="field cf">
		<div class="field field--left">
			<label for="WPSS_subscriber_firstname"><?php _e('First Name', 'WPSS'); ?></label>
			<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[firstname]" id="WPSS_subscriber_firstname" class="widefat" value="<?php echo get_post_meta($post->ID, WPSS_META_PREFIX . 'firstname', true); ?>">
		</div>
		<div class="field field--right">
			<label for="WPSS_subscriber_lastname"><?php _e('Last Name', 'WPSS'); ?></label>
			<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[lastname]" id="WPSS_subscriber_lastname" class="widefat" value="<?php echo get_post_meta($post->ID, WPSS_META_PREFIX . 'lastname', true); ?>">
		</div>
	</div>
	<div class="field cf">
		<div class="field field--left">
			<label for="WPSS_subscriber_status"><?php _e('Status', 'WPSS'); ?></label>
			<?php $status = get_post_meta($post->ID, WPSS_META_PREFIX . 'status', true); ?>
			<select name="<?php echo WPSS_META_PREFIX; ?>[status]" id="WPSS_subscriber_status" class="widefat">
				<option value="Subscribed" <?php echo ($status == 'Subscribed') ? 'selected="selected"' : null; ?>><?php _e('Subscribed', 'WPSS'); ?></option>
				<option value="Unsubscribed" <?php echo ($status == 'Unsubscribed') ? 'selected="selected"' : null; ?>><?php _e('Unsubscribed', 'WPSS'); ?></option>
			</select>
		</div>
		<div class="field field--right">
			<label for="WPSS_subscriber_date"><?php _e('Date Signed Up', 'WPSS'); ?></label>
			<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[date]" id="WPSS_subscriber_date" class="widefat js-datepicker" value="<?php echo get_post_meta($post->ID, WPSS_META_PREFIX . 'date', true); ?>">
		</div>
	</div>
	<!-- Nonce -->
	<input type="hidden" name="<?php echo WPSS_META_PREFIX; ?>[nonce]" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>">
<?php }


/**
 * WPSS_subscriber_additional_details_save
 * Saves meta box content.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_subscriber_additional_details_save($post_id, $post){
	// Check index.
	if(!isset($_POST[WPSS_META_PREFIX])){return;}
	// Check nonce.
	if(!wp_verify_nonce($_POST[WPSS_META_PREFIX]['nonce'], plugin_basename(__FILE__))){return $post->ID;}
	// Check user capability.
	if(!current_user_can('edit_post', $post->ID)){return $post->ID;}
	// Check this is not a revision or autosave.
	if(wp_is_post_revision($post->ID) && wp_is_post_autosave($post->ID)){return;}

	// Loop through all meta.
	foreach($_POST[WPSS_META_PREFIX] as $k=>$v){
		// Create or update post meta.
		update_post_meta($post->ID, WPSS_META_PREFIX . $k, $v);
		// If no value delete.
        if(!$v){
        	delete_post_meta($post->ID, WPSS_META_PREFIX . $k);
        }
	}
}
add_action('save_post', 'WPSS_subscriber_additional_details_save', 1, 2);


/**
 * WPSS_create_options_metaboxes
 * Add meta boxes.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_create_options_metaboxes(){
    add_meta_box(
    	'WPSS_subscriber_plugin_options', // Unique ID.
    	__('Plugin Settings', 'WPSS'), // Title of meta box.
    	'WPSS_subscriber_plugin_options_content', // Callback for metabox content.
    	WPSS_META_BOX_OPTIONS_1, // Page / Custom Post Type.
    	'normal', // Context.
    	'default' // Priority.
    );
}
add_action('admin_init', 'WPSS_create_options_metaboxes');


/**
 * WPSS_subscriber_plugin_options_content
 * Displays meta box content.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_subscriber_plugin_options_content(){
	global $post;

	?>
	<div class="field">
		<label for="WPSS_subscriber_options_invalid_email_address"><?php _e('Invalid email address message', 'WPSS'); ?></label>
		<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[invalid_email_address]" id="WPSS_subscriber_options_invalid_email_address" class="widefat" value="<?php echo get_option(WPSS_META_PREFIX . 'invalid_email_address'); ?>">
	</div>
	<div class="field">
		<label for="WPSS_subscriber_options_duplicate_email_address"><?php _e('Duplicate email address message', 'WPSS'); ?></label>
		<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[duplicate_email_address]" id="WPSS_subscriber_options_duplicate_email_address" class="widefat" value="<?php echo get_option(WPSS_META_PREFIX . 'duplicate_email_address'); ?>">
	</div>
	<div class="field">
		<label for="WPSS_subscriber_options_successfully_added"><?php _e('Successfully added to database message', 'WPSS'); ?></label>
		<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[successfully_added]" id="WPSS_subscriber_options_successfully_added" class="widefat" value="<?php echo get_option(WPSS_META_PREFIX . 'successfully_added'); ?>">
	</div>
	<div class="field">
		<label for="WPSS_subscriber_options_error_added"><?php _e('Can\'t add to databse error message', 'WPSS'); ?></label>
		<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[error_added]" id="WPSS_subscriber_options_error_added" class="widefat" value="<?php echo get_option(WPSS_META_PREFIX . 'error_added'); ?>">
	</div>
	<!-- Nonce -->
	<input type="hidden" name="<?php echo WPSS_META_PREFIX; ?>[nonce]" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>">
<?php }


/**
 * WPSS_subscriber_options_save
 * Saves meta box content.
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_subscriber_options_save(){
	// Check index.
	if(!isset($_POST[WPSS_META_PREFIX])){return;}
	// Check nonce.
	if(!wp_verify_nonce($_POST[WPSS_META_PREFIX]['nonce'], plugin_basename(__FILE__))){return $post->ID;}

	// Loop through all meta.
	foreach($_POST[WPSS_META_PREFIX] as $k=>$v){
		// Create or update option.
		update_option(WPSS_META_PREFIX . $k, $v);
		// If no value delete.
        if(!$v){
        	delete_option(WPSS_META_PREFIX . $k);
        }
	}
}
WPSS_subscriber_options_save();
