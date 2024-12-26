<?php  

// Exception untuk menanganai error indeks array yang tidak valid  
class InvalidArrayIndexException extends Exception {  
    public function __construct($message = "Invalid array index", $code = 0, Exception $previous = null) {  
        parent::__construct($message, $code, $previous);  
    }  

    public function errorMessage() {  
        return "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b>";  
    }  
}  

// Fungsi untuk memeriksa indeks array  
function checkArrayIndex($array, $index) {  
    if (!isset($array[$index])) {  
        throw new InvalidArrayIndexException("Index {$index} is not valid for the given array.");  
    }  
    return $array[$index];  
}  

try {  
    // Contoh array  
    $testArray = ["Apple", "Banana", "Cherry"];  
    // Mencoba mengakses indeks yang tidak valid  
    $index = 3; // Indeks yang tidak ada  
    echo checkArrayIndex($testArray, $index); // Ini akan memicu pengecualian  
} catch (InvalidArrayIndexException $e) {  
    // Menangani error indeks array tidak valid  
    echo $e->errorMessage();  
} catch (Exception $e) {  
    // Menangani exception umum  
    echo "An unexpected error occurred: " . $e->getMessage();  
}  

?>