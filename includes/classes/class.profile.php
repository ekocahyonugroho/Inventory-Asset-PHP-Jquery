<?php

class Profile extends App {


    public static function edit($data,$files) {
    	global $database;
    	$email = strtolower($data['email']);

        $count = $database->count("people",["AND" => ["id" => $data['id'], "password" => sha1($data['confirmpassword'])]]);

        if($count == 1) {

            if ( isset($files['avatar']) && $files['avatar']['size'] > 0 ) {
                $avatar = file_get_contents($files['avatar']['tmp_name']);
                $database->update("people", [ "avatar" => $avatar ], [ "id" => $data['id'] ]);
            }

        	if ($data['password'] == "") {
        		$database->update("people", [
        			"name" => $data['name'],
        			"email" => $email,
        			"title" => $data['title'],
        			"mobile" => $data['mobile'],
        			"theme" => $data['theme'],
        			"sidebar" => $data['sidebar'],
        			"layout" => $data['layout'],
        			"signature" => $data['signature'],
        			"lang" => $data['lang'],
        			"ticketsnotification" => $data['ticketsnotification']

        			],["id" => $data['id']]);
        		logSystem("Profile Edited - ID: " . $data['id']);
        		return "20";
        	}
        	else {
        		$password = sha1($data['password']);
        		$database->update("people", [
        			"name" => $data['name'],
        			"email" => $email,
        			"title" => $data['title'],
        			"mobile" => $data['mobile'],
        			"password" => $password,
        			"theme" => $data['theme'],
        			"sidebar" => $data['sidebar'],
        			"layout" => $data['layout'],
        			"signature" => $data['signature'],
        			"lang" => $data['lang'],
        			"ticketsnotification" => $data['ticketsnotification']

        			],["id" => $data['id']]);
        		logSystem("Profile Edited - ID: " . $data['id']);
        		return "20";
        	}

        }

        else {
            return "1200";
        }


    }



        public static function removeAvatar($id) {
        	global $database;
        	$database->update("people", [ "avatar" => "" ], [ "id" => $id ]);
        }


        public static function setAutorefresh($id,$autorefresh) {
        	global $database;
        	$database->update("people", ["autorefresh" => $autorefresh], ["id" => $id]);
        }

}


?>
