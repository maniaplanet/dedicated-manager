<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<form action="<?= $r->createLinkArgList('../set-teams') ?>" method="get" data-ajax="false">
			<input type="hidden" name="hostname" value="<?= $hostname ?>"/>
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?= _('Team 1') ?></li>
				<li data-role="fieldcontain">
					<label for="name1"><?= _('Name:') ?></label>
					<input type="text" id="name1" name="team1[name]" value="Blue"/>
				</li>
				<li data-role="fieldcontain">
					<label for="country1"><?= _('Country:') ?></label>
					<input type="text" id="country1" name="team1[country]" value="World|France"/>
				</li>
				<li data-role="fieldcontain">
					<label for="color1"><?= _('Color:') ?></label>
					<input type="text" id="color1" name="team1[color]" value="0.66" data-role="huepicker"/>
				</li>
				<li data-role="list-divider"><?= _('Team 2') ?></li>
				<li data-role="fieldcontain">
					<label for="name2"><?= _('Name:') ?></label>
					<input type="text" id="name2" name="team2[name]" value="Red"/>
				</li>
				<li data-role="fieldcontain">
					<label for="country2"><?= _('Country:') ?></label>
					<input type="text" id="country2" name="team2[country]" value="World|France"/>
				</li>
				<li data-role="fieldcontain">
					<label for="color2"><?= _('Color:') ?></label>
					<input id="color2" type="text" name="team2[color]" value="0" data-role="huepicker"/>
				</li>
			</ul>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" value="<?=_('Restore')?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" value="<?=_('Apply') ?>"/>
				</div>
			</div>
		</form>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>