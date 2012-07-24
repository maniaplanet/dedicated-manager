<?php $currentAction = \ManiaLib\Application\Dispatcher::getInstance()->getAction(); ?>
<link rel="stylesheet" href="<?php echo $mediaURL ?>css/navigation.css" type="text/css" media="all" />
<div class="content-secondary">
	<div data-role="collapsible" data-collapsed="true" data-theme="e" data-content-theme="d">
		<h3><?php echo _('Show more') ?></h3>
		<ul data-role="listview" data-theme="c" data-dividertheme="d">
			<li data-role="list-divider"><?php echo _('Quick menu') ?></li>
			<li data-icon="grid" <?php echo in_array($currentAction, array('maps', 'addMaps')) ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../maps', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Map list') ?></a>
			</li>
		<?php if(!$isRelay): ?>
			<li data-icon="gear" <?php echo $currentAction == 'rules' ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../rules', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Match rules') ?></a>
			</li>
		<?php endif; ?>
			<li data-icon="gear" <?php echo $currentAction == 'config' ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../config', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Server configuration') ?></a>
			</li>
			<li data-icon="grid" <?php echo in_array($currentAction, array('players', 'banlist', 'blacklist', 'guestlist')) ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../players', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Players') ?></a>
			</li>
		<?php if(!$isRelay): ?>
			<li data-icon="grid" <?php echo $currentAction == 'teams' ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../teams', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Teams') ?></a>
			</li>
		<?php endif; ?>
			<li data-icon="grid" <?php echo $currentAction == 'chat' ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../chat', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Chat') ?></a>
			</li>
		<?php if($isAdmin): ?>
			<li data-icon="grid" <?php echo $currentAction == 'managers' ? 'data-theme="e"' : '' ?>>
				<a href="<?php echo htmlentities($r->createLinkArgList('../managers', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false"><?php echo _('Managers') ?></a>
			</li>
		<?php endif; ?>
		</ul>
	</div>
</div>
