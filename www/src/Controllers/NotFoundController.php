<?php
namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Request;
use src\Metadatas\MetadataManager;
use src\View;

class NotFoundController
{
    public function get()
    {
        $view = new View();
        $view->setTplName('notFound');

        return $view;
    }
}