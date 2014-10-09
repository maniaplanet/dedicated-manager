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
		<a href="<?php echo $r->createLinkArgList('/create-server/quick-start/') ?>" data-ajax="false" data-role="button" data-icon="plus"><?php echo _('Quick start a new server') ?></a>
		<a href="<?php echo $r->createLinkArgList('/create-relay') ?>" data-ajax="false" data-role="button" data-icon="plus"><?php echo _('Start a new relay server') ?></a>
		<a href="<?php echo $r->createLinkArgList('/add-server') ?>" data-ajax="false" data-role="button" data-icon="plus"><?php echo _('Add an already running server') ?></a>
		<a href="<?php echo $r->createLinkArgList('/configs') ?>" data-ajax="false" data-role="button" data-icon="gear"><?php echo _('Manage your config files') ?></a>
		<a href="<?php echo $r->createLinkArgList('/maps') ?>" data-ajax="false" data-role="button" data-icon="gear"><?php echo _('Manage your maps') ?></a>
	<?php endif; ?>
	<?php if($servers): ?>
		<p><?php echo _('Edit a server').($isAdmin ? ' or stop managing it (the "minus" button only removes it from the interface, it does not stop it)' : '') ?></p>
		<ul data-role="listview" data-filter="true" data-inset="true">
		<?php foreach($servers as $server):
			$r->set('host', $server->rpcHost);
			$r->set('port', $server->rpcPort); ?>
			<li>
				<a href="<?php echo htmlentities($r->createLinkArgList('/server', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"
				   data-host="<?php echo $server->rpcHost; ?>" data-port="<?php echo $server->rpcPort; ?>" class="server">
					<?php echo ManiaLib\Utils\StyleParser::toHtml(\ManiaLib\Utils\Formatting::stripLinks($server->name)) ?: '&nbsp;'; ?>
				</a>
			<?php if($isAdmin): ?>
				<a href="<?php echo htmlentities($r->createLinkArgList('../remove', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-icon="minus" data-theme="c" data-ajax="false"></a>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
$(document).bind('pageinit', function() {
	$('a.server').each(function() {
		var anchor = $(this);
		$.get('<?php echo $r->createLinkArgList('../status'); ?>',
			{host: anchor.attr('data-host'), port: anchor.attr('data-port')},
			function (answer) {
				if(answer != '0') {
					anchor.append('<small><em>(' + answer + ')</em></small>');
				} else {
                    anchor.addClass('ui-disabled');
                }
			}
		);
	});
});
</script>
<?php require __DIR__.'/../Footer.php' ?>