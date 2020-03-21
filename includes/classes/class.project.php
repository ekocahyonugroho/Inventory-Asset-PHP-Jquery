<?php

class Project extends App {


    public static function add($data) {
    	global $database;
    	if (isset($data['issuesprogress'])) $progress = -1; else $progress = $data['pslider'];
    	$lastid = $database->insert("projects", [
    		"clientid" => $data['clientid'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"notes" => "",
    		"description" => $data['description'],
    		"startdate" => $data['startdate'],
    		"deadline" => $data['deadline'],
    		"progress" => $progress
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Project Added - ID: " . $lastid); return "10"; }
    	}

    public static function edit($data) {
    	global $database;
    	if (isset($data['issuesprogress'])) $progress = -1; else $progress = $data['pslider'];
    	$database->update("projects", [
    		"clientid" => $data['clientid'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"description" => $data['description'],
    		"startdate" => $data['startdate'],
    		"deadline" => $data['deadline'],
    		"progress" => $progress
    	], [ "id" => $data['id'] ]);
    	logSystem("Project Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function delete($id) {
    	global $database;
        $database->delete("projects", [ "id" => $id ]);
    	logSystem("Project Deleted - ID: " . $id);
    	return "30";
    	}

    public static function saveNotes($data) {
    	global $database;
    	$database->update("projects", [
    		"notes" => $data['notes']
    	], [ "id" => $data['id'] ]);
    	logSystem("ProjectNotes Update - ID: " . $data['id']);
    	return "20";
    	}

    public static function assignStaff($data) { //assign admin to project
    	global $database;
    	$lastid = $database->insert("projects_admins", [
    		"projectid" => $data['projectid'],
    		"adminid" => $data['adminid']
    	]);
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    	}

    public static function unassignStaff($id) { //unassign admin from client
    	global $database;
        $database->delete("projects_admins", [ "id" => $id ]);
    	return "30";
    	}

    public static function nextTag() {
    	global $database;
        $max = $database->max("projects", "id");
    	return $max+1;
    	}


    public static function progress($id) {
    	global $database;
    	$progress = 0;
        $project = getRowById("projects",$id);
    	if($project['progress'] != -1) $progress = $project['progress'];
    	else {
    		$allissues = $database->count("issues", [ "projectid" => $id ]);
    		$doneissues = $database->count("issues", [ "AND" => ["status" => "Done", "projectid" => $id] ]);
    		if($allissues == 0) $progress = 0; else $progress = round(($doneissues/$allissues) * 100, 0);
    	}
    	return $progress;
    }

}


?>
