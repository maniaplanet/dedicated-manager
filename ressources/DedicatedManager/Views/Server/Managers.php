<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?php echo $r->createLinkArgList('../do-managers') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Select managers to interact with them') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if($managers): ?>
							<?php foreach($managers as $manager): ?>
								<label for="manager-<?php echo $manager ?>"><?php echo $manager ?></label>
								<input type="checkbox" name="managers[]" id="manager-<?php echo $manager ?>" value="<?php echo $manager ?>" />
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?php echo _('There is no manager for this server.') ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li data-role="fieldcontain">
						<input type="submit" name="revoke[]" value="<?php echo _('Revoke') ?>" data-icon="delete" <?php echo !$managers ? 'disabled="disabled"' : '' ?>/>
					</li>
				</ul>
			</form>
			<form method="get" action="<?php echo $r->createLinkArgList('../add-manager') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Add a manager for this server') ?>
					</li>
					<li data-role="fieldcontain">
						<label for="managerLogin">
							<strong>Login</strong>
						</label>
						<?php echo DedicatedManager\Helpers\Input::text('login', 'managerLogin', '') ?>
					</li>
					<li data-role="fieldcontain">
						<input type="submit" name="add" value="<?php echo _('Add') ?>" data-icon="plus"/>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>