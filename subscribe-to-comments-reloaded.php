<?php
/*
Plugin Name: Subscribe to Comments Reloaded
Version: 1.0
Plugin URI: http://www.duechiacchiere.it/
Description: Subscribe to Comments with Double-Opt-In
Author: camu
Author URI: http://www.duechiacchiere.it/
*/

// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
	header('Location: /');
	exit;
}

function subscribe_reloaded_show(){
	global $post;
	
	$manager_link = get_permalink(get_option('subscribe_reloaded_manager_page', ''));
	if (strpos($manager_link, '?') !== false)
		$manager_link = "$manager_link&amp;srp=$post->ID";
	else
		$manager_link = "$manager_link?srp=$post->ID";
	
	$wp_subscribe_reloaded = new wp_subscribe_reloaded();
	if ($wp_subscribe_reloaded->is_author($post->post_author)){	// when the second parameter is empty, cookie value will be used
		echo str_replace('[manager_link]', $manager_link,
			stripslashes(get_option('subscribe_reloaded_author_label', "You are the author of this entry. <a href='[manager_link]'>Manage its subscriptions</a>.")));
	}
	elseif($wp_subscribe_reloaded->is_user_subscribed($post->ID, '', 'Y')){		
		echo str_replace('[manager_link]', $manager_link,
			stripslashes(get_option('subscribe_reloaded_subscribed_label', "You are subscribed to this entry. <a href='[manager_link]'>Manage your subscriptions</a>.")));
	}
	elseif($wp_subscribe_reloaded->is_user_subscribed($post->ID, '', 'C')){		
		echo str_replace('[manager_link]', $manager_link,
			stripslashes(get_option('subscribe_reloaded_subscribed_waiting_label', "Your subscription to this entry needs to be confirmed. <a href='[manager_link]'>Manage your subscriptions</a>.")));
	}
	else{
		$checked_by_default = get_option('subscribe_reloaded_checked_by_default', 'no');
		$checkbox_label = str_replace('[subscribe_link]', "$manager_link&amp;sra=s", 
			stripslashes(get_option('subscribe_reloaded_checkbox_label', "Notify me of followup comments via e-mail. You can also <a href='[subscribe_link]'>subscribe</a> without commenting.")));
		echo "<input type='checkbox' name='subscribe-reloaded' id='subscribe-reloaded' value='yes'".(($checked_by_default == 'yes')?" checked='checked'":'')." /> $checkbox_label";
	}
}

class wp_subscribe_reloaded {

	// Function: __construct
	// Description: Constructor -- Sets things up.
	// Input: none
	// Output: none
	public function __construct(){
		global $table_prefix;

		// We use a bunch of tables to store data
		$this->table_subscriptions = $table_prefix . 'subscribe_reloaded';
		$this->salt = defined('NONCE_KEY')?NONCE_KEY:'please create a unique key in your wp-config.php';
	}
	// end __construct

	// Function: activate
	// Description: Creates tables, imports data, etc...
	// Input: none
	// Output: none
	public function activate(){
		global $wpdb;
		
		// Load localization files
		load_plugin_textdomain('subscribe-reloaded', WP_PLUGIN_DIR .'/subscribe-to-comments-reloaded/langs', '/subscribe-to-comments-reloaded/langs');
		
		// Table that stores the actual data about subscribers
		$subscriptions_table_sql = "
			CREATE TABLE IF NOT EXISTS `$this->table_subscriptions` (
				`email` VARCHAR(255) NOT NULL DEFAULT '',
				`status` enum('Y','C','N') NOT NULL DEFAULT 'N',
				`post_ID` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
				`dt` TIMESTAMP(10) NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY `subscription` (`email`,`post_ID`),
				INDEX (`email`)
			)";
		$this->_create_table($subscriptions_table_sql, $this->table_subscriptions);

		// Import the information collected by the OLD plugin, if needed
		$result = $wpdb->get_row("DESC $wpdb->comments comment_subscribe", ARRAY_A);
		if (is_array($result)){
			$import_sql = "
				INSERT INTO `$this->table_subscriptions` (`email`,`status`,`post_ID`,`dt`)
					SELECT `comment_author_email`, `comment_subscribe`, `comment_post_ID`, `comment_date`
					FROM $wpdb->comments
					WHERE `comment_author_email` LIKE '%@%.%'
					GROUP BY `comment_author_email`, `comment_post_ID`
				";
			$wpdb->query($import_sql);

			// Remove the old data
			$wpdb->query("ALTER TABLE $wpdb->comments DROP COLUMN `comment_subscribe`");			
		}

		add_option('subscribe_reloaded_manager_page', '', '', 'no');
		add_option('subscribe_reloaded_purge_days', '30', '', 'no');		
		add_option('subscribe_reloaded_from_name', 'admin', '', 'no');
		add_option('subscribe_reloaded_from_email', get_bloginfo('admin_email'), '', 'no');
		add_option('subscribe_reloaded_notification_subject', __('There is a new comment to [post_title]','subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_notification_content', __('There is a new comment to [post_title].
Comment Link: [comment_permalink]
Author: [comment_author]
Comment:
[comment_content]

Permalink: [post_permalink]
Manage your subscriptions: [manager_link]','subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_checkbox_label', __("Notify me of followup comments via e-mail. You can also <a href='[subscribe_link]'>subscribe</a> without commenting.",'subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_checked_by_default', 'no', '', 'no');
		add_option('subscribe_reloaded_subscribed_label', __("You are subscribed to this entry. <a href='[manager_link]'>Manage</a> your subscriptions.",'subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_subscribed_waiting_label', __("Your subscription to this entry needs to be confirmed. <a href='[manager_link]'>Manage your subscriptions</a>.",'subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_author_label', __("You are the author of this entry. <a href='[manager_link]'>Manage</a> its subscriptions.",'subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_enable_double_check', 'no', '', 'no');
		add_option('subscribe_reloaded_double_check_subject', __('Please confirm your subscribtion to [post_title]','subscribe-reloaded'), '', 'no');
		add_option('subscribe_reloaded_double_check_content', __('You have requested to be notified every time a new comment is added to:
[post_permalink]

Please confirm your request by clicking on this link:
[confirm_link]','subscribe-reloaded'), '', 'no');


		add_option('subscribe_reloaded_management_subject', __('Manage your subscriptions on [blog_name]','subscribe-reloaded'));
		add_option('subscribe_reloaded_management_content', __('You have requested to manage your subscriptions to the articles on [blog_name]. Follow this link to access your personal page:
[manager_link]','subscribe-reloaded'));
	
		// Schedule the autopurge hook
		if (!wp_next_scheduled('subscribe_reloaded_purge'))
			wp_schedule_event(time(), 'daily', 'subscribe_reloaded_purge');
	}
	// end activate
	
	// Function: deactivate
	// Description: Performs some clean-up maintenance (disable cron job).
	// Input: none
	// Output: none
	public function deactivate() {

		// Unschedule the autopurge hook
		if (wp_next_scheduled('subscribe_reloaded_purge') > 0)
			wp_clear_scheduled_hook('subscribe_reloaded_purge');
	}
	// end deactivate
	
	// Function: subscribe_reloaded_purge
	// Description: Removes old entries from the database
	// Input: none
	// Output: none
	public function subscribe_reloaded_purge() {
		global $wpdb;

		if (($autopurge_interval = intval(get_option('subscribe_reloaded_purge_days', 0))) <= 0) return;

		// Delete old entries
		$delete_sql = "DELETE FROM `$this->table_subscriptions` WHERE `dt` <= DATE_SUB(NOW(), INTERVAL $autopurge_interval day)";
		$wpdb->query($delete_sql);
	}
	// end subscribe_reloaded_purge
	
	// Function: confirmation_email
	// Description: Sends the confirmation message to a given user
	// Input: email, post_id (to retrieve its title and permalink), comment_id
	// Output: none
	public function confirmation_email($_email = '', $_post_ID = 0){
		// Retrieve the options from the database
		$from_name = get_option('subscribe_reloaded_from_name', 'admin');
		$from_email = get_option('subscribe_reloaded_from_email', get_bloginfo('admin_email'));
		$subject = stripslashes(get_option('subscribe_reloaded_double_check_subject', 'Please confirm your subscribtion to [post_title]'));
		$message = stripslashes(get_option('subscribe_reloaded_double_check_content', ''));
		$manager_link = get_permalink(get_option('subscribe_reloaded_manager_page', ''));
		$clean_email = urlencode($this->clean_email($_email));
		$subscriber_salt = md5($this->salt.$clean_email);
	
		if (strpos($manager_link, '?') !== false){
			$confirm_link = "$manager_link&sre=$clean_email&srk=$subscriber_salt&srp=$_post_ID&sra=c";
			$manager_link = "$manager_link&sre=$clean_email&srk=$subscriber_salt";
		}
		else{
			$confirm_link = "$manager_link?sre=$clean_email&srk=$subscriber_salt&srp=$_post_ID&sra=c";
			$manager_link = "$manager_link?sre=$clean_email&srk=$subscriber_salt";
		}
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "From: $from_name <$from_email>\n";
		$headers .= "Content-Type: text/plain; charset=".get_bloginfo('charset')."\n";
		
		$post = get_post($_post_ID);
		$post_permalink = get_permalink($_post_ID);
		
		// Replace tags with their actual values
		$subject = str_replace('[post_title]', $post->post_title, $subject);
		$message = str_replace('[post_title]', $post->post_title, $message);
		$message = str_replace('[post_permalink]', $post_permalink, $message);
		$message = str_replace('[confirm_link]', $confirm_link, $message);
		$message = str_replace('[manager_link]', $manager_link, $message);

		wp_mail($clean_email, $subject, $message, $headers);
	}
	// end confirmation_email
	
	// Function: new_comment_posted
	// Description: Deletes a row in the subscriptions' table
	// Input: post_id, email 
	// Output: none
	public function new_comment_posted($_comment_ID = 0, $_comment_status = 0){
	    global $wpdb;
		
		// Retrieve the information about the new comment
		$info = $wpdb->get_row("SELECT `comment_post_ID`, `comment_author_email` FROM $wpdb->comments WHERE `comment_ID` = '$_comment_ID' AND `comment_approved` = 1 LIMIT 1", OBJECT);
		$subscribed_emails = array();
		
		// Send a notification to all the users subscribed to this post
		if (!empty($info)){
			$subscribed_emails = $this->_get_subscriptions($info->comment_post_ID, 'Y');
			foreach($subscribed_emails as $a_email){
				// Skip the user who posted this new comment
				if ($a_email != $info->comment_author_email) $this->_notify_user($a_email, $info->comment_post_ID, $_comment_ID);
			}
		}
		
		// Did this visitor request to be subscribed to the discussion? (and s/he is not subscribed)
		if (!empty($_POST['subscribe-reloaded']) && $_POST['subscribe-reloaded'] == 'yes' && is_array($subscribed_emails)){
			
			// Are we using double check-in?			
			$enable_double_check = get_option('subscribe_reloaded_enable_double_check', 'no');
			if ($enable_double_check == 'yes' && !$this->is_user_subscribed($info->comment_post_ID, $info->comment_author_email, 'C')){
				$this->add_subscription($info->comment_author_email, 'C', $info->comment_post_ID);
				$this->confirmation_email($info->comment_author_email, $info->comment_post_ID);
			}
			elseif(!$this->is_user_subscribed($info->comment_post_ID, $info->comment_author_email, 'Y')){
				$this->add_subscription($info->comment_author_email, 'Y', $info->comment_post_ID);
			}
		}
		return $_comment_ID;
	}
	// end new_comment_posted
	
	// Function: subscribe_reloaded_manage
	// Description: Displays the appropriate management page
	// Input: default page content
	// Output: appropriate page content
	public function subscribe_reloaded_manage($_content = ''){
		global $post;
		
		$manager_page_id = get_option('subscribe_reloaded_manager_page', '0');
		if (empty($post) || ($post->ID != $manager_page_id) || (strpos($_SERVER['REQUEST_URI'],get_permalink($manager_page_id)) !== false) ) return $_content;
		
		$post_ID = !empty($_POST['srp'])?intval($_POST['srp']):(!empty($_GET['srp'])?intval($_GET['srp']):0);

		// Is the post_id passed in the query string valid?
		$target_post = get_post($post_ID);
		if (($post_ID > 0) && !is_object($target_post)){
			return $_content;
		}
		
		$action = !empty($_POST['sra'])?$_POST['sra']:(!empty($_GET['sra'])?$_GET['sra']:0);
		$email = !empty($_POST['sre'])?urldecode($_POST['sre']):(!empty($_GET['sre'])?urldecode($_GET['sre']):0);
		$key = !empty($_POST['srk'])?$_POST['srk']:(!empty($_GET['srk'])?$_GET['srk']:0);

		// Subscribe without commenting
		if (!empty($action) && ($action == 's') && ($post_ID > 0)){
			include(WP_PLUGIN_DIR.'/subscribe-to-comments-reloaded/templates/subscribe.php');
			return '';
		}
		
		// Management page for post authors
		if (($post_ID > 0) && $this->is_author($target_post->post_author)){
			include(WP_PLUGIN_DIR.'/subscribe-to-comments-reloaded/templates/author.php');
			return '';
		}

		// Confirm your subscription (double check-in)
		if ( ($post_ID > 0) && !empty($email) && !empty($key) && !empty($action) &&
				$this->is_user_subscribed($post_ID, $email, 'C') &&
				$this->_is_valid_key($key, $email) &&
				($action = 'c') ){
			include(WP_PLUGIN_DIR.'/subscribe-to-comments-reloaded/templates/confirm.php');
			return '';
		}

		// Manage your subscriptions (user)
		if ( !empty($email) && ((!empty($key) && $this->_is_valid_key($key, $email)) || current_user_can('manage_options')) ){
			include(WP_PLUGIN_DIR.'/subscribe-to-comments-reloaded/templates/user.php');
			return '';
		}
		
		// Get the management link in your email
		if (!empty($_COOKIE['comment_author_email_'. COOKIEHASH])){
			include(WP_PLUGIN_DIR.'/subscribe-to-comments-reloaded/templates/request-management-link.php');
			return '';
		}
		
		return $_content;
	}
	// end subscribe_reloaded_manage

	// Function: add_subscription
	// Description: Adds a new row to the table
	// Input: email, status, post_id
	// Output: none
	public function add_subscription($_email, $_status, $_post_ID){
		global $wpdb;
		
		// Using Wordpress local time
		$dt = date_i18n('Y-m-d H:i:s');
		
		$clean_email = $this->clean_email($_email);
		$wpdb->query("INSERT IGNORE INTO $this->table_subscriptions (`email`, `status`, `post_ID`, `dt`) VALUES ('$clean_email', '$_status', '$_post_ID', '$dt')");
	}
	// end add_subscription
	
	// Function: delete_subscription
	// Description: Deletes a row in the subscriptions' table
	// Input: post_id, email 
	// Output: none
	public function delete_subscription($_post_ID = '', $_email = ''){
	    global $wpdb;
		
		$sql = "DELETE FROM $this->table_subscriptions WHERE `post_ID` = '$_post_ID'".(!empty($_email)?" AND `email` = '$_email'":'');
		$wpdb->query( $sql );
	}
	// end delete_subscription
	
	// Function: is_user_subscribed
	// Description: Checks if a given email address is subscribed to a post
	// Input: post_id
	// Output: boolean
	public function is_user_subscribed($_post_ID = 0, $_email = '', $_status = 'Y'){
	
		if ((empty($_COOKIE['comment_author_email_'. COOKIEHASH]) && empty($_email)) || empty($_post_ID)) return false;
		
		$subscribed_emails = $this->_get_subscriptions($_post_ID, $_status);
		$user_email = empty($_email)?urldecode($_COOKIE['comment_author_email_'. COOKIEHASH]):$_email;
		
		if (in_array($user_email, $subscribed_emails)) return true;
		return false;
	}
	// end is_user_subscribed
	
	// Function: is_author
	// Description: Checks if current logged in user is the author of this post
	// Input: author ID 
	// Output: boolean
	public function is_author($_post_author){
		global $current_user;
		return (!empty($current_user) && (($_post_author == $current_user->ID) || current_user_can('manage_options')));
	}
	// end is_author
	
	// Function: clean_email
	// Description: Returns an email address where some possible 'offending' strings have been removed
	// Input: email 
	// Output: none	
	public function clean_email($_email){
		$offending_strings = array(
			"/to\:/i",
			"/from\:/i",
			"/bcc\:/i",
			"/cc\:/i",
			"/content\-transfer\-encoding\:/i",
			"/content\-type\:/i",
			"/mime\-version\:/i" 
		); 
		return htmlspecialchars(stripslashes(strip_tags(preg_replace($offending_strings, '', $_email))));
	}
	// end clean_email
	
	// Function: add_config_menu
	// Description: Adds a new entry in the admin menu, to manage this plugin's options
	// Input: none
	// Output: none
	public function add_config_menu( $_s ) {
		global $current_user;

		if (current_user_can('manage_options')){
			add_options_page( 'Subscribe to Comments', 'Subscribe to Comments', 'manage_options', WP_PLUGIN_DIR.'/subscribe-to-comments-reloaded/options.php' );
		}
		return $_s;
	}
	// end add_config_menu

	// Function: _create_table
	// Description: Creates a table in the database
	// Input: SQL with the structure of the table to be created, name of the table
	// Output: boolean to detect if table was created
	private function _create_table($_sql = '', $_tablename = ''){
	    global $wpdb;

		$wpdb->query( $_sql );

		// Let's make sure this table was actually created
		foreach ( $wpdb->get_col("SHOW TABLES", 0) as $a_table ){
			if ( $a_table == $_tablename ) {
				return true;
			}
		}
		return false;
	}
	// end _create_table

	// Function: _is_valid_key
	// Description: Checks if a key is valid for a given email address
	// Input: key, email
	// Output: boolean
	private function _is_valid_key($_key, $_email){
		return (md5($this->salt.$_email) == $_key);
	}
	// end _is_valid_key

	// Function: _notify_user
	// Description: Sends the notification message to a given user
	// Input: email, post_id (to retrieve its title and permalink), comment_id
	// Output: none
	private function _notify_user($_email = '', $_post_ID = 0, $_comment_ID = 0){
		// Retrieve the options from the database
		$from_name = get_option('subscribe_reloaded_from_name', 'admin');
		$from_email = get_option('subscribe_reloaded_from_email', get_bloginfo('admin_email'));
		$subject = stripslashes(get_option('subscribe_reloaded_notification_subject', 'There is a new comment on the post [post_title]'));
		$message = stripslashes(get_option('subscribe_reloaded_notification_content', ''));
		$manager_link = get_permalink(get_option('subscribe_reloaded_manager_page', ''));
		$clean_email = urlencode($this->clean_email($_email));
		$subscriber_salt = md5($this->salt.$clean_email);
		if (strpos($manager_link, '?') !== false){
			$manager_link = "$manager_link&sre=".urlencode($clean_email)."&srk=$subscriber_salt";
		}
		else{
			$manager_link = "$manager_link?sre=".urlencode($clean_email)."&srk=$subscriber_salt";
		}
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "From: $from_name <$from_email>\n";
		$headers .= "Content-Type: text/plain; charset=".get_bloginfo('charset')."\n";
		
		$post = get_post($_post_ID);
		$comment = get_comment($_comment_ID);
		$post_permalink = get_permalink( $_post_ID );
		$comment_permalink = get_comment_link($_comment_ID);
		
		// Replace tags with their actual values
		$subject = str_replace('[post_title]', $post->post_title, $subject);
		$message = str_replace('[post_title]', $post->post_title, $message);
		$message = str_replace('[post_permalink]', $post_permalink, $message);
		$message = str_replace('[comment_permalink]', $comment_permalink, $message);
		$message = str_replace('[comment_author]', $comment->comment_author, $message);
		$message = str_replace('[comment_content]', $comment->comment_content, $message);
		$message = str_replace('[manager_link]', $manager_link, $message);

		wp_mail($clean_email, $subject, $message, $headers);
	}
	// end _notify_user
	
	// Function: _get_subscriptions
	// Description: Retrieves a list of emails subscribed to this post
	// Input: post_id
	// Output: array of email addresses
	private function _get_subscriptions($_post_ID = 0, $_status = 'Y'){
		global $wpdb;
		$flat_result = array();
		
		$result = $wpdb->get_results("SELECT DISTINCT `email` FROM $this->table_subscriptions WHERE `post_ID` = '$_post_ID' AND `status` = '$_status'", ARRAY_N);
		if (is_array($result)){
			foreach($result as $a_result){
				$flat_result[] = $a_result[0];
			}
		}
		
		return $flat_result;
	}
	// end _get_subscriptions
}
// end of class declaration

// Ok, let's use every tool we defined here above 
$wp_subscribe_reloaded = new wp_subscribe_reloaded();

// Initialization routine that should be executed on activation/deactivation
register_activation_hook( __FILE__, array( &$wp_subscribe_reloaded, 'activate' ) );
register_deactivation_hook( __FILE__, array( &$wp_subscribe_reloaded, 'deactivate' ) );

// Add appropriate entries in the admin menu
add_action( 'admin_menu', array( &$wp_subscribe_reloaded, 'add_config_menu' ) );

// What to do when a new comment is posted
add_action('comment_post', array( &$wp_subscribe_reloaded, 'new_comment_posted' ) );

// Add user's subscription after a pending comment is approved
// add_action('wp_set_comment_status', create_function('$a', 'global $sg_subscribe; sg_subscribe_start(); return $sg_subscribe->send_notifications($a);'));

// Remove subscriptions attached to a post that is being deleted
add_action( 'delete_post', array( &$wp_subscribe_reloaded, 'delete_subscription' ) );

// Provide content for the management page using WP filters
add_filter('the_content', array( &$wp_subscribe_reloaded, 'subscribe_reloaded_manage' ), 50);

// Show the checkbox - You can manually override this by adding the corresponding function in your template
add_action('comment_form', 'subscribe_reloaded_show');

// Create a hook to use with the daily cron job
add_action('subscribe_reloaded_purge', array( &$wp_slimstat,'subscribe_reloaded_purge') );
