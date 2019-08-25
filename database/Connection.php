<?php

namespace Softia\Challenge\CoffeeMachine\Database;

use  Softia\Challenge\CoffeeMachine\Exceptions\SqlException;

class Connection
{
    private static $instance = null;
    private $conn = null;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    protected function __construct() {
        $connectionString = sprintf("mysql:host=%s;dbname=%s", env('DB_HOST'), env('DB_DATABASE'));
        try {
            $this->conn = new \PDO($connectionString, env('DB_USERNAME'), env('DB_PASSWORD'));
        } catch (\PDOException $e) {
            echo $e->getMessage();
            throw new SqlException();
        }
    }

    public static function getConnection() {
        return (self::getInstance())->conn;
    }

    public function close() {
        $this->conn = null;
    }
}
