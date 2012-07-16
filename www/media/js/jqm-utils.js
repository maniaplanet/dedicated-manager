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
	
	// Input file hack
	$('input:file').each(function () {
		var self = $(this);
		self.css({
			display: 'block',
			opacity: 0,
			height: 0,
			margin: 0,
			border: 0,
			padding: 0
		});
		var button = $('<input type="button" value="Choose file" data-icon="search"/>')
			.insertAfter($(self))
			.button()
			.click(function () {
				self.trigger('click');
			});
		self.appendTo(button.parent());
		self.change(function() {
			button.prev().children('.ui-btn-text').text('Choose file' + (self.val() ? ': '+self.val() : ''));
		})
	});
	
	// Alert bars close
	$('.alert-bar a').click(function() {
		$(this).closest('.alert-bar').slideUp(200);
	});
});