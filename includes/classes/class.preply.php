<?php

class PReply extends App {


    public static function add($data) {
    	global $database;

    	$lastid = $database->insert("tickets_pr", [
            "name" => $data['name'],
            "content" => "<div>" . $data['content'] . "</div>"
            ]);

    	if ($lastid == "0") { return "11"; } else { logSystem("Predefined Reply Added - ID: " . $lastid); return "10"; }

    }

    public static function edit($data) {
    	global $database;

    	$database->update("tickets_pr", [
            "name" => $data['name'],
            "content" => $data['content']

            ], [ "id" => $data['id'] ]);

    	logSystem("Predefined Reply Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function delete($id) {
    	global $database;

        $database->delete("tickets_pr", [ "id" => $id ]);

    	logSystem("Predefined Reply Deleted - ID: " . $id);
    	return "30";
    	}


}

?>
