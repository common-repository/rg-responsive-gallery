<?php
/*
  Plugin Name: RG Responsive Gallery
  Description: "RG Responsive Gallery" is a plugin to add a responsive slider on your website. This plugin provide an option to categories gallery on basis you your gallery images.
  Author: WP Experts Team
  Author URI: https://www.wp-experts.in/
  License URI: license.txt
  Version: 1.6
 */
/*  Copyright 2015- 2020  rg-responsive-gallery  (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/** Register and create an option page */
add_action('admin_menu','rrg_admin_menu_init');
if(!function_exists('rrg_admin_menu_init')):
function rrg_admin_menu_init(){
add_submenu_page( 'edit.php?post_type=rrg_gallery', 'Responsive Gallery Settings', 'Settings', 'manage_options', 'rg-responsive-gallery-settings', 'rrg_gallery_admin_option_page_func' ); 
}
endif;
/** Define Action for register "Gallery" Options */
add_action('admin_init','rrg_register_settings_init');
if(!function_exists('rrg_register_settings_init')):
function rrg_register_settings_init(){
 register_setting('rrg_setting_options','rrg_active');
 register_setting('rrg_setting_options','rrg_autostart');
 register_setting('rrg_setting_options','rrg_controls');
 register_setting('rrg_setting_options','rrg_captions');
 register_setting('rrg_setting_options','rrg_infiniteLoop');
 register_setting('rrg_setting_options','rrg_speed');
 register_setting('rrg_setting_options','rrg_mode');
 register_setting('rrg_setting_options','rrg_pager');
 register_setting('rrg_setting_options','rrg_style_css');
} 
endif;
// Add settings link to plugin list page in admin
if(!function_exists('rrg_add_plugin_settings_link')):
function rrg_add_plugin_settings_link( $links ) {
            $settings_link = '<a href="edit.php?post_type=rrg_gallery&page=rg-responsive-gallery-settings">' . __( 'Settings', 'rrg' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
  }
endif;
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'rrg_add_plugin_settings_link' );
/* 
*Display the Options form for Responsive Gallery
*/
function rrg_gallery_admin_option_page_func(){ ?>
	<div style="width: 80%; padding: 10px; margin: 10px;"> 
	<h1>Responsive Gallery Settings</h1>
<!-- Start Options Form -->
	<form action="options.php" method="post" id="rg-sidebar-admin-form">
	<div id="rg-tab-menu"><a id="rg-general" class="rg-tab-links active" >General</a> <a id="rg-shortcodes" class="rg-tab-links" >Shortcodes</a> <a  id="rg-support" class="rg-tab-links">Support</a> </div>
	<div class="rg-setting">
	<!-- General Setting -->	
	<div class="first rg-tab" id="div-rg-general">
	<h2>General Settings</h2>
	<p> <input type="checkbox" id="rrg_active" name="rrg_active" value='1' <?php checked(get_option('rrg_active'),'1');?>/><label><?php _e('Enable:');?></label></p>
	<p><input type="checkbox" id="rrg_autostart" name="rrg_autostart" value='1' <?php checked(get_option('rrg_autostart'),'1');?>/><label><?php _e('Auto Start');?></label> </p>
	<p><input type="checkbox" id="rrg_controls" name="rrg_controls" value='1' <?php checked(get_option('rrg_controls'),'1');?>/><label><?php _e('Show Control Bar');?></label></p>  
	<p><input type="checkbox" id="rrg_pager" name="rrg_pager" value='1' <?php checked(get_option('rrg_pager'),'1');?>/><label><?php _e('Show Pagination');?></label></p>  
	<p><input type="checkbox" id="rrg_captions" name="rrg_captions" value='1' <?php checked(get_option('rrg_captions'),'1');?>/><label><?php _e('Enable Caption');?></label> </p>	
	<p><input type="checkbox" id="rrg_infiniteLoop" name="rrg_infiniteLoop" value='1' <?php checked(get_option('rrg_infiniteLoop'),'1');?>/><label><?php _e('Enable Infinite Loop');?></label> </p>
	<p><label><?php _e('Define Speed:');?></label><input type="text" id="rrg_speed" name="rrg_speed" size="10" value="<?php echo get_option('rrg_speed'); ?>" placeholder="500"> </p>
	<p><label><?php _e('Type of Mode:');?></label><select id="rrg_mode" name="rrg_mode" ><option value="horizontal" <?php selected(get_option('rrg_mode'),'horizontal');?>>horizontal</option><option value="vertical" <?php selected(get_option('rrg_mode'),'vertical');?>>vertical</option><option value="fade" <?php selected(get_option('rrg_mode'),'fade');?>>fade</option></select></p>
	<p valign="top"><label><?php _e('Custom CSS:');?></label><br><textarea id="rrg_style_css" name="rrg_style_css" rows="10" cols="100" placeholder="#rrg_post_thumb_gallery {background:#000;}"><?php echo get_option('rrg_style_css'); ?></textarea></p>    
	</div>
	<!-- Shortcodes -->
	<div class="author rg-tab" id="div-rg-shortcodes">
	<h2>Shortcodes</h2>
	<ol>
	<li><b>[rr_gallery slider_slug="ENTER SLIDER SLUG"]</b>:<br> You can add gallery using this shortcode on any page.<br><br>You can add shortcode into your templates files , just need to add given below code into your template files and update category slug <pre>if(function_exists('rg_responsive_gallery_func')){<br> echo do_shortcode('[rr_gallery slider_slug="ENTER SLIDER SLUG"]');<br>} <br> </pre></li>
	<li><b>[rr_post_thumb_gallery slider_slug="ENTER CATEGORY SLUG"]</b>:<br> You can add post featured image gallery using this shortcode on any page/post.<br><br>You can add shortcode into your templates files , just need to add given below code into your template files and update category slug <pre>if(function_exists('rrg_post_thumb_gallery_func')){<br> echo do_shortcode('[rr_post_thumb_gallery category="ENTER CATEGORY SLUG"]');<br>} <br>Recent post will be publish by publish date in decending order, that's means always recent publish post will come firsy. </pre></li>	
	
	<li><b>[rr_post_thumb_gallery slider_slug="ENTER POST CATEGORY SLUG" taxonomy="Enter custom taxonomy type" post_type="Enter custom post type"]</b>:please don't forget to update slider_slug, taxonomy and post_type value as per your requirement. <br><pre>if(function_exists('rrg_post_thumb_gallery_func')){<br> echo do_shortcode('[rr_post_thumb_gallery slider_slug="ENTER POST CATEGORY SLUG" taxonomy="Enter custom taxonomy type" post_type="Enter custom post type"]');<br>} <br>Recent post will be publish by publish date in decending order, that's means always recent publish post will come firsy. </pre></li>
	</ol></div>
	<!-- Support -->
	<div class="last author rg-tab" id="div-rg-support">
	<h2>Plugin Support</h2>
	<table>
	<tr>
	<td width="50%"><p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="<?php echo  plugins_url( 'images/btn_donate_LG.gif' , __FILE__ );?>" title="Donate for this plugin"></a></p>
	<p><strong>Plugin Author:</strong><a href="https://www.wp-experts.in" target="_blank">WP Experts Team</a></p>
	<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p></td>
	<td><p><strong>Our Other Plugins:</strong><br>
		<ol>
					<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
							<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
							<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
							<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
							<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
							<li><a href="https://wordpress.org/plugins/optimize-wp-website/" target="_blank">Optimize WP Website</a></li>
							<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
							<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
							<li><a href="https://wordpress.org/plugins/optimize-wp-website/" target="_blank">Optimize WP Website</a></li>
							<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
							<li><a href="https://wordpress.org/plugins/wp-sales-notifier/" target="_blank">WP Sales Notifier</a></li>
							<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
					</ol></td>
	</tr>
	</table>
	</div>
	</div>
    <span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
    <?php settings_fields('rrg_setting_options'); ?>
	</form>
<!-- End Options Form -->
	</div>
<?php
}
/** add js into admin footer */
add_action('admin_footer','init_rrg_admin_scripts');
if(!function_exists('init_rrg_admin_scripts')):
function init_rrg_admin_scripts()
{
wp_register_style( 'rrg_admin_style', plugins_url( 'css/rrg-admin.css',__FILE__ ) );
wp_enqueue_style( 'rrg_admin_style' );
echo $script='<script type="text/javascript">
	/* Responsive Gallery js for admin */
	jQuery(document).ready(function(){
		jQuery(".rg-tab").hide();
		jQuery("#div-rg-general").show();
	    jQuery(".rg-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".rg-tab-links").removeClass("active");
		jQuery(".rg-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		})
		})
	</script>';

	}	
endif;	

/** include file */
require dirname(__FILE__).'/lib/activate.php';
require dirname(__FILE__).'/lib/rrg-class.php';

add_action( 'admin_enqueue_scripts', 'rrg_image_enqueue' );
function rrg_image_enqueue() {
    global $typenow;
        wp_enqueue_media();
        // Registers and enqueues the required javascript.
        wp_register_script( 'rrg-meta-box-image', plugin_dir_url( __FILE__ ) . 'js/meta-box-image.js', array( 'jquery' ) );
        wp_localize_script( 'rrg-meta-box-image', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'prfx-textdomain' ),
                'button' => __( 'Use this image', 'prfx-textdomain' ),
            )
        );
        wp_enqueue_script( 'rrg-meta-box-image' );
}

