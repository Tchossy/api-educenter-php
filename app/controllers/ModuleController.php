<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Module.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Module;
use app\utils\Response;
use Database;
use PDO;

class ModuleController
{
  private $db;
  private $moduleModel;

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
    $this->moduleModel = new Module($this->db);
  }

  public function getAll()
  {
    $result = $this->moduleModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $modules_arr = array();
      $modules_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $module_item = array(
          'id' => $id,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($modules_arr['data'], $module_item);
      }

      Response::send(200, $modules_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->moduleModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $module_item = array(
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'course_id' => $course_id,
        'status' => $status,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $module_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->moduleModel->getByTerm($term);
    $num = $result->rowCount();

    $modules_arr = array();

    if ($num > 0) {
      $modules_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $module_item = array(
          'id' => $id,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'status' => $status,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($modules_arr['data'], $module_item);
      }

      Response::send(200, $modules_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $modules_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $status = $data['status'] ?? '';

    if (empty($name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
    } elseif (empty($description)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
    } elseif (empty($course_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
    } elseif (empty($status)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
    } else {

      if ($this->moduleModel->createNew(
        $name,
        $description,
        $course_id,
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

    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $status = $data['status'] ?? '';

    $result_data = $this->moduleModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if (empty($name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
      } elseif (empty($description)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
      } elseif (empty($course_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
      } else {
        if ($this->moduleModel->update(
          $id_doc,
          $name,
          $description,
          $course_id,
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

    $result = $this->moduleModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->moduleModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /module).'));
  }
}