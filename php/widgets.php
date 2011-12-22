<?php

// recipress_terms
function get_recipress_terms($taxonomy, $args = null) {
	$terms = get_terms($taxonomy, $args);
	$output = '<ul class="'.$taxonomy.'-list">';
	foreach($terms as $term) {
		$output .= '<li><a href="'.get_term_link($term->slug, $taxonomy).'">'.$term->name.'</a></li>';
	}
	$output .= '</ul>';
	
	return $output;
}

function recipress_terms($taxonomy, $args = null) {
	echo get_recipress_terms($taxonomy, $args);
}

class recipress_terms_widget extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( 'recipress_terms', 'Recipress Terms', array( 'description' => 'Output a list or cloud of recipe terms' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$taxonomy = $instance['taxonomy'];
		$type = $instance['type'];
		$the_taxonomy = get_taxonomy($taxonomy);
		if ($title == '') $title = $the_taxonomy->label;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		if ($type == 'list') {
			echo get_recipress_terms($taxonomy);
		} elseif ($type == 'cloud') {
			wp_tag_cloud('taxonomy='.$taxonomy);
		}
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['taxonomy'] = $new_instance['taxonomy'];
		$instance['type'] = $new_instance['type'];
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$taxonomy = $instance[ 'taxonomy' ];
			$type = $instance[ 'type' ];
		}
		else {
			$title = '';
			$taxonomy = 'course';
			$type = 'list';
		}
		$taxonomies = recipress_use_taxonomies();
		array_unshift($taxonomies, 'ingredient');
			foreach ($taxonomies as $tax) {
				$tax = get_taxonomy($tax);
				$taxes[] = array(
					'id' => $tax->query_var,
					'name' => $tax->label
					);
			}
			
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:'); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>"><?php
			foreach ($taxes as $tax) {
				echo '<option', $taxonomy == $tax['id'] ? ' selected="selected"' : '', ' value="'.$tax['id'].'">'.$tax['name'].'</option>';
			}
		?></select>
		</p>
		<p>
		<label><?php _e('Output Type:'); ?></label> <br />
		<label><input id="<?php echo $this->get_field_id('type'); ?>_list" name="<?php echo $this->get_field_name('type'); ?>" type="radio"<?php if($type == 'list') echo ' checked="checked"'; ?> value="list" /><?php _e('List'); ?></label> &nbsp; 
		<label><input id="<?php echo $this->get_field_id('type'); ?>_cloud" name="<?php echo $this->get_field_name('type'); ?>" type="radio"<?php if($type == 'cloud') echo ' checked="checked"'; ?> value="cloud" /><?php _e('Cloud'); ?></label>
		</p>
		<?php 
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("recipress_terms_widget");' ) );

// recipress_recent
function get_recipress_recent($num = '5', $image = 1) {
	global $post;
	
	$args = array(
		'meta_key' => 'hasRecipe',
		'meta_value' => 'Yes',
		'numberposts' => $num
	);
	$recipes = new WP_query($args);
	if($recipes->have_posts()) :
		$output = '<ul class="recipress-recent">';
		while($recipes->have_posts()) : $recipes->the_post();
			$output .= '<li class="clear_items">';
			$output .= '<a href="'.get_permalink().'">';
			if ($image == 1)
				$output .= recipress_recipe('photo', 'class=recipress-thumb alignleft');
			$output .= '<strong>'.recipress_recipe('title').'</strong></a></li>';
		endwhile;
		$output .= '</ul>';
	else :
		$output = '<p>No recipes found.</p>';
	endif;
	
	wp_reset_postdata();
	return $output;
}

function recipress_recent($num = '5', $image = 1) {
	echo get_recipress_recent($num , $image);
}

class recipress_recent_widget extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( 'recipress_recent', 'Recent Recipes', array( 'description' => 'Output a list of recent recipe posts' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$num = $instance['num'];
		$image = $instance['image'];
		if ($title == '') $title = 'Recent Recipes';
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo get_recipress_recent($num, $image);
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num'] = $new_instance['num'];
		$instance['image'] = $new_instance['image'];
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$num = $instance[ 'num' ];
			$image = $instance[ 'image' ];
		}
		else {
			$title = '';
			$num = 5;
			$image = 1;
		}			
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of Posts:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num'); ?>" type="text" value="<?php echo $num; ?>" />
		</p>
		<p>
		<label><input id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="checkbox"<?php if($image == 1) echo ' checked="checked"'; ?> value="1" /> <?php _e('Recipe Thumbnail'); ?></label>
		</p>
		<?php 
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("recipress_recent_widget");' ) );

?>