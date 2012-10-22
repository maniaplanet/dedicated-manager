<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php echo DedicatedManager\Helpers\Header::save(); ?>
    <div class="ui-bar ui-bar-b">
		<h2><?php echo sprintf(_('Step %d on %d'), 2, 3); ?></h2><br/>
		<h3><?php echo _('Plugin selection') ?></h3><br/>
    </div>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="config" action="<?php echo $r->createLinkArgList('../set-plugins') ?>" method="get" data-ajax="false" data-role="collapsible-group">
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Select your plugins') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						<?php foreach($plugins as $plugin): ?>
							<?php $id = uniqid('plugin-'); ?>
							<input type="checkbox" id="<?php echo $id; ?>" name="plugins[]" value="<?php echo $plugin; ?>"
								<?php echo in_array($plugin, $config->plugins) ? 'checked="checked"' : ''; ?>/>
							<label for="<?php echo $id; ?>"><?php echo $plugin; ?></label>
						<?php endforeach; ?>
						</fieldset>
					</li>
				</ul>
			</fieldset>
			<fieldset data-role="collapsible" data-collapsed="false" data-theme="b">
				<legend><?php echo _('Configure plugins') ?></legend>
				<ul data-role="listview">
					<li data-role="fieldcontain">
						<textarea name="other"><?php echo $config->__other; ?></textarea>
					</li>
				</ul>
			</fieldset>
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
