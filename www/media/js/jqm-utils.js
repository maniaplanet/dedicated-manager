$(document).bind('pageinit', function() {
	// Form reset fix
	$('form').bind('reset', function() {
		var self = this;
		setTimeout(function() {
			var elts = $(self).find('input, textarea, select, button');
			elts.not(':checkbox, :radio').change();
			elts.filter(':checkbox, :radio').checkboxradio('refresh');
		}, 1);
	});
	
	// Checkboxes hack for readonly style
	$('.readonly-checkbox').parent().css('opacity', 1);
	
	// Input file hack for custom style
	$('input:file[customized!=customized]').each(function () {
		var self = $(this);
		self.css({
			display: 'block',
			opacity: 0,
			height: 0,
			margin: 0,
			border: 0,
			padding: 0
		});
		var button = $('<input type="button" data-icon="search"/>')
			.insertAfter($(self))
			.button()
			.click(function () {
				self.trigger('click');
			});
		self.appendTo(button.parent()).attr('customized', 'customized');
		self.change(function() {
			button.prev().children('.ui-btn-text').text('Choose file' + (self.val() ? ': '+self.val() : ''));
		}).trigger('change');
	});
	
	// Alert bars close
	$('.alert-bar a').click(function() {
		$(this).closest('.alert-bar').slideUp(200);
	});
});