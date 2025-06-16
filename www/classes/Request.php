<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';
       
class Request implements \classes\interfaces\RequestInterface{

    protected $getData;
    protected $postData;
    protected $serverData;


    public function __construct(){
        $this->getData = $_GET;
        $this->postData = $this->cleanPost($_POST);
        $this->serverData = $_SERVER;
    }

	public function method(): string{
		return $this->serverData['REQUEST_METHOD'];
	}

    public function url(): string{
    	return $this->serverData['HTTP_HOST'] . $this->serverData['REQUEST_URI'];
    }

    public function get(string $key = null, $default = ''): string{
    	if ($key === null) {
            return http_build_query($this->getData);
        }
        return isset($this->getData[$key]) ? (string)$this->getData[$key] : $default;
    }
    
    public function post(string $key = null, $default = ''): string{
    	if ($key === null) {
            return http_build_query($this->postData);
        }
        return isset($this->postData[$key]) ? (string)$this->postData[$key] : $default;
    }

    public function server(string $key = null, $default = ''){
        if ($key === null) {
            return http_build_query($this->serverData);
        }
        return isset($this->serverData[$key]) ? (string)$this->serverData[$key] : $default;
    }

    protected function cleanPost($data){
        if (is_array($data)) {
            $cleaned = [];
            foreach ($data as $key => $value) {
                $cleaned[$key] = $this->cleanPost($value);
            }
            return $cleaned;
        }
        return trim(htmlspecialchars($data, ENT_QUOTES));
    }


    public function all(): array{
    	return array_merge($this->getData, $this->postData, $this->serverData);
    }

    public function isPost(): bool{
        return $this->method() === 'POST';
    }
}



