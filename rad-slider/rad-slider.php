<?php
/*
Plugin Name: Rad Slider
Plugin URI: http://wordpress.melissacabral.com
Description: Adds a Slider based on Custom Post Types
Author: Melissa Cabral
Version: 1.0
License: GPLv2
Author URI: http://melissacabral.com
*/

/* Register a Custom Post Type (Slide) */
add_action('init', 'slide_init');
function slide_init() {
	
	$args = array(
		'labels' => array(
			'name' => 'Slides', 
			'singular_name' => 'Slide', 
			'add_new' => 'Add New', 'slide',
			'add_new_item' => 'Add New Slide',
			'edit_item' => 'Edit Slide',
			'new_item' => 'New Slide',
			'view_item' => 'View Slide',
			'search_items' => 'Search Slides',
			'not_found' => 'No slides found',
			'not_found_in_trash' => 'No slides found in Trash', 
			'parent_item_colon' => '',
			'menu_name' => 'Slides'
		),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'thumbnail')
	); 
	register_post_type('slide', $args);
}


/* Put  little help box at the top of the editor */
add_action('contextual_help', 'slide_help_text', 10, 3);
function slide_help_text($contextual_help, $screen_id, $screen) {
	if ('slide' == $screen->id) {
		$contextual_help ='<p>Things to remember when adding a slide:</p>
		<ul>
		<li>Give the slide a title. The title will be used as the slide\'s headline</li>
		<li>Attach a Featured Image to give the slide its background</li>
		<li>Enter text into the Visual or HTML area. The text will appear within each slide during transitions</li>
		</ul>';
	}
	elseif ('edit-slide' == $screen->id) {
		$contextual_help = '<p>A list of all slides appears below. To edit a slide, click on the slide\'s title</p>';
	}
	return $contextual_help;
}





//This prevents 404 errors when viewing our custom post archives
//always do this whenever introducing a new post type or taxonomy
function rad_slider_rewrite_flush(){
	slide_init();
	flush_rewrite_rules();
}
//run this when the plugin activates
register_activation_hook(__FILE__, 'rad_slider_rewrite_flush');


/* Add the image size for the slider */
function rad_slider_image(){
	add_image_size( 'rad-slider', 960, 330, true );	
}
add_action('init', 'rad_slider_image');
