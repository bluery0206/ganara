<?php 

namespace App\Core;

class BaseModel {
    protected Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function get($id) { 
        
    }
}