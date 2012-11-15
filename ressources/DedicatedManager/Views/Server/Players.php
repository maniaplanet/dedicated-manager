<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?php echo $r->createLinkArgList('../do-players') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Select players to interact with them') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if($playersCount): ?>
							<legend><?php echo _('Connected players') ?></legend>
							<?php foreach($players as $player): ?>
								<label for="<?php echo $player->login ?>"><?php echo ManiaLib\Utils\StyleParser::toHtml($player->nickName) ?>
									<?php echo '('.$player->login.')' ?></label>
								<input type="checkbox" name="players[]" id="<?php echo $player->login ?>" value="<?php echo $player->login ?>" />
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?php echo _('There is no player connected for the moment.') ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li>
						<div class="ui-grid-c">
							<div class="ui-block-a">
								<input type="submit" name="guestlist" value="<?php echo _('Guestlist') ?>" data-icon="star"
									<?php echo !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
							<div class="ui-block-b">
								<input type="submit" name="kick" value="<?php echo _('Kick') ?>" data-icon="info"
									<?php echo !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
							<div class="ui-block-c">
								<input type="submit" name="ban" value="<?php echo _('Ban') ?>" data-icon="alert"
								<?php echo !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
							<div class="ui-block-d">
								<input type="submit" name="blacklist" value="<?php echo _('Blacklist') ?>" data-icon="delete"
								<?php echo !$playersCount ? 'disabled="disabled"' : '' ?>/>
							</div>
						</div>
					</li>
				</ul>
			</form>
			<div class="ui-grid-b">
				<div class="ui-block-a">
					<a href="<?php echo htmlentities($r->createLinkArgList('../guestlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="star" data-ajax="false"><?php echo _('Manage guest list') ?></a>
				</div>
				<div class="ui-block-b">
					<a href="<?php echo htmlentities($r->createLinkArgList('../blacklist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete" data-ajax="false"><?php echo _('Manage black list') ?></a>
				</div>
				<div class="ui-block-c">
					<a href="<?php echo htmlentities($r->createLinkArgList('../banlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="alert" data-ajax="false"><?php echo _('Manage ban list') ?></a>
				</div>
			</div>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>