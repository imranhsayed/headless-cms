=== Headless CMS ===
Contributors: gsayed786
Tags: headless-cms, decoupled, graphql
Requires at least: 4.6
Tested up to: 5.4.2
Stable tag: 4.9.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that adds features to use WordPress as a headless CMS with any front-end environment using REST API.

== Description ==

A WordPress plugin that adds following features to use WordPress as a headless CMS with any front-end environment using REST API
This plugin provides multiple features and you can use the one's that is relevant to your front-end application. You don't necessarily need to use all.

== Features ==

1. Custom REST API Endpoints.
2. Social links in customizer.
3. Image uploads for categories.
4. Custom header and footer menus.
5. Custom Widgets.
6. Custom Header and Footer GraphQL fields when using [wp-graphql](https://github.com/wp-graphql/wp-graphql) plugin

== Feature Details ==

## Features
* Adds option to add social links in customizer
* Registers two custom menus for header ( menu location = hcms-menu-header ) and for footer ( menu location = hcms-menu-footer )
* Registers the following sidebars
1. HCMS Footer #1 with sidebar id 'hcms-sidebar-1'
2. HCMS Footer #2 with sidebar id 'hcms-sidebar-2'

== Available Custom REST API endpoints ==
1. Get single post ( GET request ): `http://example.com/wp-json/rae/v1/post?post_id=1`

2. Get posts by page no: ( GET Request ) : `http://example.com/wp-json/rae/v1/posts?page_no=1`

3. Get header and footer date: ( GET Request )
* Get the header data ( site title, site description , site logo URL, menu items ) and footer data ( footer menu items, social icons )
* `http://example.com/wp-json/rae/v1/header-footer?header_location_id=hcms-menu-header&footer_location_id=hcms-menu-footer``

4. Get posts by page no: ( GET Request )
* Get the posts by taxonomy
* `http://example.com/wp-json/rae/v1/posts-by-tax?post_type=post&taxonomy=category&slug=xyz`

== More Features ==
1. Registers the sections for socials icons in the customizer

* Social icons urls for 'facebook', 'twitter', 'instagram', 'youtube'

2. Image upload features for categories

* Provides Image upload features for categories.

3. Plugin Settings Page

* Settings for getting data for a custom page like Hero section, Search section, Featured post section, latest posts heading.

== Installation and Use ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Your can add social icons from customizer
4. You can set up custom header and footer menus.
5. You can add image to categories.

== Demo of the Frontend applications that can be used with this plugin ==

Please check the demo of an example React front-end application, where this plugin can be used.

[2020-07-02] Demo.

[youtube https://youtu.be/nYXL1KKjKrc]

= Its not working.

Step 1. Check if your Plugin is activated.
Step 2. Deactivate all plugins and reactivate headless-cms.

== Screenshots ==

1-Plugin Settings. screenshot-1.png
2-GraphQL Fields. screenshot-2.png
3-Category Image Upload. screenshot-3.png
4-Custom Header Menu. screenshot-4.png
5-Custom Footer Menu. screenshot-5.png
