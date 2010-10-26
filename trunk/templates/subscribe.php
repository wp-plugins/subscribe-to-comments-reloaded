<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
  header('Location: /');
  exit;
}

// Load localization files
load_plugin_textdomain('subscribe-reloaded', WP_PLUGIN_DIR .'/subscribe-to-comments-reloaded/langs', '/subscribe-to-comments-reloaded/langs');

// Is the post_id passed in the query string valid?
$post_ID = !empty($_GET['srp'])?intval($_GET['srp']):0;
$post = get_post($post_ID);
if (($post_ID > 0) && !is_object($post)){
	return '';
}
if (!empty($_POST['subscribe_reloaded_email'])){
	$wp_subscribe_reloaded = new wp_subscribe_reloaded();
	$enable_double_check = get_option('subscribe_reloaded_enable_double_check', 'no');

	// Send a message to the administrator
	$from_name = get_option('subscribe_reloaded_from_name', 'admin');
	$from_email = get_option('subscribe_reloaded_from_email', get_bloginfo('admin_email'));
	$clean_email = $wp_subscribe_reloaded->clean_email($_POST['subscribe_reloaded_email']);
	
	$subject = __('New subscription to','subscribe-reloaded')." $post->post_title";
	$message = __('New subscription to','subscribe-reloaded')." $post->post_title\n".__('User:','subscribe-reloaded')." $clean_email";
		
	$headers = "MIME-Version: 1.0\n";
	$headers .= "From: $from_name <$from_email>\n";
	$headers .= "Content-Type: text/plain; charset=".get_bloginfo('charset')."\n";
	wp_mail(get_bloginfo('admin_email'), $subject, $message, $headers);

	echo '<p>';
	if ($enable_double_check == 'yes' && !$wp_subscribe_reloaded->is_user_subscribed($post_ID, $_POST['subscribe_reloaded_email'], 'C')){
		$wp_subscribe_reloaded->add_subscription($_POST['subscribe_reloaded_email'], 'C', $post_ID);
		$wp_subscribe_reloaded->confirmation_email($_POST['subscribe_reloaded_email'], $post_ID);
		printf(__("Thank you for using our subscription service. In order to confirm your request, please check your email for the verification message and follow the instructions. In the meanwhile, you can go back to <a href='%s'>%s</a>.", 'subscribe-reloaded'), get_permalink($post_ID), $post->post_title);
	}
	elseif(!$wp_subscribe_reloaded->is_user_subscribed($post_ID, $_POST['subscribe_reloaded_email'], 'Y')){
		$this->add_subscription($_POST['subscribe_reloaded_email'], 'Y', $post_ID);
		printf(__("Thank you for using our subscription service. Your request has been completed, and you are now subscribed to <a href='%s'>%s</a>. You will receive a notification email every time a new comment to this article is approved and posted by the administrator.", 'subscribe-reloaded'), get_permalink($post_ID), $post->post_title);
	}
	echo '</p>';
	
	return ''; 
} 
?>

<p><?php printf(__("You can follow the discussion on <strong>%s</strong> without having to leave a comment. Cool, huh? Just enter your email address in the form here below and you're all set.", 'subscribe-reloaded'), $post->post_title); ?></p>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" onsubmit="if(this.subscribe_reloaded_email.value=='' || his.subscribe_reloaded_email.value.indexOf('@')==0) return false">
<fieldset style="border:0">
	<p><label for="subscribe_reloaded_email"><?php _e('Email','subscribe-reloaded') ?></label> <input type="text" class="subscribe-form-field" name="subscribe_reloaded_email" value="<?php echo isset($_COOKIE['comment_author_email_'.COOKIEHASH])?$_COOKIE['comment_author_email_'.COOKIEHASH]:'email'; ?>" size="22"/>
	<input name="submit" type="submit" class="subscribe-form-button" value="<?php _e('Send','subscribe-reloaded') ?>" /></p>
</fieldset>
</form>