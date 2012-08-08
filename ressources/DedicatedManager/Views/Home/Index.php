<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
	<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>
	<div data-role="content">
	<?php if($isAdmin): ?>
		<a href="<?php echo $r->createLinkArgList('/create-server') ?>" data-ajax="false" data-role="button" data-icon="plus"><?php echo _('Start a new server') ?></a>
		<a href="<?php echo $r->createLinkArgList('/create-relay') ?>" data-ajax="false" data-role="button" data-icon="plus"><?php echo _('Start a new relay server') ?></a>
		<a href="<?php echo $r->createLinkArgList('/configs') ?>" data-ajax="false" data-role="button" data-icon="gear"><?php echo _('Manage your config files') ?></a>
		<a href="<?php echo $r->createLinkArgList('/maps') ?>" data-ajax="false" data-role="button" data-icon="gear"><?php echo _('Manage your maps') ?></a>
	<?php endif; ?>
	<?php if($servers): ?>
		<p><?php echo _('Edit a server') ?></p>
		<ul data-role="listview" data-filter="true" data-inset="true">
		<?php foreach($servers as $server):
			$r->set('host', $server->rpcHost);
			$r->set('port', $server->rpcPort); ?>
			<li>
				<a href="<?php echo htmlentities($r->createLinkArgList('/server', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo ManiaLib\Utils\StyleParser::toHtml($server->name) ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php' ?>