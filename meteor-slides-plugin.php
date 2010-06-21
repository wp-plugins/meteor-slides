<?php
/*
	Plugin Name: Meteor Slides
	Description: Adds a custom post type for slides to WordPress. Use Meteor Slides to create a quick little slideshow for your site.
	Plugin URI: http://www.jleuze.com/plugins/meteor-slides
	Author: Josh Leuze
	Author URI: http://www.jleuze.com/
	License: GPL2
	Version: 1.0.1
*/

/*  Copyright 2010 Josh Leuze (email : mail@jleuze.com)

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

	// Adds featured image functionality
	
	add_action( 'plugins_loaded', 'meteorslides_featured_image' );

	function meteorslides_featured_image() {
	
		add_theme_support( 'post-thumbnails', array( 'slide' ) );
		
		$options = get_option( 'meteorslides_options' );
		
		add_image_size( 'featured-slide', $options['slide_width'], $options['slide_height'], true );
	
	}

	// Adds custom post type
	
	add_action( 'init', 'meteorslides_register_slides' );

	function meteorslides_register_slides() {
	
		$labels = array(
		
			'name' => _x('Slides', 'post type general name'),
			'singular_name' => _x('Slide', 'post type singular name'),
			'add_new' => _x('Add New', 'slides'),
			'add_new_item' => __('Add New Slide'),
			'edit_item' => __('Edit Slide'),
			'edit' => _x('Edit', 'slides'),
			'new_item' => __('New Slide'),
			'view_item' => __('View Slide'),
			'search_items' => __('Search Slides'),
			'not_found' =>  __('No slides found'),
			'not_found_in_trash' => __('No slides found in Trash'), 
			'view' =>  __('View Slide'),
			'parent_item_colon' => ''
			
		);
  
		$args = array(
	
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => 'slides',
			'rewrite' => true,
			'capability_type' => 'page',
			'hierarchical' => false,
			'supports' => array('title', 'thumbnail'),
			'menu_icon' => ''. plugins_url('/images/slides-icon-20x20.png', __FILE__)
		
		);
  
		register_post_type( 'slide',$args );
		
	}
	
	// Customize and move featured image box to main column
	
	add_action( 'do_meta_boxes', 'meteorslides_image_box' );
	
	function meteorslides_image_box() {
	
		remove_meta_box( 'postimagediv', 'slide', 'side' );
	
		add_meta_box( 'postimagediv', __('Slide Image'), 'post_thumbnail_meta_box', 'slide', 'normal', 'high' );
	
	}
		
	// Adds meta box for slide URL
	
	add_action( 'admin_menu', 'meteorslides_create_url_meta_box' );
	add_action( 'save_post', 'meteorslides_save_postdata' );

	$meteorslides_new_meta_box =

		array(
		
			'slide_url' => array(
			
				'name' => 'slide_url',
				'std' => '',
				'description' => 'Add the URL this slide should link to.'
				
			)

		);

	function meteorslides_new_meta_box() {
	
		global $post, $meteorslides_new_meta_box;

		foreach( $meteorslides_new_meta_box as $meteorslides_meta_box ) {

			$meteorslides_meta_box_value = get_post_meta( $post->ID, $meteorslides_meta_box['name'].'_value', true );  

			if( $meteorslides_meta_box_value == "" ) $meteorslides_meta_box_value = $meteorslides_meta_box['std'];

			echo'<input type="hidden" name="'.$meteorslides_meta_box['name'].'_noncename" id="'.$meteorslides_meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';

			echo'<input type="text" name="'.$meteorslides_meta_box['name'].'_value" value="'.$meteorslides_meta_box_value.'" size="55" /><br />';

			echo'<p>'.$meteorslides_meta_box['description'].'</p>';

		}

	}

	function meteorslides_create_url_meta_box() {
	
		global $theme_name;

		if( function_exists('add_meta_box') ) {

			add_meta_box( 'meteorslides-url-box', 'Slide Link', 'meteorslides_new_meta_box', 'slide', 'normal', 'low' );

		}

	}

	function meteorslides_save_postdata( $post_id ) {

		global $post, $meteorslides_new_meta_box;

		foreach( $meteorslides_new_meta_box as $meteorslides_meta_box ) {

			if( !wp_verify_nonce( $_POST[$meteorslides_meta_box['name'].'_noncename'], plugin_basename(__FILE__) ) ) {

				return $post_id;

			}

			if( 'page' == $_POST['post_type'] ) {

				if( !current_user_can( 'edit_page', $post_id ) )

				return $post_id;

			}
			
			else {
			
				if( !current_user_can( 'edit_post', $post_id ) )

				return $post_id;

			}

			$data = $_POST[$meteorslides_meta_box['name'].'_value'];

			if( get_post_meta( $post_id, $meteorslides_meta_box['name'].'_value' ) == "" ) {
			
				add_post_meta( $post_id, $meteorslides_meta_box['name'].'_value', $data, true );
			
			}
			
			elseif( $data != get_post_meta( $post_id, $meteorslides_meta_box['name'].'_value', true ) ) {

				update_post_meta( $post_id, $meteorslides_meta_box['name'].'_value', $data );
			
			}

			elseif( $data == "" ) {

				delete_post_meta( $post_id, $meteorslides_meta_box['name'].'_value', get_post_meta( $post_id, $meteorslides_meta_box['name'].'_value', true ) );
			
			}
			
		}

	}

	// Adds settings page
	
	add_action( 'admin_menu', 'meteorslides_menu' );

	function meteorslides_menu() {
		
		add_submenu_page( 'edit.php?post_type=slide', 'Slides Settings', 'Settings', 'manage_options', 'slides-settings', 'meteorslides_settings_page' );
	
	}
	
	function meteorslides_settings_page() {
	
		include( 'meteor-slides-settings.php' );
	
	}
	
	// Register options for settings page

	add_action('admin_init', 'meteorslides_register_settings');
	
	function meteorslides_register_settings(){

		register_setting( 'meteorslides_options', 'meteorslides_options', 'meteorslides_options_validate' );
		
		add_settings_section('meteorslides_slideshow', 'Configure Slideshow', 'meteorslides_section_text', 'meteorslides');
		
		add_settings_field('slideshow_quantity', 'Slideshow Quantity', 'slideshow_quantity', 'meteorslides', 'meteorslides_slideshow');

		add_settings_field('slide_height', 'Slide Height', 'slide_height', 'meteorslides', 'meteorslides_slideshow');
		
		add_settings_field('slide_width', 'Slide Width', 'slide_width', 'meteorslides', 'meteorslides_slideshow');

		add_settings_field('transition_style', 'Transition Style', 'transition_style', 'meteorslides', 'meteorslides_slideshow');

		add_settings_field('transition_speed', 'Transition Speed', 'transition_speed', 'meteorslides', 'meteorslides_slideshow');

		add_settings_field('slide_duration', 'Slide Duration', 'slide_duration', 'meteorslides', 'meteorslides_slideshow');
		
	}
	
	// Adds default values for options on settings page
	
	register_activation_hook(__FILE__, 'meteorslides_default_options');
	
	function meteorslides_default_options() {
	
		$tmp = get_option('meteorslides_options');
  
		if(($tmp['chkbox1']=='on')||(!is_array($tmp))) {
		
			$arr = array("slideshow_quantity" => "5", "slide_height" => "200", "slide_width" => "940", "transition_style" => "fade", "transition_speed" => "2", "slide_duration" => "5");	
			
			update_option('meteorslides_options', $arr);
	
		}

	}
	
	// Validates values for options on settings page
	
	function meteorslides_options_validate($input) {

		$options = get_option('meteorslides_options');

		$options['slideshow_quantity'] = trim($input['slideshow_quantity']);

		if(!preg_match('/^[0-9]{1,3}$/i', $options['slideshow_quantity'])) {

			$options['slideshow_quantity'] = '';

		}
		
		$options['slide_height'] = trim($input['slide_height']);

		if(!preg_match('/^[0-9]{1,4}$/i', $options['slide_height'])) {

			$options['slide_height'] = '';

		}
		
		$options['slide_width'] = trim($input['slide_width']);

		if(!preg_match('/^[0-9]{1,5}$/i', $options['slide_width'])) {

			$options['slide_width'] = '';

		}
		
		$options['transition_style'] = trim($input['transition_style']);

		if(!preg_match('/^[a-z]{4,20}$/i', $options['transition_style'])) {

			$options['transition_style'] = '';

		}
		
		$options['transition_speed'] = trim($input['transition_speed']);

		if(!preg_match('/^[0-9]{1,3}$/i', $options['transition_speed'])) {

			$options['transition_speed'] = '';

		}
		
		$options['slide_duration'] = trim($input['slide_duration']);

		if(!preg_match('/^[0-9]{1,3}$/i', $options['slide_duration'])) {

			$options['slide_duration'] = '';

		}

		return $options;
		
	}

	// Adds CSS for the admin pages
	
	add_action('admin_head', 'meteorslides_admin_css');

	function meteorslides_admin_css() {
	
		global $post_type; if (($_GET['post_type'] == 'slide') || ($post_type == 'slide')) :		
	
			echo "<link type='text/css' rel='stylesheet' href='" . plugins_url('/css/meteor-slides-admin.css', __FILE__) . "' />";
	
		endif;
		
	}
	
	// Adds JavaScript for the slideshow
	
	add_action( 'wp_print_scripts', 'meteorslides_javascript' );
		
	function meteorslides_javascript() {
 
		$meteorslides_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
		
		$options = get_option( 'meteorslides_options' );
 
		if( !is_admin() ) {
	  
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-cycle', $meteorslides_plugin_url.'/js/jquery.cycle.all.js', array( 'jquery' ) );
			wp_enqueue_script( 'meteorslides-script', $meteorslides_plugin_url.'/js/slideshow.js', array( 'jquery', 'jquery-cycle' ) );
			wp_localize_script( 'meteorslides-script', 'meteorslidessettings',
			
				array(
				
					'meteorslideshowspeed' => $options['transition_speed'] * 1000,
					'meteorslideshowduration' => $options['slide_duration'] * 1000,
					'meteorslideshowtransition' => $options['transition_style']
					
				)
				
			);
			
		}
	
	}
	
	// Adds function to load slideshow in theme
		
	function meteor_slideshow() {
		
		include( 'meteor-slideshow.php' );
	
	}
		
		/* To load the slideshow, add this line to your theme:
	
			<?php if(function_exists('meteor_slideshow')) { meteor_slideshow(); } ?>
	
		*/
		
	// Adds shortcode to load slideshow in content
	
	add_shortcode( 'meteor_slideshow', 'meteor_slideshow' );
	
		/* To load the slideshow, add this line to your page or post:
	
			[meteor_slideshow]
	
		*/
		
?>