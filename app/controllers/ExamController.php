<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Exam.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Exam;
use app\utils\Response;
use Database;
use PDO;

class ExamController
{
  private $db;
  private $examModel;

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
    $this->examModel = new Exam($this->db);
  }

  public function getAll()
  {
    $result = $this->examModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $exams_arr = array();
      $exams_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'start_time' => $start_time,
          'end_time' => $end_time,
          'date_exam' => $date_exam,
          'mark' => $mark,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exams_arr['data'], $exam_item);
      }

      Response::send(200, $exams_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->examModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $exam_item = array(
        'id' => $id,
        'image' => $image,
        'name' => $name,
        'description' => $description,
        'course_id' => $course_id,
        'module_id' => $module_id,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'date_exam' => $date_exam,
        'mark' => $mark,
        'status' => $status,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $exam_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }

  public function getByModule()
  {
    $id = $this->lastPart;

    $result = $this->examModel->getByModule($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $exams_arr = array();
      $exams_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'start_time' => $start_time,
          'end_time' => $end_time,
          'date_exam' => $date_exam,
          'mark' => $mark,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exams_arr['data'], $exam_item);
      }

      Response::send(200, $exams_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getByStudent()
  {
    $id = $this->lastPart;

    $result = $this->examModel->getByStudent($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $exams_arr = array();
      $exams_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'start_time' => $start_time,
          'end_time' => $end_time,
          'date_exam' => $date_exam,
          'mark' => $mark,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exams_arr['data'], $exam_item);
      }

      Response::send(200, $exams_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->examModel->getByTerm($term);
    $num = $result->rowCount();

    $exams_arr = array();

    if ($num > 0) {
      $exams_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $exam_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'start_time' => $start_time,
          'end_time' => $end_time,
          'date_exam' => $date_exam,
          'mark' => $mark,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($exams_arr['data'], $exam_item);
      }

      Response::send(200, $exams_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $exams_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $image = $data['image'] ?? '';
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $module_id = $data['module_id'] ?? '';
    $start_time = $data['start_time'] ?? '';
    $end_time = $data['end_time'] ?? '';
    $date_exam = $data['date_exam'] ?? '';
    $mark = $data['mark'] ?? '';
    $status = $data['status'] ?? '';


    if (empty($name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
    } elseif (empty($description)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
    } elseif (empty($course_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
    } elseif (empty($module_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo módulo está vazio'));
    } elseif (empty($start_time)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo hora de inicio está vazio'));
    } elseif (empty($end_time)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo hora do fim está vazio'));
    } elseif (empty($date_exam)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo data está vazio'));
    } elseif (empty($mark)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo pontução está vazio'));
    } elseif (empty($status)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
    } else {

      $exam_id = $this->examModel->createNew(
        $image,
        $name,
        $description,
        $course_id,
        $module_id,
        $start_time,
        $end_time,
        $date_exam,
        $mark,
        $status
      );

      if ($exam_id) {
        // Busca os dados completos do exame recém-criado
        $result = $this->examModel->getById($exam_id);

        // Verifica se o exame foi encontrado
        $num = $result->rowCount();
        if ($num > 0) {
          $row = $result->fetch(PDO::FETCH_ASSOC);
          extract($row);
          $exam_item = array(
            'id' => $id,
            'image' => $image,
            'name' => $name,
            'description' => $description,
            'course_id' => $course_id,
            'module_id' => $module_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'date_exam' => $date_exam,
            'mark' => $mark,
            'status' => $status,
            'date_create' => $date_create,
            'date_update' => $date_update,
          );

          // Retorna os dados do exame recém-criado
          Response::send(200, array(
            'error' => false,
            'msg' => 'A criação foi um sucesso.',
            'data' => $exam_item
          ));
        } else {
          // Caso o exame não tenha sido encontrado
          Response::send(200, array('error' => true, 'msg' => 'Erro ao buscar o exame criado.'));
        }
      } else {
        // Caso ocorra um erro ao criar o exame
        Response::send(200, array('error' => true, 'msg' => 'Ocorreu um erro ao criar, por favor tente novamente.'));
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

    $image_body = $data['image'] ?? '';
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $module_id = $data['module_id'] ?? '';
    $start_time = $data['start_time'] ?? '';
    $end_time = $data['end_time'] ?? '';
    $date_exam = $data['date_exam'] ?? '';
    $mark = $data['mark'] ?? '';
    $status = $data['status'] ?? '';

    $result_data = $this->examModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($image_body)) {
        $image_body = $row['image'];
      }

      if (empty($name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
      } elseif (empty($description)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
      } elseif (empty($course_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
      } elseif (empty($module_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo módulo está vazio'));
      } elseif (empty($start_time)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo hora de inicio está vazio'));
      } elseif (empty($end_time)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo hora do fim está vazio'));
      } elseif (empty($date_exam)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo data está vazio'));
      } elseif (empty($mark)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo pontução está vazio'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
      } else {
        if ($this->examModel->update(
          $id_doc,
          $image_body,
          $name,
          $description,
          $course_id,
          $module_id,
          $start_time,
          $end_time,
          $date_exam,
          $mark,
          $status,
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

    $result = $this->examModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->examModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /exam).'));
  }
}