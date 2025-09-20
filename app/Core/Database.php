<?php

declare(strict_types= 1);

namespace App\Core;

use PDO;
use stdClass;
use App\Core\Enums\Separators;
use App\Core\Enums\FetchOption;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;
use App\Core\Utils\SQLBuilder\Condition;
use App\Core\Utils\SQLBuilder\Extras;


/**
 * Class Database
 *
 * Core database abstraction built on PDO.
 * Handles connections and provides protected helpers for
 * common CRUD operations that child classes (e.g. BaseModel)
 * can reuse safely.
 *
 * @package App\Core
 */
class Database {
    /**
     * Active PDO connection instance.
     * @var PDO
     */
    protected $pdo;


    /**
     * Initialize a Database instance.
     *
     * Uses environment variables (`DB_NAME`, `DB_HOST`,
     * `DB_USERNAME`, `DB_PASSWORD`) to create the PDO connection.
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
     * Create a new PDO connection.
     *
     * @param string $database  Database name.
     * @param string $hostname  Hostname of the database server.
     * @param string $username  Database user.
     * @param string $password  Database user password.
     *
     * @return PDO Active PDO connection.
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
     * Prepare and execute a SQL query.
     *
     * @param string            $sql     Raw SQL statement with placeholders.
     * @param array             $values  Values to bind to placeholders.
     * @param FetchOption|null  $option  Optional fetch mode (e.g. FETCH or FETCH_ALL).
     *
     * @return array|stdClass|bool Result object or true/false for non-fetch queries.
     */
    protected function query(
        string $sql, 
        array $values = [], 
        FetchOption|Null $option = Null
    ): array|stdClass|bool {
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($values);

        if ($option) {
            $option = $option->value; // Gets the actual value from the enumeration
            return $stmt->$option(); // Fetches the data based on the option
        }

        return $res;
    }


    /**
     * Execute a flexible SELECT query with optional filtering and SQL “extras.”
     *
     * @param string        $table        Table name to query.
     * @param array         $conditions   Optional WHERE conditions as column => value pairs.
     *                                    All conditions share the same logical/comparison operators.
     * @param array         $columnReturn Columns to return (default ["*"] for all columns).
     * @param array         $extras       Optional associative array of SQL clauses to append
     *                                    after WHERE/GROUP BY. Keys map to static methods in
     *                                    App\Core\Utils\SQLBuilder\Extras, e.g.:
     *                                    [
     *                                      'groupBy' => 'username',
     *                                      'orderBy' => ['uid', 'ASC'],
     *                                      'limit'   => 10,
     *                                      'offset'  => 5,
     *                                    ]
     * @param Logical       $logical      Logical operator (AND/OR) to join WHERE conditions.
     * @param Comparison    $comparison   Comparison operator (=, LIKE, etc.) for all conditions.
     * @param FetchOption   $fetchOption  Fetch mode (FETCH single row, FETCH_ALL, etc.).
     *
     * @return array|stdClass|bool        Query result(s) according to $fetchOption,
     *                                    or false if no matching rows.
     */
    protected function select(
        string $table, 
        array $conditions = [], 
        array $columnReturn = ["*"],
        array $extras = [],
        Logical $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS,
        FetchOption $fetchOption = FetchOption::FETCH
    ): array|stdClass|bool {
        // ECHO "table: "; print_r($table); ECHO "<BR>";

        // Contatenate each element of the array using a separator
        // Example: implode(", ", ["username", "password"]) -> "username, password"
        $formattedColumnReturn = implode(", ", $columnReturn);

        // Base SQL query that selects all rows from $table
        // Unless $conditions are supplied
        $sql = "SELECT $formattedColumnReturn FROM $table";

        $values = array_values($conditions); // The corresponding values

        // Adds a WHERE to the SQL query if $conditions are specified
        if ($conditions) {
            if ($comparison == Comparison::LIKE) {
                $values = array_map(fn($val) => "%$val%",$values);
            }

            // Generates the WHERE and concatenates it into the existing query
            $sql .= " WHERE " . Condition::generate($conditions, $logical, $comparison);
        }

        if ($extras) {
            foreach ($extras as $keyword => $value) {
                if (is_array($value)) {
                    $sql .= Extras::$keyword(...$value);
                }
                else {
                    $sql .= Extras::$keyword($value);
                }
            }
        }

        echo "SQL: $sql<BR><BR>";

        return $this->query($sql, $values, $fetchOption);
    }


    /**
     * Insert a new row and automatically generate a `uid` primary key.
     *
     * @param string    $table Table name.
     * @param array     $data  Key/value pairs for the insert.
     *
     * @return array|stdClass|bool Insert result or false on failure.
     */
    protected function insert(string $table, array $data): array|stdClass|bool {
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

        return $this->query($sql, $values);
    }


    /**
     * Update an existing row identified by its UID.
     *
     * @param string        $table      Table name.
     * @param string        $uid        Unique identifier of the row.
     * @param array         $data       Columns and values to update.
     * @param Logical       $logical    Logical operator for WHERE clause.
     * @param Comparison    $comparison Comparison operator for WHERE clause.
     *
     * @return array|stdClass|bool Update result or false on failure.
     */
    protected function update(
        string $table,
        string $uid,
        array $data,
        Logical $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS,
    ): array|stdClass|bool {
        // ECHO "uid: "; print_r($uid); ECHO "<BR>";

        // Using Condition class to generates predicates separated by ", " for 
        // the SET part of the SQL query
        $conditionSet = Condition::generate($data, Separators::COMMA);

        // Generates the WHERE and concatenates it into the existing query
        $conditionWhere = Condition::generate(["uid" => $uid], $logical, $comparison);

        $sql = "UPDATE $table SET $conditionSet WHERE $conditionWhere";

        // Gets the values of in the array
        // Insert the uid at the very end
        $values = array_values($data);
        array_push($values, $uid);

        // Intitiates the query
        return $this->query($sql, $values);
    }


    /**
     * Delete rows from a table.
     *
     * @param string        $table      Table name.
     * @param array|string  $conditions Key/value conditions or "all" to delete every row.
     * @param Logical       $logical    Logical operator for WHERE clause.
     * @param Comparison    $comparison Comparison operator for WHERE clause.
     *
     * @return bool True on success, false otherwise.
     */
    protected function destroy(
        string $table,
        array|string $conditions, 
        Logical $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS
    ): bool {
        $sql = "DELETE FROM $table";

        if ($conditions == "all") {
            return $this->query($sql);
        }

        // Generates the WHERE and concatenates it into the existing query
        $conditionWhere = Condition::generate($conditions, $logical, $comparison);

        $sql .= " WHERE " . $conditionWhere;

        return $this->query($sql, array_values($conditions));
    }


    /**
     * Check if a value exists in a given column.
     *
     * @param string $table  Table name.
     * @param string $column Column name to search.
     * @param string $value  Value to match.
     *
     * @return bool True if a matching record exists, false otherwise.
     */
    protected function exists($table, $column, string $value): bool {
        $sql = "SELECT EXISTS(SELECT 1 FROM $table WHERE $column = ?)";
        return $this->query($sql, [$value]);
    }
}
