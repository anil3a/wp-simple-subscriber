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
 * Retrieves custom CMB2 data. Will return either custom post meta or meta from options page.
 *
 * Post META: WPSS_option('status', 56);
 * Options META: WPSS_option('message_1', 'WPSS_plugin_options');
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_option($key, $ID = null){
	if(!is_numeric($ID)){
		$options = get_option($ID);
		return (isset($options[WPSS_META_PREFIX . $key])) ? $options[WPSS_META_PREFIX . $key] : false;
	}
	elseif(is_numeric($ID)){
		return (get_post_meta($ID, WPSS_META_PREFIX . $key)) ? get_post_meta($ID, WPSS_META_PREFIX . $key, true) : false;
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
    $classes[] = 'wpss_post_box';

    return $classes;
}
add_filter('postbox_classes_wpsssubscribers_WPSS_subscriber_additional_details', 'add_metabox_classes');
// add_filter('postbox_classes_{POST TYPE SLUG}_{ID OF THE METABOX}', 'add_metabox_classes');

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
    	'wpsssubscribers', // Page / Custom Post Type.
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
			<label for="wpss_subscriber_firstname"><?php _e('First Name', 'WPSS'); ?></label>
			<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[firstname]" id="wpss_subscriber_firstname" class="widefat" value="<?php echo get_post_meta($post->ID,  WPSS_META_PREFIX . 'firstname', true); ?>">
		</div>
		<div class="field field--right">
			<label for="wpss_subscriber_lastname"><?php _e('Last Name', 'WPSS'); ?></label>
			<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[lastname]" id="wpss_subscriber_lastname" class="widefat" value="<?php echo get_post_meta($post->ID,  WPSS_META_PREFIX . 'lastname', true); ?>">
		</div>
	</div>
	<div class="field cf">
		<div class="field field--left">
			<label for="wpss_subscriber_status"><?php _e('Status', 'WPSS'); ?></label>
			<?php $status = get_post_meta($post->ID,  WPSS_META_PREFIX . 'status', true); ?>
			<select name="<?php echo WPSS_META_PREFIX; ?>[status]" id="wpss_subscriber_status" class="widefat">
				<option value="Subscribed" <?php echo ($status == 'Subscribed') ? 'selected="selected"' : null; ?>><?php _e('Subscribed', 'WPSS'); ?></option>
				<option value="Unsubscribed" <?php echo ($status == 'Unsubscribed') ? 'selected="selected"' : null; ?>><?php _e('Unsubscribed', 'WPSS'); ?></option>
			</select>
		</div>
		<div class="field field--right">
			<label for="wpss_subscriber_date"><?php _e('Date Signed Up', 'WPSS'); ?></label>
			<input type="text" name="<?php echo WPSS_META_PREFIX; ?>[date]" id="wpss_subscriber_date" class="widefat" value="<?php echo get_post_meta($post->ID,  WPSS_META_PREFIX . 'date', true); ?>">
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
        	delete_post_meta($post->ID,  WPSS_META_PREFIX . $k);
        }
	}
}
add_action('save_post', 'WPSS_subscriber_additional_details_save', 1, 2);
