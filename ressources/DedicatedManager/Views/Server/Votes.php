<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?php echo $appURL ?>/edit/update-votes/" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host; ?>"/>
				<input type="hidden" name="port" value="<?php echo $port; ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo _('Call vote ratios') ?>
					</li>
				<?php if(!$isRelay): ?>
					<li data-role="fieldcontain">
						<label for="restart">
							<strong><?php echo _('Restart map'); ?></strong><br/>
							<i><?php echo _('Ratio in % to reach to restart the map'); ?></i>
						</label>
						<input type="range" id="restart" name="ratios[RestartMap]" value="<?php echo $ratios['RestartMap'] ?: $ratios['*']; ?>" min="-1" max="100" step="1" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="next">
							<strong><?php echo _('Next map'); ?></strong><br/>
							<i><?php echo _('Ratio in % to reach to skip the map'); ?></i>
						</label>
						<input type="range" id="next" name="ratios[NextMap]" value="<?php echo $ratios['NextMap'] ?: $ratios['*']; ?>" min="-1" max="100" step="1" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="autobalance">
							<strong><?php echo _('Team balance'); ?></strong><br/>
							<i><?php echo _('Ratio in % to reach to balance teams'); ?></i>
						</label>
						<input type="range" id="autobalance" name="ratios[AutoTeamBalance]" value="<?php echo $ratios['AutoTeamBalance'] ?: $ratios['*']; ?>" min="-1" max="100" step="1" data-highlight="true"/>
					</li>
				<?php endif; ?>
					<li data-role="fieldcontain">
						<label for="kick">
							<strong><?php echo _('Kick player'); ?></strong><br/>
							<i><?php echo _('Ratio in % to reach to kick a player'); ?></i>
						</label>
						<input type="range" id="kick" name="ratios[Kick]" value="<?php echo $ratios['Kick'] ?: $ratios['*']; ?>" min="-1" max="100" step="1" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="ban">
							<strong><?php echo _('Ban player'); ?></strong><br/>
							<i><?php echo _('Ratio in % to reach to ban a player'); ?></i>
						</label>
						<input type="range" id="ban" name="ratios[Ban]" value="<?php echo $ratios['Ban'] ? : $ratios['*']; ?>" min="-1" max="100" step="1" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<label for="default">
							<strong><?php echo _('Default ratio'); ?></strong><br/>
							<i><?php echo _('Ratio in % to reach for any other votes'); ?></i>
						</label>
						<input type="range" id="default" name="ratios[*]" value="<?php echo $ratios['*']; ?>" min="-1" max="100" step="1" data-highlight="true"/>
					</li>
					<li data-role="fieldcontain">
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<input type="reset" value="<?php echo _('Restore') ?>" data-icon="refresh"/>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?php echo _('Apply') ?>" data-theme="b" data-icon="check"/>
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