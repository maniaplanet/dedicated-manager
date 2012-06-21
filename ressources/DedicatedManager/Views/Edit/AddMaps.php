<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<p><?= _('The "Insert to map list" button will add selected maps after the current one. The "add to map list" will add selected maps at the end of the map list.') ?></p>
			<form action="<?= $r->createLinkArgList('../insert-maps') ?>" method="get" data-ajax="false">
				<input type="hidden" name="hostname" value="<?= $hostname ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<?= DedicatedManager\Helpers\Files::tree($files, $selected, 'selected', true) ?>
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<input type="submit" name="insert" value="<?= _('Insert to map list') ?>"/>
					</div>
					<div class="ui-block-b">
						<input type="submit" name="add" value="<?= _('Add to map list') ?>" />
					</div>
				</div>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>