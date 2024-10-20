<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Task.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Task;
use app\utils\Response;
use Database;
use PDO;

class TaskController
{
  private $db;
  private $taskModel;

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
    $this->taskModel = new Task($this->db);
  }

  public function getAll()
  {
    $result = $this->taskModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $tasks_arr = array();
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'mark' => $mark,
          'task_type' => $task_type,
          'due_date' => $due_date,
          'status' => $status,
          'file_url' => $file_url,
          'date_create' => $date_create,
          'date_update' => $date_update
        );

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getAllByModule()
  {
    $id = $this->lastPart;

    $result = $this->taskModel->getAllByModule($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $tasks_arr = array();
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'mark' => $mark,
          'task_type' => $task_type,
          'due_date' => $due_date,
          'status' => $status,
          'file_url' => $file_url,
          'date_create' => $date_create,
          'date_update' => $date_update
        );

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->taskModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $task_item = array(
        'id' => $id,
        'image' => $image,
        'name' => $name,
        'description' => $description,
        'course_id' => $course_id,
        'module_id' => $module_id,
        'mark' => $mark,
        'task_type' => $task_type,
        'due_date' => $due_date,
        'status' => $status,
        'file_url' => $file_url,
        'date_create' => $date_create,
        'date_update' => $date_update
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $task_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->taskModel->getByTerm($term);
    $num = $result->rowCount();

    $tasks_arr = array();

    if ($num > 0) {
      $tasks_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $task_item = array(
          'id' => $id,
          'image' => $image,
          'name' => $name,
          'description' => $description,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'mark' => $mark,
          'task_type' => $task_type,
          'due_date' => $due_date,
          'status' => $status,
          'file_url' => $file_url,
          'date_create' => $date_create,
          'date_update' => $date_update
        );

        array_push($tasks_arr['data'], $task_item);
      }

      Response::send(200, $tasks_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $tasks_arr));
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
    $mark = $data['mark'] ?? '';
    $task_type = $data['task_type'] ?? '';
    $due_date = $data['due_date'] ?? '';
    $status = $data['status'] ?? '';
    $file_url = $data['file_url'] ?? '';

    if (empty($name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
    } elseif (empty($description)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
    } elseif (empty($course_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
    } elseif (empty($module_id)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo modulo está vazio'));
    } elseif (empty($mark)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo nota está vazio'));
    } elseif (empty($task_type)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo tipo de tarefa está vazio'));
    } elseif (empty($due_date)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo data limite está vazio'));
    } elseif (empty($status)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
    } else {

      if ($this->taskModel->createNew(
        $image,
        $name,
        $description,
        $course_id,
        $module_id,
        $mark,
        $task_type,
        $due_date,
        $status,
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

    $image_body = $data['image'] ?? '';
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $module_id = $data['module_id'] ?? '';
    $mark = $data['mark'] ?? '';
    $task_type = $data['task_type'] ?? '';
    $due_date = $data['due_date'] ?? '';
    $status = $data['status'] ?? '';
    $file_url_body = $data['file_url'] ?? '';

    $result_data = $this->taskModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if (empty($image_body)) {
        $image_body = $row['image'];
      }
      if (empty($file_url_body)) {
        $file_url_body = $row['file_url'];
      }

      if (empty($name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo nome nome está vazio'));
      } elseif (empty($description)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo descrição está vazio'));
      } elseif (empty($course_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
      } elseif (empty($module_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo modulo está vazio'));
      } elseif (empty($mark)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo nota está vazio'));
      } elseif (empty($task_type)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo tipo de tarefa está vazio'));
      } elseif (empty($due_date)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo data limite está vazio'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
      } else {
        if ($this->taskModel->update(
          $id_doc,
          $image_body,
          $name,
          $description,
          $course_id,
          $module_id,
          $mark,
          $task_type,
          $due_date,
          $status,
          $file_url_body
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

    $result = $this->taskModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->taskModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /task).'));
  }
}