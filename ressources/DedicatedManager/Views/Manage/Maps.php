<?php
require __DIR__.'/../Header.php';
?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?= $appURL ?>/manage/delete-maps/" method="GET" data-ajax="false">
			<input type="hidden" name="path" value="<?= $path ?>"/>
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><h3>Maps/<?= $path ?></h3></li>
				<?php if($path != $parentPath): ?>
					<li data-icon="arrow-u" data-theme="e"><a href="<?= $appURL ?>/manage/maps/?path=<?= $parentPath ?>"><?= _('Parent directory') ?></a></li>
				<?php endif; ?>
				<?php
				$firstFile = false;
				$fileCount = count($files);
				?>
				<?php foreach($files as $key => $file): ?>
					<?php if($file->isDirectory): ?>
						<li><a href="<?= $appURL ?>/manage/maps/?path=<?= $file->path.$file->filename ?>/"><?= $file->filename ?></a></li>
					<?php else: ?>
						<?php if($firstFile == false): ?>
							<li>
								<fieldset data-role="controlgroup">
									<?php $firstFile = true; ?>
								<?php endif; ?>
								<label for="<?= $file->filename ?>"><?= \ManiaLib\Utils\StyleParser::toHtml($file->name) ?></label>
								<input type="checkbox" id="<?= $file->filename ?>" value="<?= $file->path.$file->filename ?>" name="maps[]"/>
								<?php if($key == $fileCount - 1): ?>
								</fieldset>
							</li>
						<?php endif; ?>
					<?php endif; ?>

				<?php endforeach; ?>
				<li>
					<div class="ui-grid-a">
						<div class="ui-block-a"><a href="<?= $appURL ?>/manage/upload-map/?path=<?= $path ?>" data-role="button" data-icon="plus"><?= _('Upload') ?></a></div>
						<div class="ui-block-b"><input type="submit" value="<?= _('Delete') ?>" data-icon="delete"/></div>
					</div>
				</li>
			</ul>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>