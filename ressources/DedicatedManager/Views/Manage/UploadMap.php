<?php
require __DIR__.'/../Header.php';
?>
<div data-role="page">
	<?php
		$header = DedicatedManager\Helpers\Header::getInstance();
		$header->rightLink = $appURL.'/manage/maps/?path='.$path;
		$header->rightText = _('Back to maps');
		$header->rightIcon = 'back';
	?>
	<?= DedicatedManager\Helpers\Header::save() ?>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?= $appURL ?>/manage/do-upload-map/" method="post" enctype="multipart/form-data" data-ajax="false">
			<input type="hidden" name="path" value="<?=$path?>"/>
			<input type="file" name="map"/>
			<input type="submit" value="<?=_('Send')?>"/>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>