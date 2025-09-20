<?php 

namespace App\Models;

use App\Core\BaseModel;


/**
 * Class User
 *
 * Represents an application user record and its validation rules.
 *
 * Columns:
 *  - uid          : Primary key (auto-generated UID).
 *  - username     : Unique username.
 *  - password     : Hashed password.
 *  - email        : User email address.
 *  - isAdmin      : Boolean/int flag for admin rights.
 *  - isStaff      : Boolean/int flag for staff rights.
 *  - isJudge      : Boolean/int flag for judge rights.
 *  - dateCreated  : Timestamp when the user was created.
 *  - dateUpdated  : Timestamp of the last update.
 *  - dateDeleted  : Timestamp when the record was soft-deleted.
 *
 * Validation rules:
 *  - username : min 3 chars, max 25 chars, required.
 *  - password : min 3 chars, max 255 chars, required.
 *  - email    : min 3 chars, max 255 chars, must be valid email format.
 */
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
