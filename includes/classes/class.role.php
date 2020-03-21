<?php

class Role extends App {

    // ----------------------------------------------------------------------------------------------
    // CLIENTS

    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("roles", [
            "type" => $data['type'],
            "name" => $data['name'],
            "perms" => serialize($data['perms'])
        ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Role Added - ID: " . $lastid); return "10"; }
    	}

    public static function edit($data) {
    	global $database;
    	$database->update("roles", [
            "type" => $data['type'],
            "name" => $data['name'],
            "perms" => serialize($data['perms'])
        ], [ "id" => $data['id'] ]);
    	logSystem("Role Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function delete($id) {
    	global $database;
        $database->delete("roles", [ "id" => $id ]);
    	logSystem("Role Deleted - ID: " . $id);
    	return "30";
    	}


}

?>
