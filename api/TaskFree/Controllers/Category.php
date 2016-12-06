<?php
namespace TaskFree\Controllers;

class Category {
  protected $ci;

  //Constructor
  public function __construct($ci) {
    $this->ci = $ci;
  }

  // GET all the categories
  public function getAll($request, $response, $args) {
    $db = $this->ci['db'];
    $stmt = $db->prepare('SELECT * FROM tf_category');
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $response->withJSON($data, 200);
  }

  public function getCategory($request, $response, $args) {
    $db = $this->ci['db'];
    $cid = $args['id'];
    $stmt = $db->prepare('SELECT * FROM tf_category WHERE cid=:cid');
    $stmt->bindParam(':cid', $cid);
    $stmt->execute();
    $data = $stmt->fetch();

    return $response->withJSON($data, 200);
  }

  public function searchCategory($keywords) {
    $db = $this->ci['db'];

    //$keywords = explode(" ", $args['keywords']);
    //echo (implode(")|(", $keywords));

    $stmt = $db->prepare('SELECT * FROM tf_category c WHERE LOWER(c.name) similar to \'%(('.implode(")|(", $keywords).'))%\' OR LOWER(c.description) similar to \'%(('.implode(")|(", $keywords).'))%\';');
    $stmt->execute();
    $res = $stmt->fetchAll();
    
    return $res;
  }
}

?>
