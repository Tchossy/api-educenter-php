<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/ExamResult.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\ExamResult;
use app\utils\Response;
use Database;
use PDO;

class ExamResultController
{
  private $db;
  private $examResultModel;

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
    $this->examResultModel = new ExamResult($this->db);
  }

  public function getAll()
  {
    $result = $this->examResultModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $exam_results_arr = array();
      $exam_results_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_result_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'student_id' => $student_id,
          'score' => $score,
          'status' => $status,
          'result' => $result,
          'feedback' => $feedback,
          'submission_date' => $submission_date,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_results_arr['data'], $exam_result_item);
      }

      Response::send(200, $exam_results_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->examResultModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $exam_result_item = array(
        'id' => $id,
        'exam_id' => $exam_id,
        'student_id' => $student_id,
        'score' => $score,
        'status' => $status,
        'result' => $result,
        'feedback' => $feedback,
        'submission_date' => $submission_date,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $exam_result_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->examResultModel->getByTerm($term);
    $num = $result->rowCount();

    $exam_results_arr = array();

    if ($num > 0) {
      $exam_results_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_result_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'student_id' => $student_id,
          'score' => $score,
          'status' => $status,
          'result' => $result,
          'feedback' => $feedback,
          'submission_date' => $submission_date,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_results_arr['data'], $exam_result_item);
      }

      Response::send(200, $exam_results_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $exam_results_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $exam_id = $data['exam_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $score = $data['score'] ?? '';
    $status = $data['status'] ?? 'pending';
    $result = $data['result'] ?? 'pending';
    $feedback = $data['feedback'] ?? '';

    if (empty($exam_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o exame'));
    } elseif (empty($student_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o estudante'));
    } else {

      if ($this->examResultModel->createNew(
        $exam_id,
        $student_id,
        $score,
        $status,
        $result,
        $feedback
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

    $exam_id = $data['exam_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $score = $data['score'] ?? '';
    $status = $data['status'] ?? '';
    $result = $data['result'] ?? '';
    $feedback = $data['feedback'] ?? '';

    $result_data = $this->examResultModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($score)) {
        $score = $row['score'];
      }
      if (empty($status)) {
        $status = $row['status'];
      }
      if (empty($result)) {
        $result = $row['result'];
      }
      if (empty($feedback)) {
        $feedback = $row['feedback'];
      }

      if (empty($exam_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o exame'));
      } elseif (empty($student_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o estudante'));
      } else {
        if ($this->examResultModel->update(
          $id_doc,
          $score,
          $status,
          $result,
          $feedback
        )) {
          Response::send(200, array('error' => false, 'msg' => 'Registo atualizado com sucesso.'));
        } else {
          Response::send(500, array('error' => true, 'msg' => 'Ocorreu um erro ao atualizar o registo.'));
        }
      }
    }
  }

  public function delete()
  {
    $id = $this->lastPart;

    $result = $this->examResultModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->examResultModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /exam_result).'));
  }
}