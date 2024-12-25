<?php
class Produk {
    private $connection;
    private $tableName = "produk";

    public $id;
    public $nama_produk;
    public $harga_jual;
    public $harga_beli;
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

        if ($row) {
            $this->nama_produk = $row["nama_produk"];
            $this->harga_jual = $row["harga_jual"];
            $this->harga_beli = $row["harga_beli"];
            $this->stok = $row["stok"];
            $this->deskripsi = $row["deskripsi"];
        }
    }

    public function create(): bool {
        $query = "INSERT INTO {$this->tableName} (nama_produk, harga_jual, harga_beli, stok, deskripsi) VALUES (:nama_produk, :harga_jual, :harga_beli, :stok, :deskripsi)";
        $statement = $this->connection->prepare($query);

        $statement->bindParam(":nama_produk", $this->nama_produk);
        $statement->bindParam(":harga_jual", $this->harga_jual);
        $statement->bindParam(":harga_beli", $this->harga_beli);
        $statement->bindParam(":stok", $this->stok);
        $statement->bindParam(":deskripsi", $this->deskripsi);

        return $statement->execute();
    }
}