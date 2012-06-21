<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?= $r->createLinkArgList('../map-action') ?>" method="get" data-ajax="false">
				<input type="hidden" name="hostname" value="<?= $hostname ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?= _('Change maps order') ?></li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend><?= _('Current map list:') ?></legend>
							<?php foreach($maps as $key => $map): ?>
								<?php $id = uniqid() ?>
								<input type="checkbox" name="maps[]" id="<?= $id ?>" value="<?= $map->fileName ?>"
								<?= $currentMap->fileName != $map->fileName ? '' : 'disabled="disabled"' ?> class="custom"/>
								<label for="<?= $id ?>">
									<img src="<?= $mediaURL ?>/images/thumbnails/<?= $map->uId ?>.jpg" class="map-thumbnail" alt="thumbnail"/>
									<?= \ManiaLib\Utils\StyleParser::toHtml($map->name) ?> <?= _('by') ?> <?= $map->author ?>
								</label>
							<?php endforeach ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<input data-theme="d" type="submit" name="nextMapIndex" value="<?= _('Set as next map') ?>" data-icon="check"/>
							</div>
							<div class="ui-block-b">
								<input data-theme="d" type="submit" name="deleteFilenames" value="<?= _('Delete from map list') ?>" data-icon="delete"/>
							</div>
						</div>
					</li>
				</ul>
			</form>
			<a href="<?= htmlentities($r->createLinkArgList('../add-maps', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="plus" data-ajax="false"><?= _('Add new maps') ?></a>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>