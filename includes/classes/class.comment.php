<?php

class Comment extends App {

    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("comments", [ "peopleid" => $data['peopleid'], "clientid" => $data['clientid'], "projectid" => $data['projectid'], "ticketid" => $data['ticketid'], "comment" => $data['comment'], "timestamp" => date('Y-m-d H:i:s') ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Comment Added - ID: " . $lastid); return "10"; }
    	}

    public static function edit($data) {
    	global $database;
    	$database->update("comments", [ "peopleid" => $data['peopleid'], "clientid" => $data['clientid'], "projectid" => $data['projectid'], "ticketid" => $data['ticketid'], "comment" => $data['comment'] ], [ "id" => $data['id'] ]);
    	logSystem("Comment Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function delete($id) {
    	global $database;
        $database->delete("comments", [ "id" => $id ]);
    	logSystem("Comment Deleted - ID: " . $id);
    	return "30";
    	}

}


?>
