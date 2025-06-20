<label for="<?= /** @var TYPE_NAME $field */
$field->getName() ?>"><?= $field->getLabel() ?></label>
<input type="<?= $field->getType() ?>" name="<?= $field->getName() ?>" id="<?= $field->getName() ?>" value="<?=$field->getValue() ?>" class="form-control">