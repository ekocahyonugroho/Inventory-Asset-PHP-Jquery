<?php

class Notification extends App {


    public static function ticketUser($ticketid,$reply,$templateid) { //send ticket notification
    	$template = getRowById("notificationtemplates",$templateid);
    	$ticket = getRowById("tickets",$ticketid);
    	$ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);

    	if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

    	$search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}');
    	$replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($ticket['email'],$subject,$message,$ticket['clientid'],$ticket['userid'],$ccs);
    }


    public static function ticketStaff($ticketid,$reply,$templateid) { //send ticket notification
    	$template = getRowById("notificationtemplates",$templateid);
    	$ticket = getRowById("tickets",$ticketid);


    	$search = array('{ticketid}', '{status}', '{subject}', '{message}', '{company}', '{appurl}');
    	$replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $reply, getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	$admins = getTableFiltered("people","type","admin","ticketsnotification","1");
    	foreach($admins as $admin) {
    		sendEmail($admin['email'],$subject,$message,0,$admin['id']);
    	}
    }


    public static function newUser($peopleid,$password) { //send new user/admin notification
    	global $database;
    	$template = getRowById("notificationtemplates",3);
    	$people = getRowById("people",$peopleid);

    	$search = array('{contact}', '{email}', '{password}', '{company}', '{appurl}');
    	$replace = array($people['name'], $people['email'], $password, getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($people['email'],$subject,$message,$people['clientid'],$people['id']);
    }


    public static function passwordReset($peopleid,$resetlink) { //send password reset link
    	global $database;
    	$template = getRowById("notificationtemplates",5);
    	$people = getRowById("people",$peopleid);

    	$search = array('{contact}', '{resetlink}', '{company}', '{appurl}');
    	$replace = array($people['name'], $resetlink, getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($people['email'],$subject,$message,$people['clientid'],$people['id']);
    }


    public static function monitoringEmail($peopleid,$hostid,$hostinfo,$status) { //send monitoting email alert
    	global $database;
    	$template = getRowById("notificationtemplates",6);
    	$people = getRowById("people",$peopleid);
    	$host = getRowById("hosts",$hostid);

    	$search = array('{hostinfo}', '{status}', '{contact}', '{company}', '{appurl}');
    	$replace = array($hostinfo, $status, $people['name'], getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($people['email'],$subject,$message,$host['clientid'],$people['id']);
    }


    public static function monitoringSMS($peopleid,$hostid,$hostinfo,$status) { //send monitoring SMS alert
    	global $database;
    	$template = getRowById("notificationtemplates",6);
    	$people = getRowById("people",$peopleid);
    	$host = getRowById("hosts",$hostid);

    	$search = array('{hostinfo}', '{status}', '{contact}', '{company}', '{appurl}');
    	$replace = array($hostinfo, $status, $people['name'], getConfigValue("company_name"), getConfigValue("app_url"));

    	$sms = str_replace($search, $replace, $template['sms']);

    	sendSMS($people['mobile'],$sms,$host['clientid'],$people['id']);
    }


}


?>
