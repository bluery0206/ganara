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
        return $this->select(
            $this->table,
            $conditions, 
            $columnReturn, 
            $logicalOperator, 
            $comparisonOperators, 
            $fetchOption, 
        );
    }


    public function store(
        array $data,
    ): PDOStatement|false {
        $lackingKeys = [];

        // Checks if the $this->requires columns are supplied in $data
        foreach ($this->requires as $value) {
            // ECHO "value: "; print_r($value); ECHO "<BR>";
            
            if (!key_exists($value, $data)) {
                array_push($lackingKeys, $value);
            }
        }

        if ($lackingKeys) {
            $lackingKeys = implode(", ", $lackingKeys);

            throw new InvalidArgumentException(
                "Required column(s) \"{$lackingKeys}\" not specified."
            );
        }

        return $this->insert(
            $this->table, 
            $data
        );
    }

    public function edit(
        string $uid,
        array $data
    ): PDOStatement|false {

        
        return $this->update(
            $this->table, 
            $uid,
            $data
        );
    }
}
