<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?= DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?= sprintf(_('Step %d on %d'), 1, 4) ?></h2><br/>
		<h3><?= _('General configuration of your server') ?></h3><br/>
		<?= _('During the whole process, feel free to leave default values.') ?>
    </div>
	<?= DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<p><?= _('If you want to load an <strong>existing server configuration</strong> click on the link: ') ?>
			<a href="#dialog" data-rel="dialog"><?= _('load server config'); ?></a></p>
		<form name="config" action="<?= $r->createLinkArgList('../save-server-config') ?>" method="get" data-ajax="false">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?= _('Basic Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="title">
							<strong><?= _('Game title'); ?></strong><br/>
							<i><?= _('Select the ManiaPlanet Title you want to use.') ?></i>
						</label>
						<select id="title" required="required" name="system[title]" data-native-menu="false">
							<optgroup label="<?= _('Official titles') ?>">
								<option value="TMCanyon" <?= $system->title == 'TMCanyon' ? 'selected="selected"' : '' ?>>TrackMania Canyon</option>
								<option value="SMStorm" <?= $system->title == 'SMStorm' ? 'selected="selected"' : '' ?>>ShootMania Storm</option>
							</optgroup>
							<optgroup label="<?= _('Custom titles') ?>">
								<option value="SMStormElite@nadeolabs" <?= $system->title == 'SMStormElite@nadeolabs' ? 'selected="selected"' : '' ?>>ShootMania Storm Elite</option>
								<option value="SMStormJoust@nadeolabs" <?= $system->title == 'SMStormJoust@nadeolabs' ? 'selected="selected"' : '' ?>>ShootMania Storm Joust</option>
							</optgroup>
						</select>
					</li>

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
						<textarea name="options[comment]" id="comment"><?= htmlentities($options->comment, ENT_QUOTES, 'utf-8') ?></textarea>
					</li>
					<li data-role="fieldcontain">
						<label for="maxPlayers">
							<strong><?= _('Max players'); ?></strong><br/>
							<i><?= _('Maximum number of players you want to be able to connect on the server.') ?></i>
						</label>
						<input type="range" name="options[nextMaxPlayers]" id="maxPlayers" value="<?= $options->nextMaxPlayers; ?>" min="0" max="255" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="isOnline">
							<strong><?= _('Is an Internet server') ?></strong><br/>
							<i><?= _('If the box "yes" is selected your server will be accessible to every one.') ?></i>
						</label>
						<select id="isOnline" name="isOnline" data-role="slider">
							<option value="0" <?= !$account->login ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $account->login ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
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
						<?= DedicatedManager\Helpers\Input::text('options[password]', 'password', $options->password) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="maxSpectators">
							<strong><?= _('Max spectators'); ?></strong><br/>
							<i><?= _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
						</label>
						<input type="range" name="options[nextMaxSpectators]" id="maxSpectators" value="<?= $options->nextMaxSpectators; ?>" min="0" max="255" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="passwordForSpectator">
							<strong><?= _('Password for spectator'); ?></strong><br/>
							<i><?= _('Enter a password if you want to limit access to spectators.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('options[passwordForSpectator]', 'passwordForSpectator', $options->passwordForSpectator) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="spectatorRelay">
							<strong><?= _('Allow Spectator Relay'); ?></strong><br/>
							<i><?= _('Allow relay server to connect as spectator on your server') ?></i>
						</label>
						<select id="spectatorRelay" name="system[allowSpectatorRelays]" data-role="slider">
							<option value="0" <?= !$system->allowSpectatorRelays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $system->allowSpectatorRelays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="hideServer">
							<strong><?= _('Hide server'); ?></strong><br/>
							<i><?= _('If "yes" is selected the server will not be visible in the server list.') ?></i>
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
					<li data-role="fieldcontain">
						<label for="callVoteRation">
							<strong><?= _('Call vote ratio (in %)'); ?></strong><br/>
							<i><?= _('Ratio in % that define if a vote passed or -1 to disable votes') ?></i>
						</label>
						<input type="range" name="options[callVoteRatio]" id="callVoteRation" value="<?= $options->callVoteRatio == -1 ? -1 : $options->callVoteRatio * 100 ?>" min="-1" max="100" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="callVoteTimeOut">
							<strong><?= _('Call vote timeout (in seconds)'); ?></strong><br/>
							<i><?= _('Time of duration of a vote') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('options[nextCallVoteTimeOut]', 'callVoteTimeOut', $options->nextCallVoteTimeOut / 1000) ?>
					</li>
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
								<i><?= _('Select if the referees will validate only top3 on each race or everyone.') ?></i>
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
							<i><?= _('If "yes" every a replay will be saved on each map.') ?></i>
						</label>
						<select id="autosaveReplays" name="options[autosaveReplays]" data-role="slider">
							<option value="0" <?= !$options->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $options->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="autosaveValidationReplays">
							<strong><?= _('Autosave replays for validation'); ?></strong><br/>
							<i><?= _('If "yes" a replay of validation will be generated on each map.') ?></i>
						</label>
						<select id="autosaveValidationReplays" name="options[autosaveValidationReplays]" data-role="slider">
							<option value="0" <?= !$options->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $options->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="field-internet" data-role="collapsible" <?= $account->login ? 'data-collapsed="false"' : '' ?> data-theme="b">
				<legend><?= _('Internet Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<?= _('Create a dedicated server account:') ?>
						<a href="http://player.maniaplanet.com/index.php/advanced/dedicated-servers/" target="blank">
							<?= _('Go to the Player page') ?>
						</a>
					</li>
					<li data-role="fieldcontain">
						<label for="masterLogin">
							<strong><?= _('Dedicated server account login'); ?></strong><br/>
							<i><?= _("Enter the dedicated server's login.") ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('account[login]', 'masterLogin', $account->login) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="masterPassword">
							<strong><?= _('Dedicated server account password'); ?></strong><br/>
							<i><?= _("Enter the dedicated server's password.") ?></i>
						</label>
						<input type="password" name="account[password]" id="masterPassword" value="<?= htmlentities($account->password, ENT_QUOTES, 'utf-8') ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="masterValidationKey">
							<strong><?= _('Validation key (optionnal)'); ?></strong><br/>
							<i><?= _("Enter the dedicated server's validation key.") ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('account[validationKey]', 'masterValidationKey', $account->validationKey) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="nextLadderMode">
							<strong><?= _('Enable Ladder'); ?></strong><br/>
							<i><?= _("Choose if you want to activate or not the ladder on your server.") ?></i>
						</label>
						<select id="nextLadderMode" name="options[nextLadderMode]" data-role="slider">
							<option value="0" <?= !$options->nextLadderMode ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $options->nextLadderMode ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="ladderServerLimitMin">
							<strong><?= _('Ladder limit min'); ?></strong><br/>
							<i><?= _("Enter the minimal number of ladder points required to connect to the server.") ?></i>
						</label>
						<input type="number" name="options[ladderServerLimitMin]" id="ladderServerLimitMin" value="<?= $options->ladderServerLimitMin ?>" min="0" max="80000" step="10000"/>
					</li>
					<li data-role="fieldcontain">
						<label for="ladderServerLimitMax">
							<strong><?= _('Ladder limit max'); ?></strong><br/>
							<i><?= _("Enter the maximum number of ladder points to win on the server.") ?></i>
						</label>
						<input type="number" name="options[ladderServerLimitMax]" id="ladderServerLimitMax" value="<?= $options->ladderServerLimitMax ?>" min="0" max="100000" step="10000"/>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fiels-internet" data-role="collapsible" data-theme="b">
				<legend><?= _('Advanced Network Settings') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="forceip">
							<strong><?= _('Force IP address'); ?></strong><br/>
							<i><?= _('Enter the IP address you want to be used to join the server.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('system[forceIpAddress]', 'forceip', $system->forceIpAddress) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="allowremote">
							<strong><?= _('Allow remote control'); ?></strong><br/>
							<i><?= _('Enter the IP address you want to be able to control your server.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('system[xmlrpcAllowremote]', 'allowremote', $system->xmlrpcAllowremote) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="useProxy">
							<strong><?= _('Use Proxy'); ?></strong><br/>
							<i><?= _("Select yes if you are connected to a proxy.") ?></i>
						</label>
						<select id="useProxy" name="system[useProxy]" data-role="slider">
							<option value="0" <?= !$system->useProxy ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $system->useProxy ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="proxyLogin">
							<strong><?= _('Proxy login'); ?></strong><br/>
							<i><?= _('Enter your proxy login.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('system[proxyLogin]', 'proxyLogin', $system->proxyLogin) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="proxyPassword">
							<strong><?= _('Proxy password'); ?></strong><br/>
							<i><?= _('Enter your proxy password.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('system[proxyPassword]', 'proxyPassword', $system->proxyPassword) ?>
					</li>
				</ul>
			</fieldset>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?= _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" id="submit" value="<?= _('Next step') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
    </div>
</div>
<div data-role="dialog" id="dialog">
    <div data-role="header" data-theme="b">
		<h1><?= _('Server configuration') ?></h1>
    </div>
    <div data-role="content">
		<form name="load_config_form" action="<?= $r->createLinkArgList('.') ?>" data-ajax="false" method="get" title="<?= _('Load existing configuration') ?>">
			<label for="configFile"><?= _('Select the configuration file you want to load') ?></label>
			<select id="configFile" name="configFile" size="5" data-native-menu="false">
			<?php foreach($configList as $config): ?>
				<option value="<?= $config ?>"><?= $config ?></option>
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
