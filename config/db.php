<?php

/**
 * DB Class
 * Used to connect to mySQL database.
 * Added for future use
 */
class DB {
    private $host = '';
    private $user = '';
    private $pass = '';
    private $dbname = '';

    public function connect(){
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,
        ];
        try {
            $conn = new PDO($conn_str, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $conn;
    }
}