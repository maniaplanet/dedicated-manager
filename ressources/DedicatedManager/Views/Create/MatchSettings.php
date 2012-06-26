<?php
require __DIR__.'/../Header.php';
use ManiaLive\DedicatedApi\Structures\GameInfos;
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?= sprintf(_('Step %d on %d'), 2, 4) ?></h2><br/>
		<h3><?= _('Choose your game mode and its rules.') ?></h3>
    </div>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<p><?= _('If you want to load an <strong>existing match settings</strong> click on the link: ') ?>
			<a id="loadConfigLink" href="#dialog" data-rel="dialog"><?= _('load match settings'); ?></a></p>
		<form action="<?= $r->createLinkArgList('../save-match-settings') ?>" method="get" data-ajax="false">
		<?php if($title == 'TMCanyon'): ?>
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?= _('Game mode selection') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="gameMode">
							<strong><?= _('Game mode'); ?></strong><br/>
							<i><?= _('Select the game mode you want to play.') ?></i>
						</label>
						<select name="gameInfos[gameMode]" id="gameMode" required="required" data-native-menu="false">
							<?php if(count($scripts)): ?>
								<option value="<?= GameInfos::GAMEMODE_SCRIPT ?>" <?=
									($gameInfos->gameMode == GameInfos::GAMEMODE_SCRIPT ? 'selected="selected"' : '')
										?>><?= _('Script') ?></option>
							<?php endif; ?>
							<option value="<?= GameInfos::GAMEMODE_ROUNDS ?>" <?=
								($gameInfos->gameMode == GameInfos::GAMEMODE_ROUNDS ? 'selected="selected"' : '')
									?>><?= _('Rounds') ?></option>
							<option value="<?= GameInfos::GAMEMODE_TIMEATTACK ?>" <?=
								($gameInfos->gameMode == GameInfos::GAMEMODE_TIMEATTACK ? 'selected="selected"' : '')
									?>><?= _('Time Attack') ?></option>
							<option value="<?= GameInfos::GAMEMODE_TEAM ?>" <?=
								($gameInfos->gameMode == GameInfos::GAMEMODE_TEAM ? 'selected="selected"' : '')
									?>><?= _('Team') ?></option>
							<option value="<?= GameInfos::GAMEMODE_LAPS ?>" <?=
								($gameInfos->gameMode == GameInfos::GAMEMODE_LAPS ? 'selected="selected"' : '')
									?>><?= _('Laps') ?></option>
							<option value="<?= GameInfos::GAMEMODE_CUP ?>" <?=
								($gameInfos->gameMode == GameInfos::GAMEMODE_CUP ? 'selected="selected"' : '')
									?>><?= _('Cup') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="chatTime">
							<strong><?= _('Chat time (in seconds)'); ?></strong><br/>
							<i><?= _('Set the time of the podium, 0 to disable.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[chatTime]', 'chatTime', $gameInfos->chatTime / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="finishTimeout">
							<strong><?= _('Finish timeout'); ?></strong><br/>
							<i><?=
								_('Represents the time for players to reach the finish line once the first player cross it.').' '.
								_('0 to disable, -1 to use an automatic time, or higher to set a time in seconds.')
									?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[finishTimeout]', 'finishTimeout',
								$gameInfos->finishTimeout == 1 ? -1 : $gameInfos->finishTimeout / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="disableRespawn">
							<strong><?= _('Disable respawn'); ?></strong><br/>
							<i><?= _('If you disable respawn, players will be disqualified when pressing the respawn key.') ?></i>
						</label>
						<select id="disableRespawn" name="gameInfos[disableRespawn]" data-role="slider">
							<option value="0"  <?= !$gameInfos->disableRespawn ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1"  <?= $gameInfos->disableRespawn ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="forceShowAllOpponents">
							<strong><?= _('Force show all opponents'); ?></strong><br/>
							<i><?= _('Set to 0 to allow players to hide opponents, 1 to force them to see all other players. Any other values will represent the number of players displayed') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[forceShowAllOpponents]', 'forceShowAllOpponents', $gameInfos->forceShowAllOpponents) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="allWarmUpDuration">
							<strong><?= _('Warm up duration (except for Cup mode)'); ?></strong><br/>
							<i><?= _("0 will disable warm-up, otherwise it's the number of rounds (in rounds/team mode), or the number of times the gold medal time (other modes).") ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[allWarmUpDuration]', 'allWarmUpDuration', $gameInfos->allWarmUpDuration) ?>
					</li>
				</ul>
			</fieldset>
			<?php if(count($scripts)): ?>
				<fieldset id="fieldset-gamemode-script" data-role="collapsible" data-theme="b" class="gamemode">
					<legend><?= _('Script mode rules') ?></legend>
					<ul data-role="listview">
						<li data-role="fieldcontain">
							<label for="scriptName">
								<strong><?= _('Script name'); ?></strong><br/>
								<i><?= _('Select the game mode script you want to use.') ?></i>
							</label>
							<select name="gameInfos[scriptName]" id="scriptName" data-native-menu="false">
								<option><?= _('Select one...') ?></option>
								<?php foreach($scripts as $script): ?>
									<option value="<?= $script ?>" <?= $gameInfos->scriptName == $script ? 'selected="selected"' : '' ?>><?= $script ?></option>
								<?php endforeach; ?>
							</select>
						</li>
					</ul>
				</fieldset>
			<?php endif; ?>
			<fieldset id="fieldset-gamemode-round" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?= _('Rounds mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="roundsPointsLimit">
							<strong><?= _('Points limit'); ?></strong><br/>
							<i><?= _('Limit of points required to win the match.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[roundsPointsLimit]', 'roundsPointsLimit', $gameInfos->roundsPointsLimit) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="roundsPointsLimitNewRules">
							<strong><?= _('Points limit with new rules'); ?></strong><br/>
							<i><?= _('Limit of points required to win the match.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[roundsPointsLimitNewRules]', 'roundsPointsLimitNewRules', $gameInfos->roundsPointsLimitNewRules) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="roundsForcedLaps">
							<strong><?= _('Forced laps'); ?></strong><br/>
							<i><?= _('Force the number of lap for multilaps maps.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[roundsForcedLaps]', 'roundsForcedLaps', $gameInfos->roundsForcedLaps) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="roundsUseNewRules">
							<strong><?= _('Use new rules'); ?></strong><br/>
							<i><?= _('With new rules, only the first win a single point.') ?></i>
						</label>
						<select id="roundsUseNewRules" name="gameInfos[roundsUseNewRules]" data-role="slider">
							<option value="0" <?= !$gameInfos->roundsUseNewRules ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $gameInfos->roundsUseNewRules ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-timeattack" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?= _('Time attack mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="timeAttackLimit">
							<strong><?= _('Time limit in seconds'); ?></strong><br/>
							<i><?= _('Map duration.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[timeAttackLimit]', 'timeAttackLimit',
								$gameInfos->timeAttackLimit == 1 ? -1 : $gameInfos->timeAttackLimit / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="timeAttackSynchStartPeriod">
							<strong><?= _('Synchronisation period at start in seconds'); ?></strong><br/>
							<i><?= _('Time of player synchronisation at the beginning of the map.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[timeAttackSynchStartPeriod]', 'timeAttackSynchStartPeriod',
								$gameInfos->timeAttackSynchStartPeriod == 1 ? -1 : $gameInfos->timeAttackSynchStartPeriod / 1000) ?>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-team" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?= _('Team mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="teamPointsLimit">
							<strong><?= _('Points limit'); ?></strong><br/>
							<i><?= _('Limit of points required to win the match.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[teamPointsLimit]', 'teamPointsLimit', $gameInfos->teamPointsLimit) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="teamPointsLimitNewRules">
							<strong><?= _('Points limit with new rules'); ?></strong><br/>
							<i><?= _('Limit of points required to win the match.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[teamPointsLimitNewRules]', 'teamPointsLimitNewRules', $gameInfos->teamPointsLimitNewRules) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="teamMaxPoints">
							<strong><?= _('Max points'); ?></strong><br/>
							<i><?= _('Maximum points that a team can win.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[teamMaxPoints]', 'teamMaxPoints', $gameInfos->teamMaxPoints) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="teamUseNewRules">
							<strong><?= _('Use new rules'); ?></strong><br/>
						</label>
						<select id="teamUseNewRules" name="gameInfos[teamUseNewRules]" data-role="slider">
							<option value="0" <?= !$gameInfos->teamUseNewRules ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $gameInfos->teamUseNewRules ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-laps" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?= _('Laps mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="lapsNbLaps">
							<strong><?= _('Laps number'); ?></strong><br/>
							<i><?= _('Number of laps to do before finnishing the race.').' '._("If set to 0, the number laps of the map is used.") ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[lapsNbLaps]', 'lapsNbLaps', $gameInfos->lapsNbLaps) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="lapsTimeLimit">
							<strong><?= _('Time limit in seconds'); ?></strong><br/>
							<i><?= _('Time allowed for player to do this number of laps.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[lapsTimeLimit]', 'lapsTimeLimit', $gameInfos->lapsTimeLimit / 1000) ?>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-gamemode-cup" data-role="collapsible" data-theme="b" class="gamemode">
				<legend><?= _('Cup mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="cupPointsLimit">
							<strong><?= _('Points limit'); ?></strong><br/>
							<i><?= _('Number of point to earn before reaching the finalist status.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[cupPointsLimit]', 'cupPointsLimit', $gameInfos->cupPointsLimit) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="cupRoundsPerMap">
							<strong><?= _('Rounds per map'); ?></strong><br/>
							<i><?= _('Number of rounds played per map.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[cupRoundsPerMap]', 'cupRoundsPerMap', $gameInfos->cupRoundsPerMap) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="cupNbWinners">
							<strong><?= _('Number of winner'); ?></strong><br/>
							<i><?= _('Number of player who has to win before the match is complete.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[cupNbWinners]', 'cupNbWinners', $gameInfos->cupNbWinners) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="cupWarmUpDuration">
							<strong><?= _('Warm up duration'); ?></strong><br/>
							<i><?= _('Number of warm up round to play on each map.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('gameInfos[cupWarmUpDuration]', 'cupWarmUpDuration', $gameInfos->cupWarmUpDuration) ?>
					</li>
				</ul>
			</fieldset>
		<?php else: ?>
			<input type="hidden" name="gameInfos[gameMode]" value="<?= GameInfos::GAMEMODE_SCRIPT ?>">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?= _('Script mode rules') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="scriptName">
							<strong><?= _('Script name'); ?></strong><br/>
							<i><?= _('Select the game mode script you want to use.') ?></i>
						</label>

						<select name="gameInfos[scriptName]" id="scriptName" data-native-menu="false">
							<option><?= _('Select one...') ?></option>
							<?php foreach($scripts as $script): ?>
								<option value="<?= $script ?>" <?= $gameInfos->scriptName == $script ? 'selected="selected"' : '' ?>><?= $script ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul>
			</fieldset>
		<?php endif; ?>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?= _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" value="<?= _('Next step') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
    </div>
</div>
<div data-role="dialog" id="dialog">
    <div data-role="header" data-theme="b">
		<h1><?= _('Load MatchSettings') ?></h1>
    </div>
    <div data-role="content">
		<form name="load_config_form" action="<?= $r->createLinkArgList('.') ?>" data-ajax="false" method="get" title="<?= _('Load existing settings') ?>">
			<label for="matchFile"><?= _('A match settings contains game mode setups and a map selection') ?></label>
			<select id="matchFile" name="matchFile" size="5" data-native-menu="false">
			<?php foreach($settingsList as $settings): ?>
				<option value="<?= $settings ?>"><?= $settings ?></option>
			<?php endforeach; ?>
			</select>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<a href="#" data-rel="back" data-role="button"><?= _('Back') ?></a>
				</div>
				<div class="ui-block-b">
					<input type="submit" value="Load" data-theme="a"/>
				</div>
			</div>
		</form>
    </div>
</div>
<?php require __DIR__.'/../Footer.php' ?>