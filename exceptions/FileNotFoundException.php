<?php  

class FileNotFoundException extends Exception {  
    public function __construct($message = "File not found", $code = 0, Exception $previous = null) {  
        parent::__construct($message, $code, $previous);  
    }  

    public function errorMessage() {  
        return "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b>";  
    }  
}