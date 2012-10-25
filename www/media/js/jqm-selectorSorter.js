(function($, undefined) {
	$.widget('mobile.selectorsorter', $.mobile.widget, {
		options: {
			initSelector: ':jqmData(role="selector-sorter")'
		},
		
		_create: function() {
			var self = $(this.element),
				checkboxes = self.closest(':jqmData(role="tree-selector")').find(':checkbox'),
				defaultSort = $.grep(self.attr('value').split('|'), function(m) {
					if(m.length == 0)
						return false;
					return checkboxes.filter(function() { return $(this).val() == m; }).length == 1;
				}),
				sort = defaultSort.slice(0);
				
			self.attr('value', sort.join('|'));
			checkboxes.change(function()
			{
				var name = $(this).attr('value'),
					key = $.inArray(name, sort);
				
				if(this.checked && key == -1)
					sort.push(name);
				else if(key != -1)
					sort.splice(key, 1);
				
				self.val(sort.join('|'));
			});

			self.closest('form').bind('reset', function() {
				sort = defaultSort.slice(0);
				self.val(sort.join('|'));
			});
		}
	});

	//auto self-init widgets
	$(document).bind('pagecreate create', function(e) {
		$.mobile.selectorsorter.prototype.enhanceWithin(e.target, true);
	});
})(jQuery);