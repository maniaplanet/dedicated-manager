<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
$c = \DedicatedManager\Config::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form method="get" action="<?php echo $r->createLinkArgList('../stop-controllers') ?>" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Running ingame controllers') ?>
					</li>
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php if(count($controllers)): ?>
							<?php foreach($controllers as $controller): ?>
								<?php $id = uniqid('controller-', true); ?>
								<label for="<?php echo $id; ?>"><?php echo $controller; ?></label>
								<input type="checkbox" name="controllers[]" id="<?php echo $id; ?>" value="<?php echo $controller; ?>"/>
							<?php endforeach; ?>
						<?php else: ?>
							<strong><?php echo _('It seems there is not any running ingame controller'); ?></strong>
						<?php endif; ?>
						</fieldset>
					</li>
					<li>
						<input type="submit" value="<?php echo _('Stop') ?>" data-icon="delete" <?php echo count($controllers) ? '' : 'disabled="disabled"' ?>/>
					</li>
				</ul>
			</form>
			<ul data-role="listview" data-inset="true">
			<?php if($c->manialivePath && !$manialiveStarted): ?>
				<li data-icon="plus" data-theme="d">
					<a href="<?php echo htmlentities($r->createLinkArgList('/manialive', 'host', 'port'), ENT_QUOTES, 'UTF-8') ?>" data-ajax="false">
						<?php echo _('Start ManiaLive'); ?>
					</a>
				</li>
			<?php endif; ?>
			</ul>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>