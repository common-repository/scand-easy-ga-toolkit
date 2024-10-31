=== Easy Google Analytics Toolkit ===
Contributors: scandltd
Tags: Google Universal Analytics, Global Site Tag, analytics, gtag, custom events
Requires at least: 3.9
Tested up to: 6.6.2
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl.html

Easy Google Analytics Toolkit: analytics code integration on the WordPress website with setting up custom selectors to be checked

== Description ==

Easy Google Analytics Toolkit takes advantage of the latest and amazing features of Universal Analytics or Global Site Tag and makes it pretty easy to add a tracking code on your blog. It allows you to type in any css selector with the appropriate category and action values that will be placed into the ga function.

This is definitely one of the easiest-to-use WordPress plugins to insert the analytic snippet on your websites built in WordPress and track events for any HTML elements on your page.


= Features: =
* <strong>Custom selectors </strong> - you can bind a Google Analytics send event to any selector you want. This feature requires administrator's skills in CSS and JavaScript.<br>
* <strong>Force SSL</strong> - Setting Force SSL to true will force HTTP pages to also send all beacons using HTTPS.<br>
* <strong>Anonymize IP</strong> - The IP address of the user will be anonymized.<br>
* <strong>User ID</strong> - This is intended to be a known identifier for a user provided by the site owner/tracking library user.<br>
* <strong>Event</strong> - for Download, Email, Phone number, Outbound links, and Error 404.<br>
* <strong>File extension</strong> - type your own filename extension that you would like to track.<br>
* <strong>Production or development mode</strong> - for easy testing and tuning up a plugin.<br>
* <strong>JavaScript code snippet</strong> - type any JavaScript code in custom defined tracking event.<br>
* <strong>Use analytics.js or gtag.js library</strong> - you can select whether to use Universal Analytics or switch to a newly released Global Site Tag.


== Installation ==
= WordPress installation =
1. Go to Plugins > Add New > Search for "scand-easy-ga-toolkit"
1. Press "Install Now" button for the "scand-easy-ga-toolkit" plugin
1. Press "Activate" button

= Manual installation =
1. Upload the "scand-easy-ga-toolkit" directory to the "/wp-content/plugins/" directory
1. Activate the plugin through the Plugins menu in WordPress


== Frequently Asked Questions ==
= What are the requirements to use this plugin? =
You need an active Google Analytics account and a WordPress blog.

= How to set up Analytics tracking? =
Please check those articles for [analytics.js](https://support.google.com/analytics/answer/7476135?hl=en) or [gtag.js](https://support.google.com/analytics/answer/1008080?rd=2)

= Where can I see the analytics report? =
You can see your detailed analytics report in your [Google Analytics](https://analytics.google.com) account.

== Localization ==
The Russian language translation is available.

== Screenshots ==
1. General settings
1. Custom Event Form
1. Help Window

== Changelog ==
= 1.0.6 (2023-03-06) =
* Added support for GA4 tracking ID

= 1.0.5 (2020-04-07) =
* Fixed warning for continue within a switch (PHP 7.3.0)

= 1.0.4 (2019-03-21) =
* Added ability to create custom events without relation with CSS selector

= 1.0.3 (2018-12-13) =
* Added ability to track Outbound HTTPS links

= 1.0.2 (2018-12-10) =
* Changed method to generation gtag commands
* Added check for array index when processing custom events

= 1.0.1 (2018-05-24) =
* Added the ability to enable IP anonymization

= 1.0.0 =
* First Release
