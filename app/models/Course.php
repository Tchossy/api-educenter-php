<?php

namespace app\models;

use PDO;

class Course
{
  private $conn;
  private $table = 'course';

  public $id;
  public $image;
  public $name;
  public $description;
  public $duration;
  public $status;
  public $date_create;
  public $date_update;

  public function __construct($db)
  {
    $this->date_create = date("d/m/Y");

    $this->conn = $db;
  }

  public function getAll()
  {
    $query = 'SELECT * FROM ' . $this->table . ' ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function getById($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id ORDER BY id DESC LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }
  public function getByTerm($term)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE name LIKE :searchTerm OR description LIKE :searchTerm ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':searchTerm', '%' . $term . '%', PDO::PARAM_STR);

    $stmt->execute();
    return $stmt;
  }

  public function deleteById($id)
  {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function createNew(
    $image,
    $name,
    $description,
    $duration,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (image, name, description, duration, status, date_create) VALUES (:image, :name, :description, :duration, :status, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date_create', $date_now);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function update(
    $id,
    $image,
    $name,
    $description,
    $duration,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET image = :image, name = :name, description = :description, duration = :duration, status = :status, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date_update', $date_now);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}