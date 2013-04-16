$(document).bind('pageinit', function() {
	$('select#isOnline').parent().find('.ui-slider-switch').css('width', '7em');
	$('select#hideServer').parent().find('.ui-slider-switch').css('width', '7em');
	$('select#nextLadderMode').parent().find('.ui-slider-switch').css('width', '7em');

	$('#isOnline').change(function() {
		if($(this).val() == '1') {
			$('#field-internet').trigger('expand');
		}
		else {
			$('#field-internet').trigger('collapse');
		}
	}).trigger('change');

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

	$('#scriptName').change(function() {
		$('.setting').hide().find('input').textinput('disable').end().find('select').select('disable');
		$('.setting.'+$('#scriptName option:selected').first().jqmData('script-id')).show().find('input').textinput('enable').end().find('select').select('enable');
		$(this).closest('ul').listview('refresh');
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

	$('#databaseEnable').change(function() {
		if($(this).val() == '1') {
			$('#field-database').trigger('expand');
		}
		else {
			$('#field-database').trigger('collapse');
		}
	}).trigger('change');

	$('#threadingEnabled').change(function() {
		if($(this).val() == '1') {
			$('#field-threading').trigger('expand');
		}
		else {
			$('#field-threading').trigger('collapse');
		}
	}).trigger('change');

	$('#add-admin-button').click(function() {
		$('<input type="text" name="admins[]"/>').insertAfter($(this).prev()).textinput();
	});

	$('#configFile').change(function() {
		$(this).parents("form").submit();
	});
});