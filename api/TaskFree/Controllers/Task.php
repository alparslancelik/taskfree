<?php
namespace TaskFree\Controllers;

class Task {
    protected $ci;

    // Constructor
    public function __construct($ci) {
        $this->ci = $ci;
    }

    public function completeStep ($request, $response, $args) {
        $db = $this->ci['db'];
        $data = json_decode($request->getBody(), true);
        $tid=$data['tid'];
        $sid=$data['sid'];

        $stmt = $db->prepare('UPDATE tf_step SET completed = TRUE WHERE tid = :tid AND sid = :sid AND NOT EXISTS (SELECT * FROM tf_step s WHERE s.tid = tid AND s.sid < sid AND s.completed = FALSE);');
        $stmt->bindParam(':tid', $tid);
        $stmt->bindParam(':sid', $sid);
        $stmt->execute();
    }

    public function updatePick ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];

        $data = json_decode($request->getBody(), true);
        $tid=$data['tid'];
        $uid=$data['uid'];

        // Check whether pick exist
        $pick = $this->getPickByIdAndCreator($tid, $uid);
        // Users can only update picks only if they own the task
        $task_owned = $this->getTaskByIdAndCreator($tid, $current_user['uid']);

        if($pick && $task_owned){
            $score = $data['score'];
            $comment = $data['comment'];

            $stmt = $db->prepare('UPDATE tf_pick SET comment=:comment, score=:score WHERE uid = :uid AND tid =:tid');
            $stmt->bindParam(':uid',$uid);
            $stmt->bindParam(':tid',$tid);
            $stmt->bindParam(':comment',$comment);
            $stmt->bindParam(':score', $score);
            $stmt->execute();
            return $response->withStatus(201);
        } else
            return $response->withStatus(400);
    }


    public function approvePick ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];

        $data = json_decode($request->getBody(), true);
        $tid=$data['tid'];
        $uid=$data['uid'];

        // Check whether the pick exist
        $pick = $this->getPickByIdAndCreator($tid, $uid);
        // Users can only approve picks only if they own the task
        $task_owned = $this->getTaskByIdAndCreator($tid, $current_user['uid']);
        // If pick is already approved, don't do anything


        if($pick && $task_owned){
            $stmt = $db->prepare('UPDATE tf_pick SET accepted = TRUE WHERE tid = :tid AND uid = :uid');
            $stmt->bindParam(':tid', $tid);
            $stmt->bindParam(':uid', $uid);
            $stmt->execute();
            return $response->withStatus(201);
        } else
            return $response->withStatus(400);
    }

    public function getTask($request, $response, $args) {
        $tid = $args['id'];
        return $response->withJSON($this->getTaskById($tid), 200);
    }

    public function listTask($request, $response) {
        $db = $this->ci['db'];
        $category = $request->getQueryParam('category');
        $expr = 'SELECT * FROM tf_task t ';
        if ($category) {
            $expr .= ' WHERE t.cid = :category';
        }
        $stmt = $db->prepare($expr);
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $response->withJSON($data, 200);
    }

    private function getTaskById($tid) {
        $db = $this->ci['db'];

        $stmt = $db->prepare('SELECT * FROM tf_task t WHERE t.tid = :tid');
        $stmt->bindParam(':tid', $tid);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function getTaskByIdAndCreator($tid, $uid) {        
        $db = $this->ci['db'];
        $stmt = $db->prepare('SELECT * FROM tf_task t WHERE t.tid = :tid AND t.creator = :uid');
        $stmt->bindParam(':tid', $tid);
        $stmt->bindParam(':uid', $uid);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getPickByTaskId($request, $response, $args) {        
        $db = $this->ci['db']; 
        $tid = $args['id'];
        $stmt = $db->prepare('SELECT * FROM tf_pick p WHERE p.tid = :tid');
        $stmt->bindParam(':tid', $tid);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $response->withJSON($data, 200);
    }

    public function getStepsByTaskId($request, $response, $args) {        
        $db = $this->ci['db']; 
        $tid = $args['id'];
        $stmt = $db->prepare('SELECT * FROM tf_step p WHERE p.tid = :tid');
        $stmt->bindParam(':tid', $tid);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $response->withJSON($data, 200);
    }

    private function getPickByIdAndCreator($tid, $uid) {        
        $db = $this->ci['db'];

        $stmt = $db->prepare('SELECT * FROM tf_pick p WHERE p.tid = :tid AND p.uid = :uid');
        $stmt->bindParam(':tid', $tid);
        $stmt->bindParam(':uid', $uid);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function pickTask ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];
        $tid = $args['id'];

        $task = $this->getTaskById($tid);

        // Make sure task exist.
        if ($task) {
            $my_task = $this->getTaskByIdAndCreator($tid, $current_user['uid']);
            $my_pick = $this->getPickByIdAndCreator($tid, $current_user['uid']);

            if ($my_task || $my_pick) {
                return $response->withStatus(400);
            } else {
                $stmt = $db->prepare('INSERT INTO tf_pick (uid, tid) VALUES (:uid, :tid)');
                $stmt->bindParam(":uid", $current_user['uid']);
                $stmt->bindParam(":tid", $tid);
                $stmt->execute();
                return $response->withStatus(201);
            }
        } else {
            return $response->withStatus(400);
        }
    }

    public function deleteTask ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];
        /*
        if (!$current_user['admin']) {    
            return $response->withStatus(400);
        }
        */
        $tid = $args['id'];
        $stmt = $db->prepare('DELETE FROM tf_task WHERE tid=:tid');
        $stmt->bindParam(":tid", $tid);
        $stmt->execute();
        return $response->withStatus(200);
    }

    public function deleteStep ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];
        /*
        if (!$current_user['admin']) {    
            return $response->withStatus(400);
        }
        */
        $tid = $args['id'];
        $sid = $args['sid'];
        $stmt = $db->prepare('DELETE FROM tf_step WHERE tid=:tid AND sid=:sid');
        $stmt->bindParam(":tid", $tid);
        $stmt->bindParam(":sid", $sid);
        return $response->withStatus(200);
    }

    public function updateTask ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];
        $data = json_decode($request->getBody(), true);
        /*
        if (!$current_user['admin']) {    
            return $response->withStatus(400);
        }
        */
        $tid = $args['id'];
        $start_time= $data['start_time'];
        $end_time= $data['end_time'] ?? NULL;
        $title= $data['title'];
        $description= $data['description'];
        $longitude= $data['longitude'];
        $latitude= $data['latitude'];
        $npeople= $data['npeople'];
        $cid= $data['cid'];
        $stmt = $db->prepare('UPDATE tf_task SET (start_time, end_time, title, description, longitude, latitude, npeople, cid)= (:start_time, :end_time, :title, :description, :longitude, :latitude, :npeople, :cid) WHERE tid=:tid;');

        $stmt->bindParam(":tid", $tid);
        $stmt->bindParam(":start_time", $start_time);
        $stmt->bindParam(":end_time", $end_time);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":longitude", $longitude);
        $stmt->bindParam(":latitude", $latitude);
        $stmt->bindParam(":npeople", $npeople);
        $stmt->bindParam(":cid", $cid);
        $stmt->execute();
        $task = $stmt->fetch();

        return $response->withJSON($task, 200);
    }

    public function updateStep ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];
        $data = json_decode($request->getBody(), true);
        /*
        if (!$current_user['admin']) {    
            return $response->withStatus(400);
        }
        */
        $tid = $args['id'];
        $sid = $args['sid'];
        $description= $data['description'];
        $longitude= $data['longitude'];
        $latitude= $data['latitude'];
        $stmt = $db->prepare('UPDATE tf_step WHERE tid=:tid AND sid=:sid SET description=:description, latitude=:latitude, longitude=:longitude;');
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":longitude", $longitude);
        $stmt->bindParam(":latitude", $latitude);
        $stmt->execute();
        $step = $stmt->fetch();

        return $response->withJSON($step, 200);
    }


    public function addTask ($request, $response, $args) {
        $db = $this->ci['db'];
        $current_user = $this->ci['current_user'];
        if (is_null($current_user)) {
            return $response->withStatus($step, 403);
        }
        $data = json_decode($request->getBody(), true);

        $start_time=$data['start_time'];
        $end_time=$data['end_time'] ?? NULL;
        $title=$data['title'];
        $description=$data['description'];
        $longitude=$data['longitude'];
        $latitude=$data['latitude'];
        $npeople=$data['npeople'];
        $cid=$data['cid'];
        $steps=$data['steps'];
        $is_step = (count($steps) == 0 ? "false" : "true");

        // Add task into database
        $stmt = $db->prepare('INSERT INTO tf_task (creator, start_time, end_time, title, description, longitude, latitude, npeople, cid, step) VALUES (:creator, :start_time, :end_time, :title, :description, :longitude, :latitude, :npeople, :cid, :step) RETURNING tid;');
        $stmt->bindParam(":creator", $current_user['uid']);
        $stmt->bindParam(":start_time", $start_time);
        $stmt->bindParam(":end_time", $end_time);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":longitude", $longitude);
        $stmt->bindParam(":latitude", $latitude);
        $stmt->bindParam(":npeople", $npeople);
        $stmt->bindParam(":cid", $cid);
        $stmt->bindParam(":step", $is_step);
        $stmt->execute();
        $task = $stmt->fetch();
        $tid = $task['tid'];

        // Add steps into database
        for ($i = 1; $i <= count($steps); $i++) {
            $step = $steps[$i - 1];
            $step_description=$step['description'];
            $step_longitude=$step['longitude'];
            $step_latitude=$step['latitude'];

            $stmt = $db->prepare('INSERT INTO tf_step (sid, tid, description, longitude, latitude) VALUES (:sid, :tid, :description, :longitude, :latitude);');
            $stmt->bindParam(":sid", $i);
            $stmt->bindParam(":tid", $tid);
            $stmt->bindParam(":description", $step_description);
            $stmt->bindParam(":longitude", $step_longitude);
            $stmt->bindParam(":latitude", $step_latitude);
            $stmt->execute();
        }

        return $response->withJSON($task, 201);
    }

    public function searchTask($keywords) {
        $db = $this->ci['db'];

        //$keywords = explode(" ", $args['keywords']);

        $stmt = $db->prepare('SELECT * FROM tf_task t WHERE LOWER(t.title) similar to \'%(('.implode(")|(", $keywords).'))%\' OR LOWER(t.description) similar to \'%(('.implode(")|(", $keywords).'))%\';');
        $stmt->execute();
        $res = $stmt->fetchAll();

        return $res;
    }
}

?>
