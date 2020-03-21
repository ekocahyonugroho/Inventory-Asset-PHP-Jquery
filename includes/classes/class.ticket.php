<?php

class Ticket extends App {

    // TICKETS

    public static function add($data) {
    	global $database;
    	if(empty($data['ccs'])) $ccs = ""; else $ccs = serialize($data['ccs']);

    	$userid = $database->get("people","id", [ "email" => $data['email'] ]);
    	if($userid == "") $userid = 0;

    	$ticketid = $database->insert("tickets", [
    		"ticket" => rand(100000,999999),
            "departmentid" => $data['departmentid'],
    		"clientid" => $data['clientid'],
    		"userid" => $userid,
    		"adminid" => $data['adminid'],
    		"assetid" => $data['assetid'],
    		"email" => $data['email'],
    		"subject" => $data['subject'],
    		"status" => "Open",
    		"priority" => $data['priority'],
    		"timestamp" => date('Y-m-d H:i:s'),
    		"notes" => "",
    		"ccs" => $ccs
    	]);

    	if($data['adminid'] != "0") $peopleid = $data['adminid']; else $peopleid = $userid;

    	$lastid = $database->insert("tickets_replies", [
    		"ticketid" => $ticketid,
    		"peopleid" => $peopleid,
    		"message" => $data['message'],
    		"timestamp" => date('Y-m-d H:i:s')
    	]);

        if(isset($_FILES["file"]["name"][0])) {
            if(!empty($_FILES["file"]["name"][0])) {
                $file_data = array();
                $file_data['clientid'] = 0;
                $file_data['projectid'] = 0;
                $file_data['assetid'] = 0;
                $file_data['ticketreplyid'] = $lastid;
                File::upload($file_data,$_FILES);
            }
        }

    	if ($lastid == "0") { return "11"; } else {
    		if(isset($data['notification'])) { if($data['notification'] == true) Notification::ticketUser($ticketid,$data['message'],1); }
    		Notification::ticketStaff($ticketid,$data['message'],7);
    		logSystem("Ticket Added - ID: " . $ticketid);
    		return "10"; }
    	}

    public static function addReply($data) {
    	global $database;
    	if($data['adminid'] != "0") $peopleid = $data['adminid']; else $peopleid = $data['userid'];
    	$lastid = $database->insert("tickets_replies", [
    		"ticketid" => $data['ticketid'],
    		"peopleid" => $peopleid,
    		"message" => $data['message'],
    		"timestamp" => date('Y-m-d H:i:s')
    	]);

        if(isset($_FILES["file"]["name"][0])) {
            if(!empty($_FILES["file"]["name"][0])) {
                $file_data = array();
                $file_data['clientid'] = 0;
                $file_data['projectid'] = 0;
                $file_data['assetid'] = 0;
                $file_data['ticketreplyid'] = $lastid;
                File::upload($file_data,$_FILES);
            }
        }

    	if(isset($data['status'])) self::updateStatus($data['ticketid'],$data['status']);

    	if ($lastid == "0") { return "11"; } else {
    		if(isset($data['notification'])) { if($data['notification'] == true) Notification::ticketUser($data['ticketid'],$data['message'],2); }
    		if(getSingleValue("people","type",$peopleid) == "user") Notification::ticketStaff($data['ticketid'],$data['message'],8);
    		if($peopleid == "0") Notification::ticketStaff($data['ticketid'],$data['message'],8);
    		logSystem("Ticket Reply Added - ID: " . $lastid);
    		return "10"; }
    }

