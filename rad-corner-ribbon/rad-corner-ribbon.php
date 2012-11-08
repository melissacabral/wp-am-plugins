<?php 
/*
Plugin Name: Subscribe to RSS Corner Ribbon
Plugin URI: http://wordpress.melissacabral.com
Description: Adds an eye-catching ribbon to the corner of the page to promote the RSS feed
Author: Melissa Cabral
Version: 1.0
License: GPLv3
*/

//generate the HTML output for our ribbon
function rad_ribbon_display(){ 
	$image_path = plugins_url( 'images/corner-ribbon.png', __FILE__ ) ;
?>
	<a href="<?php bloginfo( 'rss2_url' ); ?>" id="rad-corner-ribbon">
    	<img src="<?php echo $image_path; ?>" alt="Subscribe to RSS Feed" />
    </a>
		
<?php }
add_action( 'wp_footer', 'rad_ribbon_display' );

//load the stylesheet for the plugin
function rad_stylesheet(){
	$stylesheet_path = plugins_url( 'styles/style.css', __FILE__ ) ;
	//inform WP that this stylesheet exists
	wp_register_style('rad-ribbon-style', $stylesheet_path);
	//put it in line with the other stylesheets
	wp_enqueue_style('rad-ribbon-style');
}
add_action( 'wp_enqueue_scripts', 'rad_stylesheet' );