<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/TaskAnswer.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\TaskAnswer;
use app\utils\Response;
use Database;
use PDO;

class TaskAnswerController
{
  private $db;
  private $taskAnswerModel;

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
    $this->taskAnswerModel = new TaskAnswer($this->db);
  }

  public function getAll()
  {
    $result = $this->taskAnswerModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $task_answers_arr = array();
      $task_answers_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_answer_item = array(
          'id' => $id,
          'task_id' => $task_id,
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

        array_push($task_answers_arr['data'], $task_answer_item);
      }

      Response::send(200, $task_answers_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->taskAnswerModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $task_answer_item = array(
        'id' => $id,
        'task_id' => $task_id,
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

      Response::send(200, $task_answer_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function getByStudentQuestion()
  {
    $student_id = $this->secondLastPart;
    $question_id = $this->lastPart;

    $result = $this->taskAnswerModel->getByStudentQuestion($student_id, $question_id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $task_answer_item = array(
        'id' => $id,
        'task_id' => $task_id,
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

      Response::send(200, $task_answer_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->taskAnswerModel->getByTerm($term);
    $num = $result->rowCount();

    $task_answers_arr = array();

    if ($num > 0) {
      $task_answers_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_answer_item = array(
          'id' => $id,
          'task_id' => $task_id,
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

        array_push($task_answers_arr['data'], $task_answer_item);
      }

      Response::send(200, $task_answers_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $task_answers_arr));
    }
  }

  public function getByTask()
  {
    $id = $this->lastPart;

    $result = $this->taskAnswerModel->getByTask($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $task_answers_arr = array();
      $task_answers_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // extract($row);
        $task_answer_item = array($row);

        array_push($task_answers_arr['data'], $task_answer_item);
      }

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $task_answers_arr));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getAllByTaskAndStudent()
  {
    $task_id = $this->secondLastPart;
    $student_id = $this->lastPart;

    $result = $this->taskAnswerModel->getAllByTaskAndStudent($task_id, $student_id);
    $num = $result->rowCount();

    if ($num > 0) {
      $task_answers_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_answer_item = array(
          'id' => $id,
          'task_id' => $task_id,
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

        array_push($task_answers_arr['data'], $task_answer_item);
      }

      Response::send(200, $task_answers_arr);
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

    $task_id = $data['task_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $question_id = $data['question_id'] ?? '';
    $question_title = $data['question_title'] ?? '';
    $answer = $data['answer'] ?? '0';
    $mark = $data['mark'] ?? '0';
    $is_correct = $data['is_correct'] ?? '';
    $question_id_row = '';

    if (empty($task_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o taske'));
    } elseif (empty($student_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o estudante'));
    } elseif (empty($question_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar a questão'));
    } elseif (empty($question_title)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o titulo da questão'));
    } else {
      // Verificar se a pergunta já foi respondida
      $result_data = $this->taskAnswerModel->getByStudentQuestion($student_id, $question_id);
      $row = $result_data->fetch(PDO::FETCH_ASSOC);

      if ($row) {
        $question_id_row = $row['question_id'];
      }

      if ($question_id == $question_id_row) {
        Response::send(200, array('error' => true, 'msg' => 'Essa pergunta já foi respondida'));
        return;
      }

      if ($this->taskAnswerModel->createNew(
        $task_id,
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

    $task_id = $data['task_id'] ?? '';
    $student_id = $data['student_id'] ?? '';
    $question_id = $data['question_id'] ?? '';
    $question_title = $data['question_title'] ?? '';
    $answer = $data['answer'] ?? '';
    $mark = $data['mark'] ?? '';
    $is_correct = $data['is_correct'] ?? '';

    $result_data = $this->taskAnswerModel->getById($id_doc);
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

      if (empty($task_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o taske'));
      } elseif (empty($student_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar o estudante'));
      } elseif (empty($question_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar a questão'));
      } else {
        if ($this->taskAnswerModel->update(
          $id_doc,
          $task_id,
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

    $result = $this->taskAnswerModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->taskAnswerModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /task_answer).'));
  }
}
