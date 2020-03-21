<?php

class File extends App {


    public static function upload($data,$files) {
    	$status = 9500;
    	global $database;
    	global $scriptpath;


        $total = count($files['file']['name']);

        for($i=0; $i<$total; $i++) {

                	$targetdir = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;

                    $nextfileid = $database->max("files","id") + 1;
                	$filename = $nextfileid . "-" . basename($files["file"]["name"][$i]);
                    if(empty($data['name'])) { $emptyfilename = true; $data['name'] = $filename; }

                	$targetfile = $targetdir . $filename;

                	if (file_exists($targetfile)) { $status = 9501; }

                	if($status == 9500) {
                		if (move_uploaded_file($files["file"]["tmp_name"][$i], $targetfile)) {
                			$database->insert("files", [
                				"clientid" => $data['clientid'],
                				"projectid" => $data['projectid'],
                				"assetid" => $data['assetid'],
                				"ticketreplyid" => $data['ticketreplyid'],
                				"name" => $data['name'],
                				"file" => $filename
                			]);
                			$status == 9500;
                		}
                		else $status == 9502;
                	}

                    if($emptyfilename) { $data['name'] = ""; }

        }
    	return $status;
    }

    public static function delete($id) {
    	$status = 9503;
    	global $database;
    	global $scriptpath;

    	$targetdir = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;
    	$file = getRowById("files",$id);
    	$filename = $file['file'];
    	$targetfile = $targetdir . $filename;

    	if (!unlink($targetfile)) { deleteRowById("files",$id); $status = 9504; }
    	else { deleteRowById("files",$id); $status = 9503; }

    	return $status;
    }

    public static function icon($file) {
    	global $scriptpath;
    	$filepath = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $file;
    	$ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    	$icon = "file-o";

    	$archive = array("zip","rar","7z","gz","iso","tar","bz2","xz","ace","apk","xar","zz","war","wim","tar.gz","tgz","tar.Z","tar.bz2","tbz2","dmg","s7z");
    	$audio = array("mp3","wav","aac","aa","aax","aiff","au","flac","m4a","m4b","m4p","ogg","oga","wma");
    	$code = array("php","html","css","js","asp","htm","sql","pl");
    	$excel = array("xls","xlsx","xlsm","xml","xlam","xla","ods","fods");
    	$image = array("png","jpg","jpeg","tiff","tif","gif","bmp","ai","svg","eps");
    	$pdf = array("pdf","xps");
    	$powerpoint = array("ppt","pot","pps","pptx","pptm","potx","potm","ppam","ppsx","ppsm","sldx","sldm","odg","fodg");
    	$text = array("txt","nfo","rtf");
    	$video = array("avi","3gp","wmv","ogg",",mpeg","mpg","mpe","mov","mkv","flr","fla","flv");
    	$word = array("doc","dot","docx","docm","dotx","dotm","docb","odt","fodt");

    	if(in_array($ext,$archive)) $icon = "file-archive-o";
    	if(in_array($ext,$audio)) $icon = "file-audio-o";
    	if(in_array($ext,$code)) $icon = "file-code-o";
    	if(in_array($ext,$excel)) $icon = "file-excel-o";
    	if(in_array($ext,$image)) $icon = "file-image-o";
    	if(in_array($ext,$pdf)) $icon = "file-pdf-o";
    	if(in_array($ext,$powerpoint)) $icon = "file-powerpoint-o";
    	if(in_array($ext,$text)) $icon = "file-text-o";
    	if(in_array($ext,$video)) $icon = "file-video-o";
    	if(in_array($ext,$word)) $icon = "file-word-o";

    	return $icon;
    }

}


?>
