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
	echo sprintf(_('Step %d on %d'), 3, 4)
	?></h2><br/>
		<h3><?php echo _('Plugin selection of manialive') ?></h3><br/>
		<?php echo _('During the whole process, feel free to leave default values.') ?>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="config" action="<?php echo $r->createLinkArgList('../set-advanced') ?>" method="get" data-ajax="false">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?php echo _('Enter plugins and other configurations') ?></li>
				<li data-role="fieldcontain">
					<textarea name="advanced"></textarea>
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
