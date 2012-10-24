(function($, undefined) {
	$.widget('mobile.treeselector', $.mobile.widget, {
		options: {
			initSelector: ':jqmData(role="tree-selector")'
		},
		
		_create: function() {
			var self = this;
			
			$(this.element).find(':jqmData(role="collapsible")').each(function() {
				var checkboxes = $(this).find(':checkbox'),
					selectedCheckboxes = checkboxes.filter(':checked');
				
				var selectAll = $('<div data-shadow="false" data-icon="checkbox-off">All</div>')
					.insertAfter($(this).children().first())
					.button()
					.jqmData('total-checkboxes', checkboxes.length)
					.jqmData('current-checkboxes', selectedCheckboxes.length)
					.click(function() {
						if($(this).jqmData('current-checkboxes') == $(this).jqmData('total-checkboxes'))
							checkboxes.prop('checked', false).trigger('change').checkboxradio('refresh');
						else
							checkboxes.not(':checked').prop('checked', true).trigger('change').checkboxradio('refresh');
					})
					.prev()
						.removeClass('ui-btn-corner-all')
						.addClass('ui-corner-right')
					.parent()
						.removeClass('ui-btn-corner-all')
						.addClass('ui-corner-right')
						.css('display', 'table-cell')
					;
				
				checkboxes.change(function() {
					if($(this).is(':checked'))
					{
						selectAll.jqmData('current-checkboxes', selectAll.jqmData('current-checkboxes') + 1);
						if($(this).jqmData('current-checkboxes') == $(this).jqmData('total-checkboxes'))
							selectAll.button('refresh');
					}
					else
					{
						selectAll.jqmData('current-checkboxes', selectAll.jqmData('current-checkboxes') - 1);
						if($(this).jqmData('current-checkboxes') == 0)
							;
					}
				})
			});
		}
	});

	//auto self-init widgets
	$(document).bind('pagecreate create', function(e) {
		$.mobile.treeselector.prototype.enhanceWithin(e.target, true);
	});
})(jQuery);