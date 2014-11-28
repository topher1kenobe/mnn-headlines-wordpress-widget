=== Mission Network News Daily Headlines Widget ===
Contributors: topher1kenobe
Tags: widget
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a widget showing the most recent daily news headlines from Mission Network News.

== Description ==

Creates a widget showing the most recent daily news headlines from Mission Network News.

== Installation ==

1. Upload the `/mnn-headlines-wordpress-widget/` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit Appearance -> Widgets in the admin and place the widget in a sidebar

== Screenshots ==

1. wut?
== Usage ==

Some basic CSS is included.  If you'd like to turn it off, drop this code into your theme functions.php file or a plugin of your choosing.

`function remove-t1k-mnn-headlines-styles() {
    return false;
}
add_filter( 't1k-mnn-headlines-styles', 'remove-t1k-mnn-headlines-styles' );`

== Changelog ==

= 1.0 =
* Initial release
