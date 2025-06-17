<?php

namespace src;

use src\Forms\EmailForm;

class IndexController
{
    public function action(): string
    {
        $request = new Request();
        $response = new Response();

        $form = new EmailForm();

        if ($request->isPost()) {
            $form->populate($request->post());
            $form->send('smth@mail.ru');
        }

        $response->setRenderObject($form);
        return $response->render();
    }
}