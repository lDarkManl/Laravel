<form method="post">
    <?php foreach ($form->fields as $field): ?>
        <?=$field->render(); ?>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>