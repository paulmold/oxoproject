<?php

namespace model;

use config\Config;
use mysqli;

class DbConnection
{
    private static $instance = null;
    private mysqli $connection;

    private function __construct() {
        (new Config(__DIR__ . '/.env'))->load();

        $this->connection = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PW"), getenv("DB_NAME"));
    }

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new DbConnection();
        }

        return self::$instance;
    }

    /**
     * @return mysqli
     */
    public function connection(): mysqli {
        return $this->connection;
    }
}