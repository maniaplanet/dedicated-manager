<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?php echo $r->createLinkArgList('../set-commands') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>" />
				<input type="hidden" name="port" value="<?php echo $port ?>" />
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo sprintf(_('%s rules'), $gameMode) ?>
					</li>
					<?php foreach($matchCommands as $command): ?>
					<li data-role="fieldcontain">
						<?php if($command->type == 'boolean'): ?>
								<fieldset data-role="controlgroup">
									<legend>
										<strong><?php echo $command->name ?><br/>
										<i><?php echo $command->description ?></i>
									</legend>
									<select id="<?php echo $command->name ?>" name="commands[<?php echo $command->name ?>]" data-role="slider">
										<option value="0" selected="selected"><?php echo _('No') ?></option>
										<option value="1" ><?php echo _('Yes') ?></option>
									</select>
								</fieldset>
							<?php else: ?>
								<label for="<?php echo $command->name ?>">
									<strong><?php echo $command->name ?></strong><br/>
									<i><?php echo $command->description ?></i>
								</label>
								<input type="text" value="<?php echo $command->default ?>" id="<?php echo $command->name ?>" name="commands[<?php echo $command->name ?>]"/>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
					<li>
						<fieldset class="ui-grid-a">
							<div class="ui-block-a">
								<input type="reset" value="<?php echo _('Cancel') ?>" data-icon="refresh"/>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?php echo _('Set Commands') ?>" data-theme="b" data-icon="check"/>
							</div>
						</fieldset>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>