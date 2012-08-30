<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<ul data-role="listview" data-icon="none" data-inset="true">
				<li data-role="list-divider"><?php echo _('Chat') ?></li>
				<li>
					<div id="chat">
					</div>
				</li>
			</ul>
			<form action="<?php echo $r->createLinkArgList('../send-message') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>"/>
				<input type="hidden" name="port" value="<?php echo $port ?>"/>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider"><?php echo _('Send a message') ?></li>
					<li data-role="fieldcontain">
						<label for="message"><?php echo _('Message') ?></label>
						<input type="text" id="message" name="message" value="" placeholder="<?php echo _('Enter your message here...') ?>" required="required"/>
					</li>
					<li data-role="fieldcontain">
						<label for="receiver"><?php echo _('Receiver') ?></label>
						<select id="receiver" name="receiver[]" multiple="multiple" data-native-menu="false">
							<option><?php echo _('All players') ?></option>
							<?php foreach($players as $player): ?>
								<option value="<?php echo $player->login ?>"><?php echo ManiaLib\Utils\StyleParser::toHtml($player->nickName) ?></option>
							<?php endforeach; ?>
						</select>
					</li>
					<li data-role="fieldcontain">
						<input type="submit" value="<?php echo _('Send') ?>"/>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<script type="text/javascript">
	function updateChat() {
		$.get('<?php echo $r->createLink('../chat-display'); ?>').done(function (html) {
			$('#chat').html(html);
		});
	}
	$(document).bind('pageinit', function() {
		updateChat();
		setInterval('updateChat()', 5000);
	});
</script>
<?php require __DIR__.'/../Footer.php'; ?>