<?php

/* Recipe Output
------------------------------------------------------------------------- */
function the_recipe($content) {
	// determine if post has a recipe
	if(has_recipress_recipe() && recipress_output()) {
		// create the output
			$content .= '<div class="hrecipe '.recipress_theme().'" id="recipress_recipe">
							<h2 class="fn">'.recipress_recipe('title').'</h2>
							'.recipress_recipe('photo', 'class=alignright photo').'
							<p class="seo_only">'.__('By', 'recipress').' <span class="author">'.get_the_author().'</span>
								'.__('Published:', 'recipress').' <span class="published">'.get_the_date('F j, Y').'<span class="value-title" title="'.get_the_date('c').'"></span></span></p>
							<ul class="recipe-details">';
			if(recipress_recipe('yield'))
			$content .= 		'<li><b>'.__('Yield:', 'recipress').'</b> <span class="yield">'.recipress_recipe('yield').'</span></li>';
			if(recipress_recipe('cost'))
			$content .= 		'<li><b>'.__('Cost:', 'recipress').'</b> <span class="cost">'.recipress_recipe('cost').'</span></li>';
			if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$content .=			'<li class="clear_items"></li>';
			if(recipress_recipe('prep_time'))
			$content .= 		'<li><b>'.__('Prep:', 'recipress').'</b> <span class="preptime"><span class="value-title" title="'.recipress_recipe('prep_time', 'iso').'"></span>'.recipress_recipe('prep_time','mins').'</span></li>';
			if(recipress_recipe('cook_time'))
			$content .= 		'<li><b>'.__('Cook:', 'recipress').'</b> <span class="cooktime"><span class="value-title" title="'.recipress_recipe('cook_time','iso').'"></span>'.recipress_recipe('cook_time','mins').'</span></li>';
			if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$content .=			'<li><b>'.__('Ready In:', 'recipress').'</b> <span class="duration"><span class="value-title" title="'.recipress_recipe('ready_time','iso').'"></span>'.recipress_recipe('ready_time','mins').'</span></li>';
			$content .=		'</ul>
							'.recipress_recipe('summary').'
							<h3>'.__('Ingredients', 'recipress').'</h3>
							'.recipress_recipe('ingredients').'
							<h3>'.__('Instructions', 'recipress').'</h3>
							'.recipress_recipe('instructions').'
							<ul class="recipe-taxes">'
								.recipress_recipe('cuisine')
								.recipress_recipe('course')
								.recipress_recipe('skill_level').'
							</ul>
							'.recipress_credit().'
						</div>';
		// put it all together
	}
	return $content;
}

// shortcode function
function the_recipe_shortcode($content) {
	$autoadd = recipress_options('autoadd');
	if ( isset($autoadd) && $autoadd != 'yes' )
		$content .= the_recipe($content);
	return $content;
}

// shortcode
add_shortcode('recipe', 'the_recipe_shortcode');

// auto add?
function recipress_add_my_filter() {
	$autoadd = recipress_options('autoadd');
	if ( !isset($autoadd) || $autoadd == 'yes' )
		add_action('the_content', 'the_recipe');
}
add_action('template_redirect', 'recipress_add_my_filter');

?>