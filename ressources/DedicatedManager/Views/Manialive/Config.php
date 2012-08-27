<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php echo DedicatedManager\Helpers\Header::save(); ?>
    <div class="ui-bar ui-bar-b">
		<h3><?php echo _('Configure ManiaLive'); ?></h3><br/>
		<?php echo _('Note that you do not need to set server info'); ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<p><?php echo _('If you want to load an <strong>existing ManiaLive configuration</strong> click on the link: ') ?>
			<a href="#dialog" data-rel="dialog"><?php echo _('load ManiaLive config'); ?></a></p>
		<form name="config" action="<?php echo $r->createLinkArgList('../set-config') ?>" method="get" data-ajax="false">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Basic Configuration'); ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="admins">
							<strong><?php echo _('Admins'); ?></strong><br/>
							<i><?php echo _('ManiaPlanet logins'); ?></i>
						</label>
						<div class="input-list">
						<?php if($config->admins): ?>
							<?php foreach($config->admins as $i => $admin): ?>
							<input type="text" name="admins[]" <?php echo !$i ? 'id="admins"' : ''; ?> value="<?php echo $admin; ?>"/>
							<?php endforeach; ?>
						<?php else: ?>
							<input type="text" name="admins[]" id="admins" value="<?php echo ManiaLib\Application\Session::getInstance()->login; ?>"/>
						<?php endif; ?>
							<a href="#" id="add-admin-button" data-role="button" data-icon="plus" data-iconpos="left" data-mini="true" data-inline="true"><?php echo _('Add line'); ?></a>
						</div>
					</li>
					<li data-role="fieldcontain">
						<label for="databaseEnable">
							<strong><?php echo _('MySQL'); ?></strong><br/>
							<i><?php echo _('MySQL is needed for threading and some plugins'); ?></i>
						</label>
						<select id="databaseEnable" name="database[enable]" data-role="slider">
							<option value="0" <?php echo !$config->database->enable ? 'selected="selected"' : ''; ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $config->database->enable ? 'selected="selected"' : ''; ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="threadingEnabled">
							<strong><?php echo _('Threading'); ?></strong><br/>
							<i><?php echo _('Threading improve performances with some plugins') ?></i>
						</label>
						<select id="threadingEnabled" name="threading[enabled]" data-role="slider">
							<option value="0" <?php echo !$config->threading->enabled ? 'selected="selected"' : ''; ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $config->threading->enabled ? 'selected="selected"' : ''; ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
				</ul>
			</fieldset>
			<fieldset id="field-database" data-role="collapsible" data-theme="b">
				<legend><?php echo _('MySQL Configuration'); ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="databaseHost">
							<strong><?php echo _('Host'); ?></strong><br/>
							<i><?php echo _('The address of your server'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('database[host]', 'databaseHost', $config->database->host); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="databasePort">
							<strong><?php echo _('Port'); ?></strong><br/>
							<i><?php echo _('The port to connect to your server'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('database[port]', 'databasePort', $config->database->port); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="databaseUsername">
							<strong><?php echo _('User'); ?></strong><br/>
							<i><?php echo _('Your username'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('database[username]', 'databaseUsername', $config->database->username); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="databasePassword">
							<strong><?php echo _('Password'); ?></strong><br/>
							<i><?php echo _('The password of your user'); ?></i>
						</label>
						<input type="password" name="database[password]" id="databasePassword" value="<?php echo $config->database->password; ?>"/>
					</li>
					<li data-role="fieldcontain">
						<label for="databaseDatabase">
							<strong><?php echo _('Database'); ?></strong><br/>
							<i><?php echo _('The database to use on your server'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('database[database]', 'databaseDatabase', $config->database->database); ?>
					</li>
				</ul>
			</fieldset>
			<fieldset id="field-threading" data-role="collapsible" data-theme="b">
				<legend><?php echo _('Threading Configuration'); ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="threadingBusyTimeout">
							<strong><?php echo _('Busy timeout (in seconds)'); ?></strong><br/>
							<i><?php echo _('How long a threaded task can take before considering it has timed out'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('threading[busyTimeout]', 'threadingBusyTimeout', $config->threading->busyTimeout); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="threadingMaxTries">
							<strong><?php echo _('Max tries'); ?></strong><br/>
							<i><?php echo _('How many times a task can be tried before discarding it'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('threading[maxTries]', 'threadingMaxTries', $config->threading->maxTries); ?>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-theme="b">
				<legend><?php echo _('WebServices Configuration'); ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="wsapiUsername">
							<strong><?php echo _('User'); ?></strong><br/>
							<i><?php echo _('Your username'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('wsapi[username]', 'wsapiUsername', $config->wsapi->username); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="wsapiPassword">
							<strong><?php echo _('Password'); ?></strong><br/>
							<i><?php echo _('The password of your user'); ?></i>
						</label>
						<input type="password" name="wsapi[password]" id="wsapiPassword" value="<?php echo $config->wsapi->password; ?>"/>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-theme="b">
				<legend><?php echo _('Log Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="configLogsPath">
							<strong><?php echo _('Path'); ?></strong><br/>
							<i><?php echo _('Path to the logs folder (if empty, default is %manialivePath%/logs)') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('logs[logsPath]', 'configLogsPath', $config->logs->logsPath); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="configLogsPrefix">
							<strong><?php echo _('File prefix'); ?></strong><br/>
							<i><?php echo _('Prefix to use for log filenames'); ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('logs[logsPrefixh]', 'configLogsPrefix', $config->logs->logsPrefix); ?>
					</li>
					<li data-role="fieldcontain">
						<label for="configRuntimeLog">
							<strong><?php echo _('Runtime log') ?></strong><br/>
							<i><?php echo _('When enabled, output will be written to a log file'); ?></i>
						</label>
						<select id="configRuntimeLog" name="logs[runtimeLog]" data-role="slider">
							<option value="0" <?php echo !$config->logs->runtimeLog ? 'selected="selected"' : ''; ?>><?php echo _('No') ?></option>
							<option value="1" <?php echo $config->logs->runtimeLog ? 'selected="selected"' : ''; ?>><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="configGlobalErrorLog">
							<strong><?php echo _('Global error log'); ?></strong><br/>
							<i><?php echo _('Gather errors from all instances to a single file'); ?></i>
						</label>
						<select id="configGlobalErrorLog" name="logs[globalErrorLog]" data-role="slider">
							<option value="0" <?php echo !$config->logs->globalErrorLog ? 'selected="selected"' : ''; ?>><?php echo _('No'); ?></option>
							<option value="1" <?php echo $config->logs->globalErrorLog ? 'selected="selected"' : ''; ?>><?php echo _('Yes'); ?></option>
						</select>
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
<div data-role="dialog" id="dialog">
    <div data-role="header" data-theme="b">
		<h1><?php echo _('ManiaLive configuration') ?></h1>
    </div>
    <div data-role="content">
		<form name="load_config_form" action="<?php echo $r->createLinkArgList('.') ?>" data-ajax="false" method="get" title="<?php echo _('Load existing configuration') ?>">
			<label for="configFile"><?php echo _('Select the configuration file you want to load') ?></label>
			<select id="configFile" name="configFile" size="5" data-native-menu="false">
			<?php foreach($configList as $config): ?>
				<option value="<?php echo $config ?>"><?php echo $config ?></option>
			<?php endforeach; ?>
			</select>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<a href="#" data-rel="back" data-role="button"><?php echo _('Back') ?></a>
				</div>
				<div class="ui-block-b">
					<input type="submit" value="Load" data-theme="a"/>
				</div>
			</div>
		</form>
    </div>
</div>
<?php require __DIR__.'/../Footer.php' ?>
