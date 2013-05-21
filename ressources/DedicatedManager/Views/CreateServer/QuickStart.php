<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo _('Configuration of your server') ?></h2><br/>
		<?php echo _('Feel free to leave default values') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="config" action="<?php echo $r->createLinkArgList('../doQuickStart') ?>" method="get" data-ajax="false" data-role="collapsible-group">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Basic Server Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="quickConfigFile">
							<strong><?php echo _('Config file'); ?></strong><br/>
							<i><?php echo _('Select the config file you want to use') ?></i>
						</label>
						<select id="quickConfigFile" name="configFile" size="5" data-native-menu="false">
							<option <?php echo in_array($configFile, $configFileList) ? '' : 'selected="selected"'; ?>><?php echo _('Select file') ?></option>
						<?php foreach($configFileList as $config): ?>
							<option value="<?php echo $config ?>" <?php echo $config == $configFile ? 'selected="selected"': ''; ?>><?php echo $config ?></option>
						<?php endforeach; ?>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="quickMatchFile">
							<strong><?php echo _('Config file'); ?></strong><br/>
							<i><?php echo _('Select the config file you want to use') ?></i>
						</label>
						<select id="quickMatchFile" name="matchFile" size="5" data-native-menu="false">
						<option <?php echo in_array($matchFile, $matchSettingsFileList) ? '' : 'selected="selected"'; ?>><?php echo _('Select file') ?></option>
						<?php foreach($matchSettingsFileList as $setting): ?>
							<option value="<?php echo $setting ?>" <?php echo $setting == $matchFile ? 'selected="selected"': ''; ?>><?php echo $setting ?></option>
						<?php endforeach; ?>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="title">
							<strong><?php echo _('Game title'); ?></strong><br/>
							<i><?php echo _('Select the ManiaPlanet Title you want to use') ?></i>
						</label>
						<select id="title" required="required" name="title" data-native-menu="false">
							<optgroup label="<?php echo _('Official titles') ?>">
								<option value="TMCanyon" <?php echo $title == 'TMCanyon' ? 'selected="selected"' : '' ?>>TrackMania Canyon</option>
								<option value="TMStadium" <?php echo $title == 'TMStadium' ? 'selected="selected"' : '' ?>>TrackMania Stadium</option>
								<option value="SMStorm" <?php echo $title == 'SMStorm' ? 'selected="selected"' : '' ?>>ShootMania Storm</option>
							</optgroup>
							<optgroup label="<?php echo _('Custom titles') ?>">
							<?php foreach($titles as $titleObject): ?>
								<option value="<?php echo $titleObject->idString ?>" <?php echo $title == $titleObject->idString ? 'selected="selected"' : '' ?>><?php echo $titleObject->name ?></option>
							<?php endforeach; ?>
							</optgroup>
						</select>
					</li>

					<li data-role="fieldcontain">
						<label for="serverName">
							<strong><?php echo _('Displayed name'); ?></strong><br/>
							<i><?php echo _('Name that will be displayed in the server list'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('serverName', 'serverName', $serverName) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="isLan">
							<strong><?php echo _('Is an Internet server') ?></strong><br/>
							<i><?php echo _('If the box "yes" is selected your server will be accessible to every one'); ?></i>
						</label>
						<select id="isLan" name="isLan" data-role="slider">
							<option value="1" <?php echo !$isLan ? 'selected="selected"' : '' ?>><?php echo _('LAN') ?></option>
							<option value="0" <?php echo $isLan ? 'selected="selected"' : '' ?>><?php echo _('Online') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="login">
							<strong><?php echo _('Server login'); ?></strong>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('login', 'login', $serverLogin) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="password">
							<strong><?php echo _('Server password'); ?></strong>
						</label>
						<input type="password" name="password" id="password" value="<?php echo htmlentities($serverPassword, ENT_QUOTES, 'utf-8') ?>"/>
					</li>
				</ul>
			</fieldset>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?php echo _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" id="submit" value="<?php echo _('Start server') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
    </div>
</div>
<?php require __DIR__.'/../Footer.php' ?>
