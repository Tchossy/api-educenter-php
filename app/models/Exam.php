<?php

namespace app\models;

use PDO;

class Exam
{
  private $conn;
  private $table = 'exam';

  public $id;
  public $image;
  public $name;
  public $description;
  public $course_id;
  public $module_id;
  public $start_time;
  public $end_time;
  public $date_exam;
  public $mark;
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
  public function getByModule($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE module_id = :id ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }
  public function getByStudent($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE student_id = :id ORDER BY id DESC';
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
    $course_id,
    $module_id,
    $start_time,
    $end_time,
    $date_exam,
    $mark,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (image, name, description, course_id, module_id, start_time, end_time, date_exam, mark, status, date_create) VALUES (:image, :name, :description, :course_id, :module_id, :start_time, :end_time, :date_exam, :mark, :status, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':date_exam', $date_exam);
    $stmt->bindParam(':mark', $mark);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date_create', $date_now);

    if ($stmt->execute()) {
      return $this->conn->lastInsertId();
    } else {
      return false;
    }
  }

  public function update(
    $id,
    $image,
    $name,
    $description,
    $course_id,
    $module_id,
    $start_time,
    $end_time,
    $date_exam,
    $mark,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET image = :image, name = :name, description = :description, course_id = :course_id, module_id = :module_id, start_time = :start_time, end_time = :end_time, date_exam = :date_exam, mark = :mark, status = :status, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':date_exam', $date_exam);
    $stmt->bindParam(':mark', $mark);
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