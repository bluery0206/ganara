<?php

declare(strict_types= 1);

namespace App\Core;

use PDO;
use stdClass;
use PDOStatement;
use InvalidArgumentException;
use App\Core\Enums\FetchOption;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;
use App\Core\Utils\WhereConditionBuilder;

class Database {
    protected $pdo;

    protected function __construct() {
        $this->pdo = $this->connect(
            $_ENV["DB_NAME"],
            $_ENV["DB_HOST"],
            $_ENV["DB_USERNAME"],
            $_ENV["DB_PASSWORD"]
        );
    }

    protected function connect(
        string $database,
        string $hostname = "localhost", 
        string $username = "root", 
        string $password = ""
    ): PDO {
        $dsn = "mysql:host=$hostname;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $pdo;
    }


    protected function query(
        string $sql, 
        array $values = [],
        FetchOption $option = FetchOption::FETCH
    ): stdClass|PDOStatement|false {
        $option = $option->value; // Gets the actual value from the enumeration
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values );

        return $stmt->$option();
    }


    /**
     * Summary of select
     * 
     * Only supports one comparison and logical operator per query
     * @param string $table
     * @param array $conditions
     * @param array $columnReturn
     * @param Logical $logical
     * @param Comparison $comparison
     * @param FetchOption $fetchOption
     * @throws \InvalidArgumentException
     * @return bool|PDOStatement
     */
    protected function select(
        string $table, 
        array $conditions = [], 
        array $columnReturn = ["*"],
        Logical $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS,
        FetchOption $fetchOption = FetchOption::FETCH
    ): stdClass|false {
        // ECHO "table: "; print_r($table); ECHO "<BR>";

        // Contatenate each element of the array using a separator
        // Example: implode(", ", ["username", "password"]) -> "username, password"
        $formattedColumnReturn = implode(", ", $columnReturn);
        // ECHO "formattedColumnReturn: "; print_r($formattedColumnReturn); ECHO "<BR>";
        
        $sql = "SELECT $formattedColumnReturn FROM $table";
        // ECHO "sql (wo condition): "; print_r($sql); ECHO "<BR>";

        // Adds a WHERE to the SQL query if $conditions are specified
        if (!empty($conditions)) {
            $values = array_values($conditions); // The corresponding values
            // ECHO "values: "; print_r($values); ECHO "<BR>";

            // Generates the WHERE and concatenates it into the existing query
            $sql .= " " . WhereConditionBuilder::build($conditions, $logical, $comparison);

            // Intitiates the query
            // ECHO "sql datatype: "; print_r(gettype($sql)); ECHO "<BR>";
            return $this->query($sql, $values, $fetchOption);
        }

        // ECHO "sql (wo condition): "; print_r($sql); ECHO "<BR>";

        return $this->query($sql, option: $fetchOption);
    }


    protected function insert(
        string $table,
        array $data,
    ): PDOStatement|false {
        // Separates the columns and the values
        // Example:
        //      data=["username"=>"admin, "password"=>"admin123"]
        //      columns=["username", "password"]
        //      values=["admin", "admin123"]
        $columns = array_keys($data);
        $values = array_values($data);

        // INSERTING UID
        $uid = uniqid(); // Generates a random set of characters as the PK
        array_unshift($values, $uid); // Inserts the uid as the first element

        // Format array to become a single string separated with ", "
        // Example: ["username", "password"] -> "username, password"
        $formattedColumns = implode(", ", $columns);
        // ADD EXISTS HERE FOR UID

        // Used for placegolders in SQL Query
        // A much secure way of inserting values into the database
        $valuePlaceholders = implode(", ", array_fill(0, count($values), "?"));

        $sql = "INSERT INTO $table (uid, {$formattedColumns}) VALUES ($valuePlaceholders)";
        ECHO "sql: "; print_r($sql); ECHO "<BR>";

        // ADD FETCH OPTION SO THAT IN CREATION, WE WOULDNT HAVE TO SET
        // A NEW OR GET IT
        return $this->query($sql, $values);
        // return false;
    }


    protected function update(
        string $table,
        string $uid,
        array $data,
        array $conditions,
    ): PDOStatement|false {
        // Separates the columns and the values
        // Example:
        //      data=["username"=>"admin, "password"=>"admin123"]
        //      dataColumns=["username", "password"]
        //      dataValues=["admin", "admin123"]
        $dataColumns = array_keys($data);
        $dataValues = array_values($data);

        // // Adds a WHERE to the SQL query if $conditions are specified
        // if (!empty($conditions)) {
        //     $values = array_values($conditions); // The corresponding values
        //     // ECHO "values: "; print_r($values); ECHO "<BR>";

        //     // Generates the WHERE and concatenates it into the existing query
        //     $sql .= " " . WhereConditionBuilder::build($conditions, $logical, $comparison);

        //     // Intitiates the query
        //     // ECHO "sql datatype: "; print_r(gettype($sql)); ECHO "<BR>";
        //     return $this->query($sql, $values, $fetchOption);
        // }

        // $sql = "UPDATE $table SET $column = ? WHERE $column = ?";
        // ECHO "sql: "; print_r($sql); ECHO "<BR>";
        
        return false;
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
