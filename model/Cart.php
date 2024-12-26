<?php  
require_once "Produk.php";


// CLASS DIAGRAM RELATIONSHIPS: Composition
class Cart {  
    private $items = [];

    public function addProduct(Produk $produk): void {  
        $this->items[] = $produk; 
    }  

    public function getProducts(): array {  
        return $this->items; 
    }  

    public function totalHarga(): float {  
        $total = 0;  
        foreach ($this->items as $item) {  
            $total += $item->harga_jual; 
        }  
        return $total;  
    }  
}