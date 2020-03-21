<?php

class Monitoring extends App {


    public static function addHost($data) {
    	global $database;
    	$lastid = $database->insert("hosts", [ "clientid" => $data['clientid'], "name" => $data['name'], "address" => $data['address'], "status" => "" ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Host Added - ID: " . $lastid); return "10"; }
    }

    public static function editHost($data) {
    	global $database;
    	$database->update("hosts", [ "clientid" => $data['clientid'], "name" => $data['name'], "address" => $data['address'], "status" => "" ], [ "id" => $data['id'] ]);
    	logSystem("Host Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function deleteHost($id) {
    	global $database;
        $database->delete("hosts", [ "id" => $id ]);
    	$database->delete("hosts_checks", [ "hostid" => $id ]);
    	$database->delete("hosts_admins", [ "hostid" => $id ]);
    	logSystem("Host Deleted - ID: " . $id);
    	return "30";
    }


    public static function addCheck($data) {
    	global $database;
    	$lastid = $database->insert("hosts_checks", [ "hostid" => $data['hostid'], "name" => $data['name'], "type" => $data['type'], "port" => $data['port'], "monitoring" => $data['monitoring'], "email" => $data['email'], "sms" => $data['sms'], "status" => "" ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Check Added - ID: " . $lastid); return "10"; }
    }

    public static function editCheck($data) {
    	global $database;
    	$database->update("hosts_checks", [ "hostid" => $data['hostid'], "name" => $data['name'], "type" => $data['type'], "port" => $data['port'], "monitoring" => $data['monitoring'], "email" => $data['email'], "sms" => $data['sms'], "status" => "" ], [ "id" => $data['id'] ]);
    	logSystem("Check Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function deleteCheck($id) {
    	global $database;
        $database->delete("hosts_checks", [ "id" => $id ]);
    	logSystem("Check Deleted - ID: " . $id);
    	return "30";
    }


    public static function addHostPeople($data) { //assign people to host
    	global $database;
    	$lastid = $database->insert("hosts_people", [
    		"hostid" => $data['hostid'],
    		"peopleid" => $data['peopleid']
    	]);
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    	}

    public static function deleteHostpeople($id) { //unassign people from host
    	global $database;
        $database->delete("hosts_people", [ "id" => $id ]);
    	return "30";
    }






    public static function serviceCheck($hostaddr,$port,$timeout) //check if a service is up returns response time, 0 for down
    	{
    		$errno = 0;

    		$starttime = microtime(true);
    		$conn = @fsockopen($hostaddr, $port, $errno, $errstr, $timeout);
    		$latency = microtime(true) - $starttime;

    		if (!$conn) { return 0; } else return $latency;

    		if(is_resource($conn)) { fclose($conn); }
    }

    public static function addHistory($checkid, $status, $latency) //add check results to history
    	{
    		global $database;
    		$database->insert("hosts_history", [
    			"checkid" => $checkid,
    			"status" => $status,
    			"latency" => $latency,
    			"timestamp" => date("Y-m-d H:i:s")
    		]);
    }

    public static function alertDown($checkid) { //send alert if state changes to down
    	global $database;
    	$check = getRowById("hosts_checks",$checkid);
    	$host = getRowById("hosts",$check['hostid']);

    	if ($check['status'] != "Down") {
    		$database->update("hosts_checks", [ "status" => "Down" ],[ "id" => $checkid ]);

    		$hostinfo = $host['name'] . " - " . $check['name'];
    		$status = "DOWN";

    		$asignedPeople = getTableFiltered("hosts_people","hostid",$check['hostid']);
    		foreach($asignedPeople as $assigned) {
    			if ($check['email'] == 1) Notification::monitoringEmail($assigned['peopleid'],$check['hostid'],$hostinfo,$status);
    			if ($check['sms'] == 1) Notification::monitoringSMS($assigned['peopleid'],$check['hostid'],$hostinfo,$status);
    		}
    	}
    }

    public static function alertUp($checkid) { //send alert if state changes to up
    	global $database;
    	$check = getRowById("hosts_checks",$checkid);
    	$host = getRowById("hosts",$check['hostid']);

    	if ($check['status'] != "Up") {
    		$database->update("hosts_checks", [ "status" => "Up" ],[ "id" => $checkid ]);

    		$hostinfo = $host['name'] . " - " . $check['name'];
    		$status = "UP";

    		$asignedPeople = getTableFiltered("hosts_people","hostid",$check['hostid']);
    		foreach($asignedPeople as $assigned) {
    			if ($check['email'] == 1) Notification::monitoringEmail($assigned['peopleid'],$check['hostid'],$hostinfo,$status);
    			if ($check['sms'] == 1) Notification::monitoringSMS($assigned['peopleid'],$check['hostid'],$hostinfo,$status);
    		}
    	}
    }

    public static function runAll() //run all enabled checks
    	{
    		$checks = getTable("hosts_checks");
    		foreach ($checks as $check) {
    			$hostaddr = getSingleValue("hosts","address",$check['hostid']);
    			if ($check['monitoring'] == 1 && $check['type'] == "Service") {
    				$result = self::serviceCheck($hostaddr,$check['port'],1);

    				if ($result == 0) {
    					$recheck = self::serviceCheck($hostaddr,$check['port'],1);
    					if ($recheck == 0) { self::addHistory($check['id'], "Down", $recheck); self::alertDown($check['id']); }
    					else { self::addHistory($check['id'], "Up", $recheck); self::alertUp($check['id']); }
    				}

    				else {
    					self::addHistory($check['id'], "Up", $result);
    					self::alertUp($check['id']);
    				}

    			}
    		}

    		self::updateStatus();
    	}


    public static function updateStatus() { //update status for all hosts
    	global $database;
    	$hosts = getTable("hosts");
    	foreach ($hosts as $host) {
    		$countAll = countTableFiltered("hosts_checks","hostid",$host['id'],"","");
    		$countUp = countTableFiltered("hosts_checks","hostid",$host['id'],"status","Up");
    		$countDown = countTableFiltered("hosts_checks","hostid",$host['id'],"status","Down");

    		$generalStatus = "";

    		if($countAll != 0) {
    			if($countAll == $countUp) $generalStatus = "Up";
    			elseif($countAll == $countDown) $generalStatus = "Down";
    			else $generalStatus = "Warning";
    		}
    		$database->update("hosts", [ "status" => $generalStatus ],[ "id" => $host['id'] ]);
    	}
    }

}


?>
