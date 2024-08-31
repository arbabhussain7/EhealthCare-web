<?php
namespace care\config;

use Exception;
use PDO;
use PDOException;

class Database {
    private $host = 'localhost'; // Change this to your database host
    private $dbName = 'care'; // Change this to your database name
    private $username = 'root'; // Change this to your database username
    private $password = ''; // Change this to your database password
    private $db;

    /**
     * @throws Exception
     */
    public function __construct() {
        $this->connect();
    }

    /**
     * @throws Exception
     */

    private function connect() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
        try {
            $this->db = new PDO($dsn, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    public function prepare($sql)
    {
        return $this->db->prepare($sql);
    }

    /**
     * Begin a transaction.
     *
     * @return void
     */
    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    /**
     * Commit the transaction.
     *
     * @return void
     */
    public function commit() {
        $this->db->commit();
    }

    /**
     * Rollback the transaction.
     *
     * @return void
     */
    public function rollBack() {
        $this->db->rollBack();
    }

    public function errorInfo($query)
    {
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            $errorInfo = $this->db->errorInfo();
            throw new Exception('Failed to prepare SQL query: ' . implode(' ', $errorInfo));
        }

    }
}
