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

// Deklarasikan class yang hilang di sini
abstract class BankCard {
    protected $number;
    protected $expiry;

    abstract public function processPayment($amount);
}

class CreditCard extends BankCard {
    public function processPayment($amount) {
        return "Credit card payment of $amount processed.";
    }
}

class DebitCard extends BankCard {
    public function processPayment($amount) {
        return "Debit card payment of $amount processed.";
    }
}

abstract class VA {
    protected $nomorVA;
    protected $penggunaan;

    public function __construct($nomorVA) {
        $this->nomorVA = $nomorVA;
    }

    public function getNomorVA() {
        return $this->nomorVA;
    }

    public function getPenggunaan() {
        return $this->penggunaan;
    }
}

class FixedVA extends VA {
    public function __construct($nomorVA) {
        parent::__construct($nomorVA);
        $this->penggunaan = "Sekali pakai";
    }

    public function getBatasWaktu() {
        return "Tidak ada batas waktu untuk Fixed VA.";
    }
}

class DynamicVA extends VA {
    private $expiredDate;

    public function __construct($nomorVA, $expiredDate) {
        parent::__construct($nomorVA);
        $this->penggunaan = "Sekali pakai";
        $this->expiredDate = $expiredDate;
    }

    public function getBatasWaktu() {
        return "Batas waktu: " . $this->expiredDate;
    }
}

class Transaction {
    private $va;
    private $bankCard;
    private $total;

    public function __construct($va, $bankCard, $total) {
        $this->va = $va;
        $this->bankCard = $bankCard;
        $this->total = $total;
    }

    public function executeTransaction() {
        return [
            "VA" => $this->va->getNomorVA(),
            "VA Usage" => $this->va->getPenggunaan(),
            "Payment" => $this->bankCard->processPayment($this->total),
            "Total" => $this->total
        ];
    }
}

// Inisialisasi database
$database = new Database();
$db = $database->getConnection();
$request = $_SERVER['REQUEST_METHOD'];

// Fungsi untuk mengirim respons
function sendResponse($code, $response) {
    http_response_code($code);
    echo json_encode($response);
}

// Fungsi untuk memvalidasi ID
function validateId($id) {
    return filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
}

// Menangani metode request
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
            // Ambil data dari input JSON  
            $data = json_decode(file_get_contents("php://input"), true);  
    
            // Validasi keberadaan ID dan data yang diperlukan  
            if (  
                !isset($data['id']) ||  
                !validateId($data['id']) ||  
                !isset($data['nama_produk'], $data['harga_jual'], $data['harga_beli'], $data['stok'], $data['deskripsi'], $data['kategori'])  
            ) {  
                sendResponse(400, ["status" => "error", "message" => "Data tidak lengkap atau ID tidak valid."]);  
                break;  
            }  
    
            $id = $data['id'];  
            $kategori = $data['kategori'];  
            $produk = null;  
    
            // Pilih kelas berdasarkan kategori  
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
    
            // Update produk  
            $produk->id = $id;  
            $produk->nama_produk = $data['nama_produk'];  
            $produk->harga_jual = $data['harga_jual'];  
            $produk->harga_beli = $data['harga_beli'];  
            $produk->stok = $data['stok'];  
            $produk->deskripsi = $data['deskripsi'];  
    
            if ($produk->update()) { // Asumsi ada metode update pada kelas produk  
                sendResponse(200, ["status" => "success", "message" => "Produk berhasil diperbarui."]);  
            } else {  
                sendResponse(500, ["status" => "error", "message" => "Gagal memperbarui produk."]);  
            }  
            break;  
    
        case 'DELETE':  
            if (empty($_GET['id'])) {  
                sendResponse(400, ["status" => "error", "message" => "ID tidak diberikan."]);  
                break;  
            }  
    
            $id = $_GET['id'];  
            if (!validateId($id)) {  
                sendResponse(400, ["status" => "error", "message" => "ID tidak valid."]);  
                break;  
            }  
    
            $produk = new Produk($db);  
            $produk->id = $id;  
    
            if ($produk->delete()) { // Asumsi ada metode delete pada kelas produk  
                sendResponse(200, ["status" => "success", "message" => "Produk berhasil dihapus."]);  
            } else {  
                sendResponse(500, ["status" => "error", "message" => "Gagal menghapus produk."]);  
            }  
            break;

    default:
        sendResponse(405, ["error" => "Metode tidak diizinkan"]);
        break;
}
?>
