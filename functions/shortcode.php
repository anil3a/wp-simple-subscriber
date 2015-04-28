<?php

/**
 *
 * WordPress plugin shortcode.
 *
 * @package Functions
 * @subpackage Shortcode
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

/**
 * WPSS_shortcode
 * NULLED
 *
 * @param null
 * @return null
 * @since 1.0.0
 * @version 1.0.0
**/
function WPSS_shortcode($atts, $content = null){
	// Extract shortcode parameters.
    $atts = shortcode_atts(array(
		'names'                 => false,
		'classes'               => '',
		'button'                => __('Sign Up', 'wpss'),
		'firstname_placeholder' => __('Enter your first name', 'wpss'),
		'lastname_placeholder'  => __('Enter your last name', 'wpss'),
		'email_placeholder'     => __('Enter email address', 'wpss')
	), $atts, 'wp_simple_subscriber');
	?>
	<!-- Newsletter -->
	<form class="wpss--form <?php echo $atts['classes']; ?>" action="<?php the_permalink(); ?>" method="post">
		<?php if($atts['names']) : ?>
			<input type="text" name="wp_simple_subscriber[first_name]" id="wpss__first_name" placeholder="<?php echo $atts['firstname_placeholder']; ?>" aria-required="true" required>
			<input type="text" name="wp_simple_subscriber[last_name]" id="wpss__last_name" placeholder="<?php echo $atts['lastname_placeholder']; ?>" aria-required="true" required>
		<?php endif; ?>
		<input type="email" name="wp_simple_subscriber[emailaddress]" id="wpss__emailaddress" placeholder="<?php echo $atts['email_placeholder']; ?>" aria-required="true" required>
		<!-- Nonce -->
		<?php wp_nonce_field('do_forms', 'wp_simple_subscriber_nonce'); ?>
		<button type="submit"><?php echo $atts['button']; ?></button>
	</form>
<?php }
add_shortcode('wp_simple_subscriber', 'WPSS_shortcode');
