<?php
class NonAsset extends App
{

    public static function add($data)
    {
        global $database;

        $lastid = $database->insert("non_asset", [
            "typeid" => $data['typeid'],
            "name" => $data['name'],
            "spec" => $data['spec'],
            "locationid" => $data['location'],
            "idowner" => $data['ownerid'],
            "notes" => $data['notes'],
            "units" => $data['units'],
            "max_stock" => $data['maximumStock']
        ]);

        if ($lastid == "0") {
            return "11";
        } else {
            logSystem("Non Asset Data Added - ID: " . $lastid);
            return "10";
        }
    }

    public static function addStock($data)
    {
        global $database;

        $datetime = date('Y-m-d H:i:s');

        $stock = NonAsset::countStockNonAssetById($data['id_non_asset']);
        $newStock = $stock + $data['qty'];

        $max_stock = $database->get("non_asset", "max_stock", ["id" => $data['id_non_asset']]);

        if($newStock > $max_stock){
            return "11";
        }

        $lastid = $database->insert("non_asset_circulation", [
            "po" => $data['po'],
            "id_non_asset" => $data['id_non_asset'],
            "notes" => $data['notes'],
            "number" => $data['qty'],
            "in_out" => "in",
            "userid" => $data['userid'],
            "supplierid" => $data['supplierid'],
            "datetime" => $datetime
        ]);

        if ($lastid == "0") {
            return "11";
        } else {
            logSystem("Stock In Added - ID: " . $lastid);
            return "20";
        }
    }

    public static function addStockOut($data){
        global $database;

        $datetime = date('Y-m-d H:i:s');

        $stock = NonAsset::countStockNonAssetById($data['id_non_asset']);

        if($stock < $data['qty']){
            return "11";
        }

        $lastid = $database->insert("non_asset_circulation", [
            "id_non_asset" => $data['id_non_asset'],
            "notes" => $data['notes'],
            "number" => $data['qty'],
            "in_out" => "out",
            "userid" => $data['userid'],
            "clientid" => $data['clientid'],
            "statusid" => $data['statusid'],
            "datetime" => $datetime
        ]);

        if ($lastid == "0") {
            return "11";
        } else {
            logSystem("Stock Out Added - ID: " . $lastid);
            return "20";
        }

    }

    public static function editNonAsset($data){
        global $database;

        $lastid = $database->update("non_asset", [
            "typeid" => $data['typeid'],
            "name" => $data['name'],
            "spec" => $data['spec'],
            "locationid" => $data['location'],
            "idowner" => $data['ownerid'],
            "notes" => $data['notes'],
            "units" => $data['units'],
            "max_stock" => $data['maximumStock']
        ],[ "id" => $data['id'] ]);

        if ($lastid == "0") {
            return "11";
        } else {
            logSystem("Data Updated - ID: " . $lastid);
            return "10";
        }
    }

    public static function deleteNonAssetCirculation($id){
        global $database;
        $database->delete("non_asset_circulation", [ "id" => $id ]);
        logSystem("Non Asset Circulation Data Deleted - ID: " . $id);
        return "30";
    }

    public static function deleteNonAsset($id){
        global $database;
        $database->delete("non_asset_circulation", [ "id_non_asset" => $id ]);
        $database->delete("non_asset", [ "id" => $id ]);
        logSystem("Non Asset Data Deleted - ID: " . $id);
        return "30";
    }

    public static function countStockNonAssetById($id){
        global $database;

        $stock = 0;
        $in = 0;
        $out = 0;

        $circulations = $database->select("non_asset_circulation", "*", ["id_non_asset" => $id, "ORDER" => "id ASC"]);

        foreach($circulations as $c){
            if($c['in_out'] == "in"){
                $in = $in + $c['number'];
            }

            if($c['in_out'] == "out"){
                $out = $out + $c['number'];
            }
        }

        $stock = $in - $out;

        return $stock;
    }
}
?>
