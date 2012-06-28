<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?= $r->createLinkArgList('../action-players') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= _('Select players to interact with them') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if($playersCount): ?>
							<legend><?= _('Connected players') ?></legend>
							<?php foreach($players as $player): ?>
								<label for="<?= $player->login ?>"><?= ManiaLib\Utils\StyleParser::toHtml($player->nickName) ?>
									<?= '('.$player->login.')' ?></label>
								<input type="checkbox" name="players[]" id="<?= $player->login ?>" value="<?= $player->login ?>" />
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?= _('There is no player connected for the moment.') ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-c">
							<div class="ui-block-a">
								<input type="submit" name="guestlist" value="<?= _('Guestlist') ?>" data-icon="star"
									<?= !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
							<div class="ui-block-b">
								<input type="submit" name="kick" value="<?= _('Kick') ?>" data-icon="info"
									<?= !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
							<div class="ui-block-c">
								<input type="submit" name="ban" value="<?= _('Ban') ?>" data-icon="alert"
								<?= !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
							<div class="ui-block-d">
								<input type="submit" name="blacklist" value="<?= _('Blacklist') ?>" data-icon="delete"
								<?= !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
						</div>
					</li>
				</ul>
			</form>
			<div class="ui-grid-b">
				<div class="ui-block-a">
					<a href="<?= htmlentities($r->createLinkArgList('../guestlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="star" data-ajax="false"><?= _('Manage guest list') ?></a>
				</div>
				<div class="ui-block-b">
					<a href="<?= htmlentities($r->createLinkArgList('../blacklist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete" data-ajax="false"><?= _('Manage black list') ?></a>
				</div>
				<div class="ui-block-c">
					<a href="<?= htmlentities($r->createLinkArgList('../banlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="alert" data-ajax="false"><?= _('Manage ban list') ?></a>
				</div>
			</div>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>