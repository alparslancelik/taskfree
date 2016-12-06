<?php

namespace TaskFree;

require 'DB.php';
require 'routes.php';
require 'authentication.php';


class App {

  function __construct() { 
    session_start();
    $container = new \Slim\Container([
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]);
    
    $db = new \TaskFree\DB($container); 

    if (isset($_SESSION['user_id'])) {
      $stmt = $container['db']->prepare('SELECT * FROM tf_user WHERE uid=:uid');
      $stmt->bindParam(':uid', $_SESSION['user_id']);
      $stmt->execute();
      $container['current_user'] = $stmt->fetch();
    } else {
      $container['current_user'] = null;
    }
    
    $app = new \Slim\App($container);
    $this->app = $app;
    configureAuthentication($app);
    configureRoutes($app);
  }

  public function run() {
    return $this->app->run();
  }
}

?>
