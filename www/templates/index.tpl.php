<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php else: ?>
    <?php /** @var TYPE_NAME $tables */
    foreach ($tables as $table): ?>
        <a href="table.php?table=<?=$table?>"><?=$table?></a>
    <?php endforeach; ?>
<?php endif; ?>
