<?php

class SqlServer
{
    private $host = "(local)";
    private $port = "1433";
    private $db = "ClinicaDB";
    private $username = "sa";
    private $password = "dev123456789*";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("sqlsrv:server={$this->host},{$this->port};Database={$this->db}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            exit();
        }
    }

    public function GetConnection()
    {
        return $this->conn;
    }
}