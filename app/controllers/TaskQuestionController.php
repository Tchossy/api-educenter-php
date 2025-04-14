<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/TaskQuestion.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\TaskQuestion;
use app\utils\Response;
use Database;
use PDO;

class TaskQuestionController
{
  private $db;
  private $taskQuestionModel;

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
    $this->taskQuestionModel = new TaskQuestion($this->db);
  }

  public function getAll()
  {
    $result = $this->taskQuestionModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $task_questions_arr = array();
      $task_questions_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_question_item = array(
          'id' => $id,
          'task_id' => $task_id,
          'question_text' => $question_text,
          'question_type' => $question_type,
          'question_answer' => $question_answer,
          'question_image' => $question_image,
          'options' => $options,
          'value' => $value,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($task_questions_arr['data'], $task_question_item);
      }

      Response::send(200, $task_questions_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->taskQuestionModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $task_question_item = array(
        'id' => $id,
        'task_id' => $task_id,
        'question_text' => $question_text,
        'question_type' => $question_type,
        'question_answer' => $question_answer,
        'question_image' => $question_image,
        'options' => $options,
        'value' => $value,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $task_question_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }

  public function getByTask()
  {
    $id = $this->lastPart;

    $result = $this->taskQuestionModel->getByTask($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $task_questions_arr = array();
      $task_questions_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_question_item = array(
          'id' => $id,
          'task_id' => $task_id,
          'question_text' => $question_text,
          'question_type' => $question_type,
          'question_answer' => $question_answer,
          'question_image' => $question_image,
          'options' => $options,
          'value' => $value,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($task_questions_arr['data'], $task_question_item);
      }

      Response::send(200, $task_questions_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->taskQuestionModel->getByTerm($term);
    $num = $result->rowCount();

    $task_questions_arr = array();

    if ($num > 0) {
      $task_questions_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_question_item = array(
          'id' => $id,
          'task_id' => $task_id,
          'question_text' => $question_text,
          'question_type' => $question_type,
          'question_answer' => $question_answer,
          'question_image' => $question_image,
          'options' => $options,
          'value' => $value,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($task_questions_arr['data'], $task_question_item);
      }

      Response::send(200, $task_questions_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $task_questions_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $task_id = $data['task_id'] ?? '';
    $question_text = $data['question_text'] ?? '';
    $question_type = $data['question_type'] ?? '';
    $question_answer = $data['question_answer'] ?? '';
    $question_image = $data['question_image'] ?? '';
    $options = $data['options'] ?? '';
    $value = $data['value'] ?? '';


    if (empty($task_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar a tarefa'));
    } elseif (empty($question_text)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo questão está vazio'));
    } elseif (empty($question_type)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo tipo de questão está vazio'));
    } elseif (empty($value)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo valor da questão está vazio'));
    } else {

      if ($this->taskQuestionModel->createNew(
        $task_id,
        $question_text,
        $question_type,
        $question_answer,
        $question_image,
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

    $task_id = $data['task_id'] ?? '';
    $question_text = $data['question_text'] ?? '';
    $question_type = $data['question_type'] ?? '';
    $question_answer = $data['question_answer'] ?? '';
    $question_image = $data['question_image'] ?? '';
    $options_body = $data['options'] ?? '';
    $value = $data['value'] ?? '';

    $result_data = $this->taskQuestionModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($question_answer)) {
        $question_answer = $row['question_answer'];
      }
      if (empty($question_image)) {
        $question_image = $row['question_image'];
      }
      if (empty($options_body)) {
        $options_body = $row['options'];
      }

      if (empty($task_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Erro ao identificar a tarefa'));
      } elseif (empty($question_text)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo questão está vazio'));
      } elseif (empty($question_type)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo tippo de questão está vazio'));
      } elseif (empty($value)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo valor da questão está vazio'));
      } else {
        if ($this->taskQuestionModel->update(
          $id_doc,
          $task_id,
          $question_text,
          $question_type,
          $question_answer,
          $question_image,
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

    $result = $this->taskQuestionModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->taskQuestionModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /task_question).'));
  }
}