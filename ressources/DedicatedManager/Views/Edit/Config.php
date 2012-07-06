<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?= $r->createLinkArgList('../save-config') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
					<legend><?= _('Basic Server Configuration') ?></legend>
					<ul data-role="listview">
						<li data-role="fieldcontain">
							<label for="name">
								<strong><?= _('Displayed name'); ?></strong><br/>
								<i><?= _('Name that will be displayed in the server list.') ?></i>
							</label>
							<?= DedicatedManager\Helpers\Input::text('options[name]', 'name', $options->name) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="comment">
								<strong><?= _('Description'); ?></strong><br/>
								<i><?= _('Short description of the server.') ?></i>
							</label>
							<textarea name="options[comment]" id="comment" rows="4" cols="25"><?= $options->comment; ?></textarea>
						</li>
					<?php if($isRelay): ?>
						<li data-role="fieldcontain">
							<label for="maxSpectators">
								<strong><?= _('Max spectators'); ?></strong><br/>
								<i><?= _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
							</label>
							<input type="range" name="options[nextMaxSpectators]" id="maxSpectators" value="<?= $options->nextMaxSpectators; ?>" min="0" max="255" data-highlight="true"/>
						</li>
					<?php else: ?>
						<li data-role="fieldcontain">
							<label for="maxPlayers">
								<strong><?= _('Max players'); ?></strong><br/>
								<i><?= _('Maximum number of players you want to be able to connect on the server.') ?></i><br/>
							</label>
							<input type="range" name="options[nextMaxPlayers]" id="maxPlayers" value="<?= $options->nextMaxPlayers; ?>" min="0" max="255" data-highlight="true"/>
						</li>
					<?php endif; ?>
					</ul>
				</fieldset>
				<fieldset data-role="collapsible" data-theme="b">
					<legend><?= _('Advanced Server Configuration') ?></legend>
					<ul data-role="listview">
					<?php if(!$isRelay): ?>
						<li data-role="fieldcontain">
							<label for="password">
								<strong><?= _('Password'); ?></strong><br/>
								<i><?= _('Password if you want to limit access to players.') ?></i>
							</label>
							<?= DedicatedManager\Helpers\Input::text('options[password]', 'password', $options->password) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="maxSpectators">
								<strong><?= _('Max spectators'); ?></strong><br/>
								<i><?= _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
							</label>
							<input type="range" name="options[nextMaxSpectators]" id="maxSpectators" value="<?= $options->nextMaxSpectators; ?>" min="0" max="255" data-highlight="true"/>
						</li>
					<?php endif; ?>
						<li data-role="fieldcontain">
							<label for="passwordForSpectator">
								<strong><?= _('Password for spectator'); ?></strong><br/>
								<i><?= _('Enter a password if you want to limit access to spectators.') ?></i>
							</label>
							<?= DedicatedManager\Helpers\Input::text('options[passwordForSpectator]', 'passwordForSpectator', $options->passwordForSpectator) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="hideServer">
								<strong><?= _('Hide server'); ?></strong><br/>
								<i><?= _('If checked the server will not be visible in the server list.') ?></i>
							</label>
							<select id="hideServer" name="options[hideServer]" data-role="slider">
								<option value="0" <?= !$options->hideServer ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
								<option value="1" <?= $options->hideServer ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="allowMapDownload">
								<strong><?= _('Allow map download'); ?></strong><br/>
								<i><?= _('Allow players to download maps from the server.') ?></i>
							</label>
							<select id="allowMapDownload" name="options[allowMapDownload]" data-role="slider">
								<option value="0" <?= !$options->allowMapDownload ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
								<option value="1" <?= $options->allowMapDownload ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
							</select>
						</li>
					<?php if(!$isRelay): ?>
						<li data-role="fieldcontain">
							<label for="callVoteRatio">
								<strong><?= _('Call vote ratio (in %)'); ?></strong><br/>
								<i><?= _('Ratio in % that define if a vote passed or -1 to disable votes') ?></i>
							</label>
							<input type="range" name="options[callVoteRatio]" id="callVoteRatio" value="<?= $options->callVoteRatio == -1 ? -1 : $options->callVoteRatio * 100; ?>" min="-1" max="100" data-highlight="true"/>
						</li>
						<li data-role="fieldcontain">
							<label for="callVoteTimeOut">
								<strong><?= _('Call vote timeout (in seconds)'); ?></strong><br/>
								<i><?= _('Time of duration of a vote') ?></i>
							</label>
							<input type="number" name="options[nextCallVoteTimeOut]" id="callVoteTimeOut" value="<?= $options->nextCallVoteTimeOut / 1000; ?>"/>
						</li>
					<?php endif; ?>
						<li data-role="fieldcontain">
							<label for="refereePassword">
								<strong><?= _('Referee password'); ?></strong><br/>
								<i><?= _('Enter a password if you want to limit access to referees.') ?></i>
							</label>
							<?= DedicatedManager\Helpers\Input::text('options[refereePassword]', 'refereePassword', $options->refereePassword) ?>
						</li>
						<li data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<legend>
									<strong><?= _('Referee Mode'); ?></strong><br/>
									<i><?= _('Select if the referees will validate only top3 on eache race or everyone.') ?></i>
								</legend>
								<input type="radio" name="options[refereeMode]" id="refereeModeTop3" value="0" <?= $options->refereeMode == 0 ? 'checked="checked"' : '' ?>/>
								<label for="refereeModeTop3"><?= _('Top 3'); ?></label>
								<input type="radio" name="options[refereeMode]" id="refereeModeAll" value="1" <?= $options->refereeMode == 1 ? 'checked="checked"' : '' ?>/>
								<label for="refereeModeAll"><?= _('All players'); ?></label>
							</fieldset>
						</li>
						<li data-role="fieldcontain">
							<label for="autosaveReplays">
								<strong><?= _('Autosave replays'); ?></strong><br/>
								<i><?= _('If checked every a replay will be saved on each map.') ?></i>
							</label>
							<select id="autosaveReplays" name="options[autosaveReplays]" data-role="slider">
								<option value="0" <?= !$options->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
								<option value="1" <?= $options->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="autosaveValidationReplays">
								<strong><?= _('Autosave replays for validation'); ?></strong><br/>
								<i><?= _('If checked a replay of validation will be generated on each map.') ?></i>
							</label>
							<select id="autosaveValidationReplays" name="options[autosaveValidationReplays]" data-role="slider">
								<option value="0" <?= !$options->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
								<option value="1" <?= $options->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
							</select>
						</li>
					<?php if(!$isRelay): ?>
						<li data-role="fieldcontain">
							<label for="nextLadderMode">
								<strong><?= _('Ladder Mode'); ?></strong><br/>
								<i><?= _("Choose if you want to activate or not the ladder on your server.") ?></i>
							</label>
							<select id="nextLadderMode" name="options[nextLadderMode]" data-role="slider">
								<option value="0" <?= !$options->nextLadderMode ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
								<option value="1" <?= $options->nextLadderMode ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
							</select>
						</li>
					<?php endif; ?>
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
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>