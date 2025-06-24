<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use src\Controllers\IndexController;
use src\QueryBuilders\Requests;
try {
    $controller = new IndexController();
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit;
}
$view = $controller->action();

?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"  rel="stylesheet">
    <title>Таблицы</title>
</head>
<body>

<h1>Таблицы</h1>

<div id="table-container">
    <?=$view->renderIndex() ?>
</div>

</body>
</html>