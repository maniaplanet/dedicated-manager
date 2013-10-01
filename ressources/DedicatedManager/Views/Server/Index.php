<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<ul data-role="listview" data-theme="c" data-dividertheme="d" data-inset="true">
		<?php if(!$isRelay): ?>
			<li data-icon="grid">
				<a href="<?php echo htmlentities($r->createLinkArgList('../maps', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Map list') ?></a>
			</li>
			<li data-icon="gear">
				<a href="<?php echo htmlentities($r->createLinkArgList('../rules', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Match rules') ?></a>
			</li>
			<li data-icon="gear">
				<a href="<?php echo htmlentities($r->createLinkArgList('../commands', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Script commands') ?></a>
			</li>
		<?php endif; ?>
			<li data-icon="gear">
				<a href="<?php echo htmlentities($r->createLinkArgList('../config', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Server configuration') ?></a>
			</li>
			<li data-icon="gear">
				<a href="<?php echo htmlentities($r->createLinkArgList('../votes', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Votes ratios') ?></a>
			</li>
			<li data-icon="grid">
				<a href="<?php echo htmlentities($r->createLinkArgList('../players', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Players') ?></a>
			</li>
		<?php if(!$isRelay): ?>
			<li data-icon="grid">
				<a href="<?php echo htmlentities($r->createLinkArgList('../teams', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Teams') ?></a>
			</li>
		<?php endif; ?>
			<li data-icon="grid">
				<a href="<?php echo htmlentities($r->createLinkArgList('../chat', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Chat') ?></a>
			</li>
		<?php if($isAdmin): ?>
			<li data-icon="grid">
				<a href="<?php echo htmlentities($r->createLinkArgList('../managers', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Managers') ?></a>
			</li>
			<li data-icon="star">
				<a href="<?php echo htmlentities($r->createLinkArgList('../controllers', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Ingame controllers') ?></a>
			</li>
		<?php endif; ?>
		</ul>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>