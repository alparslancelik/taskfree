<?php
namespace TaskFree\Controllers;

class Authentication {
  protected $ci;

  // Constructor
  public function __construct($ci) {
    $this->ci = $ci;
  }

  // GET /auth
  public function getStatus($request, $response, $args) {
    $user = $this->ci['current_user'];
    return $response->withJSON($user, 200);
  }

  // POST /auth
  public function signin($request, $response, $args) {
    $db = $this->ci['db'];
    $user = $this->ci['current_user'];
    if ($user) {
      return $response->withJSON($user, 200);
    } else {
      $data = json_decode($request->getBody(), true);
      $email = $data['email'];
      $password = $data['password'];
      $stmt = $db->prepare('SELECT * FROM tf_user WHERE email = :email AND password = :password');
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);
      $stmt->execute();
      $user = $stmt->fetch();
      if ($user) {
        $_SESSION['user_id'] = $user['uid'];
        return $response->withJSON($user, 200);
      } else {
        return $response->withStatus(401);
      }
    }
  }
  
  // DELETE /auth
  public function signout($request, $response, $args) {
    if (isset($_SESSION['user_id'])) {
      unset($_SESSION['user_id']);
      return $response
        ->withStatus(302)
        ->withHeader('Location', '/');
    } else {
      return $response->withJSON($data, 200);
    }
  }
}

?>
