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
	// Form reset fix
	$('form').bind('reset', function() {
		var self = this;
		setTimeout(function() {
			$(self).find('input, textarea, select, button').trigger('change');
		}, 1);
	});
	
	// Checkboxes hack
	$('.readonly-checkbox').parent().css('opacity', 1);
	
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
	
	$('#nextLadderMode').change(function () {
		if($(this).val() == '1') {
			$('#ladderServerLimitMin').textinput('enable');
			$('#ladderServerLimitMax').textinput('enable');
		}
		else {
			$('#ladderServerLimitMin').textinput('disable');
			$('#ladderServerLimitMax').textinput('disable');
		}
	});
	$('#nextLadderMode').trigger('change');
	
	$('#useProxy').change(function () {
		if($(this).val() == '1') {
			$('#proxyLogin').textinput('enable');
			$('#proxyPassword').textinput('enable');
		}
		else {
			$('#proxyLogin').textinput('disable');
			$('#proxyPassword').textinput('disable');
		}
	});
	$('#useProxy').trigger('change');
	
	$('#roundsUseNewRules').change(function() {
		if($(this).val() == '1') {
			$('#roundsPointsLimit').textinput('disable').parent().hide();
			$('#roundsPointsLimitNewRules').textinput('enable').parent().show();
		}
		else {
			$('#roundsPointsLimitNewRules').textinput('disable').parent().hide();
			$('#roundsPointsLimit').textinput('enable').parent().show();
		}
	});
	$('#roundsUseNewRules').trigger('change');
	
	$('#teamUseNewRules').change(function() {
		if($(this).val() == '1') {
			$('#teamPointsLimit').textinput('disable').parent().hide();
			$('#teamPointsLimitNewRules').textinput('enable').parent().show();
		}
		else {
			$('#teamPointsLimitNewRules').textinput('disable').parent().hide();
			$('#teamPointsLimit').textinput('enable').parent().show();
		}
	});
	$('#teamUseNewRules').trigger('change');

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
		});
	});

	$('#gameMode').change(function() {
		$('fieldset.gamemode').trigger('collapse');
		switch($(this).val())
		{
			case '0':
				$('#fieldset-gamemode-script').trigger('expand');
				break;
			case '1':
				$('#fieldset-gamemode-round').trigger('expand');
				break;
			case '2':
				$('#fieldset-gamemode-timeattack').trigger('expand');
				break;
			case '3':
				$('#fieldset-gamemode-team').trigger('expand');
				break;
			case '4':
				$('#fieldset-gamemode-laps').trigger('expand');
				break;
			case '5':
				$('#fieldset-gamemode-cup').trigger('expand');
				break;
		}
	});
	$('#gameMode').trigger('change');
	
	$('#relayMethod').change(function() {
		switch($(this).val())
		{
			case '0':
				$('#fieldset-spectate-managed').show().find('select').select('enable');
				$('#fieldset-spectate-login').hide().find('input').textinput('disable');
				$('#fieldset-spectate-ipAndPort').hide().find('input').textinput('disable');
				break;
			case '1':
				$('#fieldset-spectate-managed').hide().find('select').select('disable');
				$('#fieldset-spectate-login').show().find('input').textinput('enable');
				$('#fieldset-spectate-ipAndPort').hide().find('input').textinput('disable');
				break;
			case '2':
				$('#fieldset-spectate-managed').hide().find('select').select('disable');
				$('#fieldset-spectate-login').hide().find('input').textinput('disable');
				$('#fieldset-spectate-ipAndPort').show().find('input').textinput('enable');
				break;
		}
	});
	$('#relayMethod').trigger('change');
});