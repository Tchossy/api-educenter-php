<?php

namespace app\models;

use PDO;

class ExamResult
{
  private $conn;
  private $table = 'exam_result';

  public $id;
  public $exam_id;
  public $student_id;
  public $score;
  public $status;
  public $result;
  public $feedback;
  public $submission_date;
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
  public function getByExamAndStudent($exam_id, $student_id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE exam_id = :exam_id AND student_id = :student_id ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    return $stmt;
  }
  public function getAllByStudent($id)
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
    $exam_id,
    $student_id,
    $score,
    $status,
    $result,
    $feedback
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (exam_id, student_id, score, status, result, feedback, submission_date, date_create) VALUES (:exam_id, :student_id, :score, :status, :result, :feedback, :submission_date, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':result', $result);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->bindParam(':submission_date', $date_now);
    $stmt->bindParam(':date_create', $date_now);

    if ($stmt->execute()) {
      return $this->conn->lastInsertId();
    } else {
      return false;
    }
  }

  public function update(
    $id,
    $score,
    $status,
    $result,
    $feedback
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET score = :score, status = :status, result = :result, feedback = :feedback, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':result', $result);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->bindParam(':date_update', $date_now);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}