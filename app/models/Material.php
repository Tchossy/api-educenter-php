<?php

namespace app\models;

use PDO;

class Material
{
  private $conn;
  private $table = 'material';

  public $id;
  public $name;
  public $description;
  public $course_id;
  public $module_id;
  public $material_type;
  public $file_url;
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
  public function getByModule($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE module_id = :id ORDER BY id DESC LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }
  public function getAllByModule($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE module_id = :id ORDER BY id DESC';
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
    $name,
    $description,
    $course_id,
    $module_id,
    $material_type,
    $file_url
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (name, description, course_id, module_id, material_type, file_url, date_create) VALUES (:name, :description, :course_id, :module_id, :material_type, :file_url, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->bindParam(':material_type', $material_type);
    $stmt->bindParam(':file_url', $file_url);
    $stmt->bindParam(':date_create', $date_now);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function update(
    $id,
    $name,
    $description,
    $course_id,
    $module_id,
    $material_type,
    $file_url
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET name = :name, description = :description, course_id = :course_id, module_id = :module_id, material_type = :material_type, file_url = :file_url, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->bindParam(':material_type', $material_type);
    $stmt->bindParam(':file_url', $file_url);
    $stmt->bindParam(':date_update', $date_now);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}