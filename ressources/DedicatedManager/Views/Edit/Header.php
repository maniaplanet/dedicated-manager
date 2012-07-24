<?php $r = ManiaLib\Application\Request::getInstance(); ?>
<?php echo DedicatedManager\Helpers\Header::save() ?>
<div class="ui-bar ui-bar-b">
	<div class="ui-grid-a">
		<div class="ui-block-a">
			<?php echo _('Server name:') ?>&nbsp;<?php echo ManiaLib\Utils\StyleParser::toHtml($options->name) ?>
		</div>
		<div class="ui-block-b">
			<?php echo _('Number of connected players:') ?>&nbsp;<?php echo $playersCount ?>
		</div>
		<div class="ui-block-a">
			<?php echo _('Current map:') ?>&nbsp;<?php echo ManiaLib\Utils\StyleParser::toHtml($currentMap->name) ?>&nbsp;(<?php echo $currentMap->author ?>)
		</div>
		<div class="ui-block-b">
			<?php echo _('Next map:') ?>&nbsp;<?php echo ManiaLib\Utils\StyleParser::toHtml($nextMap->name) ?>&nbsp;(<?php echo $nextMap->author ?>)
		</div>
	</div>
</div>
<div data-role="navbar" data-iconpos="left">
	<ul>
	<?php if($isAdmin): ?>
		<li><a href="<?php echo htmlentities($r->createLinkArgList('../stop', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-theme="b" data-role="button" data-icon="delete" data-ajax="false"><?php echo _('Close the server') ?></a></li>
	<?php endif; ?>
	<?php if($isRelay): ?>
		<li><a href="<?php echo $maniaplanetSpectate ?>" data-theme="b" data-role="button" data-icon="arrow-r" data-ajax="false"><?php echo _('Join') ?></a></li>
	<?php else: ?>
		<li><a href="<?php echo htmlentities($r->createLinkArgList('../restart', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-theme="b" data-role="button" data-icon="refresh" data-ajax="false"><?php echo _('Restart map') ?></a></li>
		<li><a href="<?php echo htmlentities($r->createLinkArgList('../next', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-theme="b" data-role="button" data-icon="forward" data-ajax="false"><?php echo _('Next map') ?></a></li>
		<li><a href="<?php echo $maniaplanetJoin ?>" data-theme="b" data-role="button" data-icon="arrow-r" data-ajax="false"><?php echo _('Join') ?></a></li>
		<li><a href="<?php echo $maniaplanetSpectate?>" data-theme="b" data-role="button" data-icon="arrow-r" data-ajax="false"><?php echo _('Spectate') ?></a></li>
	<?php endif; ?>
	</ul>
</div>
<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>