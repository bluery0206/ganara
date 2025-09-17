<?php

declare(strict_types= 1);

namespace App\Core;

use PDO;
use PDOStatement;

class Database {
    protected PDO $connection = null;

    protected function connect(string $dsn, string $username = null, string $password = null, string $database = null): PDO {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $pdo;
    }

    protected function query(string $string, array $values): bool|PDOStatement {
        $query = $this->connection->prepare($string);
        $query->execute($values);
        return $query;
    }

    protected function select(array $columnReturn, string $table, array $columnFilters, array $values) {
        return;
    }

    protected function insert() {
        return;
    }

    protected function update() {
        return;
    }

    protected function delete() {
        return;
    }
}
