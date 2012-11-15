<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?php echo $r->createLinkArgList('../unban') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Banned players') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if(count($banlist)): ?>
							<?php foreach($banlist as $player): ?>
								<label for="<?php echo $player->login ?>"><?php echo $player->login ?></label>
								<input type="checkbox" name="players[]" id="<?php echo $player->login ?>" value="<?php echo $player->login ?>" />
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?php echo _('There is not any banned player'); ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li>
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<input type="submit" value="<?php echo _('Unban') ?>" data-icon="minus" <?php echo count($banlist) ? '' : 'disabled="disabled"' ?>/>
							</div>
							<div class="ui-block-b">
								<a href="<?php echo htmlentities($r->createLinkArgList('../clean-banlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete"><?php echo _('Clean banlist') ?></a>
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