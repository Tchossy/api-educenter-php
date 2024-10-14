<?php

namespace app\routes;

class Routes
{
  public static function get()
  {
    return [
      'get' => [
        // Admin
        '/admin/get/all' => 'AdminController@getAll', // "✅"
        '/admin/get/one/[0-9]+' => 'adminController@getById', // "✅"
        '/admin/search/all/[A-Za-z0-9]+' => 'AdminController@searchByTerm', // "✅"

        // Professor
        '/professor/get/all' => 'ProfessorController@getAll', // "✅"
        '/professor/get/one/[0-9]+' => 'ProfessorController@getById', // "✅"
        '/professor/search/all/[A-Za-z0-9]+' => 'ProfessorController@searchByTerm', // "✅"

        // Student
        '/student/get/all' => 'StudentController@getAll', // "✅"
        '/student/get/one/[0-9]+' => 'StudentController@getById', // "✅"
        '/student/search/all/[A-Za-z0-9]+' => 'StudentController@searchByTerm', // "✅"

        // Course
        '/course/get/all' => 'CourseController@getAll', // "✅"
        '/course/get/one/[0-9]+' => 'CourseController@getById', // "✅"
        '/course/search/all/[A-Za-z0-9]+' => 'CourseController@searchByTerm', // "✅"

        // Module
        '/module/get/all' => 'ModuleController@getAll', // "✅"
        '/module/get/one/[0-9]+' => 'ModuleController@getById', // "✅"
        '/module/search/all/[A-Za-z0-9]+' => 'ModuleController@searchByTerm', // "✅"

        // Material
        '/material/get/all' => 'MaterialController@getAll', // "✅"
        '/material/get/one/[0-9]+' => 'MaterialController@getById', // "✅"
        '/material/search/all/[A-Za-z0-9]+' => 'MaterialController@searchByTerm', // "✅"
        '/material/get/by/module/[0-9]+' => 'MaterialController@getByModule', // "✅"
        '/material/get/all/by/module/[0-9]+' => 'MaterialController@getAllByModule', // "✅"

        // Task
        '/task/get/all' => 'TaskController@getAll', // "✅"
        '/task/get/one/[0-9]+' => 'TaskController@getById', // "✅"
        '/task/search/all/[A-Za-z0-9]+' => 'TaskController@searchByTerm', // "✅"

        '/task/get/all/by/module/[0-9]+' => 'TaskController@getAllByModule', // "✅"

        // Task submission
        '/task/submission/get/all' => 'TaskSubmissionController@getAll', // "✅"
        '/task/submission/get/one/[0-9]+' => 'TaskSubmissionController@getById', // "✅"
        '/task/submission/search/all/[A-Za-z0-9]+' => 'TaskSubmissionController@searchByTerm', // "✅"

        '/task/submission/get/all/by/student/[0-9]+' => 'TaskSubmissionController@getAllByStudent', // "✅"

        // Exam
        '/exam/get/all' => 'ExamController@getAll', // "✅"
        '/exam/get/one/[0-9]+' => 'ExamController@getById', // "✅"
        '/exam/search/all/[A-Za-z0-9]+' => 'ExamController@searchByTerm', // "✅"

        '/exam/get/all/by/module/[0-9]+' => 'ExamController@getByModule', // "✅"

        // Exam Question
        '/exam/question/get/all' => 'ExamQuestionController@getAll', // "✅"
        '/exam/question/get/one/[0-9]+' => 'ExamQuestionController@getById', // "✅"

        '/exam/question/get/all/by/exam/[0-9]+' => 'ExamQuestionController@getByExam', // "✅"

        // Exam Answer
        '/exam/answer/get/all' => 'ExamAnswerController@getAll', // "✅"
        '/exam/answer/get/one/[0-9]+' => 'ExamAnswerController@getById', // "✅"

        '/exam/answer/get/all/by/result/[0-9]+' => 'ExamAnswerController@getByExam', // "✅"

        // Exam Result
        '/exam/result/get/all' => 'ExamResultController@getAll', // "✅"
        '/exam/result/get/one/[0-9]+' => 'ExamResultController@getById', // "✅"
        '/exam/result/search/all/[A-Za-z0-9]+' => 'ExamResultController@searchByTerm', // "✅"

        '/exam/result/get/all/by/student/[0-9]+' => 'ExamResultController@getByStudent', // "✅"
      ],
      'post' => [
        // Admin
        '/admin/create' => 'AdminController@create',
        '/admin/login' => 'AdminController@login',

        // Professor
        '/professor/create' => 'ProfessorController@create',

        // Student
        '/student/create' => 'StudentController@create',

        // Course
        '/course/create' => 'CourseController@create',

        // Module
        '/module/create' => 'ModuleController@create',

        // Material
        '/material/create' => 'MaterialController@create',

        // Task
        '/task/create' => 'TaskController@create',

        // Task submission
        '/task/submission/create' => 'TaskSubmissionController@create',

        // Exam
        '/exam/create' => 'ExamController@create',

        // Exam question
        '/exam/question/create' => 'ExamQuestionController@create',

        // Exam answer
        '/exam/answer/create' => 'ExamAnswerController@create',

        // Exam result
        '/exam/result/create' => 'ExamResultController@create',


        // Upload
        '/upload/image/admin' => 'UploadController@imageAdmin', //
        '/upload/image/professor' => 'UploadController@imageProfessor', //
        '/upload/image/student' => 'UploadController@imageStudent', //
        '/upload/image/course' => 'UploadController@imageCourse', //
        '/upload/image/exam' => 'UploadController@imageExam', //

        '/upload/image/task' => 'UploadController@imageTask', //

        '/upload/video/material' => 'UploadController@videoMaterial', // 

        '/upload/pdf/material' => 'UploadController@pdfMaterial', // 
        '/upload/pdf/task/instruction' => 'UploadController@pdfTaskInstruction', //
        '/upload/pdf/task/submition' => 'UploadController@pdfTaskSubmition', //

      ],
      'delete' => [
        // Admin
        '/admin/delete/[0-9]+' => 'AdminController@delete',

        // Professor
        '/professor/delete/[0-9]+' => 'ProfessorController@delete',

        // Student
        '/student/delete/[0-9]+' => 'StudentController@delete',

        // Course
        '/course/delete/[0-9]+' => 'CourseController@delete',

        // Module
        '/module/delete/[0-9]+' => 'ModuleController@delete',

        // Material
        '/material/delete/[0-9]+' => 'MaterialController@delete',

        // Task
        '/task/delete/[0-9]+' => 'TaskController@delete',

        // Task submission
        '/task/submission/delete/[0-9]+' => 'TaskSubmissionController@delete',

        // Exam
        '/exam/delete/[0-9]+' => 'ExamController@delete',

        // Exam question
        '/exam/question/delete/[0-9]+' => 'ExamQuestionController@delete',

        // Exam answer
        '/exam/answer/delete/[0-9]+' => 'ExamAnswerController@delete',

        // Exam result
        '/exam/result/delete/[0-9]+' => 'ExamResultController@delete',
      ],
      'put' => [
        // Admin
        '/admin/update/[0-9]+' => 'AdminController@update',

        // Professor
        '/professor/update/[0-9]+' => 'ProfessorController@update',

        // Student
        '/student/update/[0-9]+' => 'StudentController@update',

        // Course
        '/course/update/[0-9]+' => 'CourseController@update',

        // Module
        '/module/update/[0-9]+' => 'ModuleController@update',

        // Material
        '/material/update/[0-9]+' => 'MaterialController@update',

        // Task
        '/task/update/[0-9]+' => 'TaskController@update',

        // Task submission
        '/task/submission/update/[0-9]+' => 'TaskSubmissionController@update',

        // Exam
        '/exam/update/[0-9]+' => 'ExamController@update',

        // Exam question
        '/exam/question/update/[0-9]+' => 'ExamQuestionController@update',

        // Exam answer
        '/exam/answer/update/[0-9]+' => 'ExamAnswerController@update',

        // Exam result
        '/exam/result/update/[0-9]+' => 'ExamResultController@update',
      ],
    ];
  }
};