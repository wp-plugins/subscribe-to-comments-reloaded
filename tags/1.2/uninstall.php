<?php

global $wpdb;

// Goodbye data...
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}subscribe_reloaded`");

// Goodbye options...
delete_option('subscribe_reloaded_manager_page');
delete_option('subscribe_reloaded_manager_page_title');
delete_option('subscribe_reloaded_purge_days');
delete_option('subscribe_reloaded_from_name');
delete_option('subscribe_reloaded_from_email');
delete_option('subscribe_reloaded_checked_by_default');
delete_option('subscribe_reloaded_enable_double_check');
delete_option('subscribe_reloaded_notify_authors');

delete_option('subscribe_reloaded_notification_subject');
delete_option('subscribe_reloaded_notification_content');
delete_option('subscribe_reloaded_checkbox_label');
delete_option('subscribe_reloaded_subscribed_label');
delete_option('subscribe_reloaded_subscribed_waiting_label');
delete_option('subscribe_reloaded_author_label');
delete_option('subscribe_reloaded_double_check_subject');
delete_option('subscribe_reloaded_double_check_content');
delete_option('subscribe_reloaded_management_subject');
delete_option('subscribe_reloaded_management_content');

// Remove scheduled autopurge events
wp_clear_scheduled_hook('subscribe_reloaded_purge');

?>