<?php
/** register custom post type */
add_action('init','rgrg_post_type_init');
//define action for create new meta boxes
add_action( 'add_meta_boxes', 'rgrg_add_post_meta_box' );
//Define action for save to "WP Youtube Gallery" Meta Box fields Value
add_action( 'save_post', 'rgrg_meta_box_save_post' );

if(!function_exists('rgg_sanitize_fields')){ 
function rgg_sanitize_fields($type='',$val='')
{
	// Is this textarea
	if($type='textarea')
	{
	  $val = sanitize_textarea_field($val);
	}else
	{
		$val = sanitize_text_field($val);
	}
	return $val;
  }
}

if(!function_exists('rgrg_post_type_init')){ 
function rgrg_post_type_init()
{
	$labels = array(
	   'name'=>__('Responsive Gallery'),
	   'singular_name'=>__('Slider'),
	   'all_items'=>__('All Slides'),
	   'add_new'=>__('Add New slide'),
	   'edit_item'         => __( 'Edit Slide' ),
	   'update_item'       => __( 'Update Slide' ),
	   'add_new_item'      => __( 'Add New Slide' ),);
	
	register_post_type('rrg_gallery',
	array(
	 'labels'=>$labels,
	 'public'=>true,
	 'has_archive'=>false,
	 'supports'=>array('title','page-attributes'),
	 'rewrite' =>true
	    )
	);
}
}

/*
 * Add New Meta Box Field For Responsive Gallery
 * define all meta boxes that will be publish on Responsive Gallery posts 
 * */

/**
 * Adds the Responsive Gallery meta box
 */
if(!function_exists('rgrg_add_post_meta_box')){ 
function rgrg_add_post_meta_box()
{
 global $wpyg_meta_box;
	$screens = array( 'rrg_gallery');
	foreach ( $screens as $screen ) {

		add_meta_box(
			'rg-meta-box',
			__( 'Slide Image', 'rg' ),
			'show_rg_responsive_gallery_meta_box',
			$screen
		);
	}

}
}

//Define meta box fields

  $rrg_prefix = '_rrg_';
    $rrg_meta_box = array(
    'id' => 'rg-meta-box',
    'title' => 'Gallery Image',
    'page' => '',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    array(
    'name' => '',
    'desc' => '',
    'id' => $rrg_prefix. 'image_path',
    'type' => 'image',
    'std' => ''
    ),array(
    'name' => 'Image Alt: ',
    'desc' => '',
    'id' => $rrg_prefix . 'alt',
    'type' => 'text',
    'std' => ''
    ),array(
    'name' => 'Description ',
    'desc' => '',
    'id' => $rrg_prefix . 'desc',
    'type' => 'textarea',
    'std' => ''
    ),
    array(
    'name' => 'Link URL: ',
    'desc' => '',
    'id' => $rrg_prefix. 'url',
    'type' => 'text',
    'std' => ''
    )
    )
    );


//Display Responsive Gallery Meta Box
if(!function_exists('show_rg_responsive_gallery_meta_box')){ 
function show_rg_responsive_gallery_meta_box()
{
global $rrg_meta_box, $post;
$crnimg='';
    wp_nonce_field( 'rg_responsive_gallery_box_field', 'rg_responsive_gallery_box_meta_box_once' );
    foreach ($rrg_meta_box['fields'] as $field) {
    // get current post meta data
    $meta = get_post_meta($post->ID, $field['id'], true);
    if($meta!='' && $field['type']=='image'){$crnimg='Current Image <img src="'.$meta.'" width="100%" height="100%">';}
    echo '<p>',
    '<label for="', $field['id'], '">', $field['name'], '</label>','';
    switch ($field['type']) {
    case 'text':
    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
    break;

    
	case 'image':
	echo '<div class="form-field term-group">
		 <label for="'.$field['id'].'">Slide Image</label>'.($meta ? '<br><a href="'.wp_get_attachment_image_src($meta,'full')[0].'" target="_blank"><img src="'.wp_get_attachment_image_src($meta,'medium')[0].'"></a>' :'').'
		 <input type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" class="custom_media_url" value="'.($meta ? $meta : $field['std']).'">
		 <div id="'.$field['id'].'-image-wrapper" class="hooks-image-wrapper"></div>
		 <p>
		   <input type="button" class="button button-secondary rgrg_media_button" id="rgrg_media_button" data-fieldid="'.$field['id'].'" name="rgrg_media_button" value="Add Image" />
		   <input type="button" class="button button-secondary rgrg_media_remove" id="rgrg_media_remove" data-fieldid="'.$field['id'].'" name="rgrg_media_remove" value="Remove Image" />
		</p>
	   </div>'; 
	break;	
    case 'textarea':
    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
    break;
    case 'select':
    echo '<select name="', $field['id'], '" id="', $field['id'], '" >';
    $optionVal=explode(',',$field['options']);
    foreach($optionVal as $optVal):
    if($meta==$optVal){
    $valseleted =' selected="selected"';}else {
		 $valseleted ='';
		}
    echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
    endforeach;
    echo '</select>','<br />',$field['desc'];
    break;
    '</p>';
    }

    }
}
}

