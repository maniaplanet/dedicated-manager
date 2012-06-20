$.fn.reverse = [].reverse;

var formattingTimeout = null;
var updateFormattingPreview = function(that)
{
	var thisThat = that;
	$.ajax({
		type : 'GET',
		url : 'http://127.0.0.1/manager/index.php/ajax/formatting',
		data: 'input=' + that.val()
	}).done(function (html) {
		thisThat.siblings('.formattingResult').html(html);
	});
}

$(document).bind('pageinit', function() {

	/*
	 * Formating preview
	 */
	$('.formattingPreview').bind('propertychange keyup input paste change', function() {
		if (formattingTimeout != null) {
			clearTimeout(formattingTimeout);
		}
		formattingTimeout = setTimeout(updateFormattingPreview, 500, $(this));
	});

	$('#isOnline').change(function () {
		if ($(this).val() == '1') {
			$('#field-internet').trigger('expand');
		}
		else {
			$('#field-internet').trigger('collapse');
		}
	});

	$('.alert-bar a').click(function() {
		$(this).closest('.alert-bar').slideUp(200);
	});
	
	$('.sortable-result').each(function() {
		var self = $(this);
		var defaultOrder = self.attr('value').split(','), order = defaultOrder.slice(0);
		self.closest('.sortable-container').find('input:checkbox').change(function()
		{
			var name = $(this).attr('value');
			var key = $.inArray(name, order);
			if(key == -1)
			{
				order.push(name);
				self.val(order.toString());
			}
			else
			{
				order.splice(key, 1);
				self.val(order.toString());
			}
		});
		
		self.closest('form').bind('reset', function() {
			order = defaultOrder.slice(0);
			setTimeout(function() { self.closest('.sortable-container').find('input:checkbox').checkboxradio('refresh'); }, 1);
		});
	});

	$('#gameMode').change(function() {
		switch($(this).val())
		{
			case '0':
				$('#fieldset-gamemode-script').trigger('expand');
				$('#fieldset-gamemode-round').trigger('collapse');
				$('#fieldset-gamemode-timeattack').trigger('collapse');
				$('#fieldset-gamemode-team').trigger('collapse');
				$('#fieldset-gamemode-laps').trigger('collapse');
				$('#fieldset-gamemode-cup').trigger('collapse');
				break;
			case '1':
				$('#fieldset-gamemode-script').trigger('collapse');
				$('#fieldset-gamemode-round').trigger('expand');
				$('#fieldset-gamemode-timeattack').trigger('collapse');
				$('#fieldset-gamemode-team').trigger('collapse');
				$('#fieldset-gamemode-laps').trigger('collapse');
				$('#fieldset-gamemode-cup').trigger('collapse');
				break;
			case '2':
				$('#fieldset-gamemode-script').trigger('collapse');
				$('#fieldset-gamemode-round').trigger('collapse');
				$('#fieldset-gamemode-timeattack').trigger('expand');
				$('#fieldset-gamemode-team').trigger('collapse');
				$('#fieldset-gamemode-laps').trigger('collapse');
				$('#fieldset-gamemode-cup').trigger('collapse');
				break;
			case '3':
				$('#fieldset-gamemode-script').trigger('collapse');
				$('#fieldset-gamemode-round').trigger('collapse');
				$('#fieldset-gamemode-timeattack').trigger('collapse');
				$('#fieldset-gamemode-team').trigger('expand');
				$('#fieldset-gamemode-laps').trigger('collapse');
				$('#fieldset-gamemode-cup').trigger('collapse');
				break;
			case '4':
				$('#fieldset-gamemode-script').trigger('collapse');
				$('#fieldset-gamemode-round').trigger('collapse');
				$('#fieldset-gamemode-timeattack').trigger('collapse');
				$('#fieldset-gamemode-team').trigger('collapse');
				$('#fieldset-gamemode-laps').trigger('expand');
				$('#fieldset-gamemode-cup').trigger('collapse');
				break;
			case '5':
				$('#fieldset-gamemode-script').trigger('collapse');
				$('#fieldset-gamemode-round').trigger('collapse');
				$('#fieldset-gamemode-timeattack').trigger('collapse');
				$('#fieldset-gamemode-team').trigger('collapse');
				$('#fieldset-gamemode-laps').trigger('collapse');
				$('#fieldset-gamemode-cup').trigger('expand');
				break;
		}
	});
});