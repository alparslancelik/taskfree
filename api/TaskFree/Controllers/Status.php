<?php
namespace TaskFree\Controllers;

class Status {
  protected $ci;

  // Constructor
  public function __construct($ci) {
    $this->ci = $ci;
  }

  // GET /status
  public function getStatus($request, $response, $args) {
    $data = array('status' => 'running');
    return $response->withJSON($data, 200);
  }
}

?>
