<?php
  abstract class PDORepository {
    private $username = '';
    private $password = '';
    private $host = '';
    private $name = '';

    private function getConnection() {
      $conn = new PDO("mysql:dbname=$name;host=$host", $username, $password);
      return $conn;
    }

    protected function queryList($sql, $args) {
      $conn = $this->getConnection();
      $stmt = $conn->prepare($sql);
      $stmt->execute($args);
      return $stmt;
    }
  }
?>
