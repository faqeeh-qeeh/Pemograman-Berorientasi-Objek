<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../config/Database.php";
require_once "../model/Produk.php";

$database = new Database();
$db = $database->getConnection();

$produk = new Produk($db);

$request = $_SERVER['REQUEST_METHOD'];

function sendResponse($code, $response) {
    http_response_code($code);
    echo json_encode($response);
}

switch ($request) {
    case 'GET':
        if (empty($_GET['id'])) {
            $statement = $produk->read();
            $num = $statement->rowCount();
            $listProduk = array("records" => array());

            if ($num > 0) {
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $itemProduk = array(
                        "id" => $id,
                        "nama_produk" => $nama_produk,
                        "harga_jual" => $harga_jual,
                        "harga_beli" => $harga_beli,
                        "stok" => $stok,
                        "deskripsi" => $deskripsi
                    );
                    array_push($listProduk["records"], $itemProduk);
                }
                sendResponse(200, $listProduk);
            } else {
                sendResponse(404, ["message" => "Produk tidak ditemukan."]);
            }
        } else {
            $produk->id = $_GET['id'];
            $produk->readById();
            if (!empty($produk->nama_produk)) {
                $itemProduk = array(
                    "id" => $produk->id,
                    "nama_produk" => $produk->nama_produk,
                    "harga_jual" => $produk->harga_jual,
                    "harga_beli" => $produk->harga_beli,
                    "stok" => $produk->stok,
                    "deskripsi" => $produk->deskripsi
                );
                sendResponse(200, $itemProduk);
            } else {
                sendResponse(404, ["message" => "Produk tidak ditemukan."]);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset($data['nama_produk']) &&
            isset($data['harga_jual']) &&
            isset($data['harga_beli']) &&
            isset($data['stok']) &&
            isset($data['deskripsi'])
        ) {
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
        } else {
            sendResponse(400, ["status" => "error", "message" => "Data tidak lengkap"]);
        }
        break;

    default:
        sendResponse(405, ["message" => "Method not allowed."]);
        break;
}