<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

class ValidateLength implements \classes\interfaces\ValidatorInterface{

	protected $maxLen;

	public function __construct($maxLen){
		$this->maxLen = $maxLen;
	}

	public function validate($field): bool{
		if (strlen($field) > $this->maxLen) return false;
		return true;
	}

	public function showError(): string{
		return 'Длина слишком большая';
	}
}

