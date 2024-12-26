<?php  

interface ProdukInterface {  
    public function create(): bool;  
    public function read(): PDOStatement;  
    public function readById(): void;
    public function update(): bool;
}  