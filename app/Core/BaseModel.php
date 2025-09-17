<?php 

namespace App\Core;

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
    public string $table;

    /**
     * Summary of columns
     * @var array
     */
    public array $columns;

    public function __construct(string $name) {
        $this->name = pluralize($name);

        // basename() treats namespace ($name) as a path and gets the last segment
        // Then converts it to lowercase
        // Example: App\Models\User -> user
        $this->table = strtolower(basename($this->name));
    }

    public function get() {
        return $this->select($this->table, $this->columns, [""]);
    }
}
