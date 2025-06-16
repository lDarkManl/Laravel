<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use \classes\interfaces\ValidatorInterface;

class ValidateRequired implements ValidatorInterface{

	public function validate($field): bool{
		if (strlen($field) === 0) return false;
		return true;
	}

	public function showError(): string{
		return 'Требуется ввести значение';
	}
}