<?php
require __DIR__.'/../Header.php';
?>
<div data-role="page">
	<?= DedicatedManager\Helpers\Header::save() ?>
	<?= DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?= $appURL ?>/manage/delete" method="get" data-ajax="false">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider">
					<?= _('Select files you want to delete') ?>
				</li>
				<li data-role="fieldcontain">
					<label for="configFiles"><?= _('Your server config files') ?></label>
					<select id="configFiles" name="configFiles[]" multiple="multiple" data-native-menu="false">
						<option><?= _('Select one...') ?></option>
						<?php foreach($configFiles as $configFile): ?>
							<option value="<?= $configFile ?>"><?= $configFile ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<li data-role="fieldcontain">
					<label for="matchFiles"><?= _('Your match settings files') ?></label>
					<select id="matchFiles" name="matchFiles[]" multiple="multiple" data-native-menu="false">
						<option><?= _('Select one...') ?></option>
						<?php foreach($matchFiles as $matchFile): ?>
							<option value="<?= $matchFile ?>"><?= $matchFile ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<li data-role="fieldcontain">
					<input type="submit" value="<?= _('Delete selected Files') ?>"/>
				</li>
			</ul>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>