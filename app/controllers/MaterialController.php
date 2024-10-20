<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Material.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Material;
use app\utils\Response;
use Database;
use PDO;

class MaterialController
{
  private $db;
  private $materialModel;

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
    $this->materialModel = new Material($this->db);
  }

  public function getAll()
  {
    $result = $this->materialModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $materials_arr = array();
      $materials_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $material_item = array(
          'id' => $id,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'material_type' => $material_type,
          'file_url' => $file_url,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($materials_arr['data'], $material_item);
      }

      Response::send(200, $materials_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->materialModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $material_item = array(
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'course_id' => $course_id,
        'module_id' => $module_id,
        'material_type' => $material_type,
        'file_url' => $file_url,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $material_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function getByModule()
  {
    $id = $this->lastPart;

    $result = $this->materialModel->getByModule($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $material_item = array(
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'course_id' => $course_id,
        'module_id' => $module_id,
        'material_type' => $material_type,
        'file_url' => $file_url,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $material_item);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function getAllByModule()
  {
    $id = $this->lastPart;

    $result = $this->materialModel->getAllByModule($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $materials_arr = array();
      $materials_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $material_item = array(
          'id' => $id,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'material_type' => $material_type,
          'file_url' => $file_url,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($materials_arr['data'], $material_item);
      }

      Response::send(200, $materials_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->materialModel->getByTerm($term);
    $num = $result->rowCount();

    $materials_arr = array();

    if ($num > 0) {
      $materials_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $material_item = array(
          'id' => $id,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'material_type' => $material_type,
          'file_url' => $file_url,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($materials_arr['data'], $material_item);
      }

      Response::send(200, $materials_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $materials_arr));
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
    $module_id = $data['module_id'] ?? '';
    $material_type = $data['material_type'] ?? '';
    $file_url = $data['file_url'] ?? '';


    if (empty($name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
    } elseif (empty($description)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
    } elseif (empty($course_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
    } elseif (empty($module_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo módulo está vazio'));
    } elseif (empty($material_type)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo tipo de material está vazio'));
    } else {

      if ($this->materialModel->createNew(
        $name,
        $description,
        $course_id,
        $module_id,
        $material_type,
        $file_url
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

    $file_body = $data['file_url'] ?? '';
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $module_id = $data['module_id'] ?? '';
    $material_type = $data['material_type'] ?? '';

    $result_data = $this->materialModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($file_body)) {
        $file_body = $row['file_url'];
      }

      if (empty($name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
      } elseif (empty($description)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
      } elseif (empty($course_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
      } elseif (empty($module_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo módulo está vazio'));
      } elseif (empty($material_type)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo tipo de material está vazio'));
      } else {
        if ($this->materialModel->update(
          $id_doc,
          $file_body,
          $name,
          $description,
          $course_id,
          $module_id,
          $material_type
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

    $result = $this->materialModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->materialModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /material).'));
  }
}