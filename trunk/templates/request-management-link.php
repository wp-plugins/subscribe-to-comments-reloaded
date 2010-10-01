<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
  header('Location: /');
  exit;
}

// Load localization files
load_plugin_textdomain('subscribe-reloaded', WP_PLUGIN_DIR .'/subscribe-to-comments-reloaded/langs', '/subscribe-to-comments-reloaded/langs');

// Is the post_id passed in the query string valid?
$post_ID = !empty($_POST['srp'])?intval($_POST['srp']):(!empty($_GET['srp'])?intval($_GET['srp']):0);
$post = get_post($post_ID);
if (($post_ID > 0) && !is_object($post)){
	return '';
}
if (!empty($_POST['subscribe_reloaded_email'])){
	$wp_subscribe_reloaded = new wp_subscribe_reloaded();

	// Send management link
	$from_name = get_option('subscribe_reloaded_from_name', 'admin');
	$from_email = get_option('subscribe_reloaded_from_email', get_bloginfo('admin_email'));
	$subject = stripslashes(get_option('subscribe_reloaded_management_subject', 'Manage your subscriptions on [blog_name]'));
	$message = stripslashes(get_option('subscribe_reloaded_management_content', ''));
	$manager_link = get_permalink(get_option('subscribe_reloaded_manager_page', ''));	
	$clean_email = $wp_subscribe_reloaded->clean_email($_POST['subscribe_reloaded_email']);
	$subscriber_salt = md5($wp_subscribe_reloaded->salt.$clean_email);
		
	$headers = "MIME-Version: 1.0\n";
	$headers .= "From: $from_name <$from_email>\n";
	$headers .= "Content-Type: text/plain; charset=".get_bloginfo('charset')."\n";
	
	if (strpos($manager_link, '?') !== false)
		$manager_link = "$manager_link&sre=".urlencode($clean_email)."&srk=$subscriber_salt";
	else
		$manager_link = "$manager_link?sre=".urlencode($clean_email)."&srk=$subscriber_salt";
		
	// Replace tags with their actual values
	$subject = str_replace('[blog_name]', get_bloginfo('name'), $subject);
	$message = str_replace('[blog_name]', get_bloginfo('name'), $message);
	$message = str_replace('[manager_link]', $manager_link, $message);

	wp_mail($clean_email, $subject, $message, $headers);
		
	echo '<p>';
	printf(__("Thank you for using our subscription service. Your request has been completed, and you should receive an email with the management link in a few minutes. In the meanwhile, feel free to go back to <a href='%s'>%s</a>.", 'subscribe-reloaded'), get_permalink($post_ID), $post->post_title);
	echo '</p>';
	
	return '';
} 
?>

<p><?php _e("To manage your subscriptions, please enter your email address here below. We will send you a message containing the link to access your personal management page.", 'subscribe-reloaded'); ?></p>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" onsubmit="if(this.subscribe_reloaded_email.value=='' || his.subscribe_reloaded_email.value.indexOf('@')==0) return false">
<fieldset>
	<p><label for="subscribe_reloaded_email"><?php _e('Email','subscribe-reloaded') ?></label> <input type="text" class="subscribe-form-field" name="subscribe_reloaded_email" value="<?php echo isset($_COOKIE['comment_author_email_'.COOKIEHASH])?$_COOKIE['comment_author_email_'.COOKIEHASH]:'email'; ?>" size="22"/>
	<input name="submit" type="submit" class="subscribe-form-button" value="<?php _e('Send','subscribe-reloaded') ?>" /></p>
</fieldset>
</form>