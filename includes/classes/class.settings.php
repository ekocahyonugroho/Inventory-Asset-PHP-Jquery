<?php

class Settings extends App {



    public static function update($name, $value) { //update config value
    	global $database;
    	$database->update("config", ["value" => $value], ["name" => $name]);
    }


    public static function editNotification($data) { //update config value
    	global $database;
    	$database->update("notificationtemplates", ["subject" => $data['subject'], "message" => $data['message'], "sms" => $data['sms']], ["id" => $data['id']]);
    	return 40;
    }


    public static function addLanguage($data) {
    	global $database;
    	$lastid = $database->insert("languages", [ "code" => $data['code'], "name" => $data['name'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Language Added - ID: " . $lastid); return "10"; }
    }


    public static function deleteLanguage($id) {
    	global $database;
        $database->delete("languages", [ "id" => $id ]);
    	logSystem("Language Deleted - ID: " . $id);
    	return "30";
    }


    public static function addDepartment($data) {
        global $database;
        $lastid = $database->insert("tickets_departments", [ "name" => $data['name'] ] );
        if ($lastid == "0") { return "11"; } else { logSystem("Support Department Added - ID: " . $lastid); return "10"; }
    }

    public static function editDepartment($data) {
        global $database;
        $database->update("tickets_departments", [ "name" => $data['name'] ], ["id" => $data['id']] );
        if ($lastid == "0") { return "11"; } else { logSystem("Support Department Edited - ID: " . $data['id']); return "20"; }
    }


    public static function deleteDepartment($id) {
        global $database;
        $database->delete("tickets_departments", [ "id" => $id ]);
        logSystem("Support Department Deleted - ID: " . $id);
        return "30";
    }

}


?>