/** register_activation_hook */
/** Delete exits options during activation the plugins */
if( function_exists('register_activation_hook') ){
   register_activation_hook(__FILE__,'init_activation_rrg_plugins');   
}
//Disable free version after activate the plugin
if(!function_exists('init_activation_rrg_plugins')):
function init_activation_rrg_plugins(){
//
}
endif;
/* 
*Delete an options during disable the plugins 
*/
if( function_exists('register_uninstall_hook') )
register_uninstall_hook(__FILE__,'rrg_plugin_uninstall');   
//Delete all Custom Tweets options after delete the plugin from admin
if(!function_exists('rrg_plugin_uninstall')):
function rrg_plugin_uninstall(){
delete_option('rrg_active');
}
endif;
/** register_deactivation_hook */
/** Delete exits options during deactivation the plugins */
if( function_exists('register_deactivation_hook') ){
   register_deactivation_hook(__FILE__,'init_deactivation_rrg_plugins');   
}
//Delete all options after uninstall the plugin
if(!function_exists('init_deactivation_rrg_plugins')):
function init_deactivation_rrg_plugins(){
delete_option('rrg_active');
}
endif;
if(!function_exists('add_rg_responsive_gallery_style')){ 
	function add_rg_responsive_gallery_style()
	{
	wp_enqueue_script( 'jquery' );
	wp_register_style( 'rrg_style', plugins_url( 'css/rrg.css',__FILE__ ) );
	wp_enqueue_style( 'rrg_style' );
    wp_register_script( 'rrg_gallery_script', plugins_url( 'js/rrg.min.js',__FILE__ ) );
    wp_enqueue_script( 'rrg_gallery_script' );
	}
}
