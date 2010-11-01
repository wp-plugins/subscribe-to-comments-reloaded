<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
	header('Location: /');
	exit;
}

// Reload the new options
$subscribe_reloaded_options = array();
$subscribe_reloaded_options['notification_subject'] = subscribe_reloaded_get_option('subscribe_reloaded_notification_subject', 'There is a new comment on the post [post_title]');
$subscribe_reloaded_options['notification_content'] = subscribe_reloaded_get_option('subscribe_reloaded_notification_content', '');
$subscribe_reloaded_options['checkbox_label'] = subscribe_reloaded_get_option('subscribe_reloaded_checkbox_label', "Notify me of followup comments via e-mail. You can also <a href='[subscribe_link]'>subscribe</a> without commenting.");
$subscribe_reloaded_options['subscribed_label'] = subscribe_reloaded_get_option('subscribe_reloaded_subscribed_label', "You are subscribed to this entry. <a href='[manager_link]'>Manage your subscriptions</a>.");
$subscribe_reloaded_options['subscribed_waiting_label'] = subscribe_reloaded_get_option('subscribe_reloaded_subscribed_waiting_label', "Your subscription to this entry needs to be confirmed. <a href='[manager_link]'>Manage your subscriptions</a>.");
$subscribe_reloaded_options['author_label'] = subscribe_reloaded_get_option('subscribe_reloaded_author_label', "You can <a href='[manager_link]'>manage the subscriptions</a> of this entry.");
$subscribe_reloaded_options['double_check_subject'] = subscribe_reloaded_get_option('subscribe_reloaded_double_check_subject', 'Please confirm your subscribtion to [post_title]');
$subscribe_reloaded_options['double_check_content'] = subscribe_reloaded_get_option('subscribe_reloaded_double_check_content', '');
$subscribe_reloaded_options['management_subject'] = subscribe_reloaded_get_option('subscribe_reloaded_management_subject', 'Manage your subscriptions on [blog_name]');
$subscribe_reloaded_options['management_content'] = subscribe_reloaded_get_option('subscribe_reloaded_management_content', '');

?>
<form action="admin.php?page=subscribe-to-comments-reloaded/options/index.php&subscribepanel=<?php echo $current_panel ?>" method="post">
<table class="form-table <?php echo $wp_locale->text_direction ?>">
<tbody>
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
