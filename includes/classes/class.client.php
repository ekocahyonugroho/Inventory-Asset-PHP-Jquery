<?php

class Client extends App {

    // ----------------------------------------------------------------------------------------------
    // CLIENTS

    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("clients", [
            "name" => $data['name'],
            "asset_tag_prefix" => $data['asset_tag_prefix'],
            "license_tag_prefix" => $data['license_tag_prefix']
        ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Client Added - ID: " . $lastid); return "10"; }
    }

    public static function edit($data) {
    	global $database;
    	$database->update("clients", [
            "name" => $data['name'],
            "asset_tag_prefix" => $data['asset_tag_prefix'],
            "license_tag_prefix" => $data['license_tag_prefix']
        ], [ "id" => $data['id'] ]);
    	logSystem("Client Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        $database->delete("clients", [ "id" => $id ]);
    	logSystem("Client Deleted - ID: " . $id);
    	return "30";
    }



    public static function assignStaff($data) { //assign admin to client
    	global $database;
    	$lastid = $database->insert("clients_admins", [
    		"adminid" => $data['adminid'],
    		"clientid" => $data['clientid']
    	]);
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    }

    public static function unassignStaff($id) { //unassign admin from client
    	global $database;
        $database->delete("clients_admins", [ "id" => $id ]);
    	return "30";
    }


}

?>
