<?php
require __DIR__.'/../Header.php';
$r = ManiaLib\Application\Request::getInstance();
?>
<div data-role="page">
	<?php require __DIR__.'/Header.php'; ?>
	<div data-role="content">
		<div class="content-primary">
			<form action="<?= $r->createLinkArgList('../set-rules') ?>" method="get" data-ajax="false">
				<input type="hidden" name="hostname" value="<?= $hostname ?>" />
				<input type="hidden" name="port" value="<?= $port ?>" />
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">
						<?= sprintf(_('%s rules'), $gameMode) ?>
					</li>
					<?php foreach($matchRules as $rule): ?>
						<li data-role="fieldcontain">
						<?php switch($rule->inputType):
							case 'radio': ?>
								<fieldset data-role="controlgroup">
									<legend>
										<strong><?= $rule->label ?><br/>
											<i><?= $rule->documentation ?></i>
									</legend>
									<?php foreach($rule->inputValues as $inputValue): ?>
										<?php $id = uniqid() ?>
										<input type="radio" name="<?= 'rules['.$rule->name.']' ?>" value="<?= $inputValue['value'] ?>" id="<?= $id ?>"
												<?= $inputValue['value'] == $rule->value ? 'checked="checked"' : '' ?>/>
										<label for = "<?= $id ?>"><?= $inputValue['label'] ?></label>
									<?php endforeach; ?>
								</fieldset>
								<?php break; ?>
							<?php case 'select': ?>
								<?php $id = uniqid(); ?>
								<label for="<?= $id ?>">
									<strong><?= $rule->label ?></strong><br/>
									<i><?= $rule->documentation ?></i>
								</label>
								<select name="<?= 'rules['.$rule->name.']' ?>">
								<?php foreach($rule->inputValues as $inputValue): ?>
									<?php $checked = is_array($rule->value) ? in_array($inputValue['value'], $rule->value) : $inputValue['value'] == $rule->value; ?>
									<option value="<?= $inputValue['value'] ?>" <?= $checked ? 'selected="selected"' : '' ?>><?= $inputValue['label'] ?></option>
								<?php endforeach; ?>
								</select>
								<?php break; ?>
							<?php case 'checkbox': ?>
								<fieldset data-role="controlgroup">
									<legend>
										<strong><?= $rule->label ?></strong><br/>
										<i><?= $rule->documentation ?></i>
									</legend>
									<?php foreach($rule->inputValues as $inputValue): ?>
										<?php $id = uniqid(); ?>
										<?php $checked = is_array($rule->value) ? in_array($inputValue['value'], $rule->value) : $inputValue['value'] == $rule->value; ?>
										<input type="checkbox" name="<?= 'rules['.$rule->name.']' ?>" value="<?= $inputValue['value'] ?>" id="<?= $id ?>"
												<?= $checked ? 'checked="checked"' : '' ?>/>
										<label for = "<?= $id ?>"><?= $inputValue['label'] ?></label>
									<?php endforeach; ?>
								</fieldset>
								<?php break; ?>
							<?php case 'text': ?>
								<?php $id = uniqid(); ?>
								<label for="<?= $id ?>">
									<strong><?= $rule->label ?></strong><br/>
									<i><?= $rule->documentation ?></i>
								</label>
								<input type="text" value="<?= $rule->value ?>" id="<?= $id ?>" name="rules[<?= $rule->name ?>]"/>
						<?php endswitch; ?>
						</li>
					<?php endforeach; ?>
					<li>
						<fieldset class="ui-grid-a">
							<div class="ui-block-a">
								<input type="reset" value="<?= _('Cancel') ?>" data-icon="refresh"/>
							</div>
							<div class="ui-block-b">
								<input type="submit" value="<?= _('Change Settings') ?>" data-theme="b" data-icon="check"/>
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