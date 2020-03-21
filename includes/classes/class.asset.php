<?php
/**
 * Created by PhpStorm.
 * User: kkevi
 * Date: 04/11/2016
 * Time: 15.22
 */
class Asset extends App {


    public static function add($data) {
    	global $database;
    	global $mainDatabase;

        $categoryid = 0;
        $manufacturerid = 0;
        $modelid = 0;
        $supplierid = 0;
        $locationid = 0;
        $typeid = 0;
        $po = "NONE";

        if(isset($data['category'])) {
            $categoryid = $database->get("assetcategories", "id", [ "name" => $data['category'] ]);
            if($categoryid == "") $categoryid = $database->insert("assetcategories", [ "name" => $data['category'], "color" => rand_color() ]);
        }

        if(isset($data['manufacturer'])) {
            $manufacturerid = $database->get("manufacturers", "id", [ "name" => $data['manufacturer'] ]);
            if($manufacturerid == "") $manufacturerid = $database->insert("manufacturers", [ "name" => $data['manufacturer'] ]);
        }

        if(isset($data['model'])) {
            $modelid = $database->get("models", "id", [ "name" => $data['model'] ]);
            if($modelid == "") $modelid = $database->insert("models", [ "name" => $data['model'] ]);
        }

        if(isset($data['supplier'])) {
            $supplierid = $database->get("suppliers", "id", [ "name" => $data['supplier'] ]);
            if($supplierid == "") {
                $supplierid = $database->insert("suppliers", [ "name" => $data['supplier'] ]);
                $mainDatabase->insert("vendor",[
                    "vendor"=>$data['supplier'],
                    "status"=>"1"
                ]);
            }
        }

        if(isset($data['location'])) {
            $locationid = $database->get("locations", "id", [ "AND" => [ "name" => $data['location'], "clientid" => $data['clientid'] ] ]);
            if($locationid == "") {
                $locationid = $database->insert("locations", [ "name" => $data['location'], "clientid" => $data['clientid'] ]);
                $mainDatabase->insert("room", [
                    "room_name" => $data['location'],
                    "category" => "General"
                ]);
            }
        }

        if(isset($data['purchasecategory'])) {
            $typeid = $database->get("assets_type", "id", [ "id" => $data['purchasecategory'] ]);
            if($typeid == "") $typeid = $database->insert("assets_type", [ "type" => $data['purchasecategory']]);
        }

        if(isset($data['purchase_order'])){
            $po = $data['purchase_order'];
        }

    	$lastid = $database->insert("assets", [
    		"categoryid" => $categoryid,
            "typeid" => $typeid,
    		"adminid" => $data['adminid'],
    		"clientid" => $data['clientid'],
    		"userid" => $data['userid'],
            "manufacturerid" => $manufacturerid,
    		"modelid" => $modelid,
    		"supplierid" => $supplierid,
    		"statusid" => $data['statusid'],
    		"purchase_date" => $data['purchase_date'],
    		"warranty_months" => $data['warranty_months'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"serial" => $data['serial'],
    		"notes" => $data['notes'],
            "locationid" => $locationid,
            "value" => $data['value'],
            "condition" => $data['condition'],
            "removal_date" => $data['removal_date'],
            "purchasecategory" => "1",
            "purchase_order" => $po,
            "is_borrow" => "0"
    	]);

    	if ($lastid == "0") { logSystem("ERROR insert New Asset Data. Message ".$database->error()); return "11"; } else { logSystem("Asset Added - ID: " . $lastid); return "10"; }
    	}

    public static function edit($data) {
    	global $database;
    	global $mainDatabase;

        $categoryid = 0;
        $manufacturerid = 0;
        $modelid = 0;
        $supplierid = 0;
        $locationid = 0;

        if(isset($data['category'])) {
            $categoryid = $database->get("assetcategories", "id", [ "name" => $data['category'] ]);
            if($categoryid == "") $categoryid = $database->insert("assetcategories", [ "name" => $data['category'], "color" => rand_color() ]);
        }

        if(isset($data['manufacturer'])) {
            $manufacturerid = $database->get("manufacturers", "id", [ "name" => $data['manufacturer'] ]);
            if($manufacturerid == "") $manufacturerid = $database->insert("manufacturers", [ "name" => $data['manufacturer'] ]);
        }

        if(isset($data['model'])) {
            $modelid = $database->get("models", "id", [ "name" => $data['model'] ]);
            if($modelid == "") $modelid = $database->insert("models", [ "name" => $data['model'] ]);
        }

        if(isset($data['supplier'])) {
            $supplierid = $database->get("suppliers", "id", [ "name" => $data['supplier'] ]);
            if($supplierid == "") {
                $supplierid = $database->insert("suppliers", [ "name" => $data['supplier'] ]);
                $mainDatabase->insert("vendor",[
                    "vendor"=>$data['supplier'],
                    "status"=>"1"
                ]);
            }
        }

        if(isset($data['location'])) {
            $locationid = $database->get("locations", "id", [ "AND" => [ "name" => $data['location'], "clientid" => $data['clientid'] ] ]);
            if($locationid == "") {
                $locationid = $database->insert("locations", [ "name" => $data['location'], "clientid" => $data['clientid'] ]);
                $mainDatabase->insert("room", [
                    "room_name" => $data['location'],
                    "category" => "General"
                ]);
            }
        }

    	$database->update("assets", [
            "categoryid" => $categoryid,
    		"adminid" => $data['adminid'],
    		"clientid" => $data['clientid'],
    		"userid" => $data['userid'],
            "manufacturerid" => $manufacturerid,
    		"modelid" => $modelid,
    		"supplierid" => $supplierid,
    		"statusid" => $data['statusid'],
    		"purchase_date" => $data['purchase_date'],
    		"warranty_months" => $data['warranty_months'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"serial" => $data['serial'],
    		"notes" => $data['notes'],
            "locationid" => $locationid,
            "purchase_order" => $data['purchase_order'],
            "value" => $data['value'],
            "condition" => $data['condition'],
            "removal_date" => $data['removal_date']
    	], [ "id" => $data['id'] ]);
    	logSystem("Asset Edited - ID: " . $data['id']);
    	return "20";
    	}

    	public static function generateQrCode($id){
            global $database;

            require_once $_SERVER['DOCUMENT_ROOT']."/plugin/phpqrcode/qrlib.php";
            require_once $_SERVER['DOCUMENT_ROOT']."/plugin/phpqrcode/config.php";

            define('EXAMPLE_TMP_SERVERPATH', $_SERVER['DOCUMENT_ROOT'].'/asset/uploads/assets/qrcode/');
            define('EXAMPLE_TMP_REALPATH', '/asset/uploads/assets/qrcode/');

            $childDir = '/asset/uploads/assets/qrcode/';
            $tempDir = $_SERVER['DOCUMENT_ROOT'].$childDir;
            $serverRoot = "https://".$_SERVER['SERVER_NAME'];

            $codeContents = "$serverRoot/modules/facility/ServerSide/scanAsset.php?id=$id";

            // we need to generate filename somehow,
            $fileName = 'asset_'.$id.'.png';

            $pngAbsoluteFilePath = $tempDir.$fileName;
            $urlRelativeFilePath = EXAMPLE_TMP_REALPATH.$fileName;

            // generating
            if (!file_exists($pngAbsoluteFilePath)) {
                QRcode::png($codeContents, $pngAbsoluteFilePath);

                // RETURN IMAGE LINK
                $lastid = $database->insert("assets_qrcode", [
                    "id_assets" => $id,
                    "dir" => $childDir.$fileName
                ]);
                if ($lastid == "0") {
                    unlink($pngAbsoluteFilePath);
                    return "42";
                }else{
                    return "41";
                }
            }else{
                return "42";
            }
        }

    public static function delete($id) {
    	global $database;
        $database->delete("assets", [ "id" => $id ]);
    	logSystem("Asset Deleted - ID: " . $id);
    	return "30";
    	}

    public static function nextAssetTag() {
    	global $database;
        $max = $database->max("assets", "id");
    	return $max+1;
    	}


    public static function assignLicense($data) { //assign license to asset
    	global $database;
    	$lastid = $database->insert("licenses_assets", [
    		"licenseid" => $data['licenseid'],
    		"assetid" => $data['assetid']
    	]);
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    }

    public static function unassignLicense($id) { //unassign license to asset
    	global $database;
        $database->delete("licenses_assets", [ "id" => $id ]);
    	return "30";
    }

    public static function updateBorrowStatus($id, $value){
        global $database;

        $database->update("assets", [
            "is_borrow" => $value
        ], [ "id" => $id ]);
        logSystem("Asset Borrow Status Edited - ID: " . $id);
        return "43";
    }

    public static function manualBorrowRecord($data){
        global $database;
        $datetime = date('Y-m-d H:i:s');

        if(!isset($data['usergiven'])){
            $idUserGiven = "0";
        }else{
            $idUserGiven = $data['usergiven'];
        }

        if(!isset($data['userid'])){
            $idUser = "0";
        }else{
            $idUser = $data['userid'];
        }

        $startdate = $_GET['startdate'];
        $enddate = $_GET['enddate'];

        $lastid = $database->insert("assets_borrowed", [
            "id_assets" => $data['assetid'],
            "id_user_given" => $idUserGiven,
            "id_user" => $idUser,
            "datetime" => $datetime,
            "start_time" => date('Y-m-d H:i:s', strtotime($startdate." ".$data['starttime'])),
            "end_time" => date('Y-m-d H:i:s', strtotime($enddate." ".$data['endtime'])),
            "purpose" => $data['purpose'],
            "return_time" => "0000-00-00 00:00:00",
            "id_user_return" => "0",
            "id_user_received" => "0",
            "notes" => ""
        ]);

        if ($lastid == "0") { return "45"; } else { return "44"; }
    }

    public static function manualReturnBorrowRecord($data){
        global $database;

        $database->update("assets_borrowed",[
            "id_user_return" => $data["userreturnid"],
            "id_user_received" => $data["userreceivedid"],
            "notes" => $data["notes"],
            "return_time" => date('Y-m-d H:i:s', strtotime($data['returndate']." ".$data['returntime'])),
            "is_returned" => "1"
        ],["id" => $data['id']]);

        logSystem("Asset Deleted - ID: " . $data['id']);
        return "30";
    }
}


?>
