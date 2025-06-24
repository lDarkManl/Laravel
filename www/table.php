<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Controllers\TableController;

try {
    $controller = new TableController();
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit;
}
try {
    $view = $controller->action();
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit;
}


?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"  rel="stylesheet">
    <title>Заявки</title>
</head>
<body>

<h1>Заявки</h1>


<div id="table-container">
    <?=$view->renderTable(); ?>
</div>



</body>
</html>