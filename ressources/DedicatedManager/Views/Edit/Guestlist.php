<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?php echo $r->createLinkArgList('../unguestlist') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Current guest list') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend><?php echo _('Players') ?></legend>
							<?php if(count($guestListedPlayers)): ?>
								<?php foreach($guestListedPlayers as $player): ?>
									<label for="<?php echo $player->login ?>"><?php echo $player->login ?></label>
									<input type="checkbox" name="players[]" id="<?php echo $player->login ?>" value="<?php echo $player->login ?>" />
								<?php endforeach; ?>
							<?php else: ?>
								<strong><?php echo _('There is no player in the guest list.') ?></strong>
							<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-d">
							<div class="ui-block-a">
								<a href="#add" data-role="button" data-icon="plus" data-rel="dialog" data-transition="pop"><?php echo _('Add a guest') ?></a>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?php echo _('Remove from list') ?>" data-icon="minus" <?php echo count($guestListedPlayers) ? '' : 'disabled="disabled"' ?>/>
							</div>
							<div class="ui-block-c">
								<a href="<?php echo htmlentities($r->createLinkArgList('../clean-guestlist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete" data-ajax="false"><?php echo _('Clean the list') ?></a>
							</div>
							<div class="ui-block-d">
								<a href="#load" data-role="button" data-icon="gear" data-rel="dialog" data-transition="pop"><?php echo _('Load a guest list') ?></a>
							</div>
							<div class="ui-block-e">
								<a href="#save" data-role="button" data-icon="check" data-rel="dialog" data-transition="pop"><?php echo _('Save a guest list') ?></a>
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
		<h1><?php echo _('Load guest list') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?php echo $r->createLinkArgList('../load-guestlist') ?>" data-ajax="false">
			<input type="hidden" name="port" value="<?php echo $port ?>"/>
			<input type="hidden" name="host" value="<?php echo $host ?>"/>
			<fieldset data-role="controlgroup">
				<legend><?php echo _('Available guestlist') ?></legend>
				<?php foreach($guestlistFiles as $file): ?>
					<input type="radio" name="filename" id="<?php echo $file ?>" value="<?php echo $file ?>"/>
					<label for="<?php echo $file ?>"><?php echo $file ?></label>
				<?php endforeach; ?>
			</fieldset>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?php echo _('Load') ?>"/>
				</div>
				<div class="ui-block-b">
					<a href="#" data-rel="back" data-role="button"><?php echo _('Back') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<div data-role="dialog" id="add">
	<div data-role="header">
		<h1><?php echo _('Add a guest') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?php echo $r->createLinkArgList('../add-guest') ?>" data-ajax="false">
			<input type="hidden" name="port" value="<?php echo $port ?>"/>
			<input type="hidden" name="host" value="<?php echo $host ?>"/>
			<label for="login"><?php echo _('Enter the login to add') ?></label>
			<input type="text" name="login" id="login" value="" placeholder="login..."/>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?php echo _('Add') ?>"/>
				</div>
				<div class="ui-block-b">
					<a href="#" data-rel="back" data-role="button"><?php echo _('Back') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<div data-role="dialog" id="save">
	<div data-role="header">
		<h1><?php echo _('Save guest list') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?php echo $r->createLinkArgList('../save-guestlist') ?>" data-ajax="false">
			<input type="hidden" name="port" value="<?php echo $port ?>"/>
			<input type="hidden" name="host" value="<?php echo $host ?>"/>
			<label for="filename"><?php echo _('Enter a filename to save the guestlist') ?></label>
			<input type="text" name="filename" id="filename" value=""/>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?php echo _('Save') ?>"/>
				</div>
				<div class="ui-block-b">
					<a href="#" data-rel="back" data-role="button"><?php echo _('Back') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>