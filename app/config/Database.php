<?php

class Database
{
  private $host = 'localhost';
  private $dbname = 'educenter';
  private $username = 'root';
  private $password = '';

  // private $host = 'localhost';
  // private $dbname = 'drilling_dfcenter';
  // private $username = 'drilling_dfcenter';
  // private $password = '!DFCenter1';

  public function getConnection()
  {
    $conn = null;

    try {
      $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
      var_dump($conn);
    }

    return $conn;
  }
}