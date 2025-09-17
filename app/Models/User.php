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

        parent::__construct(__CLASS__);
    }
}