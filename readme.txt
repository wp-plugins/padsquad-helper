=== Plugin Name ===
Contributors: kryptonite30
Tags: mobify, mobile, tablet, phone, ipad, iphone, android, padsquad
Requires at least: 3.0.0
Tested up to: 4.2.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin changes your website's mobile template to PadSquad's responsive mobile interface.

== Description ==

This plugin reformats the current theme if the user views the site on mobile or tablet views. It adds classes
to essential elements such as the post title and post body so that the Mobify.js can select them across all
installations of Wordpress. This plugin does not affect the desktop view.

For devs: If you have mobify preview running, you can add /?psdev to view a site mobified

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin folder to your '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On your admin menu, the PadSquad item should appear
4. Select the PadSquad
5. Go to the Options tab
6. Enter the Mobify URL provided to you by PadSquad
7. Tick the activate box
8. Save changes and take a look at your site on a mobile device or tablet!

== Frequently Asked Questions ==

= Does this affect my current theme? =

This plugin will only override your theme when it is viewed on mobile or tablet. Your desktop theme will remain the same.

= Where do I get the Mobify URL? =

An account manager from PadSquad will email you directly with your own URL. Visit http://padsquad.com for more details.

= How do I use this plugin with Network Admin? =

Install the plugin via Network Admin and then activate the plugin for each individual site on each site's admin page.

= What is the "Choose how to load content" option? =

When our template loads in your data, we search for images that we can use in the panels we display for homepages and category pages. 
By default, we load in entire articles and search for images through those for the first image we come across.  
All of the text that comes in with the article takes a small more amount of time to load in, however since we are loading in multiple articles at a time, the load time adds up.
Therefore we add an option to load in only article excerpts which include only the first several characters of an article, cutting down the load time.
This requires you to make sure all of your posts have thumbnail images or have an image as the first part of your posts.

= What do I do if some of my pages are not loading in correctly? =

There are custom themes that load in special content via the theme itself instead of content explicitly written by the author, (e.g. some recipe indexes on some food blogs).
Since we load in our own template when your site is viewed on a mobile device, the special content your theme loads in is not loaded by default.
If this is the case with some pages on your site, please email us at support@padsquad.com with your issue and we will be able to change your PadSquad template to accomodate the page. 

= Are there conflicts with caching plugins? =

There will be some additional settings you will need to change in your caching plugin to allow our plugin to work properly. 
First and foremost, please make sure your caching plugins are updated to the lastest version.
In many cases, using multiple caching plugins will cause issues with proper caching and clearing your cache.
We require you disable caching for mobile devices, which many popular caching plugins allow.

- Hyper Cache
1. Go to 'Hyper Cache' in your settings submenu
2. Go to the 'Mobile' tab
3. On 'Working mode' select 'Bypass the cache'
4. Press 'Save' on the bottom of the page 

- W3 Total Cache
1. Go to 'Performance' in your admin bar
2. Go to 'Page Cache' submenu in the admin bar
3. On 'Rejected user agents' paste in the list of user agents (located at the bottom of this question) into the field
4. Press 'Save all settings'
5. Go to 'Minify' submenu in the admin bar
6. On 'Rejected user agents' paste in the list of user agents (located at the bottom of this question) into the field
7. Press 'Save all settings'
8. Go to 'CDN' submenu in the admin bar
9. On 'Rejected user agents' paste in the list of user agents (located at the bottom of this question) into the field
10. Press 'Save all settings'

- Wordfence Security
1. Go to 'Wordfence' in your admin bar
2. Go to 'Performance Setup' submenu in the admin bar
3. Under 'You can add items like URLs, cookies and browsers (user-agents) to exclude from caching', add in exclusions for the user agents listed below indivdually
4. Make sure you are selecting 'User-Agent Contains' in the first dropdown box and not 'URL Starts with'

- WP Fastest Cache
1. Go to 'WP Fastest Cache' in your admin bar
2. Go to the 'Settings' tab
3. Check 'Mobile: Don't show the cached version for desktop to mobile devices' option
4. Press 'Submit' at the bottom of the page

- WP Rocket
1. Go to 'WP Rocket' in your settings submenu
2. Go to the 'Basic options' tab
3. On 'Lazyload' uncheck 'Images' and 'Iframes & Videos'
4. On 'Mobile cache' uncheck 'Enable caching for mobile devices'
5. Press 'Save Changes' at the top or bottom of the page
6. Go to the 'Advanced options' tab
7. On 'Never send cache pages for these user agents' paste in the list of user agents (located at the bottom of this question) into the field
8. Press 'Save Changes' at the top or bottom of the page

- WP Super Cache
1. Go to 'WP Super Cache' in your settings submenu
2. Go to the 'Advanced' tab
3. Under 'Rejected User Agents' paste in the list of user agents (located at the bottom of this question) into the field
4. Press 'Save UA Strings'

- User Agent List

ipod<br>
ipad<br>
iphone<br>
android<br>
blackberry.\*applewebkit<br>
bb1\d.\*mobile

= Are there conflicts with other mobile theme plugins? =
Yes. You should disable those plugins while you are running the PadSquad plugin

== Screenshots ==
1. Panels page on iPhone.
2. Panels page on iPad.
3. Article page on iPad.
4. Options menu.

== Changelog ==


== Upgrade Notice ==