    public static function edit($data) {
    	global $database;
    	if(empty($data['ccs'])) $ccs = ""; else $ccs = serialize($data['ccs']);
    	$database->update("tickets", [
            "departmentid" => $data['departmentid'],
    		"clientid" => $data['clientid'],
    		"userid" => $data['userid'],
    		"adminid" => $data['adminid'],
    		"assetid" => $data['assetid'],
    		"email" => $data['email'],
    		"subject" => $data['subject'],
    		"status" => $data['status'],
    		"priority" => $data['priority'],
    		"ccs" => $ccs
    	], [ "id" => $data['id'] ]);
    	logSystem("Ticket Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function updateStatus($id,$status) {
    	global $database;
    	$database->update("tickets", [
    		"status" => $status
    	], [ "id" => $id ]);
    	logSystem("Ticket Status Update - ID: " . $id);
    	return "20";
    }

    public static function assignTo($id,$adminid) {
    	global $database;
    	$database->update("tickets", [
    		"adminid" => $adminid
    	], [ "id" => $id ]);
    	logSystem("Ticket Assigned - ID: " . $id);
    	return "20";
    }

    public static function updateNotes($data) {
    	global $database;
    	$database->update("tickets", [
    		"notes" => $data['notes']
    	], [ "id" => $data['id'] ]);
    	logSystem("Ticket Notes Update - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        $database->delete("tickets", [ "id" => $id ]);
    	$database->delete("tickets_replies", [ "ticketid" => $id ]);
    	logSystem("Ticket Deleted - ID: " . $id);
    	return "30";
    }

    public static function lastReply($id) {
    	global $database;
    	$maxdate = $database->max("tickets_replies", "timestamp", ["ticketid" => $id]);
    	$timestamp = strtotime($maxdate);
    	return smartDate($timestamp);
    }

    public static function extractEmail($string) { //extract first email address from string
    	$pattern = '/[\\w\\.\\-+=*_]*@[\\w\\.\\-+=*_]*/';
    	preg_match($pattern, $string, $matches);
        return $matches[0];
    }

    public static function emailToTicket($from,$subject,$body,$importance,$ccs){

    	global $database;

        // decalring arrays
    	$data = array();
    	$data['ccs'] = array();

        // extracs ccs, if any
    	if(!empty($ccs)) {
    		foreach($ccs as $cc) {
    			$ccemail = self::extractEmail($cc);
    			array_push($data['ccs'],$ccemail);
    		}
    	}

        // extract from email address
    	$data['email'] = self::extractEmail($from);

        // match user based on email address
    	$data['userid'] = $database->get("people", "id", [ "AND" => [ "type" => "user", "email" => $data['email'] ] ]); if($data['userid'] == "") $data['userid'] = 0;

        // match admin based on email address
    	$data['adminid'] = $database->get("people", "id", [ "AND" => [ "type" => "admin", "email" => $data['email'] ] ]); if($data['adminid'] == "") $data['adminid'] = 0;

        // if email from user reopen ticket, if email from admin set status to answered
        if($data['adminid'] != 0) $data['status'] = "Answered"; else $data['status'] = "Reopened";

        // we do not know the asset at this time
    	$data['assetid'] = 0;

        // if we do not know the user we do not know the client
    	if($data['userid'] == 0) $data['clientid'] = 0;

        // if we know the user try to assign some data to the ticket
    	else {
            // get the user's assigned client
    		$data['clientid'] = $database->get("people", "clientid", [ "AND" => [ "type" => "user", "email" => $data['email'] ] ]);

            // get the assigned asset ( it will return only the first one if there are more than one assigned )
    		$asset = $database->get("assets","*",[ "userid" => $data['userid'] ]);
    		if(!empty($asset['id'])) {
                // assign the asset if any found
                $data['assetid'] = $asset['id'];
                // assign the admin for that asset if found
                $data['adminid'] = $asset['adminid'];
            }

    	}


    	$data['subject'] = $subject;
    	$data['priority'] = $importance;
    	$data['message'] = $body;
        $data['departmentid'] = getConfigValue("tickets_defaultdepartment");


        // select all the tickets
    	$tickets = $database->select("tickets", ["id", "ticket"]);

        // see if we can get a ticket match
    	foreach($tickets as $ticket) {

    		if (strpos($subject,$ticket['ticket']) !== false) { // match found, will add as ticket reply
                $data['ticketid'] = $ticket['id'];

                // do not send user notification if user sent the reply
                $data['notification'] = false;

                // if admin made the reply send notification to user
                if ($data['status'] == "Answered") $data['notification'] = true;

                break; // exit the loop prematurely if we find a match

            } else { // no match, new ticket will be created
                $data['ticketid'] = 0;
                $data['notification'] = getConfigValue("tickets_notification");
            }

    	}

        // in case we have an empty ticket table
    	if(empty($tickets)) { $data['ticketid'] = 0; $data['notification'] = getConfigValue("tickets_notification"); }

    	if($data['ticketid'] == 0) {
            // no match, create new ticket
            self::add($data);
        } else {
            // match found, add reply to matched ticket
            self::addReply($data);
        }

        // return last ticket reply id DON'T KNOW WHY?????? NEEDS FURTHER INVESTIGATION!!! REMOVE IF NOT REQUIRED
    	return $database->max("tickets_replies","id");
    }



    // ESCALATION RULES

    public static function addRule($data) {
    	global $database;
        if(isset($data['act_notifyadmins'])) $act_notifyadmins = 1; else $act_notifyadmins = 0;
        if(isset($data['act_addreply'])) $act_addreply = 1; else $act_addreply = 0;
        if(empty($data['cond_status'])) $cond_status = ""; else $cond_status = serialize($data['cond_status']);
        if(empty($data['cond_priority'])) $cond_priority = ""; else $cond_priority = serialize($data['cond_priority']);
        $cond_datetime = $data['cond_datetime_date'] . " " . $data['cond_datetime_time'];
    	$lastid = $database->insert("tickets_rules", [
    		"ticketid" => $data['ticketid'],
            "executed" => 0,
            "name" => $data['name'],
    		"cond_status" => $cond_status,
    		"cond_priority" => $cond_priority,
    		"cond_timeelapsed" => $data['cond_timeelapsed'],
    		"cond_datetime" => $cond_datetime,
    		"act_status" => $data['act_status'],
    		"act_priority" => $data['act_priority'],
            "act_assignto" => $data['act_assignto'],
            "act_notifyadmins" => $act_notifyadmins,
            "act_addreply" => $act_addreply,
            "reply" => $data['reply']
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Escalation Rule Added - ID: " . $lastid); return "10"; }
    	}


    public static function editRule($data) {
    	global $database;
        if(isset($data['act_notifyadmins'])) $act_notifyadmins = 1; else $act_notifyadmins = 0;
        if(isset($data['act_addreply'])) $act_addreply = 1; else $act_addreply = 0;
        if(empty($data['cond_status'])) $cond_status = ""; else $cond_status = serialize($data['cond_status']);
        if(empty($data['cond_priority'])) $cond_priority = ""; else $cond_priority = serialize($data['cond_priority']);
        $cond_datetime = $data['cond_datetime_date'] . " " . $data['cond_datetime_time'];
    	$database->update("tickets_rules", [
            "ticketid" => $data['ticketid'],
            "name" => $data['name'],
    		"cond_status" => $cond_status,
    		"cond_priority" => $cond_priority,
    		"cond_timeelapsed" => $data['cond_timeelapsed'],
    		"cond_datetime" => $cond_datetime,
    		"act_status" => $data['act_status'],
    		"act_priority" => $data['act_priority'],
            "act_assignto" => $data['act_assignto'],
            "act_notifyadmins" => $act_notifyadmins,
            "act_addreply" => $act_addreply,
            "reply" => $data['reply']
    	], [ "id" => $data['id'] ]);
    	logSystem("Escalation Rule Edited - ID: " . $data['id']);
    	return "20";
    	}



    public static function deleteRule($id) {
    	global $database;
        $database->delete("tickets_rules", [ "id" => $id ]);
    	logSystem("Esclation Rule Deleted - ID: " . $id);
    	return "30";
    	}


    public static function processRules() {
        global $database;
        $generalRules = getTableFiltered("tickets_rules","ticketid","0");
        $ticketRules = $database->select("tickets_rules", "*", [ "AND" => [ "ticketid[!]" => 0, "executed" => 0 ] ]);


        foreach ($generalRules as $rule) {
            if($rule['cond_status'] == "") $cond_status = ["Open","In Progress","Answered","Reopened","Closed"]; else $cond_status = unserialize($rule['cond_status']);
            if($rule['cond_priority'] == "") $cond_priority = ["Low","Normal","High"]; else $cond_priority = unserialize($rule['cond_priority']);

            $tickets = $database->select("tickets", "*", [ "AND" => [ "status" => $cond_status, "priority" => $cond_priority ] ]);

            foreach ($tickets as $ticket) {
                $lastreply = $database->max("tickets_replies", "timestamp", ["ticketid" => $ticket['id']]);

                $lastreply = strtotime($lastreply) / 60;
                $now = strtotime("now") / 60;
                $difference = $now - $lastreply;

                if ($rule['cond_timeelapsed'] == "" or $difference > $rule['cond_timeelapsed']) {
                    $message = "";
                    if($rule['act_status'] != "0") { $database->update("tickets", [ "status" => $rule['act_status'] ], [ "id" => $ticket['id'] ]); $message .= "Status changed to <b>" . $rule['act_status'] . "</b><br>"; }
                    if($rule['act_priority'] != "0") { $database->update("tickets", [ "priority" => $rule['act_priority'] ], [ "id" => $ticket['id'] ]); $message .= "Proirity changed to <b>" . $rule['act_priority'] . "</b><br>"; }
                    if($rule['act_assignto'] != "0") { $database->update("tickets", [ "adminid" => $rule['act_assignto'] ], [ "id" => $ticket['id'] ]); $message .= "Assigned to <b>" . getSingleValue("people","name",$rule['act_assignto']) . "</b><br>"; }
                    if($rule['act_addreply'] == "1") { $message .= "Reply added<br>";
                        $data = array();
                        $data['adminid'] = -1;
                        $data['ticketid'] = $ticket['id'];
                        $data['message'] = $rule['reply'];
                        $data['notification'] = true;
                        self::addReply($data);
                    }
                    if($rule['act_notifyadmins'] == "1") { Notification::ticketStaff($ticket['id'],$message,9); }
                }

            }

        }

        foreach ($ticketRules as $rule) {
            if($rule['cond_status'] == "") $cond_status = ["Open","In Progress","Answered","Reopened","Closed"]; else $cond_status = unserialize($rule['cond_status']);
            if($rule['cond_priority'] == "") $cond_priority = ["Low","Normal","High"]; else $cond_priority = unserialize($rule['cond_priority']);


            $ticket = $database->get("tickets","*",[ "id" => $rule['ticketid'] ]);

            if(in_array($ticket['status'],$cond_status) && in_array($ticket['priority'],$cond_priority)) {



                $processat = new DateTime($rule['cond_datetime']);
                $now = new DateTime(date("Y-m-d H:i:s"));


                if ($now->format('U') >= $processat->format('U')) {
                    $message = "";
                    if($rule['act_status'] != "0") { $database->update("tickets", [ "status" => $rule['act_status'] ], [ "id" => $ticket['id'] ]); $message .= "Status changed to <b>" . $rule['act_status'] . "</b><br>"; }
                    if($rule['act_priority'] != "0") { $database->update("tickets", [ "priority" => $rule['act_priority'] ], [ "id" => $ticket['id'] ]); $message .= "Proirity changed to <b>" . $rule['act_priority'] . "</b><br>"; }
                    if($rule['act_assignto'] != "0") { $database->update("tickets", [ "adminid" => $rule['act_assignto'] ], [ "id" => $ticket['id'] ]); $message .= "Assigned to <b>" . getSingleValue("people","name",$rule['act_assignto']) . "</b><br>"; }
                    if($rule['act_addreply'] == "1") { $message .= "Reply Added<br>";
                        $data = array();
                        $data['adminid'] = -1;
                        $data['ticketid'] = $ticket['id'];
                        $data['message'] = $rule['reply'];
                        $data['notification'] = true;
                        self::addReply($data);
                    }
                    if($rule['act_notifyadmins'] == "1") { Notification::ticketStaff($ticket['id'],$message,9); }

                    $database->update("tickets_rules", [ "executed" => 1 ], [ "id" => $rule['id'] ]);
                }
            }
        }

    }

    public static function autoClose() {
        global $database;
        $autoclose = getConfigValue("auto_close_tickets") * 3600;

        if($autoclose > 0) {
            $tickets = $database->select("tickets", "*", [ "status" => "Answered" ]);

            foreach ($tickets as $ticket) {
                $lastreply = $database->max("tickets_replies", "timestamp", ["ticketid" => $ticket['id']]);
                $lastreply = strtotime($lastreply);
                $now = strtotime("now");

                $difference = $now - $lastreply;

                if ($difference > $autoclose) {
                    self::updateStatus($ticket['id'],"Closed");
                    if( getConfigValue("auto_close_tickets_notify") == "true" ) Notification::ticketUser($ticket['id'],"",10);
                }
            }
        }
    }



}

?>
