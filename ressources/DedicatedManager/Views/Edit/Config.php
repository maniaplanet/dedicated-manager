<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<form action="<?= $r->createLinkArgList('../save-config') ?>" method="get" data-ajax="false">
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<input type="hidden" name="hostname" value="<?= $hostname ?>"/>
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?= _('Basic Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="name">
							<strong><?= _('Displayed name'); ?></strong><br/>
							<i><?= _('Name that will be displayed in the server list.') ?></i>
						</label>
						<input type="text" name="config[name]" id="name" value="<?= $serverOptions->name; ?>" required="required" class="formattingPreview"/>
						<div class="formattingResult"></div>
					</li>
					<li data-role="fieldcontain">
						<label for="comment">
							<strong><?= _('Description'); ?></strong><br/>
							<i><?= _('Short description of the server.') ?></i>
						</label>
						<textarea name="config[comment]" id="comment" rows="4" cols="25"><?= $serverOptions->comment; ?></textarea>
					</li>
					<li data-role="fieldcontain">
						<label for="maxPlayers">
							<strong><?= _('Max players'); ?></strong><br/>
							<i><?= _('Maximum number of players you want to be able to connect on the server.') ?></i><br/>
						</label>
						<input type="range" name="config[nextMaxPlayers]" id="maxPlayers" min="0" value="<?= $serverOptions->nextMaxPlayers; ?>" max="250" data-highlight="true"/>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-theme="b">
				<legend><?= _('Advanced Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="password">
							<strong><?= _('Password'); ?></strong><br/>
							<i><?= _('Password if you want to limit access to players.') ?></i>
						</label>
						<input type="text" name="config[password]" id="password" value="<?= $serverOptions->password; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="maxSpectators">
							<strong><?= _('Max spectators'); ?></strong><br/>
							<i><?= _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
						</label>
						<input type="range" name="config[nextMaxSpectators]" id="maxSpectators" min="0" value="<?= $serverOptions->nextMaxSpectators; ?>" max="250" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="passwordForSpectator">
							<strong><?= _('Password for spectator'); ?></strong><br/>
							<i><?= _('Enter a password if you want to limit access to spectators.') ?></i>
						</label>
						<input type="text" name="config[passwordForSpectator]" id="passwordForSpectator" value="<?= $serverOptions->passwordForSpectator; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="hideServer">
							<strong><?= _('Hide server'); ?></strong><br/>
							<i><?= _('If checked the server will not be visible in the server list.') ?></i>
						</label>
						<select id="hideServer" name="config[hideServer]" data-role="slider">
							<option value="0" <?= !$serverOptions->hideServer ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->hideServer ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="allowMapDownload">
							<strong><?= _('Allow map download'); ?></strong><br/>
							<i><?= _('Allow players to download maps from the server.') ?></i>
						</label>
						<select id="allowMapDownload" name="config[allowMapDownload]" data-role="slider">
							<option value="0" <?= !$serverOptions->allowMapDownload ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->allowMapDownload ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="callVoteRation">
							<strong><?= _('Call vote ratio (in %)'); ?></strong><br/>
							<i><?= _('Ratio in % that define if a vote passed or not.')._('It has to be set at -1 to inactive votes') ?></i>
						</label>
						<input type="range" name="config[callVoteRatio]" id="callVoteRation" value="<?= $serverOptions->callVoteRatio * 100; ?>" min="-1" max="100"/>
					</li>
					<li data-role="fieldcontain">
						<label for="callVoteTimeOut">
							<strong><?= _('Call vote timeout (in seconds)'); ?></strong><br/>
							<i><?= _('Time of duration of a vote') ?></i>
						</label>
						<input type="number" name="config[nextCallVoteTimeOut]" id="callVoteTimeOut" value="<?= $serverOptions->nextCallVoteTimeOut / 1000; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="refereePassword">
							<strong><?= _('Referee password'); ?></strong><br/>
							<i><?= _('Enter a password if you want to limit access to referees.') ?></i>
						</label>
						<input type="text" name="config[refereePassword]" id="refereePassword" value="<?= $serverOptions->refereePassword; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend>
								<strong><?= _('Referee Mode'); ?></strong><br/>
								<i><?= _('Select if the referees will validate only top3 on eache race or everyone.') ?></i>
							</legend>
							<input type="radio" name="config[refereeMode]" id="refereeModeTop3" value="0" <?= $serverOptions->refereeMode == 0 ? 'checked="checked"' : '' ?>/>
							<label for="refereeModeTop3"><?= _('Top 3'); ?></label>
							<input type="radio" name="config[refereeMode]" id="refereeModeAll" value="1" <?= $serverOptions->refereeMode == 1 ? 'checked="checked"' : '' ?>/>
							<label for="refereeModeAll"><?= _('All players'); ?></label>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<label for="autosaveReplays">
							<strong><?= _('Autosave replays'); ?></strong><br/>
							<i><?= _('If checked every a replay will be saved on each map.') ?></i>
						</label>
						<select id="autosaveReplays" name="config[autosaveReplays]" data-role="slider">
							<option value="0" <?= !$serverOptions->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="autosaveValidationReplays">
							<strong><?= _('Autosave replays for validation'); ?></strong><br/>
							<i><?= _('If checked a replay of validation will be generated on each map.') ?></i>
						</label>
						<select id="autosaveValidationReplays" name="config[autosaveValidationReplays]" data-role="slider">
							<option value="0" <?= !$serverOptions->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="nextLadderMode">
							<strong><?= _('Ladder Mode'); ?></strong><br/>
							<i><?= _("Choose if you want to activate or not the ladder on your server.") ?></i>
						</label>
						<select id="nextLadderMode" name="config[nextLadderMode]" data-role="slider">
							<option value="0" <?= !$serverOptions->nextLadderMode ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->nextLadderMode ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="ladderServerLimitMin">
							<strong><?= _('Ladder limit min'); ?></strong><br/>
							<i><?= _("Enter the minimal number of ladder points required to connect to the server.") ?></i>
						</label>
						<input type="number" name="config[ladderServerLimitMin]" id="ladderServerLimitMin" value="<?= $serverOptions->ladderServerLimitMin; ?>" min="0" max="80000" step="10000"/>
					</li>
					<li data-role="fieldcontain">
						<label for="ladderServerLimitMax">
							<strong><?= _('Ladder limit max'); ?></strong><br/>
							<i><?= _("Enter the maximum number of ladder points to win on the server.") ?></i>
						</label>
						<input type="number" name="config[ladderServerLimitMax]" id="ladderServerLimitMax" value="<?= $serverOptions->ladderServerLimitMax; ?>" min="10000" max="100000" step="10000"/>
					</li>
				</ul>
			</fieldset>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" value="<?= _('Restore') ?>" data-icon="refresh"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" value="<?= _('Save configuration') ?>" data-theme="b" data-icon="check"/>
				</div>
			</div>
		</form>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>