if(!function_exists('rgrg_meta_box_save_post')){ 
function rgrg_meta_box_save_post($post_id) {
	global $rrg_meta_box;
	// Check if our nonce is set.
	 if ( ! isset( $_POST['rg_responsive_gallery_box_meta_box_once'] ) ) {
			return;
		}
		
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	return $post_id;
	}

	// check permissions
	if ('rrg_gallery' == $_POST['post_type']) 
	{
		if (!current_user_can('edit_page', $post_id))
		return $post_id;
	} 
	elseif(!current_user_can('edit_post', $post_id)){
	return $post_id;
	}
	
	foreach ($rrg_meta_box['fields'] as $field) 
	{
		$old = get_post_meta($post_id, $field['id'], true);
		$new = rgg_sanitize_fields($field['type'],$_POST[$field['id']]);
		if ($new && $new != $old){
		 update_post_meta($post_id, $field['id'], $new);
		} 
		elseif ('' == $new && $old) {
		delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
}

/* 
 * Register New "Responsive Gallery" Texonimoies
 * @register_taxonomy()
 * 
 * */
// define init action and call create_wp_youtube_gallery_taxonomies when it fires
add_action( 'init', 'create_rg_responsive_gallery_taxonomies', 0 );
if(!function_exists('create_rg_responsive_gallery_taxonomies')){ 
function create_rg_responsive_gallery_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Sliders', 'Video Categories' ),
		'singular_name'     => _x( 'Slider', 'Video' ),
		'search_items'      => __( 'Search Slider' ),
		'all_items'         => __( 'All Sliders' ),
		'parent_item'       => __( 'Parent Slider' ),
		'parent_item_colon' => __( 'Parent Slider:' ),
		'edit_item'         => __( 'Edit Slider' ),
		'update_item'       => __( 'Update Slider' ),
		'add_new_item'      => __( 'Add New Slider' ),
		'new_item_name'     => __( 'New Slider Name' ),
		'menu_name'         => __( 'Sliders' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'rrg_gallery', 'with_front' => false,'hierarchical' => false),
	);

	register_taxonomy( 'rrg_gallery_taxonomy', array( 'rrg_gallery' ), $args );
}
}
/* End Responsive Gallery Taxonomies */
add_filter( 'manage_edit-rrg_gallery_columns', 'my_edit_rrg_gallery_columns' ) ;
if(!function_exists('my_edit_rrg_gallery_columns')){ 
function my_edit_rrg_gallery_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'rrg_image_path' => __( 'Image' ),
		'rrg_alt' => __( 'Alt' ),
		'menu_order' => __( 'Sort Order' ),
		'taxonomy-rrg_gallery_taxonomy' => __( 'Sliders' ),
		'date' => __( 'Date' )
	);

	return $columns;
}
}
add_action( 'manage_rrg_gallery_posts_custom_column', 'manage_rrg_gallery_columns', 10, 2 );

if(!function_exists('manage_rrg_gallery_columns')){
function manage_rrg_gallery_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'duration' column. */
		case 'rrg_image_path' :

			/* Get the post meta. */
			$rrg_img = get_post_meta( $post_id, '_rrg_image_path', true ) ? get_post_meta( $post_id, '_rrg_image_path', true ) : get_post_meta( $post_id, 'rrg_image_path', true );
			
		  if(is_numeric($rrg_img))
		  {
			  $rrg_image_path = wp_get_attachment_image_src($rrg_img)[0];
			  
			  }else
			  {
				 $rrg_image_path = $rrg_img;
				  }
			

			/* If no duration is found, output a default message. */
			if ( empty( $rrg_image_path ) )
				echo __( 'N/A' );

			/* If there is a duration, append 'minutes' to the text string. */
			else
				printf( __( '<img src="%s" width="50" height="50" title="%s"/>' ), $rrg_image_path ,get_the_title($post_id));

			break;

		/* If displaying the 'genre' column. */
		case 'rrg_alt' :

			/* Get the post meta. */
			$rrg_alt = get_post_meta( $post_id, '_rrg_alt', true ) ? get_post_meta( $post_id, '_rrg_alt', true ) : get_post_meta( $post_id, 'rrg_alt', true );

			/* If no duration is found, output a default message. */
			if ( empty( $rrg_alt ) )
				echo __( 'N/A' );

			/* If there is a duration, append 'minutes' to the text string. */
			else
				printf( __( '%s' ), $rrg_alt );

			break;
		/* If displaying the 'genre' column. */
		case 'menu_order' :

			/* Get the post meta. */
			$menu_order =get_post_field( 'menu_order', $post_id);;

			/* If no duration is found, output a default message. */
			if ( empty( $menu_order ) )
				echo __( '0' );

			/* If there is a duration, append 'minutes' to the text string. */
			else
				printf( __( '%s' ), $menu_order );

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
}

add_filter( 'manage_edit-rrg_gallery_sortable_columns', 'rrg_gallery_sortable_columns' );

add_action( 'admin_print_footer_scripts', 'add_rgrg_media_script');

/*
	 * Add media upload script in admin footer
	 * @since 1.0.0
	 */
if(!function_exists('add_rgrg_media_script')){
	function add_rgrg_media_script() { 
		wp_enqueue_media();
		$footerscript ='<script type="text/javascript">
		 if(typeof jQuery!="undefined")
		 { 			
		 jQuery(document).ready( function(){
			 jQuery("body").on("click",".rgrg_media_button",function(){
			 _orig_send_attachment = wp.media.editor.send.attachment;
			   var button_id = "#"+jQuery(this).attr("id"); 
			   var button_filed_id = "#"+jQuery(this).attr("data-fieldid");
			   var send_attachment_bkp = wp.media.editor.send.attachment;
			   var button = jQuery(button_id);
			   _custom_media = true;
			   wp.media.editor.send.attachment = function(props, attachment){
				 if ( _custom_media) {
				   jQuery(button_filed_id).val(attachment.id);
				   jQuery(button_filed_id+\'-image-wrapper\').html(\'<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:150px;float:none;" />\');
				   jQuery(button_filed_id+\'-image-wrapper .custom_media_image\').attr(\'src\',attachment.url).css(\'display\',\'block\');
						  _custom_media = false;
				 } else {
				   return _orig_send_attachment.apply( button_id, [props, attachment] );
				 }
				}
			 wp.media.editor.open(button);
			 return false;
		   });
		 jQuery(\'body\').on(\'click\',\'.rgrg_media_remove\',function(){
		   var button_id = \'#\'+jQuery(this).attr(\'id\'); 
		   var button_filed_id = \'#\'+jQuery(this).attr(\'data-fieldid\');
		   jQuery(button_filed_id).val(\'\');
		   jQuery(button_filed_id+\'-image-wrapper\').html(\'<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />\');
		 });
	   });
	   // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
		 jQuery(document).ajaxComplete(function(event, xhr, settings) {
		   var queryStringArr = settings.data.split(\'&\');
		   if( jQuery.inArray(\'action=add-tag\', queryStringArr) !== -1 ){
			 var xml = xhr.responseXML;
			 $response = jQuery(xml).find(\'term_id\').text();
			 if($response!=""){
			   // Clear the thumb image
			   jQuery(\'.hooks-image-wrapper\').html(\'\');
			 }
		   }
		 }); 
   }
		 </script>';
print $footerscript;
} 
}

if(!function_exists('rrg_gallery_sortable_columns')){
function rrg_gallery_sortable_columns( $columns ) {

	$columns['menu_order'] = 'menu_order';

	return $columns;
}
}

if(!function_exists('manage_rrg_gallery_taxonomy_columns')){
function manage_rrg_gallery_taxonomy_columns($columns)
{
 // add 'My Column'
 $columns['rgrg_shortcode_column'] = 'Shortcode';

 return $columns;
}
}
add_filter('manage_edit-rrg_gallery_taxonomy_columns','manage_rrg_gallery_taxonomy_columns');
if(!function_exists('manage_rrg_gallery_taxonomy_custom_fields')){
function manage_rrg_gallery_taxonomy_custom_fields($deprecated,$column_name,$term_id)
{
 if ($column_name == 'rgrg_shortcode_column') {
	 $term = get_term_by('id', $term_id, 'rrg_gallery_taxonomy');
   echo '[rr_gallery slider_slug="'.$term->slug.'"]';
 }
}
}
add_filter ('manage_rrg_gallery_taxonomy_custom_column', 'manage_rrg_gallery_taxonomy_custom_fields', 10,3);

// Only run our customization on the 'edit.php' page in the admin.
add_action( 'load-edit.php', 'edit_rrg_gallery_load' );
if(!function_exists('edit_rrg_gallery_load')){
function edit_rrg_gallery_load() {
	add_filter( 'request', 'sort_rrg_gallery' );
}
}
if(!function_exists('sort_rrg_gallery')){
// Sorts the movies. 
function sort_rrg_gallery( $vars ) {
	// Check if we're viewing the 'movie' post type. 
	if ( isset( $vars['post_type'] ) && 'rrg_gallery' == $vars['post_type'] ) {
	}
	return $vars;
}
}
// remove get shortlink url
add_filter( 'pre_get_shortlink', function( $false, $post_id ) {
    return 'rrg_gallery' === get_post_type( $post_id ) ? '' : $false;
}, 10, 2 );
// remove whole permilank area
add_filter('get_sample_permalink_html', 'rgrg_permalink_html', '',4);
if(!function_exists('rgrg_permalink_html')){
function rgrg_permalink_html($return, $id, $new_title, $new_slug) {
    global $post;
    return (isset($post) && is_singular('rrg_gallery')) ? '' : $return;     
}
}
