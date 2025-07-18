=== Markdown to HTML API ===
Contributors: reallyusefulplugins
Donate link: https://reallyusefulplugins.com/donate
Tags: Markdown, Converter, HTML
Requires at least: 6.5
Tested up to: 6.8.2
Stable tag: 1.0
Requires PHP: 8.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides a REST API endpoint to convert Markdown to HTML.
== Description ==

Provides a REST API endpoint to convert Markdown to HTML.

== Installation ==

1. Upload the `markdown-html-api` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. **POST** `domain.com/wp-json/md/v1/convert`


== Frequently Asked Questions ==

= How do I convert =
Send a markdown json item via a post request to yourdomain.com/wp-json/md/v1/convert i.e
{
  "markdown": "# Hello World\n**This is bold**"
}
 
== Changelog == 
= 1.0 (18 July 2025) =
New: Release