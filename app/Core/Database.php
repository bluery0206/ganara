<?php

declare(strict_types= 1);

namespace App\Core;

use PDO;
use stdClass;
use PDOStatement;
use InvalidArgumentException;
use App\Core\Enums\Separators;
use App\Core\Enums\FetchOption;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;
use App\Core\Utils\SQLBuilder\Condition;

class Database {
    protected $pdo;

    /**
     * Summary of __construct
     */
    protected function __construct() {
        $this->pdo = $this->connect(
            $_ENV["DB_NAME"],
            $_ENV["DB_HOST"],
            $_ENV["DB_USERNAME"],
            $_ENV["DB_PASSWORD"]
        );
    }


    /**
     * Summary of connect
     * @param string $database
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @return PDO
     */
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


    /**
     * Summary of query
     * @param string $sql
     * @param array $values
     * @param \App\Core\Enums\FetchOption $option
     * @return \stdClass|\PDOStatement|bool
     */
    protected function query(string $sql, array $values = [], FetchOption|Null $option = Null): stdClass|bool {
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($values);

        if ($option) {
            $option = $option->value; // Gets the actual value from the enumeration
            return $stmt->$option(); // Fetches the data based on the option
        }

        return $res;
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
    ): stdClass|bool {
        // ECHO "table: "; print_r($table); ECHO "<BR>";

        // Contatenate each element of the array using a separator
        // Example: implode(", ", ["username", "password"]) -> "username, password"
        $formattedColumnReturn = implode(", ", $columnReturn);
        
        // Base SQL query that selects all rows from $table
        // Unless $conditions are supplied
        $sql = "SELECT $formattedColumnReturn FROM $table";

        // Adds a WHERE to the SQL query if $conditions are specified
        if (!empty($conditions)) {
            $values = array_values($conditions); // The corresponding values

            // Generates the WHERE and concatenates it into the existing query
            $sql .= " WHERE " . Condition::generate($conditions, $logical, $comparison);

            // return false; // For debugging

            // Intitiates the query
            return $this->query($sql, $values, $fetchOption);
        }
        
        // return false; // For debugging

        return $this->query($sql, option: $fetchOption);
    }


    /**
     * Summary of insert
     * @param string $table
     * @param array $data
     * @return bool|PDOStatement|stdClass
     */
    protected function insert(string $table, array $data): StdClass|PDOStatement|bool {
        // Separates the columns and the values
        // Example:
        //      data=["username"=>"admin, "password"=>"admin123"]
        //      columns=["username", "password"]
        //      values=["admin", "admin123"]
        $columns = array_keys($data);
        $values = array_values($data);

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
        // ECHO "sql: "; print_r($sql); ECHO "<BR>";

        return $this->query($sql, $values);
    }


    /**
     * Summary of update
     * @param string $table
     * @param string $uid
     * @param array $data
     * @param Logical $logical
     * @param Comparison $comparison
     * @param FetchOption $fetchOption
     * @return bool|PDOStatement|stdClass
     */
    protected function update(
        string $table,
        string $uid,
        array $data,
        Logical $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS,
    ): PDOStatement|bool {
        // ECHO "uid: "; print_r($uid); ECHO "<BR>";

        // Using Condition class to generates predicates separated by ", " for 
        // the SET part of the SQL query
        $conditionSet = Condition::generate($data, Separators::COMMA);

        // Generates the WHERE and concatenates it into the existing query
        $conditionWhere = Condition::generate(["uid" => $uid], $logical, $comparison);
        // ECHO "conditionWhere: "; print_r($conditionWhere); ECHO "<BR>";

        $sql = "UPDATE $table SET $conditionSet WHERE $conditionWhere";

        // Gets the values of in the array
        // Insert the uid at the very end
        $values = array_values($data);
        array_push($values, $uid);

        // Intitiates the query
        return $this->query($sql, $values);
    }


    /**
     * Summary of destroy
     * @return bool
     */
    protected function destroy(
        string $table,
        array|string $conditions, 
        Logical $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS
    ): bool {
        $sql = "DELETE FROM $table";
        ECHO "sql: "; print_r($sql); ECHO "<BR>";

        if ($conditions == "all") {
            return $this->query($sql);
        }

        // Generates the WHERE and concatenates it into the existing query
        $conditionWhere = Condition::generate($conditions, $logical, $comparison);
        // ECHO "conditionWhere: "; print_r($conditionWhere); ECHO "<BR>";

        $sql .= " WHERE " . $conditionWhere;
        // ECHO "sql: "; print_r($sql); ECHO "<BR>";

        return $this->query($sql, array_values($conditions));
    }


    /**
     * Summary of exists
     * @param mixed $table
     * @param mixed $column
     * @param string $value
     * @return bool|stdClass
     */
    protected function exists($table, $column, string $value): bool {
        $sql = "SELECT EXISTS(SELECT 1 FROM $table WHERE $column = ?)";
        return $this->query($sql, [$value]);
    }
}
