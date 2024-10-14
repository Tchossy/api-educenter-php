<?php

namespace app\models;

use PDO;

class ExamAnswer
{
  private $conn;
  private $table = 'exam_answer';

  public $id;
  public $exam_id;
  public $student_id;
  public $question_id;
  public $question_title;
  public $answer;
  public $mark;
  public $is_correct;
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
  public function getByStudentQuestion($student_id, $question_id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE student_id = :student_id AND question_id = :question_id ORDER BY id DESC LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':question_id', $question_id);
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
    $query = 'SELECT * FROM ' . $this->table . ' WHERE question_title LIKE :searchTerm OR answer LIKE :searchTerm ORDER BY id DESC';
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
    $question_id,
    $question_title,
    $answer,
    $mark,
    $is_correct
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (exam_id, student_id, question_id, question_title, answer, mark, is_correct, submission_date, date_create) VALUES (:exam_id, :student_id, :question_id, :question_title, :answer, :mark, :is_correct, :submission_date, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->bindParam(':question_title', $question_title);
    $stmt->bindParam(':answer', $answer);
    $stmt->bindParam(':mark', $mark);
    $stmt->bindParam(':is_correct', $is_correct);
    $stmt->bindParam(':submission_date', $date_now);
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
    $student_id,
    $question_id,
    $question_title,
    $answer,
    $mark,
    $is_correct
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET exam_id = :exam_id, student_id = :student_id, question_id = :question_id, question_title = :question_title, answer = :answer, mark = :mark, is_correct = :is_correct, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->bindParam(':question_title', $question_title);
    $stmt->bindParam(':answer', $answer);
    $stmt->bindParam(':mark', $mark);
    $stmt->bindParam(':is_correct', $is_correct);
    $stmt->bindParam(':date_update', $date_now);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}