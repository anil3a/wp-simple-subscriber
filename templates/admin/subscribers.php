<?php

/**
 *
 * subscribers.php
 *
 * @package Templates
 * @subpackage Subscribers
 *
**/

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}

// Empty class var for alternate table row styling.
$class = '';

?>
<div class="wrap">
    <div class="vecom--header">
        <h2><?php _e('Subscribers', 'vecom'); ?></h2>
    </div>

    <form action="<?php echo admin_url(); ?>" method="post">
        <div class="tablenav top">
            <div class="alignleft actions">
                <select name="adverts_action" id="cat" class="postform">
                    <option value=""><?php _e('Bulk Actions', 'vecom'); ?></option>
                    <option value="deactivate"><?php _e('Deactivate', 'vecom'); ?></option>
                    <option value="delete"><?php _e('Delete', 'vecom'); ?></option>
                </select>
                <input type="submit" name="" class="button-secondary" value="<?php _e('Go', 'vecom'); ?>">
            </div>
            <br class="clear">
        </div>

        <table class="wp-list-table widefat fixed posts" style="margin-top: .5em">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column"><input type="checkbox"></th>
                    <th><?php _e('Column #1', 'vecom'); ?></th>
                    <th><?php _e('Column #2', 'vecom'); ?></th>
                    <th><?php _e('Column #3', 'vecom'); ?></th>
                    <th><?php _e('Column #4', 'vecom'); ?></th>
                    <th><?php _e('Column #5', 'vecom'); ?></th>
                    <th><?php _e('Column #6', 'vecom'); ?></th>
                </tr>
            </thead>
            <tbody>
                    <?php $class = ($class != 'alternate') ? 'alternate' : ''; ?>
                    <tr class="<?php echo $class; ?>">
                        <th class="check-column"><input type="checkbox" name="row[]"></th>
                        <td>Value #1</td>
                        <td>Value #2</td>
                        <td>Value #3</td>
                        <td>Value #4</td>
                        <td>Value #5</td>
                        <td>Value #6</td>
                    </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="manage-column column-cb check-column"><input type="checkbox"></th>
                    <th><?php _e('Column #1', 'vecom'); ?></th>
                    <th><?php _e('Column #2', 'vecom'); ?></th>
                    <th><?php _e('Column #3', 'vecom'); ?></th>
                    <th><?php _e('Column #4', 'vecom'); ?></th>
                    <th><?php _e('Column #5', 'vecom'); ?></th>
                    <th><?php _e('Column #6', 'vecom'); ?></th>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
