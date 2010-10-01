<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
  header('Location: /');
  exit;
}

// Load localization files
load_plugin_textdomain('subscribe-reloaded', WP_PLUGIN_DIR .'/subscribe-to-comments-reloaded/langs', '/subscribe-to-comments-reloaded/langs');
$wp_subscribe_reloaded = new wp_subscribe_reloaded();
global $wpdb;

$clean_post_id = !empty($_POST['srp'])?intval($_POST['srp']):(!empty($_GET['srp'])?intval($_GET['srp']):0);

if (!empty($_POST['email_list']) && !empty($_POST['action_type'])){
	$email_list = implode("','", $_POST['email_list']);
	switch($_POST['action_type']){
		case 'd':
			$wpdb->query("DELETE FROM $wp_subscribe_reloaded->table_subscriptions WHERE `email` IN ('$email_list') AND `post_ID` = '$clean_post_id'");
			break;
		case 's':
			$wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `status` = 'N' WHERE `email` IN ('$email_list') AND `post_ID` = '$clean_post_id'");
			break;
		case 'a':
			$wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `status` = 'Y' WHERE `email` IN ('$email_list') AND `post_ID` = '$clean_post_id'");
			break;
		default:
			break;
	}
	echo '<p>'.__("Subscriptions have been successfully updated. In order to cancel or suspend more notifications, select the corresponding checkbox(es) and click on the button at the end of the list.", 'subscribe-reloaded').'</p>';
}
else{
	echo '<p>'.__("You can manage the subscriptions to your articles on this page. In order to cancel or suspend one or more notifications, select the corresponding checkbox(es) and click on the button at the end of the list.", 'subscribe-reloaded').'</p>';
}

?>

<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="post" id="email_list_form"
	onsubmit="return confirm('<?php _e('Please remember: this operation cannot be undone. Are you sure you want to proceed?', 'subscribe-reloaded') ?>')">
<fieldset>
<?php
	$subscriptions = $wpdb->get_results("SELECT `email`, `status`, `dt` FROM $wp_subscribe_reloaded->table_subscriptions WHERE `post_ID` = '$clean_post_id' ORDER BY `dt` ASC, `email` ASC", OBJECT);
	if (is_array($subscriptions) && !empty($subscriptions)){
		$title = __('Title','subscribe-reloaded').': <strong>'.get_the_title($clean_post_id).'</strong>';
		echo "<p>$title</p>";
		echo '<p>'.__('Legend: Y: subscribed, N: suspended, C: awaiting confirmation','subscribe-reloaded').'</p>';
		echo '<ul>';
		foreach($subscriptions as $i => $a_subscription){
			$subscriber_salt = md5($wp_subscribe_reloaded->salt.$a_subscription->email);
			$manager_link = get_permalink(get_option('subscribe_reloaded_manager_page', ''));
			if (strpos($manager_link, '?') !== false)
				$manager_link = "$manager_link&sre=".urlencode($a_subscription->email)."&srk=$subscriber_salt";
			else
				$manager_link = "$manager_link?sre=".urlencode($a_subscription->email)."&srk=$subscriber_salt";
			echo "<li><input type='checkbox' name='email_list[]' value='{$a_subscription->email}' id='e_$i'/> <label for='e_$i'><a href='$manager_link'>$a_subscription->email</a> - $a_subscription->dt</label> [$a_subscription->status]</li>\n";
		}
		echo '</ul>';
		echo '<p>'.__('Action:','subscribe-reloaded').' <input type="radio" name="action_type" value="d" id="action_type_delete" /> <label for="action_type_delete">'.__('Delete','subscribe-reloaded').'</label> &nbsp;&nbsp;&nbsp;&nbsp; ';
		echo '<input type="radio" name="action_type" value="s" id="action_type_suspend" checked="checked" /> <label for="action_type_suspend">'.__('Suspend','subscribe-reloaded').'</label> &nbsp;&nbsp;&nbsp;&nbsp; ';
		echo '<input type="radio" name="action_type" value="a" id="action_type_activate" /> <label for="action_type_activate">'.__('Resume','subscribe-reloaded').'</label></p>';
		echo '<p><input type="button" class="subscribe-form-button" onclick="t=document.getElementById(\'email_list_form\').elements;for(i in t) t[i].checked=1" value="'.__('Select all','subscribe-reloaded').'" />';
		echo '<input type="button" class="subscribe-form-button" onclick="t=document.getElementById(\'email_list_form\').elements;for(i in t)if(t[i].checked==1){t[i].checked=0} else{t[i].checked=1}" value="'.__('Invert selection','subscribe-reloaded').'" />';
		echo '<input type="submit" class="subscribe-form-button" value="'.__('Update subscriptions','subscribe-reloaded').'" /><input type="hidden" name="srp" value="'.$clean_post_id.'"/></p>';
		
	}
	else{
		echo '<p>'.__('Sorry, no subscriptions found.','subscribe-reloaded').'</p>';
	}
?>
</fieldset>
</form>

<?php if (current_user_can('manage_options')): ?>
<h3><?php _e('Filters','subscribe-reloaded') ?></h3>
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
<?php endif; // current_user_can('manage_options') ?>