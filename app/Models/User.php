<?php 

namespace App\Models;

use App\Core\BaseModel;

class User extends BaseModel{
    public function __construct() {
        $this->columns = [
            "uid",
            "username",
            "password",
            "isAdmin",
            "isStaff",
            "isJudge",
            "dateCreated",
            "dateUpdated",
            "dateDeleted",
        ];

        $this->requires = [
          "username",  
          "password",  
        ];

        $this->rules = [
            "username" => [
                "min" => 3,
                "max" => 25,
            ],
            "password" => [
                "min" => 3,
                "max" => 255,
            ],
        ];

        parent::__construct(__CLASS__);
    }
}
