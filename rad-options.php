<?php
/*
Plugin Name: Global Custom Fields - Company Info Settings
Plugin URI: http://wordpress.melissacabral.com
Description: Adds an options page to the admin panel under "settings"
Version: 1.0
Author: Melissa Cabral
Author URI: http://melissacabral.com
License: GPLv3
*/

//whitelist the group of settings so it is allowed in the database
function rad_options_register(){
	register_setting('rad_options_group', 'rad_options', 'rad_options_validate');	
}
add_action('admin_init', 'rad_options_register');

//add admin panel page under "settings"
function rad_options_page(){
	//(title, menu label, capability, slug, function for page contents)
	add_options_page('Company Info', 'Company Info', 'manage_options', 'rad-options-page', 'rad_options_build_form');	
}
add_action('admin_menu', 'rad_options_page');


//function that handles all the HTML for our settings page
function rad_options_build_form(){ ?>
	<div class="wrap">
        <div id="icon-tools" class="icon32">
       	  <br />
        </div>
    	<h2>Company Info</h2>
        
        <form method="post" action="options.php">
        	<?php settings_fields('rad_options_group'); ?>
        </form>
        
    </div>
<?php }


//sanitizing callback function to clean the settings before adding to DB
function rad_options_validate($input){
	return $input;
}