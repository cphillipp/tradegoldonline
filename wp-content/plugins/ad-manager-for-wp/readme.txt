=== Ad Manager ===
Contributors: digitalnature
Support link: http://digitalnature.eu/forum/plugins/ad-manager/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8UA4ZYEYUQTHA
Tags: ajax, adsense, advertisment, ad manager, jquery, shortcode
Requires at least: 3.2
Tested up to: 3.3.2
Stable tag: Trunk
License: GPLv2 or later

Manage ads on your website trough the WP dashboard.

== Description ==

Light, and at the same time advanced advertisment manager. Provides you the ability to select context conditions for displaying ads, like page location / location index / page or user class.

**Note that although the current release is stable, there are features that are not yet implemented, and existing functionality that is very likely to change.**

== Installation ==

Activate the plugin, and set it up as you wish from the Options dashboard menu

== Frequently Asked Questions ==

(you can skip this section if you're not into programming at all)

= What are "theme ad locations" and how do I add support for them in my theme? =

Essentially they are just action tags that you can add trough your template files, where ads could be displayed.
For example, you could add one after the theme header, logo or whatever:

    <?php do_action('after_header'); ?>

Then register the ad location in your theme functions.php, so the plugin becomes aware of it:

    if(class_exists('AdManager'))
      AdManager()->registerAdLocation('after_header', __('After theme header'));

If you're not the theme developer you may want to make these changes inside a child theme.

= What's up with the "index" field? =

Locations don't need to be unique. You can have multiple locations on the same page with the same name, like `after_post`.
Here the index field becomes handy, because it allows you to set the ad to be displayed after the Nth location.
To add index support to your location simply append the `:index` keyword to the first argument you pass to `registerAdLocation()`:

    if(class_exists('AdManager'))
      AdManager()->registerAdLocation('after_post:index', __('After post'));

= Where does this plugin store my ads? =

The options table. I didn't think it was worth it to create a custom table, since most sites won't have more than 10-20 ads on them.

= How do I manually display an ad? =

You can use the `[ad n]` shortcode, where `n` is the ID of the ad

= I can't create / edit / manage any ads from my dashboard! =

This is most likely because a naughty plugin throws javascript errors, and since Ad Manager interface is currently entirely ajax-based,
any subsequent javascript will break.


== Changelog ==

= 0.9.4 =
* Fixed re-escaping quotes issue when magic_quotes_gpc is on

= 0.9.3 =
* Code inside the textarea is now escaped for display

= 0.9.2 =
* Fixed an issue where the HTML code would get escaped
* Implemented Quick-Enable/Disable/Clone controls

= 0.9.1 =
* CSS fix for Firefox for ad editor controls
* Fixed an ad queue issue with theme locations

= 0.9 =
* First public release (forked from the "Ads" module 1.4 from Atom).

== Screenshots ==

1. Plugin options


== Thanks ==

Thanks goes to [Mystique](http://digitalnature.eu/themes/mystique/) theme users, which encouraged me to improve
the original "Ads" module for that theme, and develop this plugin.
