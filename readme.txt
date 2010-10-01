=== Subscribe To Comments Reloaded ===

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=Z732JS7KQ6RRL&lc=US&item_name=Subscribe%20To%20Comments%20Reloaded&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: subscribe, comments, notification, subscription, manage, double check-in, follow, commenting
Requires at least: 2.9.2
Tested up to: 3.0.1
Stable tag: 1.0

== Description ==
Subscribe to Comments Reloaded is a robust plugin that enables commenters to sign up for e-mail notification of subsequent entries. The plugin includes a full-featured subscription manager that your commenters can use to unsubscribe to certain posts, block all notifications, or even change their notification e-mail address! It solves most of the issues present in Mark Jaquith's version, using the latest Wordpress features and functionality. Plus, allows administrators to enable a double opt-in mechanism, requiring users to confirm their subscription clicking on a link they will receive via email.

## Requirements
* Wordpress 2.9 or higher
* PHP 5.1 or higher
* MySQL 5.x or higher

## Main Features
* Does not modify Wordpress system tables anymore
* Imports Subscribe To Comments data
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
4. Create a new page whose content is the error message shown to the user if something goes wrong
5. Write down the ID of this new page
6. Go to Settings > Subscribe to Comments and enter the ID in the first field
7. Customize all the messages to fit your needs
8. If your template does not call the 'comment_form' action, you will have to manually edit it. To show the checkbox and its label, add: `<?php if (function_exists('subscribe_reloaded_show')) subscribe_reloaded_show(); ?>`


== Changelog ==

= 1.0 =
* First beta release