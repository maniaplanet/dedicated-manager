<?php
require __DIR__.'/../Header.php';
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
		<form action="<?= $r->createLinkArgList('../start-server') ?>" method="get" data-ajax="false">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?= _('Change config filename') ?></li>
				<li data-role="fieldcontain">
					<label for="configFile">
						<?= _('Server config filename') ?><br/>
						<i><?= _('You have to enter a filename to save your server configuration.') ?></i>
					</label>
					<input type="text" name="configFile" id="configFile" value="<?= $configFile ?>"/>
				</li>
			</ul>
			<input type="submit" value="<?= _('Start your relay server') ?>"/>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>
