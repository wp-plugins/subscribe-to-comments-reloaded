<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
	header('Location: /');
	exit;
}

// Update options
if (isset($_POST['options'])){
	$faulty_fields = '';
	if (isset($_POST['options']['manager_page']) && !subscribe_reloaded_update_option('subscribe_reloaded_manager_page', $_POST['options']['manager_page'], 'text')) $faulty_fields = __('Management Page ID','subscribe-reloaded').', ';
	if (isset($_POST['options']['purge_days']) && !subscribe_reloaded_update_option('subscribe_reloaded_purge_days', $_POST['options']['purge_days'], 'integer')) $faulty_fields = __('Autopurge requests','subscribe-reloaded').', ';
	if (isset($_POST['options']['from_name']) && !subscribe_reloaded_update_option('subscribe_reloaded_from_name', $_POST['options']['from_name'], 'text')) $faulty_fields = __('Sender name','subscribe-reloaded').', ';
	if (isset($_POST['options']['from_email']) && !subscribe_reloaded_update_option('subscribe_reloaded_from_email', $_POST['options']['from_email'], 'text')) $faulty_fields = __('Sender email address','subscribe-reloaded').', ';
	if (isset($_POST['options']['checked_by_default']) && !subscribe_reloaded_update_option('subscribe_reloaded_checked_by_default', $_POST['options']['checked_by_default'], 'yesno')) $faulty_fields = __('Checked by default','subscribe-reloaded').', ';	
	if (isset($_POST['options']['enable_double_check']) && !subscribe_reloaded_update_option('subscribe_reloaded_enable_double_check', $_POST['options']['enable_double_check'], 'yesno')) $faulty_fields = __('Enable double check','subscribe-reloaded').', ';
	if (isset($_POST['options']['notify_authors']) && !subscribe_reloaded_update_option('subscribe_reloaded_notify_authors', $_POST['options']['notify_authors'], 'yesno')) $faulty_fields = __('Notify authors','subscribe-reloaded').', ';	

	// Display an alert in the admin interface if something went wrong
	echo '<div class="updated fade"><p>';
	if (empty($faulty_fields)){
			_e('Your settings have been successfully updated.','subscribe-reloaded');
	}
	else{
		_e('There was an error updating the following fields:','subscribe-reloaded');
		echo ' <strong>'.substr($faulty_fields,0,-2).'</strong>';
	}
	echo "</p></div>\n";
}

// Reload the new options
$subscribe_reloaded_options = array();
$subscribe_reloaded_options['manager_page'] = subscribe_reloaded_get_option('subscribe_reloaded_manager_page', '');
$subscribe_reloaded_options['purge_days'] = subscribe_reloaded_get_option('subscribe_reloaded_purge_days', '0');
$subscribe_reloaded_options['from_name'] = subscribe_reloaded_get_option('subscribe_reloaded_from_name', 'admin');
$subscribe_reloaded_options['from_email'] = subscribe_reloaded_get_option('subscribe_reloaded_from_email', get_bloginfo('admin_email'));
$subscribe_reloaded_options['checked_by_default'] = subscribe_reloaded_get_option('subscribe_reloaded_checked_by_default', 'no');
$subscribe_reloaded_options['enable_double_check'] = subscribe_reloaded_get_option('subscribe_reloaded_enable_double_check', 'no');
$subscribe_reloaded_options['notify_authors'] = subscribe_reloaded_get_option('subscribe_reloaded_notify_authors', 'no');
?>
<form action="admin.php?page=subscribe-to-comments-reloaded/options/index.php&subscribepanel=<?php echo $current_panel ?>" method="post">
<table class="form-table <?php echo $wp_locale->text_direction ?>">
<tbody>
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="manager_page"><?php _e('Management Page ID','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[manager_page]" id="manager_page" value="<?php echo $subscribe_reloaded_options['manager_page']; ?>" size="10"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('The ID (<strong>not the permalink!</strong>) of the management page you created.','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="purge_days"><?php _e('Autopurge requests','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[purge_days]" id="purge_days" value="<?php echo $subscribe_reloaded_options['purge_days']; ?>" size="10"> days</td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e("Delete pending subscriptions (not confirmed) after X days. Zero disables this feature.",'subscribe-reloaded'); ?></td>
	</tr>

	<tr valign="top">
		<th scope="row" rowspan="2"><label for="from_name"><?php _e('Sender name','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[from_name]" id="from_name" value="<?php echo $subscribe_reloaded_options['from_name']; ?>" size="50"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Name to use for the "from" field when sending a new notification to the user.','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="from_email"><?php _e('Sender email address','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[from_email]" id="from_email" value="<?php echo $subscribe_reloaded_options['from_email']; ?>" size="50"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Email address to use for the "from" field when sending a new notification to the user.','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="checked_by_default"><?php _e('Checked by default','subscribe-reloaded') ?></label></th>
		<td>
			<input type="radio" name="options[checked_by_default]" id="checked_by_default" value="yes"<?php echo ($subscribe_reloaded_options['checked_by_default'] == 'yes')?' checked="checked"':''; ?>> <?php _e('Yes','subscribe-reloaded') ?> &nbsp; &nbsp; &nbsp;
			<input type="radio" name="options[checked_by_default]" value="no" <?php echo ($subscribe_reloaded_options['checked_by_default'] == 'no')?'  checked="checked"':''; ?>> <?php _e('No','subscribe-reloaded') ?>
		</td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Decide if the checkbox should be checked by default or not.','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="enable_double_check"><?php _e('Enable double check','subscribe-reloaded') ?></label></th>
		<td>
			<input type="radio" name="options[enable_double_check]" id="enable_double_check" value="yes"<?php echo ($subscribe_reloaded_options['enable_double_check'] == 'yes')?' checked="checked"':''; ?>> <?php _e('Yes','subscribe-reloaded') ?> &nbsp; &nbsp; &nbsp;
			<input type="radio" name="options[enable_double_check]" value="no" <?php echo ($subscribe_reloaded_options['enable_double_check'] == 'no')?'  checked="checked"':''; ?>> <?php _e('No','subscribe-reloaded') ?>
		</td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Send a notification email to confirm the subscription (to avoid addresses misuse).','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="notify_authors"><?php _e('Notify authors','subscribe-reloaded') ?></label></th>
		<td>
			<input type="radio" name="options[notify_authors]" id="notify_authors" value="yes"<?php echo ($subscribe_reloaded_options['notify_authors'] == 'yes')?' checked="checked"':''; ?>> <?php _e('Yes','subscribe-reloaded') ?> &nbsp; &nbsp; &nbsp;
			<input type="radio" name="options[notify_authors]" value="no" <?php echo ($subscribe_reloaded_options['notify_authors'] == 'no')?'  checked="checked"':''; ?>> <?php _e('No','subscribe-reloaded') ?>
		</td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Notify authors when a new comment is posted to one of their articles.','subscribe-reloaded'); ?></td>
	</tr>
	</tbody>
</table>
<p class="submit"><input type="submit" value="<?php _e('Save Changes') ?>" class="button-primary" name="Submit"></p>
</form>
