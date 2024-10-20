<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Course.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Course;
use app\utils\Response;
use Database;
use PDO;

class CourseController
{
  private $db;
  private $courseModel;

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
    $this->courseModel = new Course($this->db);
  }

  public function getAll()
  {
    $result = $this->courseModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $courses_arr = array();
      $courses_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $course_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'duration' => $duration,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($courses_arr['data'], $course_item);
      }

      Response::send(200, $courses_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->courseModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $course_item = array(
        'id' => $id,
        'image' => $image,
        'name' => $name,
        'description' => $description,
        'duration' => $duration,
        'status' => $status,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $course_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->courseModel->getByTerm($term);
    $num = $result->rowCount();

    $courses_arr = array();

    if ($num > 0) {
      $courses_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $course_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'duration' => $duration,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($courses_arr['data'], $course_item);
      }

      Response::send(200, $courses_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $courses_arr));
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
    $duration = $data['duration'] ?? '';
    $status = $data['status'] ?? '';


    if (empty($name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
    } elseif (empty($description)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
    } elseif (empty($duration)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo duração está vazio'));
    } elseif (empty($status)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
    } else {

      if ($this->courseModel->createNew(
        $image,
        $name,
        $description,
        $duration,
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

    $image_body = $data['image'] ?? '';
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $duration = $data['duration'] ?? '';
    $status = $data['status'] ?? '';

    $result_data = $this->courseModel->getById($id_doc);
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
      } elseif (empty($duration)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo duração está vazio'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
      } else {
        if ($this->courseModel->update(
          $id_doc,
          $image_body,
          $name,
          $description,
          $duration,
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

    $result = $this->courseModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->courseModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /course).'));
  }
}