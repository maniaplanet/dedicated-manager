<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?= $r->createLinkArgList('../action-managers') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= _('Select managers to interact with them') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if($managers): ?>
							<?php foreach($managers as $manager): ?>
								<label for="manager-<?= $manager ?>"><?= $manager ?></label>
								<input type="checkbox" name="managers[]" id="manager-<?= $manager ?>" value="<?= $manager ?>" />
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?= _('There is no manager for this server.') ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<input type="submit" name="revoke[]" value="<?= _('Revoke') ?>" data-icon="delete" <?= !$managers ? 'disabled="disabled"' : '' ?>/>
					</li>
				</ul>
			</form>
			<form method="get" action="<?= $r->createLinkArgList('../add-manager') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?= $host ?>"/>
				<input type="hidden" name="port" value="<?= $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= _('Add a manager for this server') ?>
					</li>
					<li data-role="fieldcontain">
						<label for="managerLogin">
							<strong>Login</strong>
						</label>
						<?= DedicatedManager\Helpers\Input::text('login', 'managerLogin', '') ?>
					</li>
					<li data-role="fieldcontain">
						<input type="submit" name="add" value="<?= _('Add') ?>" data-icon="plus"/>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>