<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
	<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?php echo $r->createLinkArgList('../delete'); ?>" method="get" data-ajax="false">
			<div data-role="collapsible" data-collapsed="false" data-theme="b">
				<h3><?php echo _('Config files') ?></h3>
				<fieldset data-role="controlgroup">
				<?php foreach($configFiles as $configFile): ?>
					<?php $id = uniqid('config-'); ?>
					<input type="checkbox" id="<?php echo $id; ?>" name="configFiles[]" value="<?php echo $configFile; ?>"/>
					<label for="<?php echo $id; ?>"><?php echo $configFile; ?></label>
				<?php endforeach; ?>
				</fieldset>
			</div>
			<div data-role="collapsible" data-collapsed="false" data-theme="b">
				<h3><?php echo _('Match settings files') ?></h3>
				<fieldset data-role="controlgroup">
				<?php foreach($matchFiles as $matchFile): ?>
					<?php $id = uniqid('match-'); ?>
					<input type="checkbox" id="<?php echo $id; ?>" name="matchFiles[]" value="<?php echo $matchFile; ?>"/>
					<label for="<?php echo $id; ?>"><?php echo $matchFile; ?></label>
				<?php endforeach; ?>
				</fieldset>
			</div>
			<input type="submit" value="<?php echo _('Delete selected files') ?>" data-icon="delete"/>
			</ul>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>