<?php
require __DIR__.'/../Header.php';
use DedicatedManager\Services\Spectate;
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?= sprintf(_('Step %d on %d'), 2, 2) ?></h2><br/>
		<h3><?= _('Save this configuration for later') ?></h3><br/>
		<?= _('If you want to reuse this configuration later, please choose a name wisely.') ?><br/>
		<?= _('If any configuration already exists with this name, it will be overwritten!') ?>
    </div>
	<?= DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form action="<?= $r->createLinkArgList('../start-relay') ?>" method="get" data-ajax="false">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?= _('Change config filename') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="configFile">
							<strong><?= _('Server config filename') ?></strong><br/>
							<i><?= _('You have to enter a filename to save your server configuration.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('configFile', 'configFile', $configFile) ?>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?= _('Server to relay') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="relayMethod">
							<strong><?= _('Method to join') ?></strong><br/>
							<i><?= _('You can either relay a server started with the manager or another one by specifying its login or its IP and port') ?></i>
						</label>
						<select id="relayMethod" name="spectate[method]" data-native-menu="false">
						<?php if($servers): ?>
							<option value="<?= Spectate::MANAGED ?>" <?= $spectate->method == Spectate::MANAGED ? 'selected="selected"' : '' ?>>
								<?= _('Choose a managed server') ?>
							</option>
						<?php endif; ?>
							<option value="<?= Spectate::LOGIN ?>" <?= $spectate->method == Spectate::LOGIN ? 'selected="selected"' : '' ?>>
								<?= _('Specify a login') ?>
							</option>
							<option value="<?= Spectate::IP_AND_PORT ?>" <?= $spectate->method == Spectate::IP_AND_PORT ? 'selected="selected"' : '' ?>>
								<?= _('Specify IP and port') ?>
							</option>
						</select>
					</li>
				</ul>
			</fieldset>
		<?php if($servers): ?>
			<fieldset id="fieldset-spectate-managed" class="relay-method" data-theme="b">
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?= _('Choose a managed server') ?></li>
					<li data-role="fieldcontain">
						<label for="spectateManaged">
							<strong><?= _('Set IP') ?></strong><br/>
							<i><?= _('If you don\'t know the login of the server or if it\'s a LAN server, you can use its IP.') ?></i>
						</label>
						<select id="spectateManaged" name="spectate[managedLogin]" data-native-menu="false">
						<?php foreach($servers as $server): ?>
							<option value="<?= $server->login ?>" <?= $server->login == $spectate->managedLogin ? 'selected="selected"' : '' ?>>
								<?= \ManiaLib\Utils\Formatting::stripStyles($server->name) ?>
							</option>
						<?php endforeach; ?>
						</select>
					</li>
				</ul>
			</fieldset>
		<?php endif; ?>
			<fieldset id="fieldset-spectate-login" class="relay-method" data-theme="b">
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?= _('Specify a login') ?></li>
					<li data-role="fieldcontain">
						<label for="spectateLogin">
							<strong><?= _('Set login') ?></strong><br/>
							<i><?= _('If you want to relay a server which isn\'t listed in the manager') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('spectate[login]', 'spectateLogin', $spectate->login) ?><br/>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-spectate-ipAndPort" class="relay-method" data-theme="b">
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?= _('Specify IP and port') ?></li>
					<li data-role="fieldcontain">
						<label for="spectateIp">
							<strong><?= _('Set IP') ?></strong><br/>
							<i><?= _('If you don\'t know the login of the server or if it\'s a LAN server, you can use its IP.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('spectate[ip]', 'spectateIp', $spectate->ip) ?><br/>
					</li>
					<li data-role="fieldcontain">
						<label for="spectatePort">
							<strong><?= _('Set port') ?></strong><br/>
							<i><?= _('Change the port if the server don\'t use the default one or if there are several servers on this IP.') ?></i>
						</label>
						<?= DedicatedManager\Helpers\Input::text('spectate[port]', 'spectatePort', $spectate->port) ?>
					</li>
				</ul>
			</fieldset>
			<input type="submit" value="<?= _('Start your relay server') ?>"/>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>
