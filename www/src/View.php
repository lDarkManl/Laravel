<?php

namespace src;

class View {

    protected string $tplName;

    protected array $params = [];

    public function setTplName(string $tplName): void
    {
        $this->tplName = $tplName;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function renderTemplate(): string
    {
        ob_start();
        extract($this->params);
        require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $this->tplName . '.tpl.php';
        return ob_get_clean();

    }
}
