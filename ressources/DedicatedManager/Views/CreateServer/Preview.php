<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo sprintf(_('Step %d on %d'), 4, 4) ?></h2><br/>
		<h3><?php echo _('Save this configuration for later') ?></h3><br/>
		<?php echo _('If you want to reuse this configuration later, please choose a name wisely.') ?><br/>
		<?php echo _('If any configuration already exists with this name, it will be overwritten!') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form action="<?php echo $r->createLinkArgList('../start') ?>" method="get" data-ajax="false">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?php echo _('Change config filenames') ?></li>
				<li data-role="fieldcontain">
					<label for="configFile">
						<?php echo _('Server config filename') ?><br/>
						<i><?php echo _('You have to enter a filename to save your server configuration.') ?></i>
					</label>
					<?php echo DedicatedManager\Helpers\Input::text('configFile', 'configFile', $configFile) ?>
				</li>
				<li data-role="fieldcontain">
					<label for="matchFile">
						<?php echo _('Match settings filename') ?><br/>
						<i><?php echo _('You have to enter a filename to save your match configuration.') ?></i>
					</label>
					<?php echo DedicatedManager\Helpers\Input::text('matchFile', 'matchFile', $matchFile) ?>
				</li>
			</ul>
			<input type="submit" value="<?php echo _('Start your server') ?>"/>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>
