<?php 

// Avoid direct access to this piece of code
if (!function_exists('current_user_can') || !current_user_can('manage_options')){
  header('Location: /');
  exit;
}

function subscribe_reloaded_update_option( $_option, $_value, $_type ){
	if (!isset($_value)) return true;
	switch($_type){
		case 'yesno':
			if ($_value=='yes' || $_value=='no'){
				update_option($_option, $_value);
				return true;
			}
			
			break;
		case 'integer':
			update_option($_option, abs(intval($_value)));
			
			return true;
			break;
			
		default:
			update_option($_option, str_replace('"', "'", $_value));
			return true;
			break;
	}
	
	return false;
}

function subscribe_reloaded_get_option($_option, $_default){
	$value = get_option($_option, $_default);
	return stripslashes($value);
}

// Load localization files
load_plugin_textdomain('subscribe-reloaded', WP_PLUGIN_DIR .'/subscribe-to-comments-reloaded/langs', '/subscribe-to-comments-reloaded/langs');

// Update options
if (isset($_POST['options'])){

	$faulty_fields = '';
	if (isset($_POST['options']['manager_page']) && !subscribe_reloaded_update_option('subscribe_reloaded_manager_page', $_POST['options']['manager_page'], 'text')) $faulty_fields = __('Management Page ID','subscribe-reloaded').', ';
	if (isset($_POST['options']['purge_days']) && !subscribe_reloaded_update_option('subscribe_reloaded_purge_days', $_POST['options']['purge_days'], 'integer')) $faulty_fields = __('Autopurge requests','subscribe-reloaded').', ';
	if (isset($_POST['options']['from_name']) && !subscribe_reloaded_update_option('subscribe_reloaded_from_name', $_POST['options']['from_name'], 'text')) $faulty_fields = __('Sender name','subscribe-reloaded').', ';
	if (isset($_POST['options']['from_email']) && !subscribe_reloaded_update_option('subscribe_reloaded_from_email', $_POST['options']['from_email'], 'text')) $faulty_fields = __('Sender email address','subscribe-reloaded').', ';
	if (isset($_POST['options']['notification_subject']) && !subscribe_reloaded_update_option('subscribe_reloaded_notification_subject', $_POST['options']['notification_subject'], 'text')) $faulty_fields = __('Notification subject','subscribe-reloaded').', ';
	if (isset($_POST['options']['notification_content']) && !subscribe_reloaded_update_option('subscribe_reloaded_notification_content', $_POST['options']['notification_content'], 'text')) $faulty_fields = __('Notification message','subscribe-reloaded').', ';
	if (isset($_POST['options']['checkbox_label']) && !subscribe_reloaded_update_option('subscribe_reloaded_checkbox_label', $_POST['options']['checkbox_label'], 'text')) $faulty_fields = __('Checkbox label','subscribe-reloaded').', ';
	if (isset($_POST['options']['checked_by_default']) && !subscribe_reloaded_update_option('subscribe_reloaded_checked_by_default', $_POST['options']['checked_by_default'], 'yesno')) $faulty_fields = __('Checked by default','subscribe-reloaded').', ';
	if (isset($_POST['options']['subscribed_label']) && !subscribe_reloaded_update_option('subscribe_reloaded_subscribed_label', $_POST['options']['subscribed_label'], 'text')) $faulty_fields = __('Subscribed label','subscribe-reloaded').', ';
	if (isset($_POST['options']['subscribed_waiting_label']) && !subscribe_reloaded_update_option('subscribe_reloaded_subscribed_waiting_label', $_POST['options']['subscribed_waiting_label'], 'text')) $faulty_fields = __('Awaiting label','subscribe-reloaded').', ';
	if (isset($_POST['options']['author_label']) && !subscribe_reloaded_update_option('subscribe_reloaded_author_label', $_POST['options']['author_label'], 'text')) $faulty_fields = __('Author label','subscribe-reloaded').', ';
	if (isset($_POST['options']['enable_double_check']) && !subscribe_reloaded_update_option('subscribe_reloaded_enable_double_check', $_POST['options']['enable_double_check'], 'yesno')) $faulty_fields = __('Enable double check','subscribe-reloaded').', ';
	if (isset($_POST['options']['double_check_subject']) && !subscribe_reloaded_update_option('subscribe_reloaded_double_check_subject', $_POST['options']['double_check_subject'], 'text')) $faulty_fields = __('Double check subject','subscribe-reloaded').', ';
	if (isset($_POST['options']['double_check_content']) && !subscribe_reloaded_update_option('subscribe_reloaded_double_check_content', $_POST['options']['double_check_content'], 'text')) $faulty_fields = __('Double check message','subscribe-reloaded').', ';
	if (isset($_POST['options']['management_subject']) && !subscribe_reloaded_update_option('subscribe_reloaded_management_subject', $_POST['options']['management_subject'], 'text')) $faulty_fields = __('Management subject','subscribe-reloaded').', ';
	if (isset($_POST['options']['management_content']) && !subscribe_reloaded_update_option('subscribe_reloaded_management_content', $_POST['options']['management_content'], 'text')) $faulty_fields = __('Management message','subscribe-reloaded').', ';

	// Display an alert in the admin interface if something went wrong
	echo '<div class="updated fade"><p>';
	if (empty($faulty_fields)){
		if (empty($message_to_show)){
			_e('Your settings have been successfully updated.','subscribe-reloaded');
		}
		else{
			echo $message_to_show;
		}
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
$subscribe_reloaded_options['notification_subject'] = subscribe_reloaded_get_option('subscribe_reloaded_notification_subject', 'There is a new comment on the post [post_title]');
$subscribe_reloaded_options['notification_content'] = subscribe_reloaded_get_option('subscribe_reloaded_notification_content', '');
$subscribe_reloaded_options['checkbox_label'] = subscribe_reloaded_get_option('subscribe_reloaded_checkbox_label', "Notify me of followup comments via e-mail. You can also <a href='[subscribe_link]'>subscribe</a> without commenting.");
$subscribe_reloaded_options['checked_by_default'] = subscribe_reloaded_get_option('subscribe_reloaded_checked_by_default', 'no');
$subscribe_reloaded_options['subscribed_label'] = subscribe_reloaded_get_option('subscribe_reloaded_subscribed_label', "You are subscribed to this entry. <a href='[manager_link]'>Manage your subscriptions</a>.");
$subscribe_reloaded_options['subscribed_waiting_label'] = subscribe_reloaded_get_option('subscribe_reloaded_subscribed_waiting_label', "Your subscription to this entry needs to be confirmed. <a href='[manager_link]'>Manage your subscriptions</a>.");
$subscribe_reloaded_options['author_label'] = subscribe_reloaded_get_option('subscribe_reloaded_author_label', "You are the author of this entry. <a href='[manager_link]'>Manage its subscriptions</a>.");
$subscribe_reloaded_options['enable_double_check'] = subscribe_reloaded_get_option('subscribe_reloaded_enable_double_check', 'no');
$subscribe_reloaded_options['double_check_subject'] = subscribe_reloaded_get_option('subscribe_reloaded_double_check_subject', 'Please confirm your subscribtion to [post_title]');
$subscribe_reloaded_options['double_check_content'] = subscribe_reloaded_get_option('subscribe_reloaded_double_check_content', '');
$subscribe_reloaded_options['management_subject'] = subscribe_reloaded_get_option('subscribe_reloaded_management_subject', 'Manage your subscriptions on [blog_name]');
$subscribe_reloaded_options['management_content'] = subscribe_reloaded_get_option('subscribe_reloaded_management_content', '');

?>

<div class="wrap">
<h2><?php _e('Subscribe to Comments', 'subscribe-reloaded') ?></h2>
<form action="<?php echo get_permalink(get_option('subscribe_reloaded_manager_page', '')) ?>" method="post" id="change_post_id">
<fieldset>
<p><?php _e('If you know the ID of a post you want to manage, enter it in the field here below to see a list of email addresses subscribed to it','subscribe-reloaded') ?> 
<br/><input type="text" size="10" name="srp" value="" /> <input type="submit" class="subscribe-form-button" value="<?php _e('Search post ID','subscribe-reloaded') ?>" /></p>
</fieldset>
</form>
<form action="<?php echo get_permalink(get_option('subscribe_reloaded_manager_page', '')) ?>" method="post" id="filter_email_address">
<fieldset>
<p><?php _e('If you know the email address you want to manage, enter it in the field here below to see a list of articles this reader is currently watching','subscribe-reloaded') ?> 
<br/><input type="text" size="40" name="sre" value="" /> <input type="submit" class="subscribe-form-button" value="<?php _e('Search email','subscribe-reloaded') ?>" /></p>
</fieldset>
</form>
<hr/>
<h3><?php _e('Options', 'subscribe-reloaded') ?></h3>
<form action="admin.php?page=subscribe-to-comments-reloaded/options.php" method="post">
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
		<th scope="row" rowspan="2"><label for="notification_subject"><?php _e('Notification subject','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[notification_subject]" id="notification_subject" value="<?php echo $subscribe_reloaded_options['notification_subject']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Subject of the notification email. Allowed tag: [post_title]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="notification_content"><?php _e('Notification message','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><textarea name="options[notification_content]" id="notification_content" cols="70" rows="5"><?php echo $subscribe_reloaded_options['notification_content']; ?></textarea></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Content of the notification email. Allowed tags: [post_title], [comment_permalink], [comment_author], [comment_content], [post_permalink], [manager_link]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="checkbox_label"><?php _e('Checkbox label','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[checkbox_label]" id="checkbox_label" value="<?php echo $subscribe_reloaded_options['checkbox_label']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Text to show next to the checkbox added to the comment form. Allowed tag: [subscribe_link]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="checked_by_default"><?php _e('Checked by default','subscribe-reloaded') ?></label></th>
		<td>
			<input type="radio" name="options[checked_by_default]" id="checked_by_default" value="yes"<?php echo ($subscribe_reloaded_options['checked_by_default'] == 'yes')?' checked="checked"':''; ?>> <?php _e('Yes','subscribe-reloaded') ?> &nbsp; &nbsp; &nbsp;
			<input type="radio" name="options[checked_by_default]" value="no" <?php echo ($subscribe_reloaded_options['checked_by_default'] == 'no')?'  checked="checked"':''; ?>> <?php _e('No','subscribe-reloaded') ?>
		</td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Send a notification email to confirm the subscription (to avoid addresses misuse)','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="subscribed_label"><?php _e('Subscribed label','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[subscribed_label]" id="subscribed_label" value="<?php echo $subscribe_reloaded_options['subscribed_label']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Text to show to those users who are already subscribed. Allowed tag: [manager_link]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="subscribed_waiting_label"><?php _e('Awaiting label','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[subscribed_waiting_label]" id="subscribed_waiting_label" value="<?php echo $subscribe_reloaded_options['subscribed_waiting_label']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e("Text to show to those users who are already subscribed, but haven't clicked on the confirmation link yet. Allowed tag: [manager_link]",'subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="author_label"><?php _e('Author label','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[author_label]" id="author_label" value="<?php echo $subscribe_reloaded_options['author_label']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Text to show to the author, to manage subscriptions to a given post. Allowed tag: [manager_link]','subscribe-reloaded'); ?></td>
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
		<th scope="row" rowspan="2"><label for="double_check_subject"><?php _e('Double check subject','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[double_check_subject]" id="double_check_subject" value="<?php echo $subscribe_reloaded_options['double_check_subject']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Subject of the confirmation email. Allowed tag: [post_title]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="double_check_content"><?php _e('Double check message','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><textarea name="options[double_check_content]" id="double_check_content" cols="70" rows="5"><?php echo $subscribe_reloaded_options['double_check_content']; ?></textarea></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Content of the confirmation email. Allowed tags: [post_permalink], [confirm_link], [post_title], [manager_link]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="management_subject"><?php _e('Management subject','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><input type="text" name="options[management_subject]" id="management_subject" value="<?php echo $subscribe_reloaded_options['management_subject']; ?>" size="70"></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Subject of the mail sent to those who request to access their management page. Allowed tag: [blog_name]','subscribe-reloaded'); ?></td>
	</tr>
	
	<tr valign="top">
		<th scope="row" rowspan="2"><label for="management_content"><?php _e('Management message','subscribe-reloaded') ?></label></th>
		<td style="padding-bottom:0"><textarea name="options[management_content]" id="management_content" cols="70" rows="5"><?php echo $subscribe_reloaded_options['management_content']; ?></textarea></td>
	</tr>
	<tr>
		<td class="description" style="padding-top:0"><?php _e('Content of the management email. Allowed tags: [blog_name], [manager_link]','subscribe-reloaded'); ?></td>
	</tr>
</tbody>
</table>
<p class="submit"><input type="submit" value="<?php _e('Save Changes') ?>" class="button-primary" name="Submit"></p>
</form>
</div>
