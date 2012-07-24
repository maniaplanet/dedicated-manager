<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?php echo $r->createLinkArgList('../set-rules') ?>" method="get" data-ajax="false">
				<input type="hidden" name="host" value="<?php echo $host ?>" />
				<input type="hidden" name="port" value="<?php echo $port ?>" />
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?php echo sprintf(_('%s rules'), $gameMode) ?>
					</li>
					<?php foreach($matchRules as $rule): ?>
						<li data-role="fieldcontain">
						<?php switch($rule->inputType):
							case 'radio': ?>
								<fieldset data-role="controlgroup">
									<legend>
										<strong><?php echo $rule->label ?><br/>
											<i><?php echo $rule->documentation ?></i>
									</legend>
									<?php foreach($rule->inputValues as $inputValue): ?>
										<?php $id = uniqid() ?>
										<input type="radio" name="<?php echo 'rules['.$rule->name.']' ?>" value="<?php echo $inputValue['value'] ?>" id="<?php echo $id ?>"
												<?php echo $inputValue['value'] == $rule->value ? 'checked="checked"' : '' ?>/>
										<label for = "<?php echo $id ?>"><?php echo $inputValue['label'] ?></label>
									<?php endforeach; ?>
								</fieldset>
								<?php break; ?>
							<?php case 'select': ?>
								<?php $id = uniqid(); ?>
								<label for="<?php echo $id ?>">
									<strong><?php echo $rule->label ?></strong><br/>
									<i><?php echo $rule->documentation ?></i>
								</label>
								<select name="<?php echo 'rules['.$rule->name.']' ?>">
								<?php foreach($rule->inputValues as $inputValue): ?>
									<?php $checked = is_array($rule->value) ? in_array($inputValue['value'], $rule->value) : $inputValue['value'] == $rule->value; ?>
									<option value="<?php echo $inputValue['value'] ?>" <?php echo $checked ? 'selected="selected"' : '' ?>><?php echo $inputValue['label'] ?></option>
								<?php endforeach; ?>
								</select>
								<?php break; ?>
							<?php case 'checkbox': ?>
								<fieldset data-role="controlgroup">
									<legend>
										<strong><?php echo $rule->label ?></strong><br/>
										<i><?php echo $rule->documentation ?></i>
									</legend>
									<?php foreach($rule->inputValues as $inputValue): ?>
										<?php $id = uniqid(); ?>
										<?php $checked = is_array($rule->value) ? in_array($inputValue['value'], $rule->value) : $inputValue['value'] == $rule->value; ?>
										<input type="checkbox" name="<?php echo 'rules['.$rule->name.']' ?>" value="<?php echo $inputValue['value'] ?>" id="<?php echo $id ?>"
												<?php echo $checked ? 'checked="checked"' : '' ?>/>
										<label for = "<?php echo $id ?>"><?php echo $inputValue['label'] ?></label>
									<?php endforeach; ?>
								</fieldset>
								<?php break; ?>
							<?php case 'text': ?>
								<?php $id = uniqid(); ?>
								<label for="<?php echo $id ?>">
									<strong><?php echo $rule->label ?></strong><br/>
									<i><?php echo $rule->documentation ?></i>
								</label>
								<input type="text" value="<?php echo $rule->value ?>" id="<?php echo $id ?>" name="rules[<?php echo $rule->name ?>]"/>
						<?php endswitch; ?>
						</li>
					<?php endforeach; ?>
					<li>
						<fieldset class="ui-grid-a">
							<div class="ui-block-a">
								<input type="reset" value="<?php echo _('Cancel') ?>" data-icon="refresh"/>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?php echo _('Change Settings') ?>" data-theme="b" data-icon="check"/>
							</div>
						</fieldset>
					</li>
				</ul>
			</form>
		</div>
		<?php require __DIR__.'/Navigation.php'; ?>
	</div>
</div>
<?php require __DIR__.'/../Footer.php'; ?>