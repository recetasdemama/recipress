<?php

// recipress_terms
function get_recipress_terms($taxonomy, $args = null, $type = null) {
	if ($type == 'cloud') {
		if(!empty($args)) {
			$defaults = array(
				'smallest' => 10, 'largest' => 24, 'unit' => 'px', 'number' => 45,
				'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
				'exclude' => '', 'include' => '', 'link' => 'view', 'taxonomy' => $taxonomy, 'echo' => true
			);
			$args = wp_parse_args($args, $defaults);
		} else {
			$args = array('taxonomy' => $taxonomy);
		}
		$output = '<p class="'.$taxonomy.'-cloud">'.wp_tag_cloud($args).'</p>';
	} else {
		$terms = get_terms($taxonomy, $args);
		
		$output = '<ul class="'.$taxonomy.'-list">';
		foreach($terms as $term) {
			$output .= '<li><a href="'.get_term_link($term->slug, $taxonomy).'">'.$term->name.'</a></li>';
		}
		$output .= '</ul>';
	}
	
	return $output;
}

function recipress_terms($taxonomy, $args = null, $type = null) {
	echo get_recipress_terms($taxonomy, $args, $type);
}

class recipress_terms_widget extends WP_Widget {
	/** constructor */
	function recipress_terms_widget() {
		parent::WP_Widget( 'recipress_terms', __('Recipress Terms', 'recipress'), array( 'description' => __('Output a list or cloud of recipe terms', 'recipress') ) );
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
			echo get_recipress_terms($taxonomy, 'hide_empty=0', $type);
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
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'recipress'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'recipress'); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>"><?php
			foreach ($taxes as $tax) {
				echo '<option', $taxonomy == $tax['id'] ? ' selected="selected"' : '', ' value="'.$tax['id'].'">'.$tax['name'].'</option>';
			}
		?></select>
		</p>
		<p>
		<label><?php _e('Output Type:', 'recipress'); ?></label> <br />
		<label><input id="<?php echo $this->get_field_id('type'); ?>_list" name="<?php echo $this->get_field_name('type'); ?>" type="radio"<?php if($type == 'list') echo ' checked="checked"'; ?> value="list" /><?php _e('List', 'recipress'); ?></label> &nbsp; 
		<label><input id="<?php echo $this->get_field_id('type'); ?>_cloud" name="<?php echo $this->get_field_name('type'); ?>" type="radio"<?php if($type == 'cloud') echo ' checked="checked"'; ?> value="cloud" /><?php _e('Cloud', 'recipress'); ?></label>
		</p>
		<?php 
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("recipress_terms_widget");' ) );

// recipress_recent
function get_recipress_recent($atts) {
	extract(shortcode_atts(array(
		'num' => 5,
		'image' => false
	), $atts));
	
	global $post;
	
	$args = array(
		'meta_key' => 'hasRecipe',
		'meta_value' => 'Yes',
		'posts_per_page' => $num
	);
	$recipes = new WP_query($args);
	if($recipes->have_posts()) :
		$output = '<ul class="recipress-recent">';
		while($recipes->have_posts()) : $recipes->the_post();
			$output .= '<li class="clear_items">';
			$output .= '<a href="'.get_permalink().'">';
			if ($image == true)
				$output .= recipress_recipe('photo', 'class=recipress-thumb alignleft');
			$output .= '<strong>'.recipress_recipe('title').'</strong></a></li>';
		endwhile;
		$output .= '</ul>';
	else :
		$output = '<p>'.__('No recipes found.', 'recipress').'</p>';
	endif;
	
	wp_reset_postdata();
	return $output;
}

function recipress_recent($num = '5', $image = true) {
	$atts = array(
		'num' => $num,
		'image' => $image
	);
	echo get_recipress_recent($atts);
}

add_shortcode('recipress_recent', 'get_recipress_recent');

class recipress_recent_widget extends WP_Widget {
	/** constructor */
	function recipress_recent_widget() {
		parent::WP_Widget( 'recipress_recent', __('Recent Recipes', 'recipress'), array( 'description' => __('Output a list of recent recipe posts', 'recipress') ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$atts = array(
			'num' => $instance['num'],
			'image' => $instance['image']
		);
		if ($title == '') $title = __('Recent Recipes', 'recipress');
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo get_recipress_recent($atts);
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
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'recipress'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of Posts:', 'recipress'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num'); ?>" type="text" value="<?php echo $num; ?>" />
		</p>
		<p>
		<label><input id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="checkbox"<?php if($image == true) echo ' checked="checked"'; ?> value="true" /> <?php _e('Recipe Thumbnail', 'recipress'); ?></label>
		</p>
		<?php 
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("recipress_recent_widget");' ) );

?>