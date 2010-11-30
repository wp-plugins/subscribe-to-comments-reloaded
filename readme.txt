=== Subscribe To Comments Reloaded ===
Contributors: coolmann
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=Z732JS7KQ6RRL&lc=US&item_name=Subscribe%20To%20Comments%20Reloaded&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: subscribe, comments, notification, subscription, manage, double check-in, follow, commenting
Requires at least: 2.9.2
Tested up to: 3.1
Stable tag: 1.4

Subscribe to Comments Reloaded allows commenters to sign up for e-mail notifications of subsequent replies.

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
5. Customize the Permalink value under Settings > Subscribe to Comments > Options (first value). It must reflect your permalinks' structure (nice permalinks, subfolders, post IDs, etc)
5. If you don't see the checkbox to subscribe, you will have to manually edit your template, and add `<?php if (function_exists('subscribe_reloaded_show')) subscribe_reloaded_show(); ?>` somewhere inside `comments.php`
6. If you're upgrading from a previous version, please make sure to deactivate/activate the plugin after upgrading
7. Optional: customize all the messages under Settings > Subscribe to Comments > Messages
8. Some templates are showing the latest comments on the management page: unfortunately that's a problem with the template, not the plugin. Please contact your theme's developer for more information

== Screenshots ==

1. Manage your subscriptions
2. Customize the plugin's behavior
3. Use your own messages to interact with your users

== Changelog ==

= 1.4 =
* Fixed a problem that made the latest comments appear on the management page
* Fixed a conflict with the Recent Posts Wordpress widget
* Added a new option to choose if logged in administrators should be able to subscribe to comments (by default they're not, thank you [Oyvind](http://lab.duechiacchiere.it/index.php?topic=104.0))
* Added a few HTML `<span>` tags to the lists shown in the management page, to allow owners to customize their look-an-feel via CSS
* Admin panels have been reorganized
* You can now browse through the list of ALL the subscriptions
* Registered users are now recognized by the system and don't need to request the management link via email (thank you [Acaro00](http://lab.duechiacchiere.it/index.php?topic=106.0))
* German localization added (thank you [derhenry](http://www.derhenry.net/2010/subscribe-to-comments-reloaded/))
* Norwegian localization added (thank you [Odd Henriksen](http://www.oddhenriksen.net/))

= 1.3 =
* A new column in the Edit Comments panel will now tell you on-the-fly who's subscribed to what
* StCR doesn't remove the other plugin's data anymore (it's none of my business, indeed!)
* You can now use a custom CSS class, inline styles and/or HTML code to style the checkbox shown to your visitors
* Brazilian localization added (thank you Ronaldo Richieri)
* French localization added (thank you Li-An)

= 1.2.1 =
* Maintenance release, fixes a bunch of inconsistencies highlighted by its users. Thank you everybody for your patience!
* Sorry for this 'double release' in two days, hopefully it won't happen anymore ;)

== Language Localization ==

Subscribe to Comments Reloaded can speak your language! I used the `Portable Object` (.po) standard
to implement this feature. If you want to provide a localized file in your
language, use the template files (.pot) you'll find inside the `lang` folder,
and contact me on the [support forum](http://lab.duechiacchiere.it/index.php?board=5.0) when your
localization is ready. Right now the following localizations are available (in alphabetical order):

* French ([Li-An](http://www.li-an.fr/wpplugins/mes-traductions-de-plugins/#subscribe))
* German ([derhenry](http://www.derhenry.net/2010/subscribe-to-comments-reloaded/))
* Italian
* Norwegian ([Odd Henriksen](http://www.oddhenriksen.net/))
* Portuguese, Brazil ([Ronaldo Richieri](http://richieri.com))
* Russian ([Marika Bukvonka](http://violetnotes.com))

== List of donors in alphabetical order ==
* [Pausaxn](http://pausaxn.it)
* [Yochai](http://watch-the-walking-dead-online.com/)

