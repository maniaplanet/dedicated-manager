<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?= DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?= _('Step 1 on 4'); ?></h2><br/>
		<h3><?= _('General configuration of your server') ?></h3><br/>
		<?= _('During the whole process, feel free to leave default values.') ?>
    </div>
	<?= DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<p><?= _('If you want to load an <strong>existing server configuration</strong> click on the link: ') ?>
			<a href="#dialog" data-rel="dialog" data-transition="slidedown"><?= _('load server config'); ?></a></p>
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
								<option value="TMCanyon" <?= $serverOptions->title == 'TMCanyon' ? 'selected="selected"' : '' ?>>TrackMania Canyon</option>
								<option value="SMStorm" <?= $serverOptions->title == 'SMStorm' ? 'selected="selected"' : '' ?>>ShootMania Storm</option>
							</optgroup>
							<optgroup label="<?= _('Custom titles') ?>">
								<option value="SMStormElite@nadeolabs" <?= $serverOptions->title == 'SMStormElite@nadeolabs' ? 'selected="selected"' : '' ?>>ShootMania Storm Elite</option>
								<option value="SMStormJoust@nadeolabs" <?= $serverOptions->title == 'SMStormJoust@nadeolabs' ? 'selected="selected"' : '' ?>>ShootMania Storm Joust</option>
							</optgroup>
						</select>
					</li>

					<li data-role="fieldcontain">
						<label for="name">
							<strong><?= _('Displayed name'); ?></strong><br/>
							<i><?= _('Name that will be displayed in the server list.') ?></i>
						</label>
						<input type="text" name="config[name]" id="name" value="<?= $serverOptions->name; ?>" required="required" class="formattingPreview"/>
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
							<i><?= _('Maximum number of players you want to be able to connect on the server.') ?></i>
						</label>
						<input type="text" name="config[nextMaxPlayers]" id="maxPlayers" value="<?= $serverOptions->nextMaxPlayers; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="isOnline">
							<strong><?= _('Is an Internet server') ?></strong><br/>
							<i><?= _('If the box "yes" is selected your server will be accessible to every one.') ?></i>
						</label>
						<select id="isOnline" name="isOnline" data-role="slider">
							<option value="0" <? !$account ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" ><?= _('Yes') ?></option>
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
						<input type="text" name="config[password]" id="password" value="<?= $serverOptions->password; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="maxSpectators">
							<strong><?= _('Max spectators'); ?></strong><br/>
							<i><?= _('Maximum number of spectators you want to be able to connect on the server.') ?></i>
						</label>
						<input type="text" name="config[nextMaxSpectators]" id="maxSpectators" value="<?= $serverOptions->nextMaxSpectators; ?>"/>
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
							<i><?= _('If "yes" is selected the server will not be visible in the server list.') ?></i>
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
						<input type="range" name="config[callVoteRatio]" id="callVoteRation" value="<?= $serverOptions->callVoteRatio * 100; ?>" min="-1" max="100" step="1"/>
					</li>
					<li data-role="fieldcontain">
						<label for="callVoteTimeOut">
							<strong><?= _('Call vote timeout (in seconds)'); ?></strong><br/>
							<i><?= _('Time of duration of a vote') ?></i>
						</label>
						<input type="text" name="config[nextCallVoteTimeOut]" id="callVoteTimeOut" value="<?= $serverOptions->nextCallVoteTimeOut / 1000; ?>"/>
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
								<i><?= _('Select if the referees will validate only top3 on each race or everyone.') ?></i>
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
							<i><?= _('If "yes" every a replay will be saved on each map.') ?></i>
						</label>
						<select id="autosaveReplays" name="config[autosaveReplays]" data-role="slider">
							<option value="0" <?= !$serverOptions->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->autosaveReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="autosaveValidationReplays">
							<strong><?= _('Autosave replays for validation'); ?></strong><br/>
							<i><?= _('If "yes" a replay of validation will be generated on each map.') ?></i>
						</label>
						<select id="autosaveValidationReplays" name="config[autosaveValidationReplays]" data-role="slider">
							<option value="0" <?= !$serverOptions->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('No') ?></option>
							<option value="1" <?= $serverOptions->autosaveValidationReplays ? 'selected="selected"' : '' ?>><?= _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="field-internet" data-role="collapsible" <?=($account->login ? 'data-collapsed="false"' : '')?> data-theme="b">
				<legend><?= _('Internet Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
<?= _('Create a dedicated server account:') ?><a href="http://player.maniaplanet.com/index.php/advanced/dedicated-servers/" target="blank"><?= _('Go to the Player page') ?></a>
					</li>
					<li data-role="fieldcontain">
						<label for="masterLogin">
							<strong><?= _('Dedicated server account login'); ?></strong><br/>
							<i><?= _("Enter the dedicated server's login.") ?></i>
						</label>
						<input type="text" name="account[login]" id="masterLogin" value="<?= $account->login; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="masterPassword">
							<strong><?= _('Dedicated server account password'); ?></strong><br/>
							<i><?= _("Enter the dedicated server's password.") ?></i>
						</label>
						<input type="text" name="account[password]" id="masterPassword" value="<?= $account->password; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="masterValidationKey">
							<strong><?= _('Validation key (optionnal)'); ?></strong><br/>
							<i><?= _("Enter the dedicated server's validation key.") ?></i>
						</label>
						<input type="text" name="account[validationKey]" id="masterValidationKey" value="<?= $account->validationKey; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="nextLadderMode">
							<strong><?= _('Enable Ladder'); ?></strong><br/>
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
						<input type="number" name="config[ladderServerLimitMax]" id="ladderServerLimitMax" value="<?= $serverOptions->ladderServerLimitMax; ?>" min="0" max="100000" step="10000"/>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fiels-internet" data-role="collapsible" data-theme="b">
				<legend><?= _('Advanced network settings') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="forceip">
							<strong><?= _('Force IP address'); ?></strong><br/>
							<i><?= _('Enter the IP address you want to be used to join the server.') ?></i>
						</label>
						<input type="text" name="system[forceIpAddress]" id="forceip" value="<?= $system->forceIpAddress; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="allowremote">
							<strong><?= _('Allow remote control'); ?></strong><br/>
							<i><?= _('Enter the IP address you want to be able to control your server.') ?></i>
						</label>
						<input type="text" name="system[xmlrpcAllowremote]" id="allowremote" value="<?= $system->xmlrpcAllowremote; ?>"/>
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
						<input type="text" name="system[proxyLogin]" id="proxyLogin" value="<?= $system->proxyLogin; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="proxyPassword">
							<strong><?= _('Proxy password'); ?></strong><br/>
							<i><?= _('Enter your proxy password.') ?></i>
						</label>
						<input type="text" name="system[proxyPassword]" id="proxyPassword" value="<?= $system->proxyPassword; ?>"/>
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
		<form name="load_config_form" action="<?= $r->createLinkArgList('../configure') ?>#content" method="get" title="<?= _('Load existing configuration') ?>">
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