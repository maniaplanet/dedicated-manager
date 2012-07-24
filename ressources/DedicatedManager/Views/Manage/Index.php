<?php
require __DIR__.'/../Header.php';
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
	<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?php echo $appURL ?>/manage/delete" method="get" data-ajax="false">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider">
					<?php echo _('Select files you want to delete') ?>
				</li>
				<li data-role="fieldcontain">
					<label for="configFiles"><?php echo _('Your server config files') ?></label>
					<select id="configFiles" name="configFiles[]" multiple="multiple" data-native-menu="false">
						<option><?php echo _('Select one...') ?></option>
						<?php foreach($configFiles as $configFile): ?>
							<option value="<?php echo $configFile ?>"><?php echo $configFile ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<li data-role="fieldcontain">
					<label for="matchFiles"><?php echo _('Your match settings files') ?></label>
					<select id="matchFiles" name="matchFiles[]" multiple="multiple" data-native-menu="false">
						<option><?php echo _('Select one...') ?></option>
						<?php foreach($matchFiles as $matchFile): ?>
							<option value="<?php echo $matchFile ?>"><?php echo $matchFile ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<li data-role="fieldcontain">
					<input type="submit" value="<?php echo _('Delete selected Files') ?>"/>
				</li>
			</ul>
		</form>
    </div>
</div>

<?php require __DIR__.'/../Footer.php' ?>