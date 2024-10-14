<?php

namespace app\models;

use PDO;

class ExamQuestion
{
  private $conn;
  private $table = 'exam_question';

  public $id;
  public $exam_id;
  public $question_text;
  public $question_type;
  public $options;
  public $value;
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
  public function getByExam($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE exam_id = :id ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }
  public function getByTerm($term)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE question_text LIKE :searchTerm ORDER BY id DESC';
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
    $exam_id,
    $question_text,
    $question_type,
    $options,
    $value
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (exam_id, question_text, question_type, options, value, date_create) VALUES (:exam_id, :question_text, :question_type, :options, :value, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':question_type', $question_type);
    $stmt->bindParam(':options', $options);
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':date_create', $date_now);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function update(
    $id,
    $exam_id,
    $question_text,
    $question_type,
    $options,
    $value
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET exam_id = :exam_id, question_text = :question_text, question_type = :question_type, options = :options, value = :value, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':question_type', $question_type);
    $stmt->bindParam(':options', $options);
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':date_update', $date_now);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}