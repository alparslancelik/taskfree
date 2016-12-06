<?php
namespace TaskFree\Controllers;

class User {
  protected $ci;

  // Constructor
  public function __construct($ci) {
    $this->ci = $ci;
  }

  // GET /user/{id}
  public function getUser($request, $response, $args) {
    $data = array('id' => $args['id']);
    return $response->withJSON($data, 200);
  }

  public function viewScore ($request, $response, $args) {
      $db = $this->ci['db'];
      $stmt=$db->prepare('SELECT * FROM temp');
      $stmt->execute();
      $res=$stmt->fetchAll();
      return $response->withJSON($res,200);
  }

  public function addUser ($request, $response, $args) {
    $db = $this->ci['db'];
    $data = json_decode($request->getBody(), true);

    $name=$data['name'];
    $email=$data['email'];
    $uname=$data['uname'];
    $password=$data['password'];
    $image_url=$data['image_url'];

    $stmt = $db->prepare('INSERT INTO tf_user (name, email, uname, password, image_url) VALUES (:name, :email, :uname, :password, :image_url);');
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":uname", $uname);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":image_url", $image_url);
    $stmt->execute();

    return $response->withStatus(201);
  }
}

?>
