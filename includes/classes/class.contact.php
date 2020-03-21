<?php

class Contact extends App {


    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("contacts", [
    		"name" => $data['name'],
    		"email" => $data['email']
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Contact Added - ID: " . $lastid); return "10"; }
    	}


    public static function edit($data) {
    	global $database;
    	$database->update("contacts", [
    		"name" => $data['name'],
    		"email" => $data['email']
    	], [ "id" => $data['id'] ]);
    	logSystem("Contact Edited - ID: " . $data['id']);
    	return "20";
    	}


    public static function delete($id) {
    	global $database;
        $database->delete("contacts", [ "id" => $id ]);
    	logSystem("Contact Deleted - ID: " . $id);
    	return "30";
    	}


}

?>
