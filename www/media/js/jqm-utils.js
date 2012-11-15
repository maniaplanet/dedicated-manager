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
	
	// Readonly input should not be focusable
	$('input, select, textarea').not(':jqmData(role="datebox")').focus(function() {
		if(this.readOnly)
			$(this).blur();
	});
	
	// Input file hack for custom style
	$('input:file').each(function () {
		if($(this).jqmData('customized'))
			return;
		$(this).jqmData('customized', true)
		
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
		self.appendTo(button.parent());
		self.change(function() {
			button.prev().children('.ui-btn-text').text('Choose file' + (self.val() ? ': '+self.val() : ''));
		}).change();
	});
	
	// Alert bars close
	$('.alert-bar a').click(function() {
		$(this).closest('.alert-bar').slideUp('fast');
	});
	
	// List divider hack
	$('.ui-li-divider[data-theme]').each(function() {
		$(this).removeClass('ui-bar-a ui-bar-b ui-bar-c ui-bar-d ui-bar-e').addClass('ui-bar-'+$(this).jqmData('theme'));
	});
});
