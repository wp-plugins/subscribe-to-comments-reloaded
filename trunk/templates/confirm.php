<?php
// Avoid direct access to this piece of code
if (!function_exists('add_action')){
	header('Location: /');
	exit;
}

global $wp_subscribe_reloaded;

$wp_subscribe_reloaded->update_subscription_status($post_ID, $email, '-C');
$message = stripslashes(get_option('subscribe_reloaded_subscription_confirmed'));
if(function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage'))
	$message = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($message);
return "<p>$message</p>";
