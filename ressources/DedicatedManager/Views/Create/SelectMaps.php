<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?= sprintf(_('Step %d on %d'), 3, 4) ?></h2><br/>
		<h3><?= _('Select the maps ou want to play on.') ?></h3><br/>
		<?= _('Click on the maps that you want to include.') ?><br/>
		<?= _('The order you selected them is the order you will play them.') ?>
    </div>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?= $r->createLinkArgList('../save-maps') ?>" data-ajax="false" method="get">
			<?= DedicatedManager\Helpers\Files::tree($files, $selected, 'selected') ?>
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
<?php require __DIR__.'/../Footer.php' ?>