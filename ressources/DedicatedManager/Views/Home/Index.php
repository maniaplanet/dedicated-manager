<?php require __DIR__.'/../Header.php' ?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
	<div data-role="content">
		<a href="<?= $appURL.'/create/configure/' ?>" data-ajax="false" data-role="button" data-icon="plus"><?= _('Start a new server') ?></a>
		<a href="<?= $appURL.'/manage/' ?>" data-ajax="false" data-role="button" data-icon="gear"><?= _('Manage your config files') ?></a>
		<?php if($servers): ?>
			<p><?= _('Edit a server') ?></p>
			<ul data-role="listview" data-filter="true" data-inset="true">
				<?php foreach($servers as $server): ?>
					<li>
						<a href="<?= $appURL ?>/edit/?hostname=<?= $server->hostname ?>&amp;port=<?= $server->port ?>" data-ajax="false"><?= ManiaLib\Utils\StyleParser::toHtml($server->name) ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php' ?>