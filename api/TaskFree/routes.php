<?php

/**
 * Configure Slim Application Routes
 * @param  [Slim] $app Slim application instance
 */
function configureRoutes($app) {
  $app->group('/api/v1', function () {
    $this->get('/status', 'TaskFree\Controllers\Status:getStatus');

    $this->group('/user', function () {
      $this->get('/{id:[0-9]+}', 'TaskFree\Controllers\User:getUser');
      $this->post('', 'TaskFree\Controllers\User:addUser');
      $this->get('/score','TaskFree\Controllers\User:viewScore');
    });

    $this->group('/category', function () {
      $this->get('', 'TaskFree\Controllers\Category:getAll');
      $this->get('/{id:[0-9]+}', 'TaskFree\Controllers\Category:getCategory');
    });

    $this->group('/task', function () {
      $this->get("", 'TaskFree\Controllers\Task:listTask');
      $this->post("", 'TaskFree\Controllers\Task:addTask');

      $this->get('/{id:[0-9]+}', 'TaskFree\Controllers\Task:getTask');
      $this->put('/{id:[0-9]+}', 'TaskFree\Controllers\Task:updateTask');
      $this->delete('/{id:[0-9]+}', 'TaskFree\Controllers\Task:deleteTask');

      $this->get('/{id:[0-9]+}/pick', 'TaskFree\Controllers\Task:getPickByTaskId');
      $this->post('/{id:[0-9]+}/pick', 'TaskFree\Controllers\Task:pickTask');

      $this->get('/{id:[0-9]+}/steps', 'TaskFree\Controllers\Task:getStepsByTaskId');
      $this->get('/{id:[0-9]+}/steps/{sid:[0-9]+}', 'TaskFree\Controllers\Task:getStep');
      $this->delete('/{id:[0-9]+}/steps/{sid:[0-9]+}', 'TaskFree\Controllers\Task:deleteStep');
      $this->put('/{id:[0-9]+}/steps/{sid:[0-9]+}', 'TaskFree\Controllers\Task:updateStep');

      $this->put('/completeStep','TaskFree\Controllers\Task:completeStep');
      $this->put('/updatePick','TaskFree\Controllers\Task:updatePick');
      $this->put('/approvePick', 'TaskFree\Controllers\Task:approvePick');
    });

    $this->group('/auth', function () {
      $this->get('', 'TaskFree\Controllers\Authentication:getStatus');
      $this->post('/signin', 'TaskFree\Controllers\Authentication:signin');
      $this->get('/signout', 'TaskFree\Controllers\Authentication:signout');
    });

    $this->group('/search', function() {
      $this->get('', 'TaskFree\Controllers\Search:search');
    });
  });
}

?>
