<div class="ui-li ui-li-static">
	<?php foreach($chat as $line): ?>
		<?php echo ManiaLib\Utils\StyleParser::toHtml($line) ?><br/>
	<?php endforeach; ?>
</div>