$(document).bind('pageinit', function() {
	$('.sortable-result').each(function() {
		var self = $(this);
		var defaultOrder = $.grep(self.attr('value').split('|'), function(m) {
			if(m.length == 0)
				return false;
			return self.closest('.sortable-container').find('input:checkbox').filter(function() { return $(this).val() == m; }).length == 1;
		});
		var order = defaultOrder.slice(0);
		self.attr('value', order.join('|'));
		self.closest('.sortable-container').find('input:checkbox').change(function()
		{
			var name = $(this).attr('value');
			var key = $.inArray(name, order);
			if(key == -1)
			{
				order.push(name);
				self.val(order.join('|'));
			}
			else
			{
				order.splice(key, 1);
				self.val(order.join('|'));
			}
		});
		
		self.closest('form').bind('reset', function() {
			order = defaultOrder.slice(0);
		});
	});
});