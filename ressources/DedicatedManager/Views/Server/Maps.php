<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
		<?php if(!$isRelay): ?>
			<form action="<?php echo $r->createLinkArgList('../do-maps') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
		<?php endif; ?>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo $isRelay
						? _('Maps order') : _('Change maps list') ?></li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php foreach($maps as $map): ?>
							<?php echo DedicatedManager\Helpers\Files::rawMap($map, 'maps[]', false, $isLocal, $currentMap->fileName == $map->fileName, $isRelay); ?>
						<?php endforeach ?>
						</fieldset>
					</li>
				<?php if(!$isRelay): ?>
					<li data-role="fieldcontain">
						<div class="ui-grid-b">
							<div class="ui-block-a">
								<input data-theme="d" type="submit" name="delete" value="<?php echo _('Delete from map list') ?>" data-icon="delete"/>
							</div>
							<div class="ui-block-b">
								<input data-theme="d" type="submit" name="nextList" value="<?php echo _('Set as next map') ?>" data-icon="check"/>
							</div>
							<div class="ui-block-c">
								<a href="<?php echo htmlentities($r->createLinkArgList('../add-maps', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="plus" data-theme="b" data-ajax="false"><?php echo _('Add new maps') ?></a>
							</div>
						</div>
					</li>
				<?php endif; ?>
				</ul>
			</form>
		<?php if(!$isRelay && $isLocal): ?>
			<form action="<?php echo $r->createLinkArgList('../save-match-settings') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo _('Save match settings'); ?></li>
					<li data-role="fieldcontain">
						<label for="filename">
							<strong><?php echo _('Enter the filename')?></strong><br/>
							<i><?php echo _('This file can be use when you start a server')?></i>
						</label>
						<input type="text" id="filename" name="filename" value="<?php echo ManiaLib\Utils\Formatting::stripStyles($options->name); ?>"/>
					</li>
					<li>
						<input type="submit" value="<?php echo _('Save'); ?>" data-icon="check"/>
					</li>
				</ul>
			</form>
		<?php endif; ?>
		</div>
	<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>