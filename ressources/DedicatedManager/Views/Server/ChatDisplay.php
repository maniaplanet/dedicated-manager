<?php foreach($chat as $line): ?>
	<?php echo ManiaLib\Utils\StyleParser::toHtml($line); ?><br/>
<?php endforeach; ?>