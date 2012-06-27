<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?= $r->createLinkArgList('../unguestlist') ?>" data-ajax="false">
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= _('Current guest list') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend><?= _('Players') ?></legend>
							<?php if(count($guestListedPlayers)): ?>
								<?php foreach($guestListedPlayers as $player): ?>
									<label for="<?= $player->login ?>"><?= $player->login ?></label>
									<input type="checkbox" name="players[]" id="<?= $player->login ?>" value="<?= $player->login ?>" />
								<?php endforeach; ?>
							<?php else: ?>
								<strong><?= _('There is no player in the guest list.') ?></strong>
							<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-d">
							<div class="ui-block-a">
								<a href="#add" data-role="button" data-icon="plus" data-rel="dialog" data-transition="pop"><?= _('Add a guest') ?></a>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?= _('Remove from list') ?>" data-icon="minus" <?= count($guestListedPlayers) ? '' : 'disabled="disabled"' ?>/>
							</div>
							<div class="ui-block-c">
								<a href="<?= htmlentities($r->createLinkArgList('../clean-guestlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete" data-ajax="false"><?= _('Clean the list') ?></a>
							</div>
							<div class="ui-block-d">
								<a href="#load" data-role="button" data-icon="gear" data-rel="dialog" data-transition="pop"><?= _('Load a guest list') ?></a>
							</div>
							<div class="ui-block-e">
								<a href="#save" data-role="button" data-icon="check" data-rel="dialog" data-transition="pop"><?= _('Save a guest list') ?></a>
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
		<form method="get" action="<?= $r->createLinkArgList('../load-guestlist') ?>" data-ajax="false">
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<input type="hidden" name="host" value="<?= $host ?>"/>
			<fieldset data-role="controlgroup">
				<legend><?= _('Available guestlist') ?></legend>
				<?php foreach($guestlistFiles as $file): ?>
					<input type="radio" name="filename" id="<?= $file ?>" value="<?= $file ?>"/>
					<label for="<?= $file ?>"><?= $file ?></label>
				<?php endforeach; ?>
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
		<h1><?= _('Add a guest') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?= $r->createLinkArgList('../add-guest') ?>" data-ajax="false">
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
		<form method="get" action="<?= $r->createLinkArgList('../save-guestlist') ?>" data-ajax="false">
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<input type="hidden" name="host" value="<?= $host ?>"/>
			<label for="filename"><?= _('Enter a filename to save the guestlist') ?></label>
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