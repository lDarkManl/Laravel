<?php

namespace src;

class View {
    const TPL_TABLE = 'table';

    protected array $params = [];

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function renderTable()
    {
        return $this->renderTemplate(self::TPL_TABLE, $this->params);
    }

    protected function renderTemplate($tplFile, $data = []): string
    {
        ob_start();
        extract($data);
        require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $tplFile . '.php';
        return ob_get_clean();

    }
}
