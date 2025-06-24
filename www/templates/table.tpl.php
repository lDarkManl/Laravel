<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php else: ?>
    <h2 class="mb-4">Добавить запись</h2>
    <?= /** @var TYPE_NAME $form */
    $form->render() ?>

    <table class="table table-striped table-bordered table-hover align-middle">
        <thead>
        <tr>
            <?php
            /** @var TYPE_NAME $columns */
            foreach ($columns as $column):
                /** @var TYPE_NAME $sortField */
                /** @var TYPE_NAME $sortOrder */
                $newOrder = ($sortField === $column ? ($sortOrder === 'ASC' ? 'DESC' : 'ASC') : 'ASC');
                ?>
                <th>
                    <a href="?table=<?= /** @var TYPE_NAME $table */
                    $table ?>&sort=<?=$column ?>&order=<?= $newOrder ?>&page=<?=$page?>">
                        <?=ucfirst($column) ?>
                    </a>
                </th>
            <?php endforeach; ?>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php /** @var TYPE_NAME $tableData */
        foreach ($tableData as $row): ?>
            <tr>
                <?php foreach ($row as $key => $value): ?>

                    <?php if ($key === 'status_id' && !empty($statuses)): ?>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="row_id" value="<?= $row['id'] ?>">
                                <select name="status_id" onchange="this.form.submit()">
                                    <?php foreach ($statuses as $values): ?>
                                        <option value="<?= $values['id'] ?>" <?= $values['id'] === $value ? 'selected' : '' ?>>
                                            <?=$values['name'] ?>
                                        </option>

                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                    <?php elseif ($key !== 'status_id'): ?>
                        <td><?=$value ?? '' ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>

                <td>
                    <form method="POST" onsubmit="return confirm('Вы уверены?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <a href="?table=<?=$table ?>&sort=<?=$column ?>&order=<?= $sortOrder ?>&page=<?=$page <= 1 ? 1 : $page - 1;?>"">Назад</a>
        <a href="?table=<?=$table ?>&sort=<?=$column ?>&order=<?= $sortOrder ?>&page=<?=$page + 1?>"">Вперед</a>
    </div>
<?php endif; ?>