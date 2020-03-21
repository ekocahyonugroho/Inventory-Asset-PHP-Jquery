<?php

class Kb extends App {


    // ARTICLES

    public static function add($data) {
    	global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

    	$lastid = $database->insert("kb_articles", [
            "categoryid" => $data['categoryid'],
            "clients" => $clients,
            "name" => $data['name'],
            "content" => $data['content']
            ]);

    	if ($lastid == "0") { return "11"; } else { logSystem("Knowledge Base Article Added - ID: " . $lastid); return "10"; }

    }

    public static function edit($data) {
    	global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

    	$database->update("kb_articles", [
            "categoryid" => $data['categoryid'],
            "clients" => $clients,
            "name" => $data['name'],
            "content" => $data['content']

            ], [ "id" => $data['id'] ]);

    	logSystem("Knowledge Base Article Edited - ID: " . $data['id']);
    	return "20";

    }

    public static function delete($id) {
    	global $database;

        $database->delete("kb_articles", [ "id" => $id ]);

    	logSystem("Knowledge Base Article Deleted - ID: " . $id);
    	return "30";

    }



    // CATEGORIES

    public static function addCategory($data) {
    	global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

    	$lastid = $database->insert("kb_categories", [
            "clients" => $clients,
            "name" => $data['name']
            ]);

    	if ($lastid == "0") { return "11"; } else { logSystem("Knowledge Base Category Added - ID: " . $lastid); return "10"; }

    }

    public static function editCategory($data) {
    	global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

    	$database->update("kb_categories", [
            "clients" => $clients,
            "name" => $data['name']

            ], [ "id" => $data['id'] ]);

    	logSystem("Knowledge Base Category Edited - ID: " . $data['id']);
    	return "20";

    }

    public static function deleteCategory($id) {
    	global $database;

        $database->delete("kb_categories", [ "id" => $id ]);

    	logSystem("Knowledge Base Category Deleted - ID: " . $id);
    	return "30";

    }




}

?>
