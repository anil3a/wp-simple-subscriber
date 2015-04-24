<?php

/**
 *
 * Manages WordPress posttypes.
 *
 * @package Controllers
 * @subpackage Posttypes
 *
**/

// Namespace.
namespace WPSS\Controllers;

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Define class.
class Posttypes{

	public static $posttypes;

    /**
     * __construct
     * Constructor for this class.
     *
     * @access public
     * @param null
     * @return null
     * @since 1.0.0
     * @version 1.0.0
    **/
    public function __construct($posttypes){
        $this->posttypes = $posttypes;

        add_action('init', array(&$this, 'register'));
        add_filter('post_updated_messages', array(&$this, 'messages'));
        add_action('contextual_help', array(&$this, 'contextual_help'), 10, 3);
    }

	/**
	 * register
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
    public function register(){
        foreach($this->posttypes as $pt){
            $name      = $pt['name'];
            $singular  = $pt['singular'];
            $post_name = strtolower($name);

            $labels = array(
                'name'               => _x(sprintf('%s', $name) , 'Post type general name', 'wpss'),
                'singular_name'      => _x(sprintf('%s', $singular) . ' Item', 'Post type singular name', 'wpss'),
                'add_new'            => _x('Add New ', sprintf('%s', $name) . ' Item', 'wpss'),
                'add_new_item'       => sprintf(__('Add New %s', 'wpss'), $singular),
                'edit_item'          => sprintf(__('Edit %s', 'wpss'), $singular),
                'new_item'           => sprintf(__('New %s', 'wpss'), $singular),
                'all_items'          => sprintf(__('%s', 'wpss'), $singular),
                'view_item'          => sprintf(__('View %s Item', 'wpss'), $name),
                'search_items'       => sprintf(__('Search %s Items', 'wpss'), $singular),
                'not_found'          => sprintf(__('No %s Items found', 'wpss'), $singular),
                'not_found_in_trash' => sprintf(__('No %s Items found in Trash', 'wpss'), $singular),
                'parent_item_colon'  => '',
                'menu_name'          => (isset($pt['label'])) ? $pt['label'] : $name
            );

            $pt['args']['labels'] = $labels;

            register_post_type($post_name, $pt['args']);
        }
    }

	/**
	 * messages
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
    public function messages($messages){
        global $post, $post_ID, $name, $singular, $post_name;

        $messages[$name . ' Item'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf(__($name . ' Item updated. <a href="%s">View ' . $name . ' Item</a>'), esc_url(get_permalink($post_ID))),
            2  => __('Field updated.', 'wpss'),
            3  => __('Field deleted.', 'wpss'),
            4  => __($singular . ' updated.'),
            5  => isset($_GET['revision']) ? sprintf(__($name . ' Item restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false )) : false,
            6  => sprintf(__($name . ' Item published. <a href="%s">View ' . $name . ' Item</a>'), esc_url(get_permalink($post_ID))),
            7  => __($name . ' Item saved.'),
            8  => sprintf(__($name . ' Item submitted. <a target="_blank" href="%s">Preview ' . $name . ' Item</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            9  => sprintf(__($name . ' Item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . $name . ' Item</a>'),
            date_i18n( __('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
            10 => sprintf(__($name . ' Item draft updated. <a target="_blank" href="%s">Preview ' . $name . ' Item</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))))
        );

        return $messages;
    }

	/**
	 * contextual_help
	 * NULLED.
	 *
	 * @access public
	 * @param null
	 * @return null
	 * @since 1.0.0
	 * @version 1.0.0
	**/
    public function contextual_help($contextual_help, $screen_id, $screen){
        global $name;

        if($name . ' Item' == $screen->id){
            $contextual_help =
            '<p><strong>' . __('For more information:', 'wpss') . '</strong></p>' .
            '<p><a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">' . __('Edit Posts Documentation', 'wpss') . '</a></p>' .
            '<p><a href="http://wordpress.org/support/" target="_blank">' . __('Support Forums', 'wpss') . '</a></p>';
        }
        elseif('edit-' . $name . ' Item' == $screen->id){
            $contextual_help = null;
        }

        return $contextual_help;
    }
}
