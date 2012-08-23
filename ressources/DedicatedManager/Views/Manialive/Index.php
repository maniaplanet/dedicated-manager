<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php
	$header = \DedicatedManager\Helpers\Header::getInstance();
	$header->rightText = _('Back to server configuration');
	$header->rightIcon = 'back';
	$header->rightLink = $backLink;
	echo DedicatedManager\Helpers\Header::save()
	?>
    <div class="ui-bar ui-bar-b">
		<h2><?php
	echo sprintf(_('Step %d on %d'), 1, 4)
	?></h2><br/>
		<h3><?php echo _('General configuration of manialive') ?></h3><br/>
		<?php echo _('During the whole process, feel free to leave default values.') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="config" action="<?php echo $r->createLinkArgList('../set-config') ?>" method="get" data-ajax="false">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Basic Database Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="host">
							<strong><?php echo _('MySQL host'); ?></strong><br/>
							<i><?php echo _('The address of your MySQL server.') ?></i>
						</label>
						<input type="text" id="host" name="mysql[host]" value="127.0.0.1"/>
					</li>

					<li data-role="fieldcontain">
						<label for="port">
							<strong><?php echo _('MySQL port'); ?></strong><br/>
							<i><?php echo _('The port to connect to your MySQL server.') ?></i>
						</label>
						<?php
						echo DedicatedManager\Helpers\Input::text('mysql[port]', 'port', 3306)
						?>
					</li>
					<li data-role="fieldcontain">
						<label for="user">
							<strong><?php echo _('MySQL user'); ?></strong><br/>
							<i><?php echo _('Your MySQL username.') ?></i>
						</label>
						<?php
						echo DedicatedManager\Helpers\Input::text('mysql[user]', 'user', 'root')
						?>
					</li>
					<li data-role="fieldcontain">
						<label for="password">
							<strong><?php echo _('MySQL password'); ?></strong><br/>
							<i><?php echo _('The password of your MySQL user.') ?></i>
						</label>
						<input type="password" name="mysql[password]" id="password" value=""/>
					</li>
					<li data-role="fieldcontain">
						<label for="enableThread">
							<strong><?php echo _('Enable thread') ?></strong><br/>
							<i><?php echo _('Threading improve performances with some plugins.') ?></i>
						</label>
						<select id="enableThread" name="enableThread" data-role="slider">
							<option value="0"><?php echo _('No') ?></option>
							<option value="1" selected="selected"><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="admins">
							<strong><?php echo _('Admins'); ?></strong><br/>
							<i><?php echo _('ManiaPlanet logins of the admins.') ?></i>
						</label>
						<input type="text" name="admins[]" id="admins" value="<?php echo \ManiaLib\Application\Session::getInstance()->login; ?>"/>
					</li>
				</ul>
			</fieldset>
			<fieldset id="field-internet" data-role="collapsible" data-theme="b">
				<legend><?php echo _('Log Configuration') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="logsFolder">
							<strong><?php echo _('Logs storage path'); ?></strong><br/>
							<i><?php echo _('Enter the path to the logs folder (if empty folder is %application%/logs).') ?></i>
						</label>
						<input type="text" name="logs[folder]" id="logsFolder" value=""/>
					</li>
					<li data-role="fieldcontain">
						<label for="logsPrefix">
							<strong><?php echo _('Prefix log'); ?></strong><br/>
							<i><?php echo _("Prefix to use for log files.") ?></i>
						</label>
						<input type="text" name="logs[logsprefix]" id="logsPrefix" value=""/>
					</li>
					<li data-role="fieldcontain">
						<label for="logsRuntime">
							<strong><?php echo _('Enable runtime log') ?></strong><br/>
							<i><?php echo _('Every output will be written inside.') ?></i>
						</label>
						<select id="logsRuntime" name="log[runtime]" data-role="slider">
							<option value="0" selected="selected"><?php echo _('No') ?></option>
							<option value="1"><?php echo _('Yes') ?></option>
						</select>
					</li>
					<li data-role="fieldcontain">
						<label for="logsGlobalError">
							<strong><?php echo _('Enable global error log') ?></strong><br/>
							<i><?php echo _('Gather errors from all instances to a single file.') ?></i>
						</label>
						<select id="logsGlobalError" name="log[globalError]" data-role="slider">
							<option value="0" selected="selected"><?php echo _('No') ?></option>
							<option value="1"><?php echo _('Yes') ?></option>
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
<?php require __DIR__.'/../Footer.php' ?>
