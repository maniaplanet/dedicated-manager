<?php $currentAction = \ManiaLib\Application\Dispatcher::getInstance()->getAction(); ?>
<link rel="stylesheet" href="<?= $mediaURL ?>css/navigation.css" type="text/css" media="all" />
<div class="content-secondary">
	<div data-role="collapsible" data-collapsed="true" data-theme="e" data-content-theme="d">
		<h3><?= _('Show more') ?></h3>
		<ul data-role="listview" data-theme="c" data-dividertheme="d">
			<li data-role="list-divider"><?= _('Quick menu') ?></li>
			<li data-icon="grid" <?= in_array($currentAction, array('maps', 'addMaps')) ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../maps', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Map list') ?></a>
			</li>
		<?php if(!$isRelay): ?>
			<li data-icon="gear" <?= $currentAction == 'rules' ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../rules', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Match rules') ?></a>
			</li>
		<?php endif; ?>
			<li data-icon="gear" <?= $currentAction == 'config' ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../config', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Server configuration') ?></a>
			</li>
			<li data-icon="grid" <?= in_array($currentAction, array('players', 'banlist', 'blacklist', 'guestlist')) ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../players', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Players') ?></a>
			</li>
		<?php if(!$isRelay): ?>
			<li data-icon="grid" <?= $currentAction == 'teams' ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../teams', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Teams') ?></a>
			</li>
		<?php endif; ?>
			<li data-icon="grid" <?= $currentAction == 'chat' ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../chat', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Chat') ?></a>
			</li>
		<?php if($isAdmin): ?>
			<li data-icon="grid" <?= $currentAction == 'managers' ? 'data-theme="e"' : '' ?>>
				<a href="<?= htmlentities($r->createLinkArgList('../managers', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?= _('Managers') ?></a>
			</li>
		<?php endif; ?>
		</ul>
	</div>
</div>