<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?= $r->createLinkArgList('../unban') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= _('Banned players') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend><?= _('Banned players') ?></legend>
							<?php if(count($banlist)): ?>
								<?php foreach($banlist as $player): ?>
									<label for="<?= $player->login ?>"><?= $player->login ?></label>
									<input type="checkbox" name="players[]" id="<?= $player->login ?>" value="<?= $player->login ?>" />
								<?php endforeach; ?>
							<?php else: ?>
								<strong><?= _('There is not any banned player.') ?></strong>
							<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<input type="submit" value="<?= _('Unban') ?>" data-icon="minus" <?= count($banlist) ? '' : 'disabled="disabled"' ?>/>
							</div>
							<div class="ui-block-b">
								<a href="<?= htmlentities($r->createLinkArgList('../clean-banlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete"><?= _('Clean banlist') ?></a>
							</div>
						</div>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>