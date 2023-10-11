<?php

namespace Model;

class Connexion
{
    const SERVER_NAME = "docker-lamp-mysql";
    const USERNAME = "root";
    const PASSWORD = "p@ssw0rd";
    const DB_NAME = 'users_management';

    private static $instance = NULL;
    
    private ?\PDO $conn = null; 

    static public function getInstance()
    {
        if (self::$instance === NULL) {
            try {
                self::$instance = new Connexion();
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }

        return self::$instance;
    }

    /*
     * Protected CTOR
     */
    protected function __construct()
    {
        $this->conn = new \PDO("mysql:host=". self::SERVER_NAME .";dbname=".self::DB_NAME, self::USERNAME, self::PASSWORD);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function getConn(): \PDO
    {
        return $this->conn;
    }
}