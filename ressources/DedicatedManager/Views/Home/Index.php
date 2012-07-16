<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
	<div data-role="content">
	<?php if($isAdmin): ?>
		<a href="<?= $r->createLinkArgList('/create/configure') ?>" data-ajax="false" data-role="button" data-icon="plus"><?= _('Start a new server') ?></a>
		<a href="<?= $r->createLinkArgList('/create/relay') ?>" data-ajax="false" data-role="button" data-icon="plus"><?= _('Start a new relay server') ?></a>
		<a href="<?= $r->createLinkArgList('/manage') ?>" data-ajax="false" data-role="button" data-icon="gear"><?= _('Manage your config files') ?></a>
		<a href="<?= $r->createLinkArgList('/manage/maps') ?>" data-ajax="false" data-role="button" data-icon="gear"><?= _('Manage your maps') ?></a>
	<?php endif; ?>
	<?php if($servers): ?>
		<p><?= _('Edit a server') ?></p>
		<ul data-role="listview" data-filter="true" data-inset="true">
		<?php foreach($servers as $server):
			$r->set('host', $server->rpcHost);
			$r->set('port', $server->rpcPort); ?>
			<li>
				<a href="<?= htmlentities($r->createLinkArgList('/edit', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= ManiaLib\Utils\StyleParser::toHtml($server->name) ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php' ?>