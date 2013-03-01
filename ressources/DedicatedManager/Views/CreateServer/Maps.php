<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo sprintf(_('Step %d on %d'), 3, 4) ?></h2><br/>
		<h3><?php echo _('Select the maps ou want to play on.') ?></h3><br/>
		<?php echo _('Click on the maps that you want to include.') ?><br/>
		<?php echo _('The order you selected them is the order you will play them.') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect() ?>
    <div data-role="content">
		<form action="<?php echo $r->createLinkArgList('../set-maps') ?>" data-ajax="false" method="get">
			<label for="randomize"><strong><?php echo _('Randomize map order') ?></strong></label>
			<input type="checkbox" name="randomize" id="randomize" <?= $randomize ? 'checked="checked"' : '' ?>/>
			<?php echo DedicatedManager\Helpers\Files::sortableTree($files, $selected, 'selected') ?>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?php echo _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" id="submit" value="<?php echo _('Next step') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
    </div>
</div>
<?php require __DIR__.'/../Footer.php' ?>