<?php /*
Plugin Name: Simple Widget Example
Plugin URI: http://wordpress.melissacabral.com
Description: A widget with just a title field. use this as a starting point for other widgets
Version: 1.0
Author: Melissa Cabral
Author URI: http://melissacabral.com
License: GPLv3
*/

function rad_register_widget(){
	register_widget('Rad_Simple_Widget');	
}
add_action('widgets_init', 'rad_register_widget');

//our new widget is a copy of the WP_Widget object class
class Rad_Simple_Widget extends WP_Widget{
	function Rad_Simple_Widget(){
		//widget settings
		$widget_ops = array(
			'classname' => 'simple-widget',
			'description' => 'The simplest widget ever. be descriptive here.'
		);
		//widget control settings
		$control_ops = array(
			//required to make multiple instances work
			'id_base' => 'simple-widget',
			//if you leave this off, the form will be the width of the admin panel bar
			'width' => 300 
		);
		//push these settings into the widget
		//(id-base, title, widget ops, control ops)
		$this->WP_Widget('simple-widget', 'Simplest Widget', $widget_ops, $control_ops);
	}
	
	//REQUIRED. front-end display. always use 'widget($args, $instance)' function
	function widget( $args, $instance ){
		//extract the args so we can use them.
		//args contains all the settings from the dynamic sidebar
		extract($args);
		
		//create the filter hook for the title
		$title = apply_filters( 'widget-title', $instance['title'] );
		
		//widget output begins here
		echo $before_widget;
		
		if( $title ):
			echo $before_title . $title . $after_title;
		endif;
		
		echo 'This is the "meat" of the widget';		
		
		echo $after_widget; 
		//end widget display
	}
	
	//REQUIRED. handle saving data. Always use "update($new_instance, $old_instance)"
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		
		//santitize all fields here:
		$instance['title'] = wp_filter_nohtml_kses($new_instance['title']);
		
		return $instance;		
	}
	
	//OPTIONAL. Admin panel form display and defaults. always use "form($instance)"
	function form( $instance ){
		//set up defaults for each field
		$defaults = array(
			'title' => 'Simple!'
		);
		
		//merge defaults with the user-provided values
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//Form HTML - leave off the form tag and button
		?>
        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
		<?php
	}
}



