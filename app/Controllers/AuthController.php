<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Exceptions\RowNotFoundException;

class AuthController {
    public static function login(
        string $username,
        string $password,
        array $data = []
    ) {
        $userModel = new User();

        $data["username"] = $username;
        $data["password"] = $password;

        // echo "data: "; var_dump($data); echo "<BR>";

        checkWrongKeys($userModel->columns, array_keys($data));

        $user = $userModel->get($data);

        if (empty($user)) {
            throw new RowNotFoundException("Wrong username or password.");
        }

        session_start();
        $_SESSION['user'] = $user;

        // echo "user: "; var_dump($user); echo "<BR>";

        return $_SESSION['user'];
    }

    public static function register(
        string $username,
        string $password,
        array $data = []
    ) {
        $userModel = new User();

        $data["username"] = $username;
        $data["password"] = $password;

        // echo "data: "; var_dump($data); echo "<BR>";

        return $userModel->store($data);
    }

    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
