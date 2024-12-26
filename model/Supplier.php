<?php  

// CLASS DIAGRAM RELATIONSHIPS: Association
class Supplier {  
    public $id;  
    public $nama;  
    public $kontak;  

    public function __construct(int $id, string $nama, string $kontak) {  
        if ($id <= 0) {
            throw new InvalidArgumentException("ID harus berupa angka positif.");
        }
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

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? 0, 
            $data['nama'] ?? '', 
            $data['kontak'] ?? ''
        );
    }
}
