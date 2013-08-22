<?php
require __DIR__.'/../Header.php';
use \DedicatedApi\Structures\GameInfos;
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo sprintf(_('Step %d on %d'), 2, 4) ?></h2><br/>
		<h3><?php echo _('Choose your game mode and its rules.') ?></h3>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form name="load_config_form" action="<?php echo $r->createLinkArgList('.') ?>" data-ajax="false" method="get" data-role="collapsible-group">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="e">
				<legend><?php echo _('Load a Match settings file') ?></legend>
				<ul data-role="listview" >
					<li>
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<select id="matchFile" name="matchFile" size="5" data-native-menu="false">
									<option <?php echo in_array($matchFile, $settingsList) ? '' : 'selected="selected"'; ?>><?php echo _('Select file') ?></option>
								<?php foreach($settingsList as $setting): ?>
									<option value="<?php echo $setting ?>" <?php echo $setting == $matchFile ? 'selected="selected"': ''; ?>><?php echo $setting ?></option>
								<?php endforeach; ?>
								</select>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="Load" data-theme="a"/>
							</div>
						</div>
					</li>
				</ul>
			</fieldset>
		</form>

		<form action="<?php echo $r->createLinkArgList('../set-rules') ?>" method="post" data-ajax="false" data-role="collapsible-group">
		<?php if($title == 'TMCanyon' || $title == 'TMStadium' || $title == 'TMValley'): ?>
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Game mode selection') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="gameMode">
							<strong><?php echo _('Game mode'); ?></strong><br/>
							<i><?php echo _('Select the game mode you want to play.') ?></i>
						</label>
						<select name="rules[gameMode]" id="gameMode" required="required" data-native-menu="false">
							<?php if(count($scripts)): ?>
								<option value="<?php echo GameInfos::GAMEMODE_SCRIPT ?>" <?php echo
									($gameInfos->gameMode == GameInfos::GAMEMODE_SCRIPT ? 'selected="selected"' : '')
										?>><?php echo _('Script') ?></option>
							<?php endif; ?>
							<option value="<?php echo GameInfos::GAMEMODE_ROUNDS ?>" <?php echo
								($gameInfos->gameMode == GameInfos::GAMEMODE_ROUNDS ? 'selected="selected"' : '')
									?>><?php echo _('Rounds') ?></option>
							<option value="<?php echo GameInfos::GAMEMODE_TIMEATTACK ?>" <?php echo
								($gameInfos->gameMode == GameInfos::GAMEMODE_TIMEATTACK ? 'selected="selected"' : '')
									?>><?php echo _('Time Attack') ?></option>
							<option value="<?php echo GameInfos::GAMEMODE_TEAM ?>" <?php echo
								($gameInfos->gameMode == GameInfos::GAMEMODE_TEAM ? 'selected="selected"' : '')
									?>><?php echo _('Team') ?></option>
							<option value="<?php echo GameInfos::GAMEMODE_LAPS ?>" <?php echo
								($gameInfos->gameMode == GameInfos::GAMEMODE_LAPS ? 'selected="selected"' : '')
									?>><?php echo _('Laps') ?></option>
							<option value="<?php echo GameInfos::GAMEMODE_CUP ?>" <?php echo
								($gameInfos->gameMode == GameInfos::GAMEMODE_CUP ? 'selected="selected"' : '')
									?>><?php echo _('Cup') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="chatTime">
							<strong><?php echo _('Chat time (in seconds)'); ?></strong><br/>
							<i><?php echo _('Set the time of the podium, 0 to disable.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[chatTime]', 'chatTime', $gameInfos->chatTime / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="finishTimeout">
							<strong><?php echo _('Finish timeout'); ?></strong><br/>
							<i><?php echo
								_('Represents the time for players to reach the finish line once the first player cross it.').' '.
								_('0 to disable, -1 to use an automatic time, or higher to set a time in seconds.')
									?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[finishTimeout]', 'finishTimeout',
								$gameInfos->finishTimeout == 1 ? -1 : $gameInfos->finishTimeout / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="disableRespawn">
							<strong><?php echo _('Disable respawn'); ?></strong><br/>
							<i><?php echo _('If you disable respawn, players will be disqualified when pressing the respawn key.') ?></i>
						</label>
						<select id="disableRespawn" name="rules[disableRespawn]" data-role="slider">
							<option value="0"  <?php echo !$gameInfos->disableRespawn ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1"  <?php echo $gameInfos->disableRespawn ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="forceShowAllOpponents">
							<strong><?php echo _('Force show all opponents'); ?></strong><br/>
							<i><?php echo _('Set to 0 to allow players to hide opponents, 1 to force them to see all other players. Any other values will represent the number of players displayed') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[forceShowAllOpponents]', 'forceShowAllOpponents', $gameInfos->forceShowAllOpponents) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="allWarmUpDuration">
							<strong><?php echo _('Warm up duration (except for Cup mode)'); ?></strong><br/>
							<i><?php echo _("0 will disable warm-up, otherwise it's the number of rounds (in rounds/team mode), or the number of times the gold medal time (other modes).") ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[allWarmUpDuration]', 'allWarmUpDuration', $gameInfos->allWarmUpDuration) ?>
					</li>
				</ul>
			</fieldset>
		<?php if(count($scripts)): ?>
			<fieldset id="fieldset-gamemode-script" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Script mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="scriptName">
							<strong><?php echo _('Script name'); ?></strong><br/>
							<i><?php echo _('Select the game mode script you want to use.') ?></i>
						</label>
						<select name="rules[scriptName]" id="scriptName" data-native-menu="false">
							<option><?php echo _('Select one...') ?></option>
							<?php foreach($scripts as $script): ?>
								<option value="<?php echo $script ?>" <?php echo $gameInfos->scriptName == $script ? 'selected="selected"' : '' ?> data-script-id="<?php echo $scriptIds[$script]; ?>"><?php echo $script ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				<?php foreach($scriptsRules as $script => $scriptRules): ?>
					<?php foreach($scriptRules as $scriptRule): ?>
						<li data-role="fieldcontain" class="setting <?php echo $scriptIds[$script]; ?>">
							<label for="<?php echo $script.'_'.$scriptRule->name ?>">
								<strong><?php echo $scriptRule->name; ?></strong><br/>
								<i><?php echo $scriptRule->desc; ?></i>
							</label>
						<?php if($scriptRule->type == 'boolean'): ?>
							<select name="scriptRules[<?php echo $script?>][<?php echo $scriptRule->name ?>]" id="<?php echo $script.'_'.$scriptRule->name ?>" data-role="slider">
								<option value="0" <?php echo (!$scriptRule->default ? 'selected="selected"' : '') ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo ($scriptRule->default ? 'selected="selected"' : '') ?>><?php echo _('Yes') ?></option>
							</select>
						<?php else: ?>
							<input type="text" id="<?php echo $script.'_'.$scriptRule->name ?>" name="scriptRules[<?php echo $script?>][<?php echo $scriptRule->name ?>]" value="<?php echo $scriptRule->default ?>"/>
						<?php endif; ?>
						</li>
					<?php endforeach; ?>
				<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endif; ?>
			<fieldset id="fieldset-gamemode-round" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Rounds mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="roundsPointsLimit">
							<strong><?php echo _('Points limit'); ?></strong><br/>
							<i><?php echo _('Limit of points required to win the match.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[roundsPointsLimit]', 'roundsPointsLimit', $gameInfos->roundsPointsLimit) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="roundsPointsLimitNewRules">
							<strong><?php echo _('Points limit with new rules'); ?></strong><br/>
							<i><?php echo _('Limit of points required to win the match.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[roundsPointsLimitNewRules]', 'roundsPointsLimitNewRules', $gameInfos->roundsPointsLimitNewRules) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="roundsForcedLaps">
							<strong><?php echo _('Forced laps'); ?></strong><br/>
							<i><?php echo _('Force the number of lap for multilaps maps.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[roundsForcedLaps]', 'roundsForcedLaps', $gameInfos->roundsForcedLaps) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="roundsUseNewRules">
							<strong><?php echo _('Use new rules'); ?></strong><br/>
							<i><?php echo _('With new rules, only the first win a single point.') ?></i>
						</label>
						<select id="roundsUseNewRules" name="rules[roundsUseNewRules]" data-role="slider">
							<option value="0" <?php echo !$gameInfos->roundsUseNewRules ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $gameInfos->roundsUseNewRules ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-timeattack" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Time attack mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="timeAttackLimit">
							<strong><?php echo _('Time limit in seconds'); ?></strong><br/>
							<i><?php echo _('Map duration.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[timeAttackLimit]', 'timeAttackLimit',
								$gameInfos->timeAttackLimit == 1 ? -1 : $gameInfos->timeAttackLimit / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="timeAttackSynchStartPeriod">
							<strong><?php echo _('Synchronisation period at start in seconds'); ?></strong><br/>
							<i><?php echo _('Time of player synchronisation at the beginning of the map.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[timeAttackSynchStartPeriod]', 'timeAttackSynchStartPeriod',
								$gameInfos->timeAttackSynchStartPeriod == 1 ? -1 : $gameInfos->timeAttackSynchStartPeriod / 1000) ?>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-team" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Team mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="teamPointsLimit">
							<strong><?php echo _('Points limit'); ?></strong><br/>
							<i><?php echo _('Limit of points required to win the match.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[teamPointsLimit]', 'teamPointsLimit', $gameInfos->teamPointsLimit) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="teamPointsLimitNewRules">
							<strong><?php echo _('Points limit with new rules'); ?></strong><br/>
							<i><?php echo _('Limit of points required to win the match.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[teamPointsLimitNewRules]', 'teamPointsLimitNewRules', $gameInfos->teamPointsLimitNewRules) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="teamMaxPoints">
							<strong><?php echo _('Max points'); ?></strong><br/>
							<i><?php echo _('Maximum points that a team can win.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[teamMaxPoints]', 'teamMaxPoints', $gameInfos->teamMaxPoints) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="teamUseNewRules">
							<strong><?php echo _('Use new rules'); ?></strong><br/>
						</label>
						<select id="teamUseNewRules" name="rules[teamUseNewRules]" data-role="slider">
							<option value="0" <?php echo !$gameInfos->teamUseNewRules ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $gameInfos->teamUseNewRules ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-laps" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Laps mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="lapsNbLaps">
							<strong><?php echo _('Laps number'); ?></strong><br/>
							<i><?php echo _('Number of laps to do before finishing the race, or 0 to use map default.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[lapsNbLaps]', 'lapsNbLaps', $gameInfos->lapsNbLaps) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="lapsTimeLimit">
							<strong><?php echo _('Time limit in seconds'); ?></strong><br/>
							<i><?php echo _('Time allowed for player to do this number of laps.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[lapsTimeLimit]', 'lapsTimeLimit', $gameInfos->lapsTimeLimit / 1000) ?>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-cup" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Cup mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="cupPointsLimit">
							<strong><?php echo _('Points limit'); ?></strong><br/>
							<i><?php echo _('Number of point to earn before reaching the finalist status.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[cupPointsLimit]', 'cupPointsLimit', $gameInfos->cupPointsLimit) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="cupRoundsPerMap">
							<strong><?php echo _('Rounds per map'); ?></strong><br/>
							<i><?php echo _('Number of rounds played per map.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[cupRoundsPerMap]', 'cupRoundsPerMap', $gameInfos->cupRoundsPerMap) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="cupNbWinners">
							<strong><?php echo _('Number of winner'); ?></strong><br/>
							<i><?php echo _('Number of player who has to win before the match is complete.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[cupNbWinners]', 'cupNbWinners', $gameInfos->cupNbWinners) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="cupWarmUpDuration">
							<strong><?php echo _('Warm up duration'); ?></strong><br/>
							<i><?php echo _('Number of warm up round to play on each map.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('rules[cupWarmUpDuration]', 'cupWarmUpDuration', $gameInfos->cupWarmUpDuration) ?>
					</li>
				</ul>
			</fieldset>
		<?php else: ?>
			<input type="hidden" id="gameMode" name="rules[gameMode]" value="<?php echo GameInfos::GAMEMODE_SCRIPT ?>">
			<fieldset id="fieldset-gamemode-script" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?php echo _('Script mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="scriptName">
							<strong><?php echo _('Script name'); ?></strong><br/>
							<i><?php echo _('Select the game mode script you want to use.') ?></i>
						</label>
						<select name="rules[scriptName]" id="scriptName" data-native-menu="false">
							<option><?php echo _('Select one...') ?></option>
							<?php foreach($scripts as $script): ?>
								<option value="<?php echo $script ?>" <?php echo $gameInfos->scriptName == $script ? 'selected="selected"' : '' ?> data-script-id="<?php echo $scriptIds[$script]; ?>"><?php echo $script ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				<?php foreach($scriptsRules as $script => $scriptRules): ?>
					<?php foreach($scriptRules as $scriptRule): ?>
						<li data-role="fieldcontain" class="setting <?php echo $scriptIds[$script]; ?>">
							<label for="<?php echo $script.'_'.$scriptRule->name ?>">
								<strong><?php echo $scriptRule->name; ?></strong><br/>
								<i><?php echo $scriptRule->desc; ?></i>
							</label>
						<?php if($scriptRule->type == 'boolean'): ?>
							<select name="scriptRules[<?php echo $script?>][<?php echo $scriptRule->name ?>]" id="<?php echo $script.'_'.$scriptRule->name ?>" data-role="slider">
								<option value="0" <?php echo (!$scriptRule->default ? 'selected="selected"' : '') ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo ($scriptRule->default ? 'selected="selected"' : '') ?>><?php echo _('Yes') ?></option>
							</select>
						<?php else: ?>
							<input type="text" id="<?php echo $script.'_'.$scriptRule->name ?>" name="scriptRules[<?php echo $script?>][<?php echo $scriptRule->name ?>]" value="<?php echo $scriptRule->default ?>"/>
						<?php endif; ?>
						</li>
					<?php endforeach; ?>
				<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endif; ?>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?php echo _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" value="<?php echo _('Next step') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
    </div>
</div>
<?php require __DIR__.'/../Footer.php' ?>