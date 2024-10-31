<?php 
/** Plugin created by raghunath gurjar 
 * Create a new responsive gallery post type
 * define the all options for new post type
 * @init
 * @register_post_type
 * @add_meta_boxes
 * @bxSlider library
 * */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Function for get all options
 * @WP_Query()
 * 
 * */
if(!function_exists('get_rrg_admin_options')){ 
	function get_rrg_admin_options() {
		global $wpdb;
		$rgOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'rrg_%'");
								
		foreach ($rgOptions as $option) {
			$rgOptions[$option->option_name] =  $option->option_value;
		}
	
		return $rgOptions;	
	}
}
/* Get Plugin Options */	
$pluginOptions=get_rrg_admin_options();	
/*Is plugin active*/
$isEnable='';
if(isset($pluginOptions['rrg_active']) && $pluginOptions['rrg_active']!=''){$isEnable=$pluginOptions['rrg_active'];}
if($isEnable){
/** add css to wp_head */
add_action('wp_enqueue_scripts','add_rg_responsive_gallery_style');
}

/*Is plugin active*/
$isEnable='';
if(isset($pluginOptions['rrg_active']) && $pluginOptions['rrg_active']!=''){$isEnable=$pluginOptions['rrg_active'];}
if($isEnable){
/** shortcode */
add_shortcode( 'rr_gallery', 'rrg_responsive_gallery_func' );
/** category post */
add_shortcode( 'rr_post_thumb_gallery', 'rrg_post_thumb_gallery_func' );
}

/*  Post thumbnail slider */
if(!function_exists('rrg_post_thumb_gallery_func')):
function rrg_post_thumb_gallery_func( $attr ) {
$terms=isset($attr['category']) ? $attr['category'] : (isset($attr['slider_slug']) ? $attr['slider_slug'] : '');
$taxonomy=isset($attr['taxonomy']) ? $attr['taxonomy'] : 'category';
$postType=isset($attr['post_type']) ? $attr['post_type'] : 'post';
$field='slug';
if($terms!=''){
wp_reset_query();
$args = array(
    'post_type' => $postType,
    'post_status' => 'publish',
    'show_ui'            => true,
	'show_in_menu'       => true,
	'orderby' => 'post_date',
	'order'   => 'DESC',
	'menu_position' =>5,
	'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => $taxonomy,
            'field' => $field,
            'terms' => $terms
        )
    )
);
$rrg_query = new WP_Query( $args );
//echo $rrg_query->request;
$rrg_thumbcontent='';
if($rrg_query->have_posts()):
$rrg_thumbcontent.="<div id='rrg_post_thumb_gallery'><div class='rrg_inner'>";
$category = get_term_by($field, $terms, $taxonomy);
$rrg_thumbcontent.='<div class="heading"><span>'.$category->name.'</span></div>';
$rrg_thumbcontent.='<div class="rrg-thumb-gallery">';
while($rrg_query->have_posts()) : $rrg_query->the_post();
 $postTitle=get_the_title();
 if(has_post_thumbnail()):
  $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'  );
  $rrg_thumburl =$large_image_url[0];
  else:
  $rrg_thumburl=get_post_meta(get_the_ID(),'_rrg_image_path',true) ? get_post_meta(get_the_ID(),'_rrg_image_path',true) : get_post_meta(get_the_ID(),'rrg_image_path',true);
  endif;
  $rrg_thumbcontent.='<div class="slide"><a href="'.get_the_permalink().'" title="'. $postTitle.'"><img src="'.$rrg_thumburl.'" title="'. $postTitle.'" alt="'. $postTitle.'" /></a><a href="'.get_the_permalink().'" title="'. $postTitle.'" class="link-heading">'.$postTitle.'</a></div>';
endwhile;
$rrg_thumbcontent.='</div>';
$rrg_thumbcontent.="</div></div>";
add_action('wp_footer','rrg_post_thumb_add_inline_js');
return $rrg_thumbcontent;
endif;
return "";
}else
{
	return "You have not added slider slug in shortcode, please add your slider slug";
	}
}
endif;
/** Responsive slider */
if(!function_exists('rrg_responsive_gallery_func')):
function rrg_responsive_gallery_func( $attr ) {
$terms='';
$terms=$attr['slider_slug'];
$field='slug';
if($terms!=''){
wp_reset_query();
$args = array(
    'post_type' => 'rrg_gallery',
    'post_status' => 'publish',
    'show_ui'            => true,
	'show_in_menu'       => true,
	'menu_position' =>5,
    'tax_query' => array(
        array(
            'taxonomy' => 'rrg_gallery_taxonomy',
            'field' => $field,
            'terms' => $terms
        )
    )
);
$rrg_query = new WP_Query( $args );
$rrg_content='';
if($rrg_query->have_posts()):
$rrg_content.="<div class='rrg_gallery_block'><div class='rrg_inner'>";
$rrg_image_path=$rrg_image_alt=$rrg_image_desc=$rrg_url='';
$rrg_content.='<ul class="rrgallery">';
while($rrg_query->have_posts()) : $rrg_query->the_post();
 
 $rrg_img=get_post_meta(get_the_ID(),'_rrg_image_path',true) ? get_post_meta(get_the_ID(),'_rrg_image_path',true) : get_post_meta(get_the_ID(),'rrg_image_path',true);
  
  if(is_numeric($rrg_img))
  {
	  $rrg_path = wp_get_attachment_image_src($rrg_img,'full')[0];
	  
	  }else
	  {
		 $rrg_path = $rrg_img;
		  }
  
  $rrg_alt=get_post_meta(get_the_ID(),'_rrg_alt',true) ? get_post_meta(get_the_ID(),'_rrg_alt',true) : get_post_meta(get_the_ID(),'rrg_alt',true);
  $rrg_desc=get_post_meta(get_the_ID(),'_rrg_desc',true) ? get_post_meta(get_the_ID(),'_rrg_desc',true) :get_post_meta(get_the_ID(),'rrg_desc',true);
  $rrg_url=get_post_meta(get_the_ID(),'_rrg_url',true) ? get_post_meta(get_the_ID(),'_rrg_url',true) : get_post_meta(get_the_ID(),'rrg_url',true);
  if($rrg_url!=''):
  $rrg_content.='<li><a href="'.$rrg_url.'" title="'.$rrg_alt.'"><img src="'.$rrg_path.'" title="'.$rrg_alt.'" alt="'.$rrg_alt.'" /></a></li>';
  else:
  $rrg_content.='<li><img src="'.$rrg_path.'" title="'.$rrg_alt.'" alt="'.$rrg_alt.'" /></li>';
  endif;
endwhile;
$rrg_content.='</ul>';
$rrg_content.="</div></div>";
add_action('wp_footer','rrg_add_inline_js');
return $rrg_content;
endif;
return "";
}else
{
	return "You have not added slider slug in shortcode, please add your slider slug";
	}
}
endif;
/** load slider inline js */
if(!function_exists('rrg_add_inline_js')):
function rrg_add_inline_js()
{
//rrg_autostart rrg_controls rrg_captions rrg_infiniteLoop rrg_speed
$mode=get_option('rrg_mode');
if($mode==''){$mode='fade';}

$autoplay=get_option('rrg_autostart');
if($autoplay!=''){$autoplay=true;}else{$autoplay=0;}

$rrg_captions=get_option('rrg_captions');
if($rrg_captions==''){$rrg_captions='false';}

$rrg_controls=get_option('rrg_controls');
if($rrg_controls==''){$rrg_controls='0';}

$rrg_pager=get_option('rrg_pager');
if($rrg_pager==''){$rrg_pager='0';}

$infiniteLoop=get_option('rrg_infiniteLoop');
if($infiniteLoop==''){$infiniteLoop='0';}

$rrg_speed=get_option('rrg_speed');
if($rrg_speed==''){$rrg_speed='500';}

$custoStyle = get_option('rrg_style_css');
$rrg_inline_js="<script>jQuery('.rrgallery').rrGallery({
  mode: '".$mode."',
  captions: ".$rrg_captions.",
  controls: ".$rrg_controls.",
  pager:".$rrg_pager.",
  auto:$autoplay,
  infiniteLoop:".$infiniteLoop.",
  speed:".$rrg_speed."
});</script>";
$rrg_inline_js .='<style type="text/css">'.$custoStyle.'</style>';
print $rrg_inline_js;
}
endif;
/** post thumbnail */

if(!function_exists('rrg_post_thumb_add_inline_js')):
function rrg_post_thumb_add_inline_js()
{
$custoStyle = get_option('rrg_style_css');
$rrg_inline_js="<script>
jQuery(document).ready(function(){
  jQuery('.rrg-thumb-gallery').rrGallery({
    slideWidth: 180,
    minSlides: 2,
    maxSlides: 3,
    slideMargin: 10,
    pager:false,
    auto:true,
    autoStart:1
    
  });
});
</script>";
$rrg_inline_js .='<style type="text/css">'.$custoStyle.'</style>';
print $rrg_inline_js;
}
endif;
