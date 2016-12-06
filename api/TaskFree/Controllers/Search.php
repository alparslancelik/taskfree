<?php
namespace TaskFree\Controllers;

class Search {
    protected $ci;

    // Constructor
    public function __construct($ci) {
        $this->ci = $ci;
    }

    public function search($request, $response, $args) {
        $db = $this->ci['db'];
        $type = $request->getQueryParam('type');
        $keywords = explode(" ", $request->getQueryParam('q'));
        $keywords = array_map('strtolower', $keywords);

        if(strcmp($type, "category") == 0){
            $categ = new Category($this->ci); 
            $res = $categ->searchCategory($keywords);
        }
        elseif (strcmp($type, "task") == 0) {
            $task = new Task($this->ci); 
            $res = $task->searchTask($keywords);
        }
        else
            $res = null;

        return $response->withJSON($res, 200);
    }
}

?>
