<?php

namespace app\models;

use PDO;

class WeeklyAverage
{
  private $conn;
  private $table = 'weekly_average';

  public $id;
  public $student_id;
  public $week_start;
  public $week_end;
  public $task_average;
  public $exam_grade;
  public $weekly_average;
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

  public function getAllByStudent($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE student_id = :id ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
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
    $student_id,
    $week_start,
    $week_end,
    $task_average,
    $exam_grade,
    $weekly_average,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (student_id, week_start, week_end, task_average, exam_grade, weekly_average, status, date_create) VALUES (:student_id, :week_start, :week_end, :task_average, :exam_grade, :weekly_average, :status, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':week_start', $week_start);
    $stmt->bindParam(':week_end', $week_end);
    $stmt->bindParam(':task_average', $task_average);
    $stmt->bindParam(':exam_grade', $exam_grade);
    $stmt->bindParam(':weekly_average', $weekly_average);
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
    $student_id,
    $week_start,
    $week_end,
    $task_average,
    $exam_grade,
    $weekly_average,
    $status
  ) {
    $date_now = $this->date_create;

    $query = 'UPDATE ' . $this->table . ' SET student_id = :student_id, week_start = :week_start, week_end = :week_end, task_average = :task_average, exam_grade = :exam_grade, weekly_average = :weekly_average, status = :status, date_update = :date_update WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':week_start', $week_start);
    $stmt->bindParam(':week_end', $week_end);
    $stmt->bindParam(':task_average', $task_average);
    $stmt->bindParam(':exam_grade', $exam_grade);
    $stmt->bindParam(':weekly_average', $weekly_average);
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