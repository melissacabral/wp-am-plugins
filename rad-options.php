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
        	<?php
			//connects the settings we registered to this form. Must match the first arg of register_setting above 
			 settings_fields('rad_options_group'); 
			 
			 //get existing values from the table to make it "sticky"
			 $values = get_option('rad_options');
			 ?>
             
             <p>
             <label>Company Phone:</label>
             <br />
             <input type="tel" name="rad_options[phone]" id="rad_options[phone]" value="<?php echo $values['phone']; ?>" />
             </p>
             
             <p>
             <label>Company Address:</label>
             <br />
             <textarea name="rad_options[address]" id="rad_options[address]" cols="40" rows="5"><?php echo $values['address']; ?></textarea>
             </p>
             
             <p>
             <label>Customer Support Email Address:</label>
             <br />
             <input type="email" name="rad_options[email]" id="rad_options[email]" value="<?php echo $values['email']; ?>" />
             </p>
             
             <hr />
             <h2>Home Page Settings</h2>
             
             <p>
             <input type="checkbox" name="rad_options[show_message]" id="rad_options[show_message]" value="1"  <?php checked( $values['show_message'] ,'1'); ?> />
             <label>Display the message on the home page</label>
             </p>
             
             <p>
             <label>Home Page Message or Quote:</label>
             <br />
             <textarea name="rad_options[message]" id="rad_options[message]" cols="40" rows="5"><?php echo $values['message']; ?></textarea>
             </p>
             
             <p>
             <label>Quote Source:</label>
             <br />
             <input type="text" name="rad_options[source]" id="rad_options[source]" value="<?php echo $values['source']; ?>" />
             </p>
             
             <p class="submit">
             	<input type="submit" class="button-primary" value="Save Settings" />
             </p>
             
        </form>
        
    </div>
<?php }


//sanitizing callback function to clean the settings before adding to DB
function rad_options_validate($input){
	//strip all tags out
	$input['phone'] = wp_filter_nohtml_kses($input['phone']);
	$input['email'] = wp_filter_nohtml_kses($input['email']);
	$input['source'] = wp_filter_nohtml_kses($input['source']);
	
	//checkbox - make a null value equal to 0
	$input['show_quote'] = ( $input['show_quote'] == 1 ? 1 : 0 );
	
	//allow HTML in some fields
	$allowed_tags = array(
		'br' => array(),
		'p' => array(),
		'a' => array( 
			'href' => array(),
			'title' => array()
		)
	);
	$input['address'] = wp_kses( $input['address'], $allowed_tags );
	$input['message'] = wp_kses( $input['message'], $allowed_tags );
	
	//make sure to return the clean array or no data will go into the DB
	return $input;
}