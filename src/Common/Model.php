<?php

namespace Softia\Challenge\CoffeeMachine\Common;

use Softia\Challenge\CoffeeMachine\Database\Connection;

class Model
{
    protected $conn = null;
    public $columns = [];

    public function __construct($data = []) {
        foreach ($this->columns as $column) {
            if (isset($data[$column])) {
                $this->$column = $data[$column];
            } else {
                $this->$column = null;
            }

        }
        $this->conn = Connection::getConnection();
    }

}