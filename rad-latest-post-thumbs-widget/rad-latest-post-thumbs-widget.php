<?php /*
Plugin Name: Latest Posts with Thumbnails
Plugin URI: http://wordpress.melissacabral.com
Description: A widget with customizable number of posts and featured images
Version: 1.0
Author: Melissa Cabral
Author URI: http://melissacabral.com
License: GPLv3
*/

function rad_register_thumbs_widget(){
	register_widget('Rad_Thumbs_Widget');	
}
add_action('widgets_init', 'rad_register_thumbs_widget');

//our new widget is a copy of the WP_Widget object class
class Rad_Thumbs_Widget extends WP_Widget{
	function Rad_Thumbs_Widget(){
		//widget settings
		$widget_ops = array(
			'classname' => 'thumbs-widget',
			'description' => 'Shows a customizable list of posts with featured image thumbnails'
		);
		//widget control settings
		$control_ops = array(
			//required to make multiple instances work
			'id_base' => 'thumbs-widget',
			//if you leave this off, the form will be the width of the admin panel bar
			'width' => 300 
		);
		//push these settings into the widget
		//(id-base, title, widget ops, control ops)
		$this->WP_Widget('thumbs-widget', 'Recent Posts With Thumbnails', $widget_ops, $control_ops);
	}
	
	//REQUIRED. front-end display. always use 'widget($args, $instance)' function
	function widget( $args, $instance ){
		//extract the args so we can use them.
		//args contains all the settings from the dynamic sidebar
		extract($args);
		
		//create the filter hook for the title
		$title = apply_filters( 'widget-title', $instance['title'] );
		
		//make simple variables for each setting
		$number = $instance['number'];
		$show_excerpt = $instance['show_excerpt'];
		
		//widget output begins here
		echo $before_widget;
		
		if( $title ):
			echo $before_title . $title . $after_title;
		endif;
		?>
			<ul>
            <?php 
			//create new instance of the WP_Query object
			$thumbs_query = new WP_Query( array(
				'showposts' => $number,
				'ignore_sticky_posts' => 1,
				'orderby' => 'post_date',
				'order' => 'desc'
			) );
			
			while( $thumbs_query->have_posts() ):
				$thumbs_query->the_post();
			 ?>
            	<li>
                	<?php
					if( has_post_thumbnail() ): ?>
                        <a href="<?php the_permalink(); ?>" class="thumbnail-link">
                            <?php the_post_thumbnail(); ?>
                        <span class="zoom"></span>
                        </a>
                    <?php endif; //has post thumb ?>
                    
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    
					<?php if( $show_excerpt ): ?>
                    	<p><?php the_excerpt(); ?></p> 
                    <?php endif; //show excerpt ?>                  
                </li>
            <?php
            endwhile;
			//clean up after our custom loop!
			wp_reset_query(); ?>
            </ul>
            	
		<?php
		echo $after_widget; 
		//end widget display
	}
	
	//REQUIRED. handle saving data. Always use "update($new_instance, $old_instance)"
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		
		//santitize all fields here:
		$instance['title'] = wp_filter_nohtml_kses($new_instance['title']);
		$instance['number'] = wp_filter_nohtml_kses($new_instance['number']);
		
		//ensure checkboxes are either 1 or 0 (not null)
		$instance['show_excerpt'] = ($new_instance['show_excerpt'] == 1 ? 1 : 0);
		
		return $instance;		
	}
	
	//OPTIONAL. Admin panel form display and defaults. always use "form($instance)"
	function form( $instance ){
		//set up defaults for each field
		$defaults = array(
			'title' => '',
			'number' => 5,
			'show_excerpt' => 1 //true
		);
		
		//merge defaults with the user-provided values
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//Form HTML - leave off the form tag and button
		?>
        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id('number'); ?>">Number of posts:</label>
            <input type="text" name="<?php echo $this->get_field_name('number'); ?>" id="<?php echo $this->get_field_id('number'); ?>" value="<?php echo $instance['number']; ?>" style="width:20%" />
        </p>
        
        <p>
        <input type="checkbox" name="<?php echo $this->get_field_name('show_excerpt'); ?>" id="<?php echo $this->get_field_id('show_excerpt'); ?>" value="1" <?php 
		checked( 1, $instance['show_excerpt'] ); ?> />
        
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Show Excerpts?</label>
        </p> 
		<?php
	}
}



