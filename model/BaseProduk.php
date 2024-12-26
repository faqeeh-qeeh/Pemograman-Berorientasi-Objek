<?php  

abstract class BaseProduk {  
    protected $connection;  
    protected $tableName = "produk";  

    public $id;  
    public $nama_produk;  
    public $harga_jual;  
    public $harga_beli;  
    public $stok;  
    public $deskripsi;  
    
    public function __construct($database) {  
        $this->connection = $database;  
    }  

    abstract public function create(): bool;  
    abstract public function read(): PDOStatement;  
    abstract public function readById(): void;  

}