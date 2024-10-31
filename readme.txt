=== RG Responsive Gallery ===
Contributors: wpexpertsin, india-web-developer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4
Tags: responsive gallery, Image Gallery,featured image sldier, featured image,wordpress slider, image slider, gallery, slider
Requires at least: 5.0
Tested up to: 5.7.1
Stable tag: 1.6
Requires PHP: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a simple and light weighted image gallery. Featured image slider

== Description ==

  It's the very simple image gallery plugin. With this plugin we have provided admin control panel of gallery, where admin can manage all settings in a very easy way.

  In this plugin we are providing one important feature i.e "Post Featured Image Slider". If you want to display post featured images of any custom post type as slider then don't worry, we are providing a shortcode to display the post featrued images as slider.  
  
  Video Tutorial : 
  
  https://youtu.be/I9UDBU2E4Qk


= Features = 
 * Responsive gallery
 * Shortcode
 * Featured Image gallery for all custom post type
 
= Shortcode = 
 ==[rr_gallery slider_slug="ENTER SLIDER SLUG"]==
* Using this shortcode we can add slider on any page/post. 
 
 ==[rr_post_thumb_gallery category="ENTER POST CATEGORY SLUG" taxonomy="Enter custom taxonomy type" post_type="Enter custom post type"]==
* Using this shortcode we can display any tpye of posts featured image as slider.

== Installation ==

Step 1. Upload "rg-responsive-gallery" folder to the `/wp-content/plugins/` directory

Step 2. Activate the plugin through the Plugins menu in WordPress

Step 3. Go to Settings/"Responsive Gallery" and configure the plugin settings.

== Frequently Asked Questions ==
= Q.1 How add gallery on my website? =

Ans. Use [rr_gallery slider_slug="ENTER SLIDER SLUG"] shortcode to add the gallery on any page/post.

= Q.2 How add gallery in theme template files? =

Ans. Add given code = echo do_shortcode('[rr_gallery slider_slug="ENTER SLIDER SLUG"]');

= Q.3 How i can find the slider slug? =

Ans. You can find the slider slug from sliders list page.

= Q.4 How i can add custom post type thumbnail gallery ? =

Ans. Use given shortcode [rr_post_thumb_gallery slider_slug="ENTER POST CATEGORY SLUG" taxonomy="Enter custom taxonomy type" post_type="Enter custom post type"] . please don't forget to update slider_slug, taxonomy and post_type value as per your requirement.

== Screenshots ==

1. screenshot-1.png

2. screenshot-2.png

3. screenshot-3.png

4. screenshot-4.png

== Changelog == 

= 1.5 = 
 * Testetd with wordpress version 5.4.2
 * Optimized code

= 1.4 = 
 * Testetd with wordpress version 5.2.4
 * Added shortcode column on slider taxonomy pages
 * Optimized the code
 
= 1.3 = 
 * Testetd with wordpress version 4.8.1
 * Added an option for publish thumbnail gallery of any post types
 
= 1.2 = 
 * Testetd with wordpress version 4.5.2
 * Fixed gallery slider major issue

= 1.1 = 
 * Fixed some js and css related issue
 
= 1.0 = 
 * First stable release
