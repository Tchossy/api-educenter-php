<?php

namespace app\models;

use PDO;

class TaskQuestion
{
  private $conn;
  private $table = 'task_question';

  public $id;
  public $task_id;
  public $question_text;
  public $question_type;
  public $question_answer;
  public $question_image;
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
  public function getByTask($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE task_id = :id ORDER BY id DESC';
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
    $task_id,
    $question_text,
    $question_type,
    $question_answer,
    $question_image,
    $options,
    $value
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (task_id, question_text, question_type, question_answer, question_image, options, value, date_create) VALUES (:task_id, :question_text, :question_type, :question_answer, :question_image, :options, :value, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':question_type', $question_type);
    $stmt->bindParam(':question_answer', $question_answer);
    $stmt->bindParam(':question_image', $question_image);
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
    $task_id,
    $question_text,
    $question_type,
    $question_answer,
    $question_image,
    $options,
    $value
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET task_id = :task_id, question_text = :question_text, question_type = :question_type, question_answer = :question_answer, question_image = :question_image, options = :options, value = :value, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':question_type', $question_type);
    $stmt->bindParam(':question_answer', $question_answer);
    $stmt->bindParam(':question_image', $question_image);
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