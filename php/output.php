<?

/* Recipe Output
------------------------------------------------------------------------- */
function the_recipe($content) {
	// determine if post has a recipe
	if(has_recipress_recipe() && recipress_output()) {
		// create the output
			$content .= '<div class="hrecipe '.recipress_theme().'" id="recipress_recipe">
							<h2 class="fn">'.recipress_recipe('title').'</h2>
							'.recipress_recipe('photo').'
							<p class="seo_only">By <span class="author">'.get_the_author().'</span>
								Published: <span class="published">'.get_the_date('F j, Y').'<span class="value-title" title="'.get_the_date('c').'"></span></span></p>
							<ul class="recipe-details">';
			$yieldClass = '';
			$liClass = ' class="recipe_time"';
			if(!(recipress_recipe('prep_time') && recipress_recipe('cook_time'))) $yieldClass = $liClass;
			if(recipress_recipe('yield'))
			$content .= 		'<li'.$yieldClass.'><b>Yeild:</b> <span class="yield">'.recipress_recipe('yield').'</span></li>';
			if(recipress_recipe('prep_time'))
			$content .= 		'<li'.$liClass.'><b>Prep:</b> <span class="preptime"><span class="value-title" title="'.recipress_recipe('prep_time', 'iso').'"></span>'.recipress_recipe('prep_time','mins').'</span></li>';
			if(recipress_recipe('cook_time'))
			$content .= 		'<li'.$liClass.'><b>Cook:</b> <span class="cooktime"><span class="value-title" title="'.recipress_recipe('cook_time','iso').'"></span>'.recipress_recipe('cook_time','mins').'</span></li>';
			if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$content .=			'<li'.$liClass.'><b>Ready In:</b> <span class="duration"><span class="value-title" title="'.recipress_recipe('ready_time','iso').'"></span>'.recipress_recipe('ready_time','mins').'</span></li>';
			$content .=		'</ul>
							'.recipress_recipe('summary').'
							<h3>Ingredients</h3>
							'.recipress_recipe('ingredients').'
							<h3>Instructions</h3>
							'.recipress_recipe('instructions').'
							<ul class="recipe-taxes">
								<li>'.recipress_recipe('cuisine').'</li>
								<li>'.recipress_recipe('course').'</li>
								<li>'.recipress_recipe('skill_level').'</li>
							</ul>
						</div>';
		// put it all together
	}
	return $content;
}

// shortcode
add_shortcode('recipe', 'the_recipe');

// auto add?
function _add_my_filter() {
	if ( !recipress_options('autoadd') || recipress_options('autoadd') == 'Yes' ) {
		add_filter('the_content', 'the_recipe');
	}
}
add_action('template_redirect', '_add_my_filter');

?>