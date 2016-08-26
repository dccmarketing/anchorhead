=== Anchorhead ===
Contributors: slushman
Donate link: http://slushman.com
Tags:
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds anchor links to the h2 headings in a page/post.

== Description ==

Creates a table of contents at the top of a page or post with anchor links to h2 headings in the document. Each h2 also has a "back to top" link. uses the SmoothScroll.js library to animate the scrolling nicely.

This happens automagically on every page/post.


@todo: Make the threshhold value bigger as the menu gets longer so you avoid conflicts.
@todo: Should headings with no back-to-top link be listed in the menu?



== Installation ==

1. Upload `anchorhead.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress



== Frequently Asked Questions ==

= Troubleshooting tip: =

If the "back to top" links appear in odd places, check the formatting on the H2 tags. They should only be an H2 tag, not bold, italicizes, or linked. Additional tags break how the plugin finds the H2 headings which then causes the "back to top" links to appear in odd places.



== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot



== Changelog ==

= 1.1 =
* Adds metabox to all pages allowing the anchor menu to be turned off for that page.
* Updates classes and views.
* Updates default styling.
* Changes table of contents title to a class and updates scripts and styling accordingly.
* New option allows for not adding a "back to top" link to the headings close to the top of the page. Instead, it makes those headings inline, so they wrap around the menu.

= 1.0.1 =
* Updating to latest plugin structure. Adds autoloader.
* Fixes bug with headers containing the same text.

= 1.0 =
* Initial version.



== Upgrade Notice ==

= 1.1 =
* Adds per-page option to not display anchor menu.

= 1.0.1 =
* Fixes bug with "back to top" links and disappearing content.

= 1.0 =
Initial version.
