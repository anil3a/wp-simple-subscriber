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

?>
<div class="wrap">
    <h2><?php printf(__('%s Options', 'WPSS'), WPSS_PLG_NAME); ?></h2>
    <form class="WPSS_plugin_options_form" action="" method="post">
        <button class="button-primary"><?php _e('Save Options', 'WPSS'); ?></button>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="postbox-container-1" class="postbox-container">
                </div>
                <div id="postbox-container-2" class="postbox-container">
                    <?php do_meta_boxes(WPSS_META_BOX_OPTIONS_1, 'normal', null); ?>
                </div>
            </div>
        </div>
    </form>
</div>
