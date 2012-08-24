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
	
	$('#nextLadderMode').change(function () {
		if($(this).val() == '1') {
			$('#ladderServerLimitMin').textinput('enable');
			$('#ladderServerLimitMax').textinput('enable');
		}
		else {
			$('#ladderServerLimitMin').textinput('disable');
			$('#ladderServerLimitMax').textinput('disable');
		}
	}).trigger('change');
	
	$('#useProxy').change(function () {
		if($(this).val() == '1') {
			$('#proxyLogin').textinput('enable');
			$('#proxyPassword').textinput('enable');
		}
		else {
			$('#proxyLogin').textinput('disable');
			$('#proxyPassword').textinput('disable');
		}
	}).trigger('change');
	
	$('#roundsUseNewRules').change(function() {
		if($(this).val() == '1') {
			$('#roundsPointsLimit').textinput('disable').parent().hide();
			$('#roundsPointsLimitNewRules').textinput('enable').parent().show();
		}
		else {
			$('#roundsPointsLimitNewRules').textinput('disable').parent().hide();
			$('#roundsPointsLimit').textinput('enable').parent().show();
		}
	}).trigger('change');
	
	$('#teamUseNewRules').change(function() {
		if($(this).val() == '1') {
			$('#teamPointsLimit').textinput('disable').parent().hide();
			$('#teamPointsLimitNewRules').textinput('enable').parent().show();
		}
		else {
			$('#teamPointsLimitNewRules').textinput('disable').parent().hide();
			$('#teamPointsLimit').textinput('enable').parent().show();
		}
	}).trigger('change');

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
	}).trigger('change');
	
	$('#relayMethod').change(function() {
		if($(this).val() == 'managed')
		{
			$('#fieldset-spectate-managed').show().find('select').select('enable');
			$('#fieldset-spectate-login').hide().find('input').textinput('disable');
			$('#fieldset-spectate-ipAndPort').hide().find('input').textinput('disable');
		}
		else if($(this).val() == 'login')
		{
			$('#fieldset-spectate-managed').hide().find('select').select('disable');
			$('#fieldset-spectate-login').show().find('input').textinput('enable');
			$('#fieldset-spectate-ipAndPort').hide().find('input').textinput('disable');
		}
		else if($(this).val() == 'ip')
		{
			$('#fieldset-spectate-managed').hide().find('select').select('disable');
			$('#fieldset-spectate-login').hide().find('input').textinput('disable');
			$('#fieldset-spectate-ipAndPort').show().find('input').textinput('enable');
		}
	}).trigger('change');
});