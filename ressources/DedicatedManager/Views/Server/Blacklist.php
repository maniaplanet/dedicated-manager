<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?php echo $r->createLinkArgList('../unblacklist') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Current blacklist') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if(count($blackListedPlayers)): ?>
							<?php foreach($blackListedPlayers as $player): ?>
								<label for="<?php echo $player->login ?>"><?php echo $player->login ?></label>
								<input type="checkbox" name="players[]" id="<?php echo $player->login ?>" value="<?php echo $player->login ?>" />
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?php echo _('There is not any blacklisted player'); ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li>
						<div class="<?php echo ($isLocal ? 'ui-grid-d' : 'ui-grid-c'); ?>">
							<div class="ui-block-a">
								<a href="#add" data-role="button" data-icon="plus" data-rel="dialog" data-transition="pop"><?php echo _('Add a player') ?></a>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?php echo _('Remove from list') ?>" data-icon="minus" <?php echo count($blackListedPlayers) ? '' : 'disabled="disabled"' ?>/>
							</div>
							<div class="ui-block-c">
								<a href="<?php echo htmlentities($r->createLinkArgList('../clean-blacklist', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-role="button" data-icon="delete" data-ajax="false"><?php echo _('Clean blacklist') ?></a>
							</div>
						<?php if($isLocal): ?>
							<div class="ui-block-d">
								<a href="#load" data-role="button" data-icon="gear" data-rel="dialog" data-transition="pop"><?php echo _('Load a black list') ?></a>
							</div>
						<?php endif; ?>
							<div class="<?php echo ($isLocal ? 'ui-block-e' : 'ui-block-d') ?>">
								<a href="#save" data-role="button" data-icon="check" data-rel="dialog" data-transition="pop"><?php echo _('Save a save list') ?></a>
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
		<form method="get" action="<?php echo $appURL ?>/server/load-blacklist/" data-ajax="false">
			<input type="hidden" name="port" value="<?php echo $port ?>"/>
			<input type="hidden" name="host" value="<?php echo $host ?>"/>
			<fieldset data-role="controlgroup">
				<legend><?php echo _('Available guestlist') ?></legend>
			<?php if(count($blacklistFiles)): ?>
				<?php foreach($blacklistFiles as $file): ?>
					<input type="radio" name="filename" id="<?php echo $file ?>" value="<?php echo $file ?>"/>
					<label for="<?php echo $file ?>"><?php echo $file ?></label>
				<?php endforeach; ?>
			<?php else: ?>
				<strong><?php echo _('There is no blacklist file available') ?></strong>
			<?php endif; ?>
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
		<h1><?php echo _('Add a player to blacklist') ?></h1>
	</div>
	<div data-role="content">
		<form method="get" action="<?php echo $appURL ?>/server/add-black/" data-ajax="false">
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
		<form method="get" action="<?php echo $appURL ?>/server/save-blacklist/" data-ajax="false">
			<input type="hidden" name="port" value="<?php echo $port ?>"/>
			<input type="hidden" name="host" value="<?php echo $host ?>"/>
			<label for="filename"><?php echo _('Enter a filename to save the blacklist') ?></label>
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