<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/ExamQuestion.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\ExamQuestion;
use app\utils\Response;
use Database;
use PDO;

class ExamQuestionController
{
  private $db;
  private $examQuestionModel;

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
    $this->examQuestionModel = new ExamQuestion($this->db);
  }

  public function getAll()
  {
    $result = $this->examQuestionModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $exam_questions_arr = array();
      $exam_questions_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_question_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'question_text' => $question_text,
          'question_type' => $question_type,
          'options' => $options,
          'value' => $value,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_questions_arr['data'], $exam_question_item);
      }

      Response::send(200, $exam_questions_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->examQuestionModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $exam_question_item = array(
        'id' => $id,
        'exam_id' => $exam_id,
        'question_text' => $question_text,
        'question_type' => $question_type,
        'options' => $options,
        'value' => $value,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $exam_question_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }

  public function getByExam()
  {
    $id = $this->lastPart;

    $result = $this->examQuestionModel->getByExam($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $exam_questions_arr = array();
      $exam_questions_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_question_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'question_text' => $question_text,
          'question_type' => $question_type,
          'options' => $options,
          'value' => $value,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_questions_arr['data'], $exam_question_item);
      }

      Response::send(200, $exam_questions_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->examQuestionModel->getByTerm($term);
    $num = $result->rowCount();

    $exam_questions_arr = array();

    if ($num > 0) {
      $exam_questions_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_question_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'question_text' => $question_text,
          'question_type' => $question_type,
          'options' => $options,
          'value' => $value,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_questions_arr['data'], $exam_question_item);
      }

      Response::send(200, $exam_questions_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $exam_questions_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $exam_id = $data['exam_id'] ?? '';
    $question_text = $data['question_text'] ?? '';
    $question_type = $data['question_type'] ?? '';
    $options = $data['options'] ?? '';
    $value = $data['value'] ?? '';


    if (empty($exam_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o exame'));
    } elseif (empty($question_text)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo questão está vazio'));
    } elseif (empty($question_type)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo tippo de questão está vazio'));
    } elseif (empty($value)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo valor da questão está vazio'));
    } else {

      if ($this->examQuestionModel->createNew(
        $exam_id,
        $question_text,
        $question_type,
        $options,
        $value
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
    $question_text = $data['question_text'] ?? '';
    $question_type = $data['question_type'] ?? '';
    $options_body = $data['options'] ?? '';
    $value = $data['value'] ?? '';

    $result_data = $this->examQuestionModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($options_body)) {
        $options_body = $row['options'];
      }

      if (empty($exam_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o exame'));
      } elseif (empty($question_text)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo questão está vazio'));
      } elseif (empty($question_type)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo tippo de questão está vazio'));
      } elseif (empty($value)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo valor da questão está vazio'));
      } else {
        if ($this->examQuestionModel->update(
          $id_doc,
          $exam_id,
          $question_text,
          $question_type,
          $options_body,
          $value
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

    $result = $this->examQuestionModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->examQuestionModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /exam_question).'));
  }
}