<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?= $r->createLinkArgList('../unblacklist') ?>" data-ajax="false">
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= _('Current black list') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend><?= _('Blacklisted players') ?></legend>
							<?php if(count($blackListedPlayers)): ?>
								<?php foreach($blackListedPlayers as $player): ?>
									<label for="<?= $player->login ?>"><?= $player->login ?></label>
									<input type="checkbox" name="players[]" id="<?= $player->login ?>" value="<?= $player->login ?>" />
								<?php endforeach; ?>
							<?php else: ?>
								<strong><?= _('There is no blacklisted player.') ?></strong>
							<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-d">
							<div class="ui-block-a">
								<a href="#add" data-role="button" data-icon="plus" data-rel="dialog" data-transition="pop"><?= _('Add a player') ?></a>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?= _('Remove from list') ?>" data-icon="minus" <?= count($blackListedPlayers) ? '' : 'disabled="disabled"' ?>/>
							</div>
							<div class="ui-block-c">
								<a href="<?= htmlentities($r->createLinkArgList('../clean-blacklist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete" data-ajax="false"><?= _('Clean blacklist') ?></a>
							</div>
							<div class="ui-block-d">
								<a href="#load" data-role="button" data-icon="gear" data-rel="dialog" data-transition="pop"><?= _('Load a black list') ?></a>
							</div>
							<div class="ui-block-e">
								<a href="#save" data-role="button" data-icon="check" data-rel="dialog" data-transition="pop"><?= _('Save a save list') ?></a>
							</div>
						</div>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<div data-role="dialog" id="load">
	<div data-role="header">
		<h1><?= _('Load guest list') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?= $appURL ?>/edit/load-blacklist/" data-ajax="false">
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<input type="hidden" name="host" value="<?= $host ?>"/>
			<fieldset data-role="controlgroup">
				<legend><?= _('Available guestlist') ?></legend>
				<?php if(count($blacklistFiles)): ?>
					<?php foreach($blacklistFiles as $file): ?>
						<input type="radio" name="filename" id="<?= $file ?>" value="<?= $file ?>"/>
						<label for="<?= $file ?>"><?= $file ?></label>
					<?php endforeach; ?>
				<?php else: ?>
					<strong><?= _('There is no blacklist file available') ?></strong>
				<?php endif; ?>
			</fieldset>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?= _('Load') ?>"/>
				</div>
				<div class="ui-block-b">
					<a href="#" data-rel="back" data-role="button"><?= _('Back') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<div data-role="dialog" id="add">
	<div data-role="header">
		<h1><?= _('Add a player to blacklist') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?= $appURL ?>/edit/add-black/" data-ajax="false">
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<input type="hidden" name="host" value="<?= $host ?>"/>
			<label for="login"><?= _('Enter the login to add') ?></label>
			<input type="text" name="login" id="login" value="" placeholder="login..."/>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?= _('Add') ?>"/>
				</div>
				<div class="ui-block-b">
					<a href="#" data-rel="back" data-role="button"><?= _('Back') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<div data-role="dialog" id="save">
	<div data-role="header">
		<h1><?= _('Save guest list') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?= $appURL ?>/edit/save-blacklist/" data-ajax="false">
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<input type="hidden" name="host" value="<?= $host ?>"/>
			<label for="filename"><?= _('Enter a filename to save the blacklist') ?></label>
			<input type="text" name="filename" id="filename" value=""/>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?= _('Save') ?>"/>
				</div>
				<div class="ui-block-b">
					<a href="#" data-rel="back" data-role="button"><?= _('Back') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>