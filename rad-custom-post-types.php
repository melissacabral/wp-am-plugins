<?php
/*
Plugin Name: Custom Post Type - Products
Plugin URI: http://wordpress.melissacabral.com
Description: Adds Products to the admin panel
Version: 1.0
Author: Melissa Cabral
Author URI: http://melissacabral.com
License: GPLv3
*/

//tell wordpress about our products post type
function rad_custom_post_type(){
	register_post_type( 'product', array(
		'labels' => array(
			'name' => 'Products',
			'singular_name' => 'Product',
			'add_new_item' => 'Add New Product',
			'not_found' => 'No Products Found',
			'view_item' => 'View Product',
			'new_item' => 'New Product'
		),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array( 'slug' => 'shop' )
	) );
	//turn on admin panel features
	$supports = array( 'thumbnail', 'excerpt', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'author' );
	add_post_type_support( 'product', $supports );
	
	//add Brand taxo to the products (taxonomy slug, post type slug, args)
	register_taxonomy( 'brand', 'product', array(
		'labels' => array(
			'name' => 'Brands',
			'singular_name' => 'Brand',
			'add_new_item' => 'Add New Brand',
			'search_items' => 'Search Brands'
		),
		'hierarchical' => true, //parent/child, like categories
		'rewrite' => array( 'slug' => 'brands' )
	) );
	
	//add features taxo to the products (taxonomy slug, post type slug, args)
	register_taxonomy( 'feature', 'product', array(
		'labels' => array(
			'name' => 'Features',
			'singular_name' => 'Feature',
			'add_new_item' => 'Add New Feature',
			'search_items' => 'Search Features'
		),
		'hierarchical' => false, //flat, like tags
		'rewrite' => array( 'slug' => 'features' )
	) );
	
}
add_action( 'init', 'rad_custom_post_type' );


//Rebuild the permalinks WHEN THIS PLUGIN ACTIVATES ONLY!!!!!
function rad_rewrite_flush(){
	rad_custom_post_type();
	//never put this function outside the activation hook
	flush_rewrite_rules();	
}
register_activation_hook( __FILE__, 'rad_rewrite_flush' );