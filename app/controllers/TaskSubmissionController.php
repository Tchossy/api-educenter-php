<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/TaskSubmission.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\TaskSubmission;
use app\utils\Response;
use Database;
use PDO;

class TaskSubmissionController
{
  private $db;
  private $taskSubmissionModel;

  public $completeDate;
  public $lastPart;

  public function __construct()
  {
    $currentURL = $_SERVER['REQUEST_URI'];
    // Obtém a última parte da URI
    $parts = explode('/', $currentURL);

    $database = new Database();
    $this->lastPart = end($parts);
    $this->db = $database->getConnection();
    $this->taskSubmissionModel = new TaskSubmission($this->db);
  }

  public function getAll()
  {
    $result = $this->taskSubmissionModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $tasks_arr = array();
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // extract($row);
        $task_item = array(
          'id' => $row['id'],
          'task_id' => $row['task_id'],
          'student_id' => $row['student_id'],
          'submission_text' => $row['submission_text'],
          'submission_url' => $row['submission_url'],
          'result' => $row['result'],
          'feedback' => $row['feedback'],
          'submission_date' => $row['submission_date'],
          'grade' => $row['grade'],
          'status' => $row['status'],
          'date_create' => $row['date_create'],
          'date_update' => $row['date_update']
        );

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getAllByStudent()
  {
    $id = $this->lastPart;

    $result = $this->taskSubmissionModel->getAllByStudent($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $tasks_arr = array();
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // extract($row);
        $task_item = array(
          'id' => $row['id'],
          'task_id' => $row['task_id'],
          'student_id' => $row['student_id'],
          'submission_text' => $row['submission_text'],
          'submission_url' => $row['submission_url'],
          'result' => $row['result'],
          'feedback' => $row['feedback'],
          'submission_date' => $row['submission_date'],
          'grade' => $row['grade'],
          'status' => $row['status'],
          'date_create' => $row['date_create'],
          'date_update' => $row['date_update']
        );

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->taskSubmissionModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $task_item = array(
        'id' => $id,
        'task_id' => $task_id,
        'student_id' => $student_id,
        'submission_text' => $submission_text,
        'submission_url' => $submission_url,
        'result' => $result,
        'feedback' => $feedback,
        'submission_date' => $submission_date,
        'grade' => $grade,
        'status' => $status,
        'date_create' => $date_create,
        'date_update' => $date_update
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $task_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->taskSubmissionModel->getByTerm($term);
    $num = $result->rowCount();

    $tasks_arr = array();

    if ($num > 0) {
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // extract($row);
        $task_item = array($row);

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $tasks_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $task_id = $data['task_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $submission_text = $data['submission_text'] ?? '';
    $submission_url = $data['submission_url'] ?? '';
    $result = 'pending';
    $feedback = $data['feedback'] ?? '';
    $grade = '0';
    $status = 'pending';

    if (empty($task_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Errro ao identificar a tarefa'));
    } elseif (empty($student_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Errro ao identificar o estudante'));
    } else {
      if ($this->taskSubmissionModel->createNew(
        $task_id,
        $student_id,
        $submission_text,
        $submission_url,
        $result,
        $feedback,
        $grade,
        $status
      )) {
        Response::send(200, array('error' => false, 'msg' => 'A criação foi um com sucesso.'));
      } else {
        Response::send(200, array('error' => true, 'msg' => 'Ocorreu um erro ao criar, por favor tente novamnete.'));
      }
    }
  }

  public function update()
  {
    $id_doc = $this->lastPart;

    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $task_id = $data['task_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $submission_text = $data['submission_text'] ?? '';
    $submission_url_body = $data['submission_url'] ?? '';
    $result = $data['result'] ?? '';
    $feedback = $data['feedback'] ?? '';
    $grade = $data['grade'] ?? '';
    $status = $data['status'] ?? '';

    $result_data = $this->taskSubmissionModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($task_id)) {
        $task_id = $row['task_id'];
      }
      if (empty($student_id)) {
        $student_id = $row['student_id'];
      }
      if (empty($submission_text)) {
        $submission_text = $row['submission_text'];
      }
      if (empty($submission_url_body)) {
        $submission_url_body = $row['submission_url'];
      }
      if (empty($result)) {
        $result = $row['result'];
      }
      if (empty($feedback)) {
        $feedback = $row['feedback'];
      }
      if (empty($grade)) {
        $grade = $row['grade'];
      }
      if (empty($status)) {
        $status = $row['status'];
      }

      if ($this->taskSubmissionModel->update(
        $id_doc,
        $task_id,
        $student_id,
        $submission_text,
        $submission_url_body,
        $result,
        $feedback,
        $grade,
        $status
      )) {
        Response::send(200, array('error' => false, 'msg' => 'Registo atualizado com sucesso.'));
      } else {
        Response::send(500, array('error' => true, 'msg' => 'Ocorreu um erro ao atualizar o registo.'));
      }
    }
  }

  public function delete()
  {
    $id = $this->lastPart;

    $result = $this->taskSubmissionModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->taskSubmissionModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /task).'));
  }
}