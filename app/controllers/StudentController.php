<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Student.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Student;
use app\utils\Response;
use Database;
use PDO;

class StudentController
{
  private $db;
  private $studentModel;

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
    $this->studentModel = new Student($this->db);
  }

  public function login()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $new_password = md5($password);

    if (empty($email)) {
      $return = ['error' => true, 'msg' => 'O campo email está vazio'];
    } elseif (empty($password)) {
      $return = ['error' => true, 'msg' => 'O campo palavra-passe de telefone está vazio'];
    } else {

      $result = $this->studentModel->getByEmailAndPassword($email, $new_password);
      $num = $result->rowCount();

      if ($num > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $student_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'phone' => $phone,
          'email' => $email,
          'gender' => $gender,
          'date_of_birth' => $date_of_birth,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'status' => $status,
          'password' => $password,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );
        $return = ['data' => $student_item, 'msg' => 'Login efetuado com sucesso.'];
      } else {
        $return = ['error' => true, 'msg' => 'Dados de aceeso incorretos, tente novamente.'];
      }
    }

    echo json_encode($return);
  }

  public function getAll()
  {
    $result = $this->studentModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $students_arr = array();
      $students_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $student_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'phone' => $phone,
          'email' => $email,
          'gender' => $gender,
          'date_of_birth' => $date_of_birth,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'status' => $status,
          'password' => $password,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($students_arr['data'], $student_item);
      }

      Response::send(200, $students_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->studentModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $student_item = array(
        'id' => $id,
        'photo' => $photo,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'phone' => $phone,
        'email' => $email,
        'gender' => $gender,
        'date_of_birth' => $date_of_birth,
        'course_id' => $course_id,
        'module_id' => $module_id,
        'status' => $status,
        'password' => $password,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, array('error' => false, 'msg' => 'Registo encontrado.', 'data' => $student_item));
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->studentModel->getByTerm($term);
    $num = $result->rowCount();

    $students_arr = array();

    if ($num > 0) {
      $students_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $student_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'phone' => $phone,
          'email' => $email,
          'gender' => $gender,
          'date_of_birth' => $date_of_birth,
          'course_id' => $course_id,
          'module_id' => $module_id,
          'status' => $status,
          'password' => $password,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($students_arr['data'], $student_item);
      }

      Response::send(200, $students_arr);
    } else {
      Response::send(200, array('error' => true, 'msg' => 'Nenhum registo encontrado.', $students_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $photo = $data['photo'] ?? '';
    $first_name = $data['first_name'] ?? '';
    $last_name = $data['last_name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $gender = $data['gender'] ?? '';
    $date_of_birth = $data['date_of_birth'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $module_id = $data['module_id'] ?? '';
    $status = $data['status'] ?? '';
    $password = $data['password'] ?? '';
    $new_password = md5($password);

    $result = $this->studentModel->getByEmail($email);
    $num_row = $result->rowCount();

    if ($num_row > 0) {
      Response::send(200, array('error' => true, 'msg' => 'Este email já encontra-se registado'));
    } else {
      if (empty($first_name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo primeiro nome está vazio'));
      } elseif (empty($last_name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo ultimo número de telefone está vazio'));
      } elseif (empty($phone)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
      } elseif (empty($email)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
      } elseif (empty($gender)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo genero está vazio'));
      } elseif (empty($date_of_birth)) {
        Response::send(200, array('error' => true, 'msg' => 'Informe a data de aniversario'));
      } elseif (empty($course_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
      } elseif (empty($module_id)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo módulo está vazio'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
      } elseif (empty($password)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo palavra-passe está vazio'));
      } else {

        if ($this->studentModel->createNew(
          $photo,
          $first_name,
          $last_name,
          $phone,
          $email,
          $gender,
          $date_of_birth,
          $course_id,
          $module_id,
          $status,
          $new_password
        )) {
          Response::send(200, array('error' => false, 'msg' => 'A criação foi um com sucesso.'));
        } else {
          Response::send(200, array('error' => true, 'msg' => 'Ocorreu um erro ao criar, por favor tente novamnete.'));
        }
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

    $photo_body = $data['photo'] ?? '';
    $first_name = $data['first_name'] ?? '';
    $last_name = $data['last_name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $gender = $data['gender'] ?? '';
    $date_of_birth = $data['date_of_birth'] ?? '';
    $course_id = $data['course_id'] ?? '';
    $module_id = $data['module_id'] ?? '';
    $status = $data['status'] ?? '';
    $new_password = '';

    $result_data = $this->studentModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);
    $row_email = $row['email'];

    $result_count = $this->studentModel->getByEmail($email);
    $num_row = $result_count->rowCount();

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {
      if ($num_row > 0 && $email !== $row_email) {
        Response::send(200, array('error' => true, 'msg' => 'Este registo já encontra-se registado'));
      } else {

        if (empty($photo_body)) {
          $photo_body = $row['photo'];
        }
        if (!empty($data['password'])) {
          $new_password = md5($data['password']);
        } else {
          $new_password = $row['password'];
        }

        if (empty($first_name)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo primeiro nome está vazio'));
        } elseif (empty($last_name)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo ultimo número de telefone está vazio'));
        } elseif (empty($phone)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
        } elseif (empty($email)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
        } elseif (empty($gender)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo genero está vazio'));
        } elseif (empty($date_of_birth)) {
          Response::send(200, array('error' => true, 'msg' => 'Informe a data de aniversario'));
        } elseif (empty($course_id)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo curso está vazio'));
        } elseif (empty($module_id)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo módulo está vazio'));
        } elseif (empty($status)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
        } else {
          if ($this->studentModel->update(
            $id_doc,
            $photo_body,
            $first_name,
            $last_name,
            $phone,
            $email,
            $gender,
            $date_of_birth,
            $course_id,
            $module_id,
            $status,
            $new_password
          )) {
            Response::send(200, array('error' => false, 'msg' => 'Registo atualizado com sucesso.'));
          } else {
            Response::send(500, array('error' => true, 'msg' => 'Ocorreu um erro ao atualizar o registo.'));
          }
        }
      }
    }
  }

  public function delete()
  {
    $id = $this->lastPart;

    $result = $this->studentModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Registo não encontrado'));
    } else {

      if ($this->studentModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Registo excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 200
  public function notFound()
  {
    Response::send(200, array('msg' => 'Erro: Ouve algum erro, tente novamente (rota: /student).'));
  }
}