<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
	header('Location: /');
	exit;
}

if (!empty($_POST['subscription_list']) && !empty($_POST['action_type'])){	
	foreach($_POST['subscription_list'] as $a_subscription){
		list($post_list_array[],$email_list_array[]) = explode(',', $a_subscription);
	}
	$post_list = implode("','", $post_list_array);
	$email_list = implode("','", $email_list_array);
	$rows_affected = 0;
	switch($_POST['action_type']){
		case 'd':
			$rows_affected = $wpdb->query("DELETE FROM $wp_subscribe_reloaded->table_subscriptions WHERE `post_ID` IN ('$post_list') AND `email` IN ('$email_list')");
			break;
		case 's':
			$rows_affected = $wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `status` = 'N' WHERE `post_ID` IN ('$post_list') AND `email` IN ('$email_list')");
			break;
		case 'a':
			$rows_affected = $wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `status` = 'Y' WHERE `post_ID` IN ('$post_list') AND `email` IN ('$email_list')");
			break;
		default:
			break;
	}
	echo '<div class="updated fade"><p>'.__('The status has been successfully updated. Rows affected:', 'subscribe-reloaded')." $rows_affected</p></div>";
}
if (!empty($_POST['old_sre']) && !empty($_POST['action_type']) && $_POST['action_type'] == 'u'){
	$clean_old_email = !empty($_POST['old_sre'])?$wp_subscribe_reloaded->clean_email($_POST['old_sre']):(!empty($_GET['old_sre'])?$wp_subscribe_reloaded->clean_email($_GET['old_sre']):'');
	$rows_affected = $wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `email` = '$clean_email' WHERE `email` = '$clean_old_email'");
	echo '<div class="updated fade"><p>'.__('The status has been successfully updated. Rows affected:', 'subscribe-reloaded')." $rows_affected</p></div>";
}
if (!empty($_POST['mass_update']) && !empty($_POST['sre']) && !empty($_POST['action_type'])){
	$clean_email = $wp_subscribe_reloaded->clean_email($_POST['sre']);
	switch($_POST['action_type']){
		case 'd':
			$wpdb->query("DELETE FROM $wp_subscribe_reloaded->table_subscriptions WHERE `email` = '$clean_email'");
			break;
		case 's':
			$wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `status` = 'N' WHERE `email` = '$clean_email'");
			break;
		case 'a':
			$wpdb->query("UPDATE $wp_subscribe_reloaded->table_subscriptions SET `status` = 'Y' WHERE `email` = '$clean_email'");
			break;
		default:
			break;
	}
	echo '<div class="updated fade"><p>'.__('The status has been successfully updated.', 'subscribe-reloaded')." $rows_affected</p></div>";
}
?>
<h3><?php _e('Update email address','subscribe-reloaded') ?></h3>
<p><?php _e('You can "mass update" all the occurrences of a given email address in the database with a new one.','subscribe-reloaded') ?></p>
<form action="admin.php?page=subscribe-to-comments-reloaded/options/index.php&subscribepanel=1" method="post" id="update_address_form"
	onsubmit="return confirm('<?php _e('Please remember: this operation cannot be undone. Are you sure you want to proceed?', 'subscribe-reloaded') ?>')">
<fieldset style="border:0">
<p><?php _e('From','subscribe-reloaded') ?> &nbsp;<input type='text' size='40' name='old_sre' value='' /> <?php _e('To','subscribe-reloaded') ?> &nbsp; <input type='text' size='40' name='sre' value='' />
<input type='submit' class='subscribe-form-button' value='<?php _e('Update email address','subscribe-reloaded') ?>' /></p>
<input type='hidden' name='action_type' value='u'/>
</fieldset>
</form>

<h3><?php _e('Remove or suspend email address','subscribe-reloaded') ?></h3>
<p><?php _e('Change the status or permanently delete all the subscriptions for a given email address.','subscribe-reloaded') ?></p>
<form action="admin.php?page=subscribe-to-comments-reloaded/options/index.php&subscribepanel=1" method="post">
<p><input type="text" size="40" name="sre" value="" /> 
<select name="action_type">
	<option value=''>-------------</option>
	<option value='s'><?php _e('Suspend','subscribe-reloaded') ?></option>
	<option value='a'><?php _e('Resume','subscribe-reloaded') ?></option>
	<option value='d'><?php _e('Delete forever','subscribe-reloaded') ?></option>
</select>
<input type="submit" class="subscribe-form-button" value="<?php _e('Update','subscribe-reloaded') ?>" />
</p>
</form>

<h3><?php _e('Search email address','subscribe-reloaded') ?></h3>
<form action="admin.php?page=subscribe-to-comments-reloaded/options/index.php&subscribepanel=1" method="post">
<p><?php _e('Find all the subscriptions where <b>email</b>','subscribe-reloaded') ?>&nbsp;
<select name="search_type">
	<option value='e'><?php _e('equals','subscribe-reloaded') ?></option>
	<option value='c'><?php _e('contains','subscribe-reloaded') ?></option>
	<option value='n'><?php _e('does not contain','subscribe-reloaded') ?></option>
</select>
<input type="text" size="40" name="sre" value="" /> 
<input type="submit" class="subscribe-form-button" value="<?php _e('Search','subscribe-reloaded') ?>" /></p>
</form>

<?php
$clean_email = !empty($_POST['sre'])?$wp_subscribe_reloaded->clean_email($_POST['sre']):(!empty($_GET['sre'])?$wp_subscribe_reloaded->clean_email($_GET['sre']):'');
if (empty($clean_email) && empty($_POST['post_list']) && empty($_POST['action_type'])) return;
?>

<form action="admin.php?page=subscribe-to-comments-reloaded/options/index.php<?php if(!empty($current_panel)) echo '&subscribepanel='.$current_panel; ?>" method="post" id="post_list_form"
	onsubmit="return confirm('<?php _e('Please remember: this operation cannot be undone. Are you sure you want to proceed?', 'subscribe-reloaded') ?>')">
<fieldset style="border:0">
<?php
	$search_type = !empty($_POST['search_type'])?$_POST['search_type']:'';
	switch($search_type){
		case 'c':
			$where_clause = "`email` LIKE '%$clean_email%'";
			break;
		case 'n':
			$where_clause = "`email` NOT LIKE '%$clean_email%'";
			break;
		default:
			$where_clause = "`email` = '$clean_email'";					
			break;
	}
	$sql = "SELECT `email`, `status`, `post_ID`, `dt`
					FROM $wp_subscribe_reloaded->table_subscriptions
					WHERE $where_clause
					ORDER BY `status` ASC, `post_ID` ASC";
	
	$subscriptions = $wpdb->get_results($sql, OBJECT);
	if (is_array($subscriptions) && !empty($subscriptions)){
		echo '<p>'.__('Subscriptions for:','subscribe-reloaded')." <b>$clean_email</b></p>";
		echo '<p>'.__('Legend: Y: subscribed, N: suspended, C: awaiting confirmation','subscribe-reloaded').'</p>';
		echo '<ul>';
		foreach($subscriptions as $a_subscription){
			$permalink = get_permalink($a_subscription->post_ID);
			$title = get_the_title($a_subscription->post_ID);
			$specific_email = (($_POST['search_type'] == 'c') || ($_POST['search_type'] == 'n'))?"$a_subscription->email] [":'';
			echo "<li><input type='checkbox' name='subscription_list[]' value='$a_subscription->post_ID,$a_subscription->email' id='sub_{$a_subscription->dt}'/> <label for='sub_{$a_subscription->dt}'><a href='$permalink'>$title</a></label> [$specific_email$a_subscription->status]</li>\n";
		}
		echo '</ul>';
		echo '<p><a class="small-text" href="#" onclick="t=document.getElementById(\'post_list_form\').elements;for(i in t) t[i].checked=1">'.__('Select all','subscribe-reloaded').'</a> - ';
		echo '<a class="small-text" href="#" onclick="t=document.getElementById(\'post_list_form\').elements;for(i in t)if(t[i].checked==1){t[i].checked=0} else{t[i].checked=1}">'.__('Invert selection','subscribe-reloaded').'</a></p>';
		echo '<p>'.__('Action:','subscribe-reloaded').' <input type="radio" name="action_type" value="d" id="action_type_delete" /> <label for="action_type_delete">'.__('Delete forever','subscribe-reloaded').'</label> &nbsp;&nbsp;&nbsp;&nbsp; ';
		echo '<input type="radio" name="action_type" value="s" id="action_type_suspend" checked="checked" /> <label for="action_type_suspend">'.__('Suspend','subscribe-reloaded').'</label> &nbsp;&nbsp;&nbsp;&nbsp; ';
		echo '<input type="radio" name="action_type" value="a" id="action_type_activate" /> <label for="action_type_activate">'.__('Resume','subscribe-reloaded').'</label></p>';
		echo '<p><input type="submit" class="subscribe-form-button" value="'.__('Update subscriptions','subscribe-reloaded').'" /></p>';
		if (!empty($clean_email)) echo "<input type='hidden' name='sre' value='$clean_email'/>";
		if (!empty($_POST['search_type'])) echo "<input type='hidden' name='search_type' value='{$_POST['search_type']}'/>";		
	}
	else{
		echo '<p>'.__('Sorry, no subscriptions found for','subscribe-reloaded')." <b>$clean_email</b></p>";
	}
?>
</fieldset>
</form>