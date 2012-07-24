<?php
require __DIR__.'/../Header.php';
use DedicatedManager\Services\Spectate;
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo sprintf(_('Step %d on %d'), 2, 2) ?></h2><br/>
		<h3><?php echo _('Save this configuration for later') ?></h3><br/>
		<?php echo _('If you want to reuse this configuration later, please choose a name wisely.') ?><br/>
		<?php echo _('If any configuration already exists with this name, it will be overwritten!') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form action="<?php echo $r->createLinkArgList('../start-relay') ?>" method="get" data-ajax="false">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Change config filename') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="configFile">
							<strong><?php echo _('Server config filename') ?></strong><br/>
							<i><?php echo _('You have to enter a filename to save your server configuration.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('configFile', 'configFile', $configFile) ?>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Server to relay') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<label for="relayMethod">
							<strong><?php echo _('Method to join') ?></strong><br/>
							<i><?php echo _('You can either relay a server started with the manager or another one by specifying its login or its IP and port') ?></i>
						</label>
						<select id="relayMethod" name="spectate[method]" data-native-menu="false">
						<?php if($servers): ?>
							<option value="<?php echo Spectate::MANAGED ?>" <?php echo $spectate->method == Spectate::MANAGED ? 'selected="selected"' : '' ?>>
								<?php echo _('Choose a managed server') ?>
							</option>
						<?php endif; ?>
							<option value="<?php echo Spectate::LOGIN ?>" <?php echo $spectate->method == Spectate::LOGIN ? 'selected="selected"' : '' ?>>
								<?php echo _('Join by login') ?>
							</option>
							<option value="<?php echo Spectate::IP_AND_PORT ?>" <?php echo $spectate->method == Spectate::IP_AND_PORT ? 'selected="selected"' : '' ?>>
								<?php echo _('Join by IP and port') ?>
							</option>
						</select>
					</li>
				</ul>
			</fieldset>
		<?php if($servers): ?>
			<fieldset id="fieldset-spectate-managed" class="relay-method" data-theme="b">
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo _('Choose a managed server') ?></li>
					<li data-role="fieldcontain">
						<label for="spectateManaged">
							<strong><?php echo _('Select server') ?></strong><br/>
							<i><?php echo _('You can easily connect a relay to a server you previously started.') ?></i>
						</label>
						<select id="spectateManaged" name="spectate[managed]" data-native-menu="false">
						<?php foreach($servers as $server): ?>
							<?php $serverId = $server->rpcHost.':'.$server->rpcPort.':'.$server->rpcPassword; ?>
							<option value="<?php echo $serverId ?>" <?php echo $serverId == $spectate->managed ? 'selected="selected"' : '' ?>>
								<?php echo \ManiaLib\Utils\Formatting::stripStyles($server->name) ?>
							</option>
						<?php endforeach; ?>
						</select>
					</li>
				</ul>
			</fieldset>
		<?php endif; ?>
			<fieldset id="fieldset-spectate-login" class="relay-method" data-theme="b">
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo _('Join by login') ?></li>
					<li data-role="fieldcontain">
						<label for="spectateLogin">
							<strong><?php echo _('Login') ?></strong><br/>
							<i><?php echo _('If you want to relay a server which isn\'t listed in the manager') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('spectate[login]', 'spectateLogin', $spectate->login) ?><br/>
					</li>
					<li data-role="fieldcontain">
						<label for="spectateLoginPassword">
							<strong><?php echo _('Password (optionnal)') ?></strong><br/>
							<i><?php echo _('If the server has a password for spectators, you have to set it.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('spectate[password]', 'spectateLoginPassword', $spectate->password) ?><br/>
					</li>
				</ul>
			</fieldset>
			<fieldset id="fieldset-spectate-ipAndPort" class="relay-method" data-theme="b">
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo _('Join by IP and port') ?></li>
					<li data-role="fieldcontain">
						<label for="spectateIp">
							<strong><?php echo _('Set IP') ?></strong><br/>
							<i><?php echo _('If you don\'t know the login of the server or if it\'s a LAN server, you can use its IP.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('spectate[ip]', 'spectateIp', $spectate->ip) ?><br/>
					</li>
					<li data-role="fieldcontain">
						<label for="spectatePort">
							<strong><?php echo _('Set port') ?></strong><br/>
							<i><?php echo _('Change the port if the server don\'t use the default one or if there are several servers on this IP.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('spectate[port]', 'spectatePort', $spectate->port) ?>
					</li>
					<li data-role="fieldcontain">
						<label for="spectateIpAndPortPassword">
							<strong><?php echo _('Password (optionnal)') ?></strong><br/>
							<i><?php echo _('If the server has a password for spectators, you have to set it.') ?></i>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('spectate[password]', 'spectateIpAndPortPassword', $spectate->password) ?><br/>
					</li>
				</ul>
			</fieldset>
			<input type="submit" value="<?php echo _('Start your relay server') ?>"/>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>
