<?php
class Database {
    private $host = "localhost";
    private $databaseName = "FotoCopy_PBO";
    private $userName = "root";
    private $password = "";
    public $connection;

    public function getConnection() {
        $this->connection = null;
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->databaseName}",
                $this->userName,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $exception) {
            error_log("Database Connection Error: " . $exception->getMessage());
            throw new Exception("Database connection failed");
        }
        return $this->connection;
    }
}


// CREATE TABLE produk (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     nama_produk VARCHAR(255) NOT NULL,
//     harga_jual INT NOT NULL,
//     harga_beli INT NOT NULL,
//     stok INT NOT NULL,
//     deskripsi TEXT
// );