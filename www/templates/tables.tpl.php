<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php else: ?>
    <?php /** @var TYPE_NAME $tables */
    foreach ($tables as $table): ?>
        <?php $url = 'http://' . $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . $table; ?>
        <a href="<?=$url?>"><?=$table?></a>
    <?php endforeach; ?>
<?php endif; ?>
