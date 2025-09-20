<?php 

namespace App\Models;

use App\Core\BaseModel;

class User extends BaseModel{
    public function __construct() {
        $this->columns = [
            "uid",
            "username",
            "password",
            "email",
            "isAdmin",
            "isStaff",
            "isJudge",
            "dateCreated",
            "dateUpdated",
            "dateDeleted",
        ];

        $this->rules = [
            "username" => "min:3|max:25|required",
            "password" => "min:3|max:255|required",
            "email" => "min:3|max:255|email",
        ];

        parent::__construct(__CLASS__);
    }
}
