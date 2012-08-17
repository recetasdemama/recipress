jQuery(function(jQuery) {
	
	var hasRecipe = jQuery('#hasRecipe');
	jQuery(hasRecipe).change(function(){
			jQuery('#recipress_table').slideToggle('slow');
	});
	if(jQuery(hasRecipe).is(':checked')) jQuery('#recipress_table').show();
	
	
	jQuery('#media-items').bind('DOMNodeInserted',function(){
		jQuery('input[value="Insert into Post"]').each(function(){
				jQuery(this).attr('value','Use This Image');
		});
	});
	
	jQuery('.recipress_upload_image_button').click(function() {
		formID = jQuery(this).attr('rel');
		formfield = jQuery(this).siblings('.recipress_upload_image');
		preview = jQuery(this).siblings('.recipress_preview_image');
		tb_show('Add Image', 'media-upload.php?post_id='+formID+'&type=image&TB_iframe=1');
		window.send_to_editor = function(html) {
			img = jQuery('img',html);
			imgurl = img.attr('src');
			classes = img.attr('class');
			id = classes.replace(/(.*?)wp-image-/, '');
			formfield.val(id);
			preview.attr('src', imgurl);
			tb_remove();
		}
		return false;
	});
	
	jQuery('.recipress_clear_image_button').click(function() {
		jQuery(this).parent().siblings('.recipress_upload_image').val('');
		jQuery(this).parent().siblings('.recipress_preview_image').attr('src', pluginDir + 'img/image.png');
		return false;
	});
	
	jQuery('.ingredient_add').click(function() {
		jQuery('#ingredients_table .tbody>.tr:last')
			.clone(true)
			.insertAfter('#ingredients_table .tbody>.tr:last')
			.addClass('more')
			.find('input[type=text], input[type=number], select').val('')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.parent().find('input, select')
			.attr('id', function(index, id) {
				return id.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.parent().find('input.ingredient')
			.attr('onfocus', function(index, onfocus) {
				return onfocus.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
		return false;
	});
	
	jQuery('.instruction_add').click(function() {
		jQuery('#instructions_table .tbody>.tr:last')
			.clone(true)
			.insertAfter('#instructions_table .tbody>.tr:last')
			.addClass('more')
			.find('input[type=hidden], textarea').val('')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.parent().find('.recipress_preview_image').attr('src', pluginDir + 'img/image.png')
		return false;
	});
	
	jQuery('.ingredient_remove').click(function(){
		jQuery(this).parent().parent().remove();
		return false;
	});
	
	jQuery('.instruction_remove').click(function(){
		jQuery(this).parent().parent().remove();
		return false;
	});
	
	jQuery('#ingredients_table .tbody').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.sort'
	});
		
	jQuery('#instructions_table .tbody').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.sort'
	});
	
	//On Click Event
	jQuery('.image_radio label').click(function() {
		jQuery('.image_radio label').removeClass('active'); //Remove any 'active' class
		jQuery(this).addClass('active'); //Add 'active' class to selected tab
	});

});