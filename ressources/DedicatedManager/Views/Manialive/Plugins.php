<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php
	$header = \DedicatedManager\Helpers\Header::getInstance();
	$header->rightText = _('Back to configuration');
	$header->rightIcon = 'back';
	$header->rightLink = $backLink;
	echo DedicatedManager\Helpers\Header::save()
	?>
    <div class="ui-bar ui-bar-b">
		<h2><?php
	echo sprintf(_('Step %d on %d'), 2, 4)
	?></h2><br/>
		<h3><?php echo _('Plugin selection of manialive') ?></h3><br/>
		<?php echo _('During the whole process, feel free to leave default values.') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="config" action="<?php echo $r->createLinkArgList('../set-plugins') ?>" method="get" data-ajax="false">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?php echo _('Select your plugins') ?></li>
				<li data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<?php foreach($plugins as $plugin): ?>
							<?php $id = uniqid() ?>
							<label for="<?php echo $id ?>">
								<strong><?php echo $plugin ?></strong>
							</label>
							<input type="checkbox" id="<?php echo $id ?>" name="plugins[<?php echo $plugin ?>]" />
						<?php endforeach; ?>
					</fieldset>
				</li>
			</ul>
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
