<label for="<?= /** @var TYPE_NAME $field */
$field->getName() ?>" class="form-label"><?= $field->label ?></label>
<select name="<?= $field->getName() ?>" id="<?= $field->getName() ?>" class="form-control">
    <?php foreach ($field->getOptions() as $id => $name): ?>
        <option value="<?= $id ?>" <?= $id === $field->getValue() ? 'selected' : '' ?>>
            <?=$name ?>
        </option>
    <?php endforeach; ?>
</select>