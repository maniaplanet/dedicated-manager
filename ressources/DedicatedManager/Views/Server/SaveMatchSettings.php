<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?php echo $r->createLinkArgList('../do-save-match-settings') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="fieldcontain">
						<label for="filename">
							<strong><?php echo _('Enter the filename')?></strong><br/>
							<i><?php echo _('This filename could be use when you will restart the server')?></i>
						</label>
						<input type="text" id="filename" name="filename" value="<?php echo ManiaLib\Utils\Formatting::stripStyles($options->name) ?>"/>
					</li>
					<li>
						<input type="submit" name="insert" value="<?php echo _('Save match settings') ?>"/>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>