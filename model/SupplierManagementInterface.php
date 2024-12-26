<?php  

interface SupplierManagementInterface {  
    public function addSupplier(Supplier $supplier): void;  
    public function getSuppliers(): array;  
} 