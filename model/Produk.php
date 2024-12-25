<?php

class Produk {

    private $connection;
    private $tableName = "produk";

    public $id;
    public $namaproduk;
    public $hargajual;
    public $hargabeli;
    public $stok;
    public $deskripsi;

    public function __construct($database) {
        $this->connection = $database;
    }    

    public function read(): PDOStatement {
        $query = "SELECT * FROM {$this->tableName} ORDER BY id ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement;
    }

    public function readById(): void {
        $query = "SELECT * FROM {$this->tableName} WHERE id = ?";
        $statement = $this->connection->prepare($query);

        $statement->bindParam(1, $this->id);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $this->namaproduk = $row["nama_produk"];
        $this->hargajual = $row["harga_jual"];
        $this->hargabeli = $row["harga_beli"];
        $this->stok = $row["stok"];
        $this->deskripsi = $row["deskripsi"];
    }

    public function create() {
        $query = "INSERT INTO {$this->tableName} SET nama_produk = :namaproduk, harga_jual = :hargajual, harga_beli = :hargabeli, stok = :stok, deskripsi = :deskripsi";

        $statement = $this->connection->prepare($query);

        $statement->bindParam(":namaproduk", $this->namaproduk);
        $statement->bindParam(":hargajual", $this->hargajual);
        $statement->bindParam(":hargabeli", $this->hargabeli);
        $statement->bindParam(":stok", $this->stok);
        $statement->bindParam(":deskripsi", $this->deskripsi);
        
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

}