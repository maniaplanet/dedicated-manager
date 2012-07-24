<?php
require __DIR__.'/../Header.php';
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
	<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?php echo $appURL ?>/manage/delete-maps/" method="GET" data-ajax="false">
			<input type="hidden" name="path" value="<?php echo $path ?>"/>
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><h3>Maps/<?php echo $path ?></h3></li>
				<?php if($path != $parentPath): ?>
					<li data-icon="arrow-u" data-theme="e"><a href="<?php echo $appURL ?>/manage/maps/?path=<?php echo $parentPath ?>"><?php echo _('Parent directory') ?></a></li>
				<?php endif; ?>
				<?php
				$firstFile = false;
				$fileCount = count($files);
				?>
				<?php foreach($files as $key => $file): ?>
					<?php if($file->isDirectory): ?>
						<li><a href="<?php echo $appURL ?>/manage/maps/?path=<?php echo $file->path.$file->filename ?>/"><?php echo $file->filename ?></a></li>
					<?php else: ?>
						<?php if($firstFile == false): ?>
							<li>
								<fieldset data-role="controlgroup">
									<?php $firstFile = true; ?>
								<?php endif; ?>
								<label for="<?php echo $file->filename ?>"><?php echo \ManiaLib\Utils\StyleParser::toHtml($file->name) ?></label>
								<input type="checkbox" id="<?php echo $file->filename ?>" value="<?php echo $file->path.$file->filename ?>" name="maps[]"/>
								<?php if($key == $fileCount - 1): ?>
								</fieldset>
							</li>
						<?php endif; ?>
					<?php endif; ?>

				<?php endforeach; ?>
				<li><input type="submit" value="<?php echo _('Delete') ?>" data-icon="delete"/></li>
			</ul>
		</form>
		<form action="<?php echo $appURL ?>/manage/upload-map/" method="POST" data-ajax="false" enctype="multipart/form-data">
			<input type="hidden" name="path" value="<?php echo$path?>"/>
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><h3><?php echo _('Upload in this folder') ?></h3></li>
				<li><input type="file" name="map"/></li>
				<li><input type="submit" value="<?php echo_('Upload map')?>" data-icon="plus"/></li>
			</ul>
		</form>
	</div>
</div>

<?php require __DIR__.'/../Footer.php' ?>