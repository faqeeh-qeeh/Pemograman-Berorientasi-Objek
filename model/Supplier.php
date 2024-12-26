<?php  

// CLASS DIAGRAM RELATIONSHIPS: Association
class Supplier {  
    public $id;  
    public $nama;  
    public $kontak;  

    public function __construct($id, $nama, $kontak) {  
        $this->id = $id;  
        $this->nama = $nama;  
        $this->kontak = $kontak;  
    }  

    public function getDetailSupplier(): array {  
        return [  
            'id' => $this->id,  
            'nama' => $this->nama,  
            'kontak' => $this->kontak  
        ];  
    }  
}