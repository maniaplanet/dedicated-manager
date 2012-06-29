$(document).bind('pageinit', function() {
	// Form reset fix
	$('form').bind('reset', function() {
		var self = this;
		setTimeout(function() {
			$(self).find('input, textarea, select, button').trigger('change');
		}, 1);
	});
	
	// Checkboxes hack
	$('.readonly-checkbox').parent().css('opacity', 1);
	
	// Alert bars close
	$('.alert-bar a').click(function() {
		$(this).closest('.alert-bar').slideUp(200);
	});
});