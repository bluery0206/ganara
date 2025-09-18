<?php 

namespace App\Core;

use stdClass;
use PDOStatement;
use App\Core\Database;
use InvalidArgumentException;
use App\Core\Enums\FetchOption;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;

class BaseModel extends Database {
    /**
     * Summary of name
     * @var string
     */
    public string $name;

    /**
     * Summary of table
     * @var string
     */
    protected string $table;

    /**
     * Summary of columns
     * @var array
     */
    public array $columns;

    /**
     * The required columns
     * Example: ["username", "password"]
     * @var array
     */
    protected array $requires;

    /**
     * Summary of rules
     * @var array
     */
    public array $rules;
 

    /**
     * Summary of __construct
     * @param string $name
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
     * Wrapper method
     * @param array $conditions
     * @param array $columnReturn
     * @param Logical $logicalOperator
     * @param Comparison $comparisonOperators
     * @param FetchOption $fetchOption
     * @return PDOStatement|false
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
     * Summary of store
     * @param array $data
     * @throws \InvalidArgumentException
     * @return bool|PDOStatement
     */
    public function store(
        array $data,
    ): StdClass|bool {
        // ECHO "data: "; var_dump($data); ECHO "<BR>";

        checkMissingKeys($this->requires, $data);
        checkWrongKeys($this->columns, array_keys($data));

        $res = $this->insert($this->table, $data);
        // ECHO "idk: "; var_dump($res); ECHO "<BR>";

        // Returns the created data
        return $res ? $this->get($data) : false;
    }


    /**
     * Summary of edit
     * @param string $uid
     * @param array $data
     * @return stdClass|bool
     */
    public function edit(string $uid, array $data): stdClass|bool {
        checkWrongKeys($this->columns, array_keys($data));

        $res = $this->update($this->table, $uid, $data);
        // ECHO "idk: "; var_dump($res); ECHO "<BR>";

        // Returns the updated user data
        return $res ? $this->get(["uid" => $uid]) : false;
    }


    /**
     * Summary of delete
     * @param bool $softDelete // if true, "deletes" by setting a "deleted_at" timestamp 
     * @return bool
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
     * Summary of deleteAll
     * @param array $conditions
     * @return bool
     */
    public function deleteAll(array $conditions = []): bool {
        if ($conditions) {
            return $this->destroy($this->table, $conditions);
        }
        return $this->destroy($this->table, "all");
    }
}
