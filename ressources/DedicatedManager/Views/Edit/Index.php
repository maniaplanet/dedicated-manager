<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div data-role="controlgroup" data-type="horizontal">
			<a href="<?= htmlentities($r->createLinkArgList('../maps', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false" data-role="button" data-icon="grid"><?= _('Edit map list') ?></a>
			<a href="<?= htmlentities($r->createLinkArgList('../rules', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false" data-role="button" data-icon="gear"><?= _('Edit match rules') ?></a>
			<a href="<?= htmlentities($r->createLinkArgList('../config', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false" data-role="button" data-icon="gear"><?= _('Edit server configuration') ?></a>
			<a href="<?= htmlentities($r->createLinkArgList('../players', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false" data-role="button" data-icon="grid"><?= _('Manage players') ?></a>
			<a href="<?= htmlentities($r->createLinkArgList('../teams', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false" data-role="button" data-icon="grid"><?= _('Manage teams') ?></a>
			<a href="<?= htmlentities($r->createLinkArgList('../chat', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false" data-role="button" data-icon="grid"><?= _('Chat') ?></a>
		</div>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>