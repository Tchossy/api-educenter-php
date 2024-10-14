<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/ExamAnswer.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\ExamAnswer;
use app\utils\Response;
use Database;
use PDO;

class ExamAnswerController
{
  private $db;
  private $examAnswerModel;

  public $completeDate;
  public $lastPart;
  public $secondLastPart;

  public function __construct()
  {
    $currentURL = $_SERVER['REQUEST_URI'];
    // Obtém a última parte da URI
    $parts = explode('/', $currentURL);

    $database = new Database();
    $this->lastPart = end($parts);
    $this->secondLastPart = prev($parts); // Penúltima parte
    $this->db = $database->getConnection();
    $this->examAnswerModel = new ExamAnswer($this->db);
  }

  public function getAll()
  {
    $result = $this->examAnswerModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $exam_answers_arr = array();
      $exam_answers_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_answer_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'student_id' => $student_id,
          'question_id' => $question_id,
          'question_title' => $question_title,
          'answer' => $answer,
          'mark' => $mark,
          'is_correct' => $is_correct,
          'submission_date' => $submission_date,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_answers_arr['data'], $exam_answer_item);
      }

      Response::send(200, $exam_answers_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->examAnswerModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $exam_answer_item = array(
        'id' => $id,
        'exam_id' => $exam_id,
        'student_id' => $student_id,
        'question_id' => $question_id,
        'question_title' => $question_title,
        'answer' => $answer,
        'mark' => $mark,
        'is_correct' => $is_correct,
        'submission_date' => $submission_date,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $exam_answer_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function getByStudentQuestion()
  {
    $student_id = $this->secondLastPart;
    $question_id = $this->lastPart;

    $result = $this->examAnswerModel->getByStudentQuestion($student_id, $question_id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $exam_answer_item = array(
        'id' => $id,
        'exam_id' => $exam_id,
        'student_id' => $student_id,
        'question_id' => $question_id,
        'question_title' => $question_title,
        'answer' => $answer,
        'mark' => $mark,
        'is_correct' => $is_correct,
        'submission_date' => $submission_date,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $exam_answer_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->examAnswerModel->getByTerm($term);
    $num = $result->rowCount();

    $exam_answers_arr = array();

    if ($num > 0) {
      $exam_answers_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_answer_item = array(
          'id' => $id,
          'exam_id' => $exam_id,
          'student_id' => $student_id,
          'question_id' => $question_id,
          'question_title' => $question_title,
          'answer' => $answer,
          'mark' => $mark,
          'is_correct' => $is_correct,
          'submission_date' => $submission_date,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exam_answers_arr['data'], $exam_answer_item);
      }

      Response::send(200, $exam_answers_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $exam_answers_arr));
    }
  }

  public function getByExam()
  {
    $id = $this->lastPart;

    $result = $this->examAnswerModel->getByExam($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $tasks_arr = array();
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // extract($row);
        $task_item = array($row);

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function create()
  {
    $id_doc = $this->lastPart;
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $exam_id = $data['exam_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $question_id = $data['question_id'] ?? '';
    $question_title = $data['question_title'] ?? '';
    $answer = $data['answer'] ?? '';
    $mark = $data['mark'] ?? '0';
    $is_correct = $data['is_correct'] ?? '';
    $question_id_row = '';

    if (empty($exam_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o exame'));
    } elseif (empty($student_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o estudante'));
    } elseif (empty($question_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar a questão'));
    } elseif (empty($question_title)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o titulo da questão'));
    } elseif (empty($answer)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo resposta está vazio'));
    } else {
      // Verificar se a pergunta já foi respondida
      $result_data = $this->examAnswerModel->getByStudentQuestion($student_id, $question_id);
      $row = $result_data->fetch(PDO::FETCH_ASSOC);

      if ($row) {
        $question_id_row = $row['question_id'];
      }

      if ($question_id == $question_id_row) {
        Response::send(200, array('error' => true, 'msg' => 'Essa pergunta já foi respondida'));
        return;
      }

      if ($this->examAnswerModel->createNew(
        $exam_id,
        $student_id,
        $question_id,
        $question_title,
        $answer,
        $mark,
        $is_correct
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
    $question_id = $data['question_id'] ?? '';
    $question_title = $data['question_title'] ?? '';
    $answer = $data['answer'] ?? '';
    $mark = $data['mark'] ?? '';
    $is_correct = $data['is_correct'] ?? '';

    $result_data = $this->examAnswerModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($question_title)) {
        $question_title = $row['question_title'];
      }
      if (empty($answer)) {
        $answer = $row['answer'];
      }
      if (empty($mark)) {
        $mark = $row['mark'];
      }
      if (empty($is_correct)) {
        $is_correct = $row['is_correct'];
      }

      if (empty($exam_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o exame'));
      } elseif (empty($student_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o estudante'));
      } elseif (empty($question_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar a questão'));
      } else {
        if ($this->examAnswerModel->update(
          $id_doc,
          $exam_id,
          $student_id,
          $question_id,
          $question_title,
          $answer,
          $mark,
          $is_correct
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

    $result = $this->examAnswerModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->examAnswerModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /exam_answer).'));
  }
}