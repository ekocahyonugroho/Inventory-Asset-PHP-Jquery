<?php

class User extends App {


    public static function add($data) {
        global $database;
        global $mainDatabase;
    	$email = strtolower($data['email']);
        $countMainUser = $mainDatabase->count("access",["email" => $email]);
        if ($countMainUser == "0") { return "11"; }
    	$count = $database->count("people",["email" => $email]);
    	if ($count == "1") { return "11"; }
        //$password = sha1($data['password']);
        $dataMainUser = $mainDatabase->get("access","*",["email" => $email]);
    	$lastid = $database->insert("people", [
            "idUser" => $dataMainUser['id'],
    		"name" => $dataMainUser['name'],
    		"type" => "user",
    		"roleid" => $data['roleid'],
    		"clientid" => $data['clientid'],
    		"email" => $email,
    		"title" => "",
    		"mobile" => "",
    		"password" => "",
    		"theme" => "skin-blue",
    		"sidebar" => "opened",
    		"layout" => "",
    		"notes" => "",
    		"signature" => "",
    		"sessionid" => "",
    		"resetkey" => "",
    		"autorefresh" => 0,
    		"lang" => getConfigValue("default_lang"),
    		"ticketsnotification" => 0
    		]);
    	if ($lastid == "0") { return "11"; } else {
    		if(isset($data['notification'])) { if($data['notification'] == true) Notification::newUser($lastid,""); }
    		logSystem("User Added - ID: " . $lastid);
    		return "10";
    	}
    }

    public static function edit($data) {
    	global $database;
    	$email = strtolower($data['email']);
    	if ($data['password'] == "") {
    		$database->update("people", [
    			"roleid" => $data['roleid'],
    			"clientid" => $data['clientid'],
    			"name" => $data['name'],
    			"email" => $email,
    			"title" => $data['title'],
    			"mobile" => $data['mobile'],
    			"theme" => $data['theme'],
    			"sidebar" => $data['sidebar'],
    			"layout" => $data['layout'],
    			"notes" => $data['notes'],
    			"lang" => $data['lang']

    			],["id" => $data['id']]);
    		logSystem("User Edited - ID: " . $data['id']);
    		return "20";
    	}
    	else {
    		$password = sha1($data['password']);
    		$database->update("people", [
    			"roleid" => $data['roleid'],
    			"clientid" => $data['clientid'],
    			"name" => $data['name'],
    			"email" => $email,
    			"title" => $data['title'],
    			"mobile" => $data['mobile'],
    			"password" => $password,
    			"theme" => $data['theme'],
    			"sidebar" => $data['sidebar'],
    			"layout" => $data['layout'],
    			"notes" => $data['notes'],
    			"lang" => $data['lang']

    			],["id" => $data['id']]);
    		logSystem("User Edited - ID: " . $data['id']);
    		return "20";
    	}
    }

    public static function delete($id) {
    	global $database;
        $database->delete("people", [ "id" => $id ]);
    	logSystem("User Deleted - ID: " . $id);
    	return "30";
    }

}


?>
