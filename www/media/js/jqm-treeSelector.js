(function($, undefined) {
	$.widget('mobile.treeselector', $.mobile.widget, {
		options: {
			initSelector: ':jqmData(role="tree-selector") :jqmData(role="collapsible")'
		},
		
		_create: function() {
			var self = this,
				elt = $(this.element),
				checkboxes = elt.find(':checkbox'),
				selectedCheckboxes = checkboxes.filter(':checked');

			var button = $('<a data-shadow="false" data-icon="checkbox-off">All</a>').button()
				.insertAfter(elt.children().first())
				.click(function() {
					if(self.current == self.total)
						checkboxes.each(function() { this.checked = false; }).change().checkboxradio('refresh');
					else
						checkboxes.not(':checked').each(function() { this.checked = true; }).change().checkboxradio('refresh');
				}),
				icon = button.find('.ui-icon');
			
			$.extend(this, {
				total: checkboxes.length,
				current: selectedCheckboxes.length,
				initial: selectedCheckboxes.length,
				button: button,
				icon: icon
			});
			this.refresh(0);
			
			// Change own style
			button
				.removeClass('ui-btn-corner-all')
				.addClass('ui-corner-right')
				.addClass('ui-checkbox')
				.css('display', 'table-cell')
			.children(':first')
				.removeClass('ui-btn-corner-all')
				.addClass('ui-corner-right');
			// Change collapsible header style
			button.prev()
				.css('display', 'table-cell')
				.css('width', '100%')
			.children(':first')
				.removeClass('ui-corner-top')
				.removeClass('ui-corner-bottom')
				.addClass('ui-corner-left')
			.children(':first')
				.removeClass('ui-corner-top')
				.removeClass('ui-corner-bottom')
				.addClass('ui-corner-left');

			checkboxes.change(function() {
				var old = self.current;
				if(this.checked)
					++self.current;
				else
					--self.current;
				
				self.refresh(old);
			});

			elt.closest('form').bind('reset', function() {
				var old = self.current;
				self.current = self.initial;
				self.refresh(old);
			});
		},
		
		refresh: function(old)
		{
			if(old == this.current)
				return;
			if(old > 0 && old < this.total && this.current > 0 && this.current < this.total)
				return;
			
			if(this.current == this.total) {
				this.button.removeClass('ui-checkbox-off').addClass('ui-checkbox-on');
				
				if(old == 0)
					this.icon.removeClass('ui-icon-checkbox-off')
				else
					this.icon.removeClass('ui-icon-minus')
				this.icon.addClass('ui-icon-checkbox-on');
			}
			else if(this.current == 0) {
				if(old == this.total) {
					this.button.removeClass('ui-checkbox-on').addClass('ui-checkbox-off');
					this.icon.removeClass('ui-icon-checkbox-on');
				}
				this.icon.addClass('ui-icon-checkbox-off');
			}
			else if(old == 0)
				this.icon.removeClass('ui-icon-checkbox-off').addClass('ui-icon-minus');
			else if(old == this.total) {
				this.button.removeClass('ui-checkbox-on').addClass('ui-checkbox-off');
				this.icon.removeClass('ui-icon-checkbox-on').addClass('ui-icon-minus');
			}
		}
	});

	//auto self-init widgets
	$(document).bind('pagecreate create', function(e) {
		$.mobile.treeselector.prototype.enhanceWithin(e.target, true);
	});
})(jQuery);