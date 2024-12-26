<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../config/Database.php";
require_once "../model/Produk.php";
require_once "../model/ProdukSpesifik/ProdukAlatTulis.php";
require_once "../model/ProdukSpesifik/ProdukJasa.php";
require_once "../model/ProdukSpesifik/ProdukDigital.php";

$database = new Database();
$db = $database->getConnection();
$request = $_SERVER['REQUEST_METHOD'];

function sendResponse($code, $response) {
    http_response_code($code);
    echo json_encode($response);
}

function validateId($id) {
    return filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
}

switch ($request) {
    case 'GET':
        if (empty($_GET['id'])) {
            $produk = new Produk($db);
            $statement = $produk->read();
            $num = $statement->rowCount();
            $listProduk = ["records" => []];

            if ($num > 0) {
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $listProduk["records"][] = $row;
                }
                sendResponse(200, $listProduk);
            } else {
                sendResponse(404, ["message" => "Produk tidak ditemukan."]);
            }
        } else {
            $id = $_GET['id'];
            if (!validateId($id)) {
                sendResponse(400, ["message" => "ID tidak valid."]);
                break;
            }

            $produk = new Produk($db);
            $produk->id = $id;
            $produk->readById();

            if (!empty($produk->nama_produk)) {
                sendResponse(200, [
                    "id" => $produk->id,
                    "nama_produk" => $produk->nama_produk,
                    "harga_jual" => $produk->harga_jual,
                    "harga_beli" => $produk->harga_beli,
                    "stok" => $produk->stok,
                    "deskripsi" => $produk->deskripsi
                ]);
            } else {
                sendResponse(404, ["message" => "Produk tidak ditemukan."]);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            !isset($data['nama_produk'], $data['harga_jual'], $data['harga_beli'], $data['stok'], $data['deskripsi'], $data['kategori'])
        ) {
            sendResponse(400, ["status" => "error", "message" => "Data tidak lengkap."]);
            break;
        }

        $kategori = $data['kategori'];
        $produk = null;

        if ($kategori === "Alat Tulis") {
            $produk = new ProdukAlatTulis($db);
        } elseif ($kategori === "Jasa") {
            $produk = new ProdukJasa($db);
        } elseif ($kategori === "Digital") {
            $produk = new ProdukDigital($db, $data['formatFile'] ?? '', $data['kapasitas'] ?? 0);
        } else {
            sendResponse(400, ["status" => "error", "message" => "Kategori tidak valid."]);
            break;
        }

        $produk->nama_produk = $data['nama_produk'];
        $produk->harga_jual = $data['harga_jual'];
        $produk->harga_beli = $data['harga_beli'];
        $produk->stok = $data['stok'];
        $produk->deskripsi = $data['deskripsi'];

        if ($produk->create()) {
            sendResponse(201, ["status" => "success", "message" => "Produk berhasil ditambahkan."]);
        } else {
            sendResponse(500, ["status" => "error", "message" => "Gagal menambahkan produk."]);
        }
        break;


        case 'PUT':  
            $data = json_decode(file_get_contents("php://input"), true);  
            
            if (!isset($data['id'], $data['nama_produk'], $data['harga_jual'], $data['harga_beli'], $data['stok'], $data['deskripsi'], $data['kategori'])) {  
                sendResponse(400, ["status" => "error", "message" => "Data tidak lengkap."]);  
                break;  
            }  
        
            $kategori = $data['kategori'];  
            $produk = null;  
        
            // Inisialisasi objek produk berdasarkan kategori  
            if ($kategori === "Alat Tulis") {  
                $produk = new ProdukAlatTulis($db);  
            } elseif ($kategori === "Jasa") {  
                $produk = new ProdukJasa($db);  
            } elseif ($kategori === "Digital") {  
                // Mempertimbangkan data formatFile dan kapasitas  
                $produk = new ProdukDigital($db, $data['formatFile'] ?? '', $data['kapasitas'] ?? 0);  
            } else {  
                sendResponse(400, ["status" => "error", "message" => "Kategori tidak valid."]);  
                break;  
            }  
        
            // Atur ID dan atribut produk  
            $produk->id = $data['id'];  
            $produk->nama_produk = $data['nama_produk'];  
            $produk->harga_jual = $data['harga_jual'];  
            $produk->harga_beli = $data['harga_beli'];  
            $produk->stok = $data['stok'];  
            $produk->deskripsi = $data['deskripsi'];  
        
            // Memanggil metode update() untuk memperbarui data produk  
            if ($produk->update()) {  
                sendResponse(200, ["status" => "success", "message" => "Produk berhasil diperbarui."]);  
            } else {  
                sendResponse(500, ["status" => "error", "message" => "Gagal memperbarui produk."]);  
            }  
            break;
}
