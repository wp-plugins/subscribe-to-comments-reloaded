=== Subscribe To Comments Reloaded ===
Contributors: coolmann
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=Z732JS7KQ6RRL&lc=US&item_name=Subscribe%20To%20Comments%20Reloaded&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: subscribe, comments, notification, subscription, manage, double check-in, follow, commenting
Requires at least: 2.9.2
Tested up to: 3.1
Stable tag: 1.1

== Description ==
Subscribe to Comments Reloaded is a robust plugin that enables commenters to sign up for e-mail notification of subsequent entries. The plugin includes a full-featured subscription manager that your commenters can use to unsubscribe to certain posts or suspend all notifications. It solves most of the issues that affect Mark Jaquith's version, using the latest Wordpress features and functionality. Plus, allows administrators to enable a double opt-in mechanism, requiring users to confirm their subscription clicking on a link they will receive via email.

## Requirements
* Wordpress 2.9 or higher
* PHP 5.1 or higher
* MySQL 5.x or higher

## Main Features
* Does not modify Wordpress system tables anymore
* Imports Mark Jaquith's Subscribe To Comments data (**copies and then deletes** the old column in `wp_comments`)
* Management page is now easier to create and adapts to EVERY layout existing out there
* If you decide to uninstall this plugin, no crap will be left around in your DB, I promise!
* Source code is easier to read and update, and its size is almost halved (you're welcome, providers!)
* Messages are fully customizable, no poEdit required
* Fully localizable in your language (please contribute!)
* Uses WP timezone settings and date formatting

== Installation ==

1. If you are using Subscribe To Comments by Mark Jaquith, disable it
2. Upload the entire folder and all the subfolders to your Wordpress plugins' folder
3. Activate it
4. Customize all the messages (Settings > Subscribe to Comments > Messages)
5. If your template does not call the 'comment_form' action, you will have to manually edit it. To show the checkbox and its label, add: `<?php if (function_exists('subscribe_reloaded_show')) subscribe_reloaded_show(); ?>`
6. StCR creates a new page in your blog, feel free to customize the 'error message' it contains to fit your needs. That copy will be shown to the user if an error occurs, i.e. the security key in the URL is not valid.

== Screenshots ==

1. Manage your subscriptions
2. Customize the behavior
3. Use your own messages to interact with your users

== Changelog ==

= 1.1 =
* The admin interface has been completely reorganized
* Added a new option to notify the authors when new comments are posted to one of their articles
* The management page for your visitors is now automatically created by StCR
* A cookie is now set to remember when a visitor subscribes without commenting
* Spam comments that make it through Akismet's filters, are now ignored (thank you Andrea Pinti)
* Actions on comments have been tied to actions on subscriptions (i.e. comment deleted -> subscription deleted, etc)
* Activated the official [support forum](http://lab.duechiacchiere.it/index.php?board=5.0)

= 1.0 =
* First beta release