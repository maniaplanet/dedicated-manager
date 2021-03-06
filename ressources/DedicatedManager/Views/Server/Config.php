<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?php echo $r->createLinkArgList('../set-config') ?>" method="get" data-ajax="false" data-role="collapsible-group">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
					<legend><?php echo _('Basic Server Configuration') ?></legend>
					<ul data-role="listview">
						<li data-role="fieldcontain">
							<label for="name">
								<strong><?php echo _('Displayed name'); ?></strong><br/>
								<i><?php echo _('Name that will be displayed in the server list.') ?></i>
							</label>
							<input type="text" name="options[name]" id="name" value="<?php echo htmlentities($options->name, ENT_QUOTES, 'utf-8'); ?>" maxlength="75" data-role="maniaplanet-style"/>
						</li>
						<li data-role="fieldcontain">
							<label for="comment">
								<strong><?php echo _('Description'); ?></strong><br/>
								<i><?php echo _('Short description of the server.') ?></i>
							</label>
							<textarea name="options[comment]" id="comment" data-role="maniaplanet-style"><?php echo htmlentities($options->comment, ENT_QUOTES, 'utf-8') ?></textarea>
						</li>
					<?php if($isRelay): ?>
						<li data-role="fieldcontain">
							<label for="maxSpectators">
								<strong><?php echo _('Max spectators'); ?></strong><br/>
								<i><?php echo _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
							</label>
							<input type="range" name="options[nextMaxSpectators]" id="maxSpectators" value="<?php echo $options->nextMaxSpectators; ?>" min="0" max="255" data-highlight="true"/>
						</li>
					<?php else: ?>
						<li data-role="fieldcontain">
							<label for="maxPlayers">
								<strong><?php echo _('Max players'); ?></strong><br/>
								<i><?php echo _('Maximum number of players you want to be able to connect on the server.') ?></i><br/>
							</label>
							<input type="range" name="options[nextMaxPlayers]" id="maxPlayers" value="<?php echo $options->nextMaxPlayers; ?>" min="0" max="255" data-highlight="true"/>
						</li>
					<?php endif; ?>
					</ul>
				</fieldset>
				<fieldset data-role="collapsible" data-theme="b">
					<legend><?php echo _('Advanced Server Configuration') ?></legend>
					<ul data-role="listview">
					<?php if(!$isRelay): ?>
						<li data-role="fieldcontain">
							<label for="password">
								<strong><?php echo _('Password'); ?></strong><br/>
								<i><?php echo _('Password if you want to limit access to players.') ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('options[password]', 'password', $options->password) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="maxSpectators">
								<strong><?php echo _('Max spectators'); ?></strong><br/>
								<i><?php echo _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
							</label>
							<input type="range" name="options[nextMaxSpectators]" id="maxSpectators" value="<?php echo $options->nextMaxSpectators; ?>" min="0" max="255" data-highlight="true"/>
						</li>
					<?php endif; ?>
						<li data-role="fieldcontain">
							<label for="passwordForSpectator">
								<strong><?php echo _('Password for spectator'); ?></strong><br/>
								<i><?php echo _('Enter a password if you want to limit access to spectators.') ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('options[passwordForSpectator]', 'passwordForSpectator', $options->passwordForSpectator) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="hideServer">
								<strong><?php echo _('Hide server'); ?></strong><br/>
								<i><?php echo _('If checked the server will not be visible in the server list.') ?></i>
							</label>
							<select id="hideServer" name="options[hideServer]" data-role="slider">
								<option value="0" <?php echo !$options->hideServer ? 'selected="selected"' : '' ?>><?php echo _('Visible') ?></option>
								<option value="1" <?php echo $options->hideServer ? 'selected="selected"' : '' ?>><?php echo _('Hidden') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="allowMapDownload">
								<strong><?php echo _('Allow map download'); ?></strong><br/>
								<i><?php echo _('Allow players to download maps from the server.') ?></i>
							</label>
							<select id="allowMapDownload" name="options[allowMapDownload]" data-role="slider">
								<option value="0" <?php echo !$options->allowMapDownload ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo $options->allowMapDownload ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="callVoteRatio">
								<strong><?php echo _('Call vote ratio (in %)'); ?></strong><br/>
								<i><?php echo _('Ratio in % that define if a vote passed or -1 to disable votes') ?></i>
							</label>
							<input type="range" name="options[callVoteRatio]" id="callVoteRatio" value="<?php echo $options->callVoteRatio == -1 ? -1 : $options->callVoteRatio * 100; ?>" min="-1" max="100" data-highlight="true"/>
						</li>
						<li data-role="fieldcontain">
							<label for="callVoteTimeOut">
								<strong><?php echo _('Call vote timeout (in seconds)'); ?></strong><br/>
								<i><?php echo _('Time of duration of a vote') ?></i>
							</label>
							<input type="number" name="options[nextCallVoteTimeOut]" id="callVoteTimeOut" value="<?php echo $options->nextCallVoteTimeOut / 1000; ?>"/>
						</li>
						<li data-role="fieldcontain">
							<label for="refereePassword">
								<strong><?php echo _('Referee password'); ?></strong><br/>
								<i><?php echo _('Enter a password if you want to limit access to referees.') ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('options[refereePassword]', 'refereePassword', $options->refereePassword) ?>
						</li>
						<li data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<legend>
									<strong><?php echo _('Referee Mode'); ?></strong><br/>
									<i><?php echo _('Select if the referees will validate only top3 on eache race or everyone.') ?></i>
								</legend>
								<input type="radio" name="options[refereeMode]" id="refereeModeTop3" value="0" <?php echo $options->refereeMode == 0 ? 'checked="checked"' : '' ?>/>
								<label for="refereeModeTop3"><?php echo _('Top 3'); ?></label>
								<input type="radio" name="options[refereeMode]" id="refereeModeAll" value="1" <?php echo $options->refereeMode == 1 ? 'checked="checked"' : '' ?>/>
								<label for="refereeModeAll"><?php echo _('All players'); ?></label>
							</fieldset>
						</li>
						<li data-role="fieldcontain">
							<label for="disableHorns">
								<strong><?php echo _('Disable horns'); ?></strong><br/>
							</label>
							<select id="disableHorns" name="options[disableHorns]" data-role="slider">
								<option value="0" <?php echo !$options->disableHorns ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo $options->disableHorns ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="inputsMaxLatency">
								<strong><?php echo _('Inputs max latency (in ms)'); ?></strong><br/>
								<i><?php echo _('How long the server waits for player inputs, or 0 for automatic adjustment'); ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('options[clientInputsMaxLatency]', 'inputsMaxLatency', $options->clientInputsMaxLatency) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="autosaveReplays">
								<strong><?php echo _('Autosave replays'); ?></strong><br/>
								<i><?php echo _('If checked every a replay will be saved on each map.') ?></i>
							</label>
							<select id="autosaveReplays" name="options[autoSaveReplays]" data-role="slider">
								<option value="0" <?php echo !$options->autoSaveReplays ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo $options->autoSaveReplays ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="autosaveValidationReplays">
								<strong><?php echo _('Autosave replays for validation'); ?></strong><br/>
								<i><?php echo _('If checked a replay of validation will be generated on each map.') ?></i>
							</label>
							<select id="autosaveValidationReplays" name="options[autoSaveValidationReplays]" data-role="slider">
								<option value="0" <?php echo !$options->autoSaveValidationReplays ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo $options->autoSaveValidationReplays ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
							</select>
						</li>
					<?php if(!$isRelay): ?>
						<li data-role="fieldcontain">
							<label for="nextLadderMode">
								<strong><?php echo _('Ladder Mode'); ?></strong><br/>
								<i><?php echo _("Choose if you want to activate or not the ladder on your server.") ?></i>
							</label>
							<select id="nextLadderMode" name="options[nextLadderMode]" data-role="slider">
								<option value="0" <?php echo !$options->nextLadderMode ? 'selected="selected"' : '' ?>><?php echo _('Disable') ?></option>
								<option value="1" <?php echo $options->nextLadderMode ? 'selected="selected"' : '' ?>><?php echo _('Enable') ?></option>
							</select>
						</li>
					<?php endif; ?>
					</ul>
				</fieldset>
				<fieldset data-role="collapsible" data-theme="b">
					<legend><?php echo _('Advanced Network Settings'); ?></legend>
					<ul data-role="listview">
						<li data-role="fieldcontain">
							<label for="superadmin">
								<strong><?php echo _('SuperAdmin password'); ?></strong><br/>
								<i><?php echo _('Enter the password of the SuperAdmin user in remote control.') ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('rpcPassword', 'superadmin', $rpcPassword) ?>
						</li>
						<li data-role="fieldcontain">
							<label for="downloadRate">
								<strong><?php echo _('Download rate'); ?></strong><br/>
								<i><?php echo _('In KB/s'); ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('connectionRates[download]', 'downloadRate', $connectionRates['download']); ?>
						</li>
						<li data-role="fieldcontain">
							<label for="uploadRate">
								<strong><?php echo _('Upload rate'); ?></strong><br/>
								<i><?php echo _('In KB/s'); ?></i>
							</label>
							<?php echo DedicatedManager\Helpers\Input::text('connectionRates[upload]', 'uploadRate', $connectionRates['upload']); ?>
						</li>
						<li data-role="fieldcontain">
							<label for="p2pUpload">
								<strong><?php echo _('Enable P2P upload'); ?></strong><br/>
								<i><?php echo _('When enabled, server will send custom data to players'); ?></i>
							</label>
							<select id="p2pUpload" name="options[isP2PUpload]" data-role="slider">
								<option value="0" <?php echo !$options->isP2PUpload ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo $options->isP2PUpload ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
							</select>
						</li>
						<li data-role="fieldcontain">
							<label for="p2pDownload">
								<strong><?php echo _('Enable P2P download'); ?></strong><br/>
								<i><?php echo _('When enabled, server will download custom data from players'); ?></i>
							</label>
							<select id="p2pDownload" name="options[isP2PDownload]" data-role="slider">
								<option value="0" <?php echo !$options->isP2PDownload ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
								<option value="1" <?php echo $options->isP2PDownload ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
							</select>
						</li>
					</ul>
				</fieldset>
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<input type="reset" value="<?php echo _('Restore') ?>" data-icon="refresh"/>
					</div>
					<div class="ui-block-b">
						<input type="submit" value="<?php echo _('Save configuration') ?>" data-theme="b" data-icon="check"/>
					</div>
				</div>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>