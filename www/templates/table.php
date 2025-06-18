<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php else: ?>
<h2 class="mb-4">Добавить запись</h2>
<form method="POST" class="mb-5 p-4 border rounded bg-white shadow-sm">
    <div class="mb-3">
        <label for="name" class="form-label">Имя</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Сохранить</button>
</form>
<table class="table table-striped table-bordered table-hover align-middle">
    <tr>
        <?php
        $columns = ['ID', 'Имя', 'Email', 'Статус'];
        $fieldMap = ['ID' => 'id', 'Имя' => 'name', 'Email' => 'email', 'Статус' => 'status_id'];
        foreach ($columns as $col) {
            $field = $fieldMap[$col];
            /** @var TYPE_NAME $sortField */
            /** @var TYPE_NAME $sortOrder */
            $newOrder = ($sortField === $field && $sortOrder === 'ASC') ? 'DESC' : 'ASC';
            echo "<th><a href='?sort={$field}&order={$newOrder}'>{$col}</a></th>";
        }
        ?>
        <th>Действия</th>
    </tr>
    <?php /** @var TYPE_NAME $requests */
    foreach ($requests as $row): ?>
        <tr>
            <td><?=$row['id'] ?></td>
            <td><?=$row['name'] ?></td>
            <td><?=$row['email'] ?></td>
            <td>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="request_id" value="<?=$row['id'] ?>">
                    <select name="status_id" onchange="this.form.submit()">
                        <?php /** @var TYPE_NAME $statuses */
                        foreach ($statuses as $id => $name): ?>
                            <option value="<?=$id ?>" <?= $id === $row['status_id'] ? 'selected' : '' ?>>
                                <?=$name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
            <td>
                <form method="POST" onsubmit="return confirm('Вы уверены?');">
                    <input type="hidden" name="delete_id" value="<?=$row['id'] ?>">
                    <button type="submit">Удалить</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>