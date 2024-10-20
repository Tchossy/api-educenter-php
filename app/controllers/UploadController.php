<?php

namespace app\controllers;

require_once(__DIR__ . '/../utils/Response.php');

use app\utils\Response;

class UploadController
{
  public function imageAdmin()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageAdmin']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['imageAdmin'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_imagesDb/admin/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "img_admin-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $image_admin = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'url' => $image_admin]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou a imagem.'];
      echo json_encode($return);
    }
  }

  public function imageProfessor()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageProfessor']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['imageProfessor'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_imagesDb/professor/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "img_professor-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $image_professor = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'url' => $image_professor]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => "Erro: Não selecionou a imagem."]);
    }
  }

  public function imageStudent()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageStudent']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['imageStudent'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_imagesDb/student/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "img_student-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $image_student = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'url' => $image_student]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => "Erro: Não selecionou a imagem."]);
    }
  }

  public function imageCourse()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageCourse']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['imageCourse'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_imagesDb/course/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "img_course-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $image_course = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'url' => $image_course]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => "Erro: Não selecionou a imagem."]);
    }
  }

  public function imageExam()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageExam']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['imageExam'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_imagesDb/exam/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "img_exam-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $image_exam = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'url' => $image_exam]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => "Erro: Não selecionou a imagem."]);
    }
  }

  public function imageTask()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageTask']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['imageTask'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_imagesDb/task/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "img_task-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $image_task = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'url' => $image_task]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => "Erro: Não selecionou a imagem."]);
    }
  }

  public function pdfTaskInstruction()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['pdfTaskInstruction']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['pdfTaskInstruction'];
      $size_max = 10485760; //10MB
      $accept  = array("pdf");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: O pdf excedeu o tamanho máximo de 4MB!"];
        Response::send(200, ['error' => true, 'msg' => "Erro: O pdf excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_pdfDb/task_instruction/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "pdf_task_instruction-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $pdf_task_instruction = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload do pdf realizado com sucesso", 'url' => $pdf_task_instruction]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou o pdf.'];
      echo json_encode($return);
    }
  }

  public function pdfTaskSubmition()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['pdfTaskSubmition']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['pdfTaskSubmition'];
      $size_max = 10485760; //10MB
      $accept  = array("pdf");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: O pdf excedeu o tamanho máximo de 4MB!"];
        Response::send(200, ['error' => true, 'msg' => "Erro: O pdf excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_pdfDb/task_submition/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "pdf_task_submition-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $pdf_task_submition = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload do pdf realizado com sucesso", 'url' => $pdf_task_submition]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou o pdf.'];
      echo json_encode($return);
    }
  }

  public function pdfMaterial()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['pdfMaterial']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['pdfMaterial'];
      $size_max = 10485760; //10MB
      $accept  = array("pdf");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: O pdf excedeu o tamanho máximo de 4MB!"];
        Response::send(200, ['error' => true, 'msg' => "Erro: O pdf excedeu o tamanho máximo de 4MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos
      $folder = '_pdfDb/material/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "pdf_material-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $pdf_material = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload do pdf realizado com sucesso", 'url' => $pdf_material]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou o pdf.'];
      echo json_encode($return);
    }
  }

  public function videoMaterial()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['videoMaterial']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['videoMaterial'];
      $size_max = 104857600; //100MB
      $accept = array("mp4"); // Adicione as extensões de video permitidas
      $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

      // Verifica o tamanho do arquivo
      if ($file['size'] >= $size_max) {
        Response::send(200, ['error' => true, 'msg' => "Erro: O video excedeu o tamanho máximo de 100MB!"]);
        return; // Retorna imediatamente se o tamanho exceder o limite
      }

      // Verifica a extensão do arquivo
      if (!in_array($extension, $accept)) {
        Response::send(200, ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"]);
        return; // Retorna imediatamente se a extensão não for permitida
      }

      // Diretório para armazenar os arquivos de video
      $folder = '_video/material/';

      if (!is_dir($folder)) {
        mkdir($folder, 755, true);
      }

      // Nome temporário do arquivo
      $tmp = $file['tmp_name'];
      // Novo nome do arquivo
      $newName = "video_material-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
      // Caminho completo para o novo arquivo
      $newPath = $folder . $newName;

      // Move o arquivo para o diretório de destino
      if (move_uploaded_file($tmp, $newPath)) {
        $video_material = 'http://localhost:8000/' . $newPath;

        Response::send(200, ['error' => false, 'msg' => "Upload do video realizado com sucesso", 'url' => $video_material]);
      } else {
        Response::send(200, ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo de video."]);
        return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => 'Não selecionou o video.']);
    }
  }

  public function imagesNews()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['imageNews'])) {
      // Obtém as informações dos arquivos
      $files = $_FILES['imageNews'];

      // Array para armazenar os caminhos das imagens
      $imagePaths = [];

      // Verifica se realmente um array
      // if (is_array($files['name'])) {
      //   Response::send(200, ['error' => true, 'msg' => "Nenhum arquivo foi enviado!"]);
      //   return;
      // }

      // Percorre cada arquivo
      foreach ($files['name'] as $key => $name) {
        $size_max = 4916838; // 4MB
        $accept = array("jpg", "png", "jpeg");
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        // Verifica o tamanho do arquivo
        if ($files['size'][$key] >= $size_max) {
          $return = ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"];
          echo json_encode($return);
          return; // Retorna imediatamente se o tamanho exceder o limite
        }

        // Verifica a extensão do arquivo
        if (!in_array($extension, $accept)) {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"];
          echo json_encode($return);
          return; // Retorna imediatamente se a extensão não for permitida
        }

        // Diretório para armazenar os arquivos
        $folder = '_imagesDb/news/';

        if (!is_dir($folder)) {
          mkdir($folder, 0755, true);
        }

        // Nome temporário do arquivo
        $tmp = $files['tmp_name'][$key];
        // Novo nome do arquivo
        $newName = "img_news-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
        // Caminho completo para o novo arquivo
        $newPath = $folder . $newName;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($tmp, $newPath)) {
          $image_news = 'http://localhost:8000/' . $newPath;
          $imagePaths[] = $image_news;
        } else {
          $return = ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."];
          echo json_encode($return);
          return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
        }
      }

      // Retorna a resposta com sucesso
      Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'imagesUrl' => $imagePaths]);
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      Response::send(200, ['error' => true, 'msg' => 'Não selecionou a(s) imagem(s) da(s) notícia.']);
    }
  }
}