<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?php echo $r->createLinkArgList('../set-teams') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo _('Team 1') ?></li>
					<li data-role="fieldcontain">
						<label for="name1"><?php echo _('Name:') ?></label>
						<input type="text" id="name1" name="team1[name]" value="Blue" data-role="maniaplanet-style"/>
					</li>
					<li data-role="fieldcontain">
						<label for="country1"><?php echo _('Country:') ?></label>
						<input type="text" id="country1" name="team1[country]" value="World|France"/>
					</li>
					<li data-role="fieldcontain">
						<label for="color1"><?php echo _('Color:') ?></label>
						<input type="text" id="color1" name="team1[color]" value="0.66" data-role="huepicker"/>
					</li>
					<li data-role="list-divider"><?php echo _('Team 2') ?></li>
					<li data-role="fieldcontain">
						<label for="name2"><?php echo _('Name:') ?></label>
						<input type="text" id="name2" name="team2[name]" value="Red" data-role="maniaplanet-style"/>
					</li>
					<li data-role="fieldcontain">
						<label for="country2"><?php echo _('Country:') ?></label>
						<input type="text" id="country2" name="team2[country]" value="World|France"/>
					</li>
					<li data-role="fieldcontain">
						<label for="color2"><?php echo _('Color:') ?></label>
						<input type="text" id="color2" name="team2[color]" value="0" data-role="huepicker"/>
					</li>
				</ul>
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<input type="reset" value="<?php echo _('Restore'); ?>" data-icon="refresh"/>
					</div>
					<div class="ui-block-b">
						<input type="submit" value="<?php echo _('Apply'); ?>" data-icon="check" data-theme="b"/>
					</div>
				</div>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>