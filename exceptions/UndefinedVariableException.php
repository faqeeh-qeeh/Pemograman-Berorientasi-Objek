<?php  

// Exception untuk menanganai error variabel yang tidak terdefinisi  
class UndefinedVariableException extends Exception {  
    public function __construct($message = "Undefined variable", $code = 0, Exception $previous = null) {  
        parent::__construct($message, $code, $previous);  
    }  

    public function errorMessage() {  
        return "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b>";  
    }  
}  

// Fungsi untuk mendemonstrasikan error undefined variable  
function checkVariable($var) {  
    if (!isset($var)) {  
        throw new UndefinedVariableException("Variable is not defined.");  
    }  
    return $var;  
}  

try {  
    // Simulasi variabel yang tidak terdefinisi  
    // Uncomment berikut untuk menguji secara terpisah  
    // $testVariable = "This variable is defined";   
    echo checkVariable($testVariable);  
} catch (UndefinedVariableException $e) {  
    // Menangani error dan menampilkan pesan error  
    echo $e->errorMessage();  
} catch (Exception $e) {  
    // Menangani exception umum  
    echo "An unexpected error occurred: " . $e->getMessage();  
}  

?>