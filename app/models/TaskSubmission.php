<?php

namespace app\models;

use PDO;

class TaskSubmission
{
  private $conn;
  private $table = 'task_submission';

  public $id;
  public $task_id;
  public $student_id;
  public $submission_text;
  public $submission_url;
  public $result;
  public $feedback;
  public $submission_date;
  public $grade;
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
    $query = 'SELECT * FROM ' . $this->table . ' WHERE submission_text LIKE :searchTerm OR grade LIKE :searchTerm ORDER BY id DESC';
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
    $task_id,
    $student_id,
    $submission_text,
    $submission_url,
    $result,
    $feedback,
    $grade,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (task_id, student_id, submission_text, submission_url, result, feedback, submission_date, grade, status, date_create) VALUES (:task_id, :student_id, :submission_text, :submission_url, :result, :feedback, :submission_date, :grade, :status, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':submission_text', $submission_text);
    $stmt->bindParam(':submission_url', $submission_url);
    $stmt->bindParam(':result', $result);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->bindParam(':submission_date', $date_now);
    $stmt->bindParam(':grade', $grade);
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
    $task_id,
    $student_id,
    $submission_text,
    $submission_url,
    $result,
    $feedback,
    $grade,
    $status,
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET task_id = :task_id, student_id = :student_id, submission_text = :submission_text, submission_url = :submission_url, result = :result, feedback = :feedback, grade = :grade, status = :status, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':submission_text', $submission_text);
    $stmt->bindParam(':submission_url', $submission_url);
    $stmt->bindParam(':result', $result);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->bindParam(':grade', $grade);
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