<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/WeeklyAverage.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\WeeklyAverage;
use app\utils\Response;
use Database;
use PDO;

class WeeklyAverageController
{
  private $db;
  private $weeklyAverageModel;

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
    $this->weeklyAverageModel = new WeeklyAverage($this->db);
  }

  public function getAll()
  {
    $result = $this->weeklyAverageModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $weekly_averages_arr = array();
      $weekly_averages_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $weekly_average_item = array(
          'id' => $id,
          'student_id' => $student_id,
          'week_start' => $week_start,
          'week_end' => $week_end,
          'task_average' => $task_average,
          'exam_grade' => $exam_grade,
          'weekly_average' => $weekly_average,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($weekly_averages_arr['data'], $weekly_average_item);
      }

      Response::send(200, $weekly_averages_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }
  public function getAllByStudent()
  {
    $id = $this->lastPart;

    $result = $this->weeklyAverageModel->getAllByStudent($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $weekly_averages_arr = array();
      $weekly_averages_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $weekly_average_item = array(
          'id' => $id,
          'student_id' => $student_id,
          'week_start' => $week_start,
          'week_end' => $week_end,
          'task_average' => $task_average,
          'exam_grade' => $exam_grade,
          'weekly_average' => $weekly_average,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($weekly_averages_arr['data'], $weekly_average_item);
      }

      Response::send(200, $weekly_averages_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->weeklyAverageModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $weekly_average_item = array(
        'id' => $id,
        'student_id' => $student_id,
        'week_start' => $week_start,
        'week_end' => $week_end,
        'task_average' => $task_average,
        'exam_grade' => $exam_grade,
        'weekly_average' => $weekly_average,
        'status' => $status,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $weekly_average_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $student_id = $data['student_id'] ?? '';
    $week_start = $data['week_start'] ?? '';
    $week_end = $data['week_end'] ?? '';
    $task_average = $data['task_average'] ?? '';
    $exam_grade = $data['exam_grade'] ?? '';
    $weekly_average = $data['weekly_average'] ?? '';
    $status = $data['status'] ?? '';

    if (empty($student_id)) {
      Response::send(200, array('error' => true, 'msg' => 'Não foi possivel identificar o estudante'));
    } elseif (empty($week_start)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo inicio da semana'));
    } elseif (empty($week_end)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo fim da semana'));
    } elseif (empty($status)) {
      Response::send(200, array('error' => true, 'msg' => 'Não foi possível obter o status da semana'));
    } else {

      if ($this->weeklyAverageModel->createNew(
        $student_id,
        $week_start,
        $week_end,
        $task_average,
        $exam_grade,
        $weekly_average,
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

    $student_id = $data['student_id'] ?? '';
    $week_start = $data['week_start'] ?? '';
    $week_end = $data['week_end'] ?? '';
    $task_average = $data['task_average'] ?? '';
    $exam_grade = $data['exam_grade'] ?? '';
    $weekly_average = $data['weekly_average'] ?? '';
    $status = $data['status'] ?? '';

    $result_data = $this->weeklyAverageModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if (empty($student_id)) {
        Response::send(200, array('error' => true, 'msg' => 'Não foi possivel identificar o estudante'));
      } elseif (empty($week_start)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo inico da semana'));
      } elseif (empty($week_end)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo fim da semana'));
      } elseif (empty($task_average)) {
        Response::send(200, array('error' => true, 'msg' => 'Não foi possível calcular a média das tarefas'));
      } elseif (empty($exam_grade)) {
        Response::send(200, array('error' => true, 'msg' => 'Não foi possível obter a nota do exame'));
      } elseif (empty($weekly_average)) {
        Response::send(200, array('error' => true, 'msg' => 'Não foi possível calcular a média semanal'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'Não foi possível obter o status da semana'));
      } else {
        if ($this->weeklyAverageModel->update(
          $id_doc,
          $student_id,
          $week_start,
          $week_end,
          $task_average,
          $exam_grade,
          $weekly_average,
          $status
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

    $result = $this->weeklyAverageModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->weeklyAverageModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /weekly_average).'));
  }
}