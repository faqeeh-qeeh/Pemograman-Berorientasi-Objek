<?php
// Dasar Class Seller
class Seller {
    // Properties
    public $nama;
    public $produk;

    // Constructor
    public function __construct($nama, $produk) {
        $this->nama = $nama;
        $this->produk = $produk;
    }

    // Method
    public function tampilkanProduk() {
        return "Produk yang dijual oleh $this->nama: " . implode(", ", $this->produk) . PHP_EOL;
    }
}

// Pewarisan: Multi-Level Inheritance
class SellerPremium extends Seller {
    public function bonus() {
        return "Seller premium mendapatkan bonus eksklusif." . PHP_EOL;
    }
}

class SellerSpesialis extends SellerPremium {
    public function keahlian() {
        return "Seller spesialis memiliki keahlian khusus dalam kategori tertentu." . PHP_EOL;
    }
}

// Single Inheritance
class OnlineSeller extends Seller {
    public function pengiriman() {
        return "Online seller menawarkan layanan pengiriman." . PHP_EOL;
    }
}

// Interface: Penjualan
interface Penjualan {
    public function jual();
}

class SellerOffline implements Penjualan {
    public function jual() {
        return "Seller menjual produk secara offline." . PHP_EOL;
    }
}

class SellerOnlineInterface implements Penjualan {
    public function jual() {
        return "Seller menjual produk secara online." . PHP_EOL;
    }
}

// Generalization
class GeneralizedSeller {
    public $nama;
    public $lokasi;

    public function __construct($nama, $lokasi) {
        $this->nama = $nama;
        $this->lokasi = $lokasi;
    }

    public function info() {
        return "$this->nama berlokasi di $this->lokasi." . PHP_EOL;
    }
}

class GeneralizedSellerOnline extends GeneralizedSeller {
    public function metode() {
        return "Berjualan melalui platform online." . PHP_EOL;
    }
}

class GeneralizedSellerOffline extends GeneralizedSeller {
    public function metode() {
        return "Berjualan di toko fisik." . PHP_EOL;
    }
}

// Single Responsibility Principle (SRP)
class Keuntungan {
    public function hitung($hargaJual, $hargaBeli) {
        return $hargaJual - $hargaBeli;
    }
}

// ======= Penggunaan =======

// Dasar Seller
$seller = new Seller("Budi", ["Buku", "Pulpen", "Penggaris"]);
echo $seller->tampilkanProduk();

// Multi-Level Inheritance
$sellerSpesialis = new SellerSpesialis("Dewi", ["Laptop"]);
echo $sellerSpesialis->tampilkanProduk();
echo $sellerSpesialis->bonus();
echo $sellerSpesialis->keahlian();

// Single Inheritance
$sellerOnline = new OnlineSeller("Rini", ["Keyboard", "Mouse"]);
echo $sellerOnline->tampilkanProduk();
echo $sellerOnline->pengiriman();

// Interface
$sellerOffline = new SellerOffline();
$sellerOnlineInterface = new SellerOnlineInterface();
echo $sellerOffline->jual();
echo $sellerOnlineInterface->jual();

// Generalization
$generalizedSeller = new GeneralizedSellerOnline("Andi", "Jakarta");
echo $generalizedSeller->info();
echo $generalizedSeller->metode();

// Single Responsibility Principle
$sellerProduk = new Seller("Budi", ["Buku", "Pulpen"]);
$keuntungan = new Keuntungan();
echo $sellerProduk->tampilkanProduk();
echo "Keuntungan per produk: " . $keuntungan->hitung(50000, 30000) . PHP_EOL;