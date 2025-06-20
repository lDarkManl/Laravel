<form method="POST">
    <?php /** @var TYPE_NAME $form */
    foreach ($form->getFields() as $field): ?>
        <?= $field->render() ?>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>