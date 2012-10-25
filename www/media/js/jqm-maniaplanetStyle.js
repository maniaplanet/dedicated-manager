(function($, undefined) {
	$.widget('mobile.maniaplanetstyle', $.mobile.widget, {
		options: {
			initSelector: ':jqmData(role="maniaplanet-style")'
		},
		
		_create: function() {
			var self = this,
				elt = $(this.element),
				preview = $('<div class="ui-corner-all ui-body-c ui-shadow"/>').hide();
			
			$.extend(this, {
				preview: preview
			});
			
			$('<div class="maniaplanet-style-preview"/>')
				.insertBefore(elt)
				.append(elt)
				.append(preview);
			
			elt.bind('focus propertychange keyup input paste', function() {
				self.refresh();
			}).focus(function () {
				self.preview.fadeIn('fast');
			}).blur(function () {
				self.preview.fadeOut('fast');
			});
			
			this.refresh();
		},
		
		refresh: function() {
			var html = MPStyle.Parser.toHTML($(this.element).val());
			if(html != '')
				this.preview.html(html);
			else
				this.preview.html('&nbsp;');
		}
	});

	//auto self-init widgets
	$(document).bind('pagecreate create', function(e) {
		$.mobile.maniaplanetstyle.prototype.enhanceWithin(e.target, true);
	});
})(jQuery);