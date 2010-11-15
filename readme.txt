=== Subscribe To Comments Reloaded ===
Contributors: coolmann
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=Z732JS7KQ6RRL&lc=US&item_name=Subscribe%20To%20Comments%20Reloaded&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: subscribe, comments, notification, subscription, manage, double check-in, follow, commenting
Requires at least: 2.9.2
Tested up to: 3.1
Stable tag: 1.3

== Description ==
Subscribe to Comments Reloaded is a robust plugin that enables commenters to sign up for e-mail notification of subsequent entries. The plugin includes a full-featured subscription manager that your commenters can use to unsubscribe to certain posts or suspend all notifications. It solves most of the issues that affect Mark Jaquith's version, using the latest Wordpress features and functionality. Plus, allows administrators to enable a double opt-in mechanism, requiring users to confirm their subscription clicking on a link they will receive via email.

## Requirements
* Wordpress 2.9 or higher
* PHP 5.1 or higher
* MySQL 5.x or higher

## Main Features
* Does not modify Wordpress system tables anymore
* Imports Mark Jaquith's Subscribe To Comments data (**copies but does not delete** the old data)
* The Management page for your visitors adapts to EVERY template existing out there
* If you decide to uninstall this plugin, no crap will be left around in your DB, I promise!
* Messages are fully customizable, no poEdit required
* Fully localizable in your language (please contribute!)

== Installation ==

1. If you are using Subscribe To Comments by Mark Jaquith, disable it (no need to uninstall it, though)
2. Upload the entire folder and all the subfolders to your Wordpress plugins' folder
3. Activate it
4. Customize all the messages (Settings > Subscribe to Comments > Messages)
5. If your template does not call the 'comment_form' action, you will have to manually edit it. To show the checkbox and its label, add: `<?php if (function_exists('subscribe_reloaded_show')) subscribe_reloaded_show(); ?>`
6. If you're updating from a previous version, please make sure to deactivate/activate the plugin after upgrading

== Screenshots ==

1. Manage your subscriptions
2. Customize the plugin's behavior
3. Use your own messages to interact with your users

== Changelog ==

= 1.3 =
* A new column in the Edit Comments panel will now tell you on-the-fly who's subscribed to what
* StCR doesn't remove the other plugin's data anymore (it's none of my business, indeed!)
* You can now use a custom CSS class, inline styles and/or HTML code to style the checkbox shown to your visitors
* Brazilian localization added (thank you Ronaldo Richieri)
* French localization added (thank you Li-An)

= 1.2.1 =
* Maintenance release, fixes a bunch of inconsistencies highlighted by its users. Thank you everybody for your patience!
* Sorry for this 'double release' in two days, hopefully it won't happen anymore ;)

= 1.2 =
* Since a lot of people have asked me to avoid the creation of a new page, I've implemented a new solution to satisfy their request. If you were using an earlier version, **you must delete the page** it had created, it's not needed anymore
* You can now customize more strings and messages 
* Fully compatible with [Fluency Admin](http://deanjrobinson.com/projects/fluency-admin/) (thank you [voyagerfan5761](http://wordpress.org/support/topic/plugin-subscribe-to-comments-reloaded-feature-ideas))
* Added a new option to choose if users should be notified for pingbacks/trackbacks

= 1.1 =
* The admin interface has been completely reorganized
* Added a new option to notify the authors when new comments are posted to one of their articles
* The management page for your visitors is now automatically created by StCR
* A cookie is now set to remember when a visitor subscribes without commenting
* Spam comments that make it through Akismet's filters, are now ignored (thank you Andrea Pinti)
* Actions on comments have been tied to actions on subscriptions (i.e. comment deleted -> subscription deleted, etc)
* Activated the official [support forum](http://lab.duechiacchiere.it/index.php?board=5.0)

= 1.0 =
* First release

== Language Localization ==

Subscribe to Comments Reloaded can speak your language! I used the `Portable Object` (.po) standard
to implement this feature. If you want to provide a localized file in your
language, use the template files (.pot) you'll find inside the `lang` folder,
and contact me on the [support forum](http://lab.duechiacchiere.it/index.php?board=5.0) when your
localization is ready. Right now the following localizations are available (in alphabetical order):

* Portuguese, Brazil ([Ronaldo Richieri](http://richieri.com))
* French ([Li-An](http://www.li-an.fr/wpplugins/mes-traductions-de-plugins/#subscribe))
* Italian
* Russian ([Marika Bukvonka](http://violetnotes.com))

== List of donors in alphabetical order ==
* [Yochai](http://watch-the-walking-dead-online.com/)
