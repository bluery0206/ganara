<?php 

namespace App\Core;

use stdClass;
use PDOStatement;
use App\Core\Database;
use InvalidArgumentException;
use App\Core\Enums\FetchOption;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;


/**
 * Class BaseModel
 *
 * Base data-access layer that extends the core Database class.
 * Provides generic CRUD helpers for models so individual model
 * classes can focus on their own logic instead of repeating
 * query boilerplate.
 *
 * Typical usage:
 * - Auto-sets `$name` and `$table` based on the subclass name.
 * - Validates column keys before database operations.
 * - Wraps common SQL actions (`select`, `insert`, `update`, `destroy`).
 *
 * @package App\Core
 */
class BaseModel extends Database {
    /**
     * Name of the model, derived from the subclass (pluralized + lowercased).
     * @var string
     */
    public string $name;

    /**
     * Database table name matching this model.
     * @var string
     */
    protected string $table;

    /**
     * List of valid column names for this model’s table.
     * Used for key validation.
     * @var array
     */
    public array $columns;

    /**
     * Validation or business rules tied to the model’s data.
     * @var array
     */
    public array $rules;
 

    /**
     * Construct a BaseModel.
     *
     * Sets `$name` and `$table` automatically from the provided
     * fully qualified class name and initializes the database
     * connection.
     *
     * @param string $name Fully qualified class name of the child model.
     */
    public function __construct(string $name) {
        // __CLASS__ returns the namespace of the class (i.e.: App\Core\BaseModel)
        // and basename() returns the last folder or file name but
        // it accepts $path (i.e.: asset/images) so we convert the namespace
        // into a path to get the class name
        // Example: App\Models\User -> user
        $this->name = basename(pluralize(str_replace("\\", "/", $name)));

        // Converts it to lowercase
        $this->table = strtolower($this->name);
        
        parent::__construct();
    }


    /**
     * Retrieve records from the table.
     *
     * @param array         $conditions  Optional WHERE conditions.
     * @param array         $columnReturn Columns to return, defaults to `["*"]`.
     * @param Logical       $logicalOperator Logical operator to join conditions.
     * @param Comparison    $comparisonOperators Comparison operator for each condition.
     * @param FetchOption   $fetchOption Fetch mode (single or all).
     *
     * @return stdClass|bool Single record as object, or false if none found.
     */
    public function get(
        array $conditions = [], 
        array $columnReturn = ["*"],
        Logical $logicalOperator = Logical::AND,
        Comparison $comparisonOperators = Comparison::EQUALS,
        FetchOption $fetchOption = FetchOption::FETCH
    ): stdClass|bool {
        checkWrongKeys($this->columns, array_keys($conditions));

        return $this->select(
            $this->table,
            $conditions, 
            $columnReturn, 
            $logicalOperator, 
            $comparisonOperators, 
            $fetchOption, 
        );
    }


    /**
     * Insert a new record into the table.
     *
     * @param array $data Key/value pairs to insert.
     *
     * @return stdClass|bool The created record as object, or false on failure.
     */
    public function store(
        array $data,
    ): StdClass|bool {
        // ECHO "data: "; var_dump($data); ECHO "<BR>";

        checkWrongKeys($this->columns, array_keys($data));

        $res = $this->insert($this->table, $data);
        // ECHO "idk: "; var_dump($res); ECHO "<BR>";

        // Returns the created data
        return $res ? $this->get($data) : false;
    }


    /**
     * Update an existing record by unique identifier.
     *
     * @param string    $uid  Unique identifier (e.g., primary key).
     * @param array     $data Data to update.
     *
     * @return stdClass|bool The updated record as object, or false on failure.
     */
    public function edit(string $uid, array $data): stdClass|bool {
        checkWrongKeys($this->columns, array_keys($data));

        $res = $this->update($this->table, $uid, $data);
        // ECHO "idk: "; var_dump($res); ECHO "<BR>";

        // Returns the updated user data
        return $res ? $this->get(["uid" => $uid]) : false;
    }


    /**
     * Delete a record by unique identifier.
     *
     * @param string $uid        Unique identifier.
     * @param bool   $softDelete If true, sets a "dateDeleted" timestamp
     *                           instead of a hard delete.
     *
     * @return bool True on success, false otherwise.
     */
    public function delete(string $uid, bool $softDelete = true): bool {
        // Soft delete doesn't actually delete the record
        // but sets the "dateDeleted" column to the current timestamp
        if ($softDelete) {
            return $this->update($this->table, $uid, [
                "dateDeleted" => date("Y-m-d H:i:s")
            ]);
        }

        return $this->destroy($this->table, ["uid" => $uid] );
    }


    /**
     * Delete multiple records or the entire table.
     *
     * @param array $conditions Optional WHERE conditions.
     *                          If empty, deletes all records.
     *
     * @return bool True on success, false otherwise.
     */
    public function deleteAll(array $conditions = []): bool {
        if ($conditions) {
            return $this->destroy($this->table, $conditions);
        }
        return $this->destroy($this->table, "all");
    }
}
