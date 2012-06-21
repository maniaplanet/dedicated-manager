<?php $r = ManiaLib\Application\Request::getInstance(); ?>
<?= DedicatedManager\Helpers\Header::save() ?>
<div class="ui-bar ui-bar-b">
	<div class="ui-grid-a">
		<div class="ui-block-a">
			<?= _('Server name:') ?>&nbsp;<?= ManiaLib\Utils\StyleParser::toHtml($serverOptions->name) ?>
		</div>
		<div class="ui-block-b">
			<?= _('Number of connected players:') ?>&nbsp;<?= $playersCount ?>
		</div>
		<div class="ui-block-a">
			<?= _('Current map:') ?>&nbsp;<?= ManiaLib\Utils\StyleParser::toHtml($currentMap->name) ?>&nbsp;(<?= $currentMap->author ?>)
		</div>
		<div class="ui-block-b">
			<?= _('Next map:') ?>&nbsp;<?= ManiaLib\Utils\StyleParser::toHtml($nextMap->name) ?>&nbsp;(<?= $nextMap->author ?>)
		</div>
	</div>
</div>
<div data-role="navbar" data-iconpos="left">
	<ul>
		<li><a href="<?= htmlentities($r->createLinkArgList('../stop', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-theme="b" data-role="button" data-icon="delete" data-ajax="false"><?= _('Close the server') ?></a></li>
		<li><a href="<?= htmlentities($r->createLinkArgList('../restart', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-theme="b" data-role="button" data-icon="refresh" data-ajax="false"><?= _('Restart map') ?></a></li>
		<li><a href="<?= htmlentities($r->createLinkArgList('../next', 'hostname', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-theme="b" data-role="button" data-icon="forward" data-ajax="false"><?= _('Next map') ?></a></li>
		<li><a href="maniaplanet://#join=<?= $hostname ?>:<?= $port ?>" data-theme="b" data-role="button" data-icon="arrow-r" data-ajax="false"><?= _('Join server') ?></a></li>
	</ul>
</div>
<?= DedicatedManager\Helpers\Box\Box::detect() ?>