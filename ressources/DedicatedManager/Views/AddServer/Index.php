<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page" id="content">
	<?php echo DedicatedManager\Helpers\Header::save() ?>
	<?php echo DedicatedManager\Helpers\Box\Box::detect(); ?>
    <div data-role="content">
		<form name="config" action="<?php echo $r->createLinkArgList('../add') ?>" method="get" data-ajax="false">
				<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?php echo _('XML-RPC Configuration of your server') ?></li>
					<li data-role="fieldcontain">
						<label for="rpcHost">
							<strong><?php echo _('Server address') ?></strong><br/>
							<i><?php echo _('The IP address of your game server') ?></i>
						</label>
						<input type="text" name="rpcHost" id="rpcHost" value="127.0.0.1"/>
					</li>
					<li data-role="fieldcontain">
						<label for="rpcPort">
							<strong><?php echo _('XML-RPC port') ?></strong><br/>
							<i><?php echo _('The xml-rpc port displayed in the console at launch') ?></i>
						</label>
						<input type="text" name="rpcPort" id="rpcPort" value="5000"/>
					</li>
					<li data-role="fieldcontain">
						<label for="rpcPassword">
							<strong><?php echo _('SuperAdmin password') ?></strong><br/>
							<i><?php echo _('The SuperAdmin password you gave in the config file') ?></i>
						</label>
						<input type="text" name="rpcPassword" id="rpcPassword" value="SuperAdmin"/>
					</li>
				</ul>
				<div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="reset" id="reset" value="<?php echo _('Restore') ?>"/>
				</div>
				<div class="ui-block-b">
					<input type="submit" id="submit" value="<?php echo _('Add') ?>" data-theme="b"/>
				</div>
			</div>
		</form>
	</div>
</div>
<?php require __DIR__.'/../Footer.php' ?>
