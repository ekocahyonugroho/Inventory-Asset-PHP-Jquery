<?php

class Credential extends App {


    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("credentials", [
    		"clientid" => $data['clientid'],
    		"assetid" => $data['assetid'],
    		"type" => $data['type'],
    		"username" => $data['username'],
    		"password" => mc_encrypt($data['password'])
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Credential Added - ID: " . $lastid); return "10"; }
    	}

    public static function edit($data) {
    	global $database;
    	if($data['password'] != "") {
    		$database->update("credentials", [
    			"clientid" => $data['clientid'],
    			"assetid" => $data['assetid'],
    			"type" => $data['type'],
    			"username" => $data['username'],
    			"password" => mc_encrypt($data['password'])
    		], [ "id" => $data['id'] ]);
    	}
    	else {
    		$database->update("credentials", [
    			"clientid" => $data['clientid'],
    			"assetid" => $data['assetid'],
    			"type" => $data['type'],
    			"username" => $data['username']
    		], [ "id" => $data['id'] ]);
    	}
    	logSystem("Credential Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function delete($id) {
    	global $database;
        $database->delete("credentials", [ "id" => $id ]);
    	logSystem("Credential Deleted - ID: " . $id);
    	return "30";
    	}

}


?>
