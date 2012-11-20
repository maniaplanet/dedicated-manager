<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo sprintf(_('Step %d on %d'), 1, 4) ?></h2><br/>
		<h3><?php echo _('General configuration of your server') ?></h3><br/>
		<?php echo _('During the whole process, feel free to leave default values') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="load_config_form" action="<?php echo $r->createLinkArgList('.') ?>" data-ajax="false" method="get" data-role="collapsible-group">
			<fieldset data-role="collapsible" data-theme="e">
				<legend><?php echo _('Load a configuration file') ?></legend>
				<ul data-role="listview">
					<li>
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<select id="configFile" name="configFile" size="5" data-native-menu="false">
									<option <?php echo in_array($configFile, $configList) ? '' : 'selected="selected"'; ?>><?php echo _('Select file') ?></option>
								<?php foreach($configList as $config): ?>
									<option value="<?php echo $config ?>" <?php echo $config == $configFile ? 'selected="selected"': ''; ?>><?php echo $config ?></option>
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
		
		<form name="config" action="<?php echo $r->createLinkArgList('../set-config') ?>" method="get" data-ajax="false" data-role="collapsible-group">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Basic Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="title">
							<strong><?php echo _('Game title'); ?></strong><br/>
							<i><?php echo _('Select the ManiaPlanet Title you want to use') ?></i>
						</label>
						<select id="title" required="required" name="system[title]" data-native-menu="false">
							<optgroup label="<?php echo _('Official titles') ?>">
								<option value="TMCanyon" <?php echo $system->title == 'TMCanyon' ? 'selected="selected"' : '' ?>>TrackMania Canyon</option>
								<option value="SMStorm" <?php echo $system->title == 'SMStorm' ? 'selected="selected"' : '' ?>>ShootMania Storm</option>
							</optgroup>
							<optgroup label="<?php echo _('Custom titles') ?>">
							<?php foreach($titles as $title): ?>
								<option value="<?php echo $title->idString ?>" <?php echo $system->title == $title->idString ? 'selected="selected"' : '' ?>><?php echo $title->name ?></option>
							<?php endforeach; ?>
							</optgroup>
						</select>
					</li>

					<li data-role="fieldcontain">
						<label for="name">
							<strong><?php echo _('Displayed name'); ?></strong><br/>
							<i><?php echo _('Name that will be displayed in the server list'); ?></i>
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
					<li data-role="fieldcontain">
						<label for="maxPlayers">
							<strong><?php echo _('Max players'); ?></strong><br/>
							<i><?php echo _('Maximum number of players you want to be able to connect on the server'); ?></i>
						</label>
						<input type="range" name="options[nextMaxPlayers]" id="maxPlayers" value="<?php echo $options->nextMaxPlayers; ?>" min="0" max="255" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="isOnline">
							<strong><?php echo _('Is an Internet server') ?></strong><br/>
							<i><?php echo _('If the box "yes" is selected your server will be accessible to every one'); ?></i>
						</label>
						<select id="isOnline" name="isOnline" data-role="slider">
							<option value="0" <?php echo !$account->login ? 'selected="selected"' : '' ?>><?php echo _('LAN') ?></option>
							<option value="1" <?php echo $account->login ? 'selected="selected"' : '' ?>><?php echo _('Online') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-theme="b">
				<legend><?php echo _('Advanced Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="password">
							<strong><?php echo _('Password'); ?></strong><br/>
							<i><?php echo _('Password if you want to limit access to players'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('options[password]', 'password', $options->password) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="maxSpectators">
							<strong><?php echo _('Max spectators'); ?></strong><br/>
							<i><?php echo _('Maximum number of spectators you want to be able to connect on the server'); ?></i>
						</label>
						<input type="range" name="options[nextMaxSpectators]" id="maxSpectators" value="<?php echo $options->nextMaxSpectators; ?>" min="0" max="255" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="passwordForSpectator">
							<strong><?php echo _('Password for spectator'); ?></strong><br/>
							<i><?php echo _('Enter a password if you want to limit access to spectators'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('options[passwordForSpectator]', 'passwordForSpectator', $options->passwordForSpectator) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="spectatorRelay">
							<strong><?php echo _('Allow Spectator Relay'); ?></strong><br/>
							<i><?php echo _('Allow relay server to connect as spectator on your server'); ?></i>
						</label>
						<select id="spectatorRelay" name="system[allowSpectatorRelays]" data-role="slider">
							<option value="0" <?php echo !$system->allowSpectatorRelays ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $system->allowSpectatorRelays ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="hideServer">
							<strong><?php echo _('Hide server'); ?></strong><br/>
							<i><?php echo _('If "yes" is selected the server will not be visible in the server list'); ?></i>
						</label>
						<select id="hideServer" name="options[hideServer]" data-role="slider">
							<option value="0" <?php echo !$options->hideServer ? 'selected="selected"' : '' ?>><?php echo _('Visible') ?></option>
							<option value="1" <?php echo $options->hideServer ? 'selected="selected"' : '' ?>><?php echo _('Hidden') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="allowMapDownload">
							<strong><?php echo _('Allow map download'); ?></strong><br/>
							<i><?php echo _('Allow players to download maps from the server'); ?></i>
						</label>
						<select id="allowMapDownload" name="options[allowMapDownload]" data-role="slider">
							<option value="0" <?php echo !$options->allowMapDownload ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $options->allowMapDownload ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="callVoteRatio">
							<strong><?php echo _('Call vote ratio (in %)'); ?></strong><br/>
							<i><?php echo _('Default ratio in % that define if a vote passed or -1 to disable votes'); ?></i>
						</label>
						<input type="range" name="options[callVoteRatio]" id="callVoteRatio" value="<?php echo $options->callVoteRatio == -1 ? -1 : $options->callVoteRatio * 100 ?>" min="-1" max="100" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="callVoteTimeOut">
							<strong><?php echo _('Call vote timeout (in seconds)'); ?></strong><br/>
							<i><?php echo _('Time of duration of a vote'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('options[nextCallVoteTimeOut]', 'callVoteTimeOut', $options->nextCallVoteTimeOut / 1000) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="refereePassword">
							<strong><?php echo _('Referee password'); ?></strong><br/>
							<i><?php echo _('Enter a password if you want to limit access to referees'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('options[refereePassword]', 'refereePassword', $options->refereePassword) ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend>
								<strong><?php echo _('Referee Mode'); ?></strong><br/>
								<i><?php echo _('Select if the referees will validate only top3 on each race or everyone') ?></i>
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
						<label for="autoSaveReplays">
							<strong><?php echo _('Autosave replays'); ?></strong><br/>
							<i><?php echo _('If "yes" every a replay will be saved on each map'); ?></i>
						</label>
						<select id="autoSaveReplays" name="options[autoSaveReplays]" data-role="slider">
							<option value="0" <?php echo !$options->autoSaveReplays ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $options->autoSaveReplays ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="autoSaveValidationReplays">
							<strong><?php echo _('Autosave replays for validation'); ?></strong><br/>
							<i><?php echo _('If "yes" a replay of validation will be generated on each map'); ?></i>
						</label>
						<select id="autoSaveValidationReplays" name="options[autoSaveValidationReplays]" data-role="slider">
							<option value="0" <?php echo !$options->autoSaveValidationReplays ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $options->autoSaveValidationReplays ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="field-internet" data-role="collapsible" <?php echo $account->login ? 'data-collapsed="false"' : '' ?> data-theme="b">
				<legend><?php echo _('Internet Server Configuration') ?></legend>
				<ul data-role="listview">
					<li>
						<a href="http://player.maniaplanet.com/index.php/advanced/dedicated-servers/" target="blank">
							<?php echo _('Create a dedicated server account'); ?>
						</a>
					</li>
					<li data-role="fieldcontain">
						<label for="accountLogin">
							<strong><?php echo _('Server login'); ?></strong>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('account[login]', 'accountLogin', $account->login) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="accountPassword">
							<strong><?php echo _('Server password'); ?></strong>
						</label>
						<input type="password" name="account[password]" id="accountPassword" value="<?php echo htmlentities($account->password, ENT_QUOTES, 'utf-8') ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="accountValidationKey">
							<strong><?php echo _('Server validation key (optional)'); ?></strong>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('account[validationKey]', 'accountValidationKey', $account->validationKey) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="nextLadderMode">
							<strong><?php echo _('Enable Ladder'); ?></strong><br/>
							<i><?php echo _('Choose if you want to activate or not the ladder on your server'); ?></i>
						</label>
						<select id="nextLadderMode" name="options[nextLadderMode]" data-role="slider">
							<option value="0" <?php echo !$options->nextLadderMode ? 'selected="selected"' : '' ?>><?php echo _('Disable') ?></option>
							<option value="1" <?php echo $options->nextLadderMode ? 'selected="selected"' : '' ?>><?php echo _('Enable') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="ladderServerLimitMin">
							<strong><?php echo _('Ladder limit min'); ?></strong><br/>
							<i><?php echo _('Enter the minimal number of ladder points required to connect to the server'); ?></i>
						</label>
						<input type="number" name="options[ladderServerLimitMin]" id="ladderServerLimitMin" value="<?php echo $options->ladderServerLimitMin ?>" min="0" max="80000" step="10000"/>
					</li>
					<li data-role="fieldcontain">
						<label for="ladderServerLimitMax">
							<strong><?php echo _('Ladder limit max'); ?></strong><br/>
							<i><?php echo _('Enter the maximum number of ladder points to win on the server'); ?></i>
						</label>
						<input type="number" name="options[ladderServerLimitMax]" id="ladderServerLimitMax" value="<?php echo $options->ladderServerLimitMax ?>" min="0" max="100000" step="10000"/>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-theme="b">
				<legend><?php echo _('Advanced Network Settings') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="forceip">
							<strong><?php echo _('Force IP address'); ?></strong><br/>
							<i><?php echo _('Enter the IP address you want to be used to join the server') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[forceIpAddress]', 'forceip', $system->forceIpAddress) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="serverport">
							<strong><?php echo _('Main port'); ?></strong><br/>
							<i><?php echo _('If it\'s already used, first free one starting from this value will be taken instead'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[serverPort]', 'serverport', $system->serverPort) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="serverp2pport">
							<strong><?php echo _('P2P port'); ?></strong><br/>
							<i><?php echo _('If it\'s already used, first free one starting from this value will be taken instead'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[serverP2pPort]', 'serverp2pport', $system->serverP2pPort) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="allowremote">
							<strong><?php echo _('Allow remote control'); ?></strong><br/>
							<i><?php echo _('Enter the IP address from which you want to be able to control your server'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[xmlrpcAllowremote]', 'allowremote', $system->xmlrpcAllowremote) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="xmlrpcport">
							<strong><?php echo _('XML-RPC port'); ?></strong><br/>
							<i><?php echo _('If it\'s already used, first free one starting from this value will be taken instead'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[xmlrpcPort]', 'xmlrpcport', $system->xmlrpcPort) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="superadmin">
							<strong><?php echo _('SuperAdmin password'); ?></strong><br/>
							<i><?php echo _('Enter the password of the SuperAdmin user in remote control'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('authLevel[superAdmin]', 'superadmin', $authLevel->superAdmin) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="useProxy">
							<strong><?php echo _('Use Proxy'); ?></strong><br/>
							<i><?php echo _('Select yes if you are connected to a proxy'); ?></i>
						</label>
						<select id="useProxy" name="system[useProxy]" data-role="slider">
							<option value="0" <?php echo !$system->useProxy ? 'selected="selected"' : '' ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $system->useProxy ? 'selected="selected"' : '' ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="proxyLogin">
							<strong><?php echo _('Proxy login'); ?></strong><br/>
							<i><?php echo _('Enter your proxy login.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[proxyLogin]', 'proxyLogin', $system->proxyLogin) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="proxyPassword">
							<strong><?php echo _('Proxy password'); ?></strong><br/>
							<i><?php echo _('Enter your proxy password'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('system[proxyPassword]', 'proxyPassword', $system->proxyPassword) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="downloadRate">
							<strong><?php echo _('Download rate'); ?></strong><br/>
							<i><?php echo _('In KB/s'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('sytem[connectionDownloadrate]', 'downloadRate', $system->connectionDownloadrate); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="uploadRate">
							<strong><?php echo _('Upload rate'); ?></strong><br/>
							<i><?php echo _('In KB/s'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('sytem[connectionUploadrate]', 'uploadRate', $system->connectionUploadrate); ?>
					</li>
				</ul>
			</fieldset>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?php echo _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" id="submit" value="<?php echo _('Next step') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
    </div>
</div>
<?php require __DIR__.'/../Footer.php' ?>
