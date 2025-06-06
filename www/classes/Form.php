<?php
namespace classes;
//Класс для работы с формой (создание и отправка)
class Form
{
    protected $formData;

    public function __construct()
    {
        $this->formData = [];
    }

    public function addInput($name, $type, $placeholder, $required = false)
    {
        $this->formData[] = [$name, $type, $placeholder, $required];
    }

    public function getHtml()
    {
        $html = '<form method="POST" class="ms-1">';

        foreach ($this->formData as $inputData) {
            list($name, $type, $placeholder, $required) = $inputData;
            $requiredAttr = $required ? 'required' : '';

            $html .= <<<HTML
                <div class="input-group mb-3 mt-3 flex-column">
                    <label class="form-label">$placeholder</label>
                    <input name="$name" type="$type" placeholder="$placeholder" class="form-control w-25" $requiredAttr>
                </div>
            HTML;
        }

            $html .= <<<HTML
                <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            HTML;

        return $html;
    }


    public function sendForm($email, $data)   
    {   $message = '';
        foreach ($data as $key => $value) {
            $message .= "$key: $value\n";
        }

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        return mail($email, "Данные формы", $message, $headers);
    }
}
?>