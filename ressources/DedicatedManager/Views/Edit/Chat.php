<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<ul data-role="listview" data-icon="none" data-inset="true">
			<li data-role="list-divider"><?= _('Chat') ?></li>
			<?php foreach($chat as $line): ?>
				<li><?= ManiaLib\Utils\StyleParser::toHtml($line) ?></li>
			<?php endforeach; ?>
		</ul>
		<form action="<?= $r->createLinkArgList('../send-message') ?>" method="get" data-ajax="false">
			<input type="hidden" name="hostname" value="<?= $hostname ?>"/>
			<input type="hidden" name="port" value="<?= $port ?>"/>
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider"><?= _('Send a message') ?></li>
				<li data-role="fieldcontain">
					<label for="message"><?= _('Message') ?></label>
					<input type="text" id="message" name="message" value="" placeholder="<?= _('Enter your message here...') ?>"/>
				</li>
				<li data-role="fieldcontain">
					<label for="receiver"><?= _('Receiver') ?></label>
					<select id="receiver" name="receiver[]" multiple="multiple" data-native-menu="false">
						<option><?= _('All players') ?></option>
						<?php foreach($players as $player): ?>
							<option value="<?= $player->login ?>"><?= ManiaLib\Utils\StyleParser::toHtml($player->nickName) ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<li data-role="fieldcontain">
					<input type="submit" value="<?= _('Send') ?>"/>
				</li>
			</ul>
		</form>
		<?php //var_dump($chat) ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>