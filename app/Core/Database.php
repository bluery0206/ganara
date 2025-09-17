<?php

declare(strict_types= 1);

namespace App\Core;

use PDO;
use PDOStatement;

class Database {
    protected $pdo;

    private function __construct() {
        $this->pdo = $this->connect(
            $_ENV["DB_NAME"],
            $_ENV["DB_HOST"],
            $_ENV["DB_USERNAME"],
            $_ENV["DB_PASSWORD"],
        );
    }

    protected function connect(
        string $database,
        string $hostname = "localhost", 
        string $username = "root", 
        string $password = "", 
    ): PDO {
        $dsn = "mysql:host=$hostname;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $pdo;
    }


    protected function query(string $sql, array $values, string $option = "fetch"): bool|PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($values);
        return $option != "fetch" ? $stmt->$option() : $result;
    }


    protected function select(string $table, array $columnFilters, array $values, array $columnReturn = ["*"]) {
        $formattedColumnReturn = implode(", ", $columnReturn);
        $formattedColumnFilters = array_map(fn($col) => "$col = ?", $columnFilters);
        print_r($formattedColumnReturn);
        print_r($formattedColumnFilters);
        // $sql = "SELECT $formattedColumnReturn FROM $table WHERE $columnFilters";
        return;
    }


    protected function store() {
        $uid = uniqid();
        $sql = "INSERT INTO $table (uid, $columns) VALUES ($uid, $values)";

        return;
    }


    protected function update() {
        $sql = "UPDATE $table SET $column = ? WHERE $column = ?";
        return;
    }


    protected function destroy() {
        $sql = "DELETE FROM $table WHERE $column = ?";
        return;
    }


    protected function exists($table, $column, string $value): bool {
        $sql = "SELECT EXISTS(SELECT 1 FROM $table WHERE $column = ?)";
        return $this->query($sql, [$value]);
    }
}
