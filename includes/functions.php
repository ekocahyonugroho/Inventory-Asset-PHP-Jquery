<?php
// ----------------------------------------------------------------------------------------------
// GENERAL FUNCTIONS

function randomString($chars=10) { //generate random string
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randstring = '';
	for ($i = 0; $i < $chars; $i++) { $randstring .= $characters[rand(0, strlen($characters) -1)]; }
	return $randstring;
}

function currentFileName() { //return current file name
	return basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
}

function baseURL($sub=0) { //return base url for cron jobs
	 $requesturi = explode("?",$_SERVER["REQUEST_URI"]);
	 $subdir =  $requesturi[0];
	 $pageURL = 'http';
	 if(isset($_SERVER["HTTPS"])) { if($_SERVER["HTTPS"] == "on") {$pageURL .= "s";} }
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"] . $subdir;
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"] . $subdir;
	 }
	 return $pageURL;
}

function getGravatar($email,$size) { //get gravatar image for the given email address
	global $database;

	$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=mm" . "&s=" . $size;
	$avatar = $database->get("people", "avatar", [ "email" => strtolower($email) ]);

	if($avatar != "") { return "data:image/jpeg;base64," . base64_encode($avatar); }

	else return $grav_url;
}

function curlReturn($url) { //get url with curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
	curl_setopt($ch,CURLOPT_URL, $url);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function rand_color() { //generate random color
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function ttruncat($text,$numb=30) { //truncate text
	if (strlen($text) > $numb) {
	  	$text = substr($text, 0, $numb);
	  	$text = substr($text,0,strrpos($text," "));
	  	$etc = " ...";
	  	$text = $text.$etc;
	  }
	return $text;
}

function smartDate($timestamp) {
	$diff = time() - $timestamp;

	if ($diff <= 0) {
		return __('Now');
	}
	else if ($diff < 60) {
		return _x("%d second ago","%d seconds ago",floor($diff));
	}
	else if ($diff < 60*60) {
		return _x("%d minute ago","%d minutes ago",floor($diff/60));
	}
	else if ($diff < 60*60*24) {
		return _x("%d hour ago","%d hours ago",floor($diff/(60*60)));
	}
	else if ($diff < 60*60*24*30) {
		return _x("%d day ago","%d days ago",floor($diff/(60*60*24)));
	}
	else if ($diff < 60*60*24*30*12) {
		return _x("%d month ago","%d months ago",floor($diff/(60*60*24*30)));
	}
	else {
		return _x("%d year ago","%d years ago",floor($diff/(60*60*24*30*12)));
	}
}

function escapeJavaScriptText($string) {
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}


// ----------------------------------------------------------------------------------------------
// GENERAL DATABASE FUNCTIONS

function getRowById($table,$id) //return associative array from one row by id
	{
	global $database;
	$row = $database->get($table, "*", ["id" => $id]);
	return $row;
	}

function getSingleValue($table,$column,$id) //returns single value from table row by id
	{
	global $database;
	$value = $database->get($table, $column, ["id" => $id]);
	return $value;
	}

function getTable($table,$columns="*",$sortby="id",$sortway="ASC") //get entire table
	{
	global $database;
	$table = $database->select($table, $columns, ["ORDER" => $sortby." ".$sortway]);
	return $table;
	}

function getTableFiltered($table,$filterColumn1,$filterValue1,$filterColumn2="",$filterValue2="",$columns="*",$sortby="id",$sortway="ASC") //get entire table filtered
	{
	global $database;
	if ($filterColumn2 == "") {
		$table = $database->select($table, $columns, [$filterColumn1 => $filterValue1, "ORDER" => $sortby." ".$sortway]);
	}
	else {
		$table = $database->select($table, $columns, [ "AND" => [$filterColumn1 => $filterValue1, $filterColumn2 => $filterValue2] ], ["ORDER" => $sortby." ".$sortway]);
	}
	return $table;
	}

function countTable($table) //count table rows
	{
	global $database;
	$count = $database->count($table);
	return $count;
	}

function countTableFiltered($table,$filterColumn1,$filterValue1,$filterColumn2="",$filterValue2="") //count table rows with filter
	{
	global $database;
	if ($filterColumn2 == "") { $count = $database->count($table, [$filterColumn1 => $filterValue1]); }
	else { $count = $database->count($table, [ "AND" => [$filterColumn1 => $filterValue1, $filterColumn2 => $filterValue2] ]); }
	return $count;
	}

function getConfigValue($name) //return config value from database
	{
	global $database;
	return $database->get("config", "value", ["name" => $name]);
	}

function deleteRowById($table,$id) //detete row(s) by id
	{
	global $database;
    $database->delete($table, [ "id" => $id ]);
	return "delOK";
	}

	function countStockNonAssetById($id){
		$stock = 0;
		$in = 0;
		$out = 0;

		$circulations = getTableFiltered("non_asset_circulation", "id_non_asset",$id);

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

function getPO($table="po",$columns="*",$sortby="id",$sortway="ASC") //get entire table
{
	global $mainDatabase;
	$year = date('Y');
	//$table = $mainDatabase->select($table, $columns, ["ORDER" => $sortby." ".$sortway]);
	$table = $mainDatabase->query("SELECT * FROM sis.po,sis.po_payment_type WHERE po.poStatus = 'released' AND po.id = po_payment_type.idPo AND po_payment_type.ppdNumber <> '' AND po.date LIKE '%$year%'");
	return $table;
}


// ----------------------------------------------------------------------------------------------
// NAVIGATION

function reroute($data,$status=0) {
	$location = "Location:?route=" . $data['route'];
	if(isset($data['routeid'])) $location .= "&id=" . $data['routeid'];
	if(isset($data['section'])) $location .= "&section=" . $data['section'];
	setStatus($status);
	header($location);
}

function setStatus($status) {
	if($status != 0 && $status != "") $_SESSION["statuscode"] = $status;
}

function clearStatus() {
	$_SESSION["statuscode"] = "";
}


// ----------------------------------------------------------------------------------------------
// CLASS LOADERS

function vendorClassAutoload($classname) {
	global $scriptpath;
	$file = $scriptpath . DIRECTORY_SEPARATOR .'vendor'. DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
	if (file_exists($file)) require($file);
}

function appClassAutoload($classname) {
	global $scriptpath;
	$file = $scriptpath . DIRECTORY_SEPARATOR .'includes'. DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
	if (file_exists($file)) require($file);
}


// ----------------------------------------------------------------------------------------------
// TEXT OUTPUT

function __($text) {
	global $t;
	if(isset($t)) return $t->translate($text);
	else return $text;
}

function _e($text) {
	echo __($text);
}

function _x($sg,$pl,$count) {
	global $t;
	if(isset($t)) return sprintf($t->ngettext($sg,$pl,intval($count)), $count);
	else {
		if($count == "1") return sprintf($sg,$count);
		elseif($count > 1) return sprintf($pl,$count);
	}
}

// ----------------------------------------------------------------------------------------------
// AUTHENTICATION FUNCTIONS

function signIn($email,$password) //login and set session
	{
		$idUser = $_SESSION['id'];
		global $database;
		$email = strtolower($email);
		//$people = $database->count("people",["AND" => ["email" => $email,"password" => sha1($password)]]);
        $people = $database->count("people",["idUser" => $idUser]);

		if ($people == "1") {
			$sessionid = session_id();
            $database->update("people", ["sessionid" => $sessionid], ["idUser" => $idUser]);
			$people = $database->get("people","*",["idUser" => $idUser]);
			logSystem("User/Admin Logged In - ID: " . $people['id']);
			header("Location:?route=dashboard");
			exit;
		}
		else {
			logSystem("User/Admin Login Failure - SESSION USER: " . $idUser." COUNT PEOPLE : ".$people);
			setStatus(1200);
			//header("Location:?route=signin");
            header("Location:https://".$_SERVER['SERVER_NAME']);
			exit;
		}
	}

function isSignedIn() //check if someone is logged in, if not redirect to login page
{
    global $database;
    //session_start();
    $idUser = $_SESSION['id'];
    $sessionid = session_id();
    $people = $database->count("people", ["idUser" => $idUser]);
    if($people != 1) { header("Location:https://".$_SERVER['SERVER_NAME']); exit; }
}


function isAuthorized($action) {
    global $perms;

    if(!in_array($action,$perms)) { setStatus("1"); header("Location:?route=dashboard"); exit; }
}

function signOut($id) //unset user/admin session
{
    global $database;
    $database->update("people", ["sessionid" => ""], ["id" => $id]);
    logSystem("User/Staff Logged Out - ID: " . $id);
    header("Location:https://".$_SERVER['SERVER_NAME']);
    exit;
}

function resetConfirmation($email) //set password resetkey and send confirmation email for password reset
	{
		global $database;
		$email = strtolower($email);
		$count = $database->count("people",["email" => $email]);

		if ($count == "1") {
			$people = $database->get("people","*",["email" => $email]);
			$resetkey = randomString(32);
			$database->update("people", ["resetkey" => $resetkey], ["email" => $email]);
			$resetlink = baseURL(-14) . "/?route=forgot&resetkey=" . $resetkey;
			Notification::passwordReset($people['id'],$resetlink);
			setStatus(1300);
			header("Location:?route=forgot");
			exit;
		}
		else { setStatus(1400); header("Location:?route=forgot");  exit; }
	}

function resetPassword($resetkey,$password) //reset password
	{
		global $database;
		$count = $database->count("people",["resetkey" => $resetkey]);

		if ($count == "1") {
			$people = $database->get("people","*",["resetkey" => $resetkey]);
			$database->update("people", ["password" => sha1($password),"resetkey" => ""], ["resetkey" => $resetkey]);
			logSystem("User/Admin Password Reset - ID: " . $people['id']);
			setStatus(1600);
			header("Location:?route=login");
			exit;
		}
		else { setStatus(1500); header("Location:?route=forgot");  exit; }
	}




// ----------------------------------------------------------------------------------------------
// APP LOGGING FUNCTIONS

function logSystem($description) //add to system log
	{
		global $liu;
		if(isset($liu['id'])) $peopleid = $liu['id']; else $peopleid = -1;
		global $database;
		$database->insert("systemlog", [
			"peopleid" => $peopleid,
			"ipaddress" => $_SERVER['REMOTE_ADDR'],
			"description" => $description,
			"timestamp" => date('Y-m-d H:i:s')
		]);
	}

function logEmail($clientid,$peopleid,$to,$subject,$message) //add to email log
	{
		global $database;
		$database->insert("emaillog", [
			"peopleid" => $peopleid,
			"clientid" => $clientid,
			"to" => $to,
			"subject" => $subject,
			"message" => $message,
			"timestamp" => date('Y-m-d H:i:s')
		]);
	}

function logSMS($clientid,$peopleid,$mobile,$sms) //add to sms log
	{
		global $database;
		$database->insert("smslog", [
			"peopleid" => $peopleid,
			"clientid" => $clientid,
			"mobile" => $mobile,
			"sms" => $sms,
			"timestamp" => date('Y-m-d H:i:s')
		]);
	}


// ----------------------------------------------------------------------------------------------
// COMMUNICATIONS FUNCTIONS

function sendEmail($to,$subject,$message,$clientid="0",$peopleid="0",$ccs=array()) //send email
	{
		$mail = new PHPMailer;
		$mail->CharSet = "UTF-8";
		if (getConfigValue("email_smtp_enable") == "true") {
			$mail->isSMTP();
			$mail->Host = getConfigValue("email_smtp_host");
			$mail->SMTPAuth = getConfigValue("email_smtp_auth");
			$mail->Username = getConfigValue("email_smtp_username");
			$mail->Password = getConfigValue("email_smtp_password");
			$mail->SMTPSecure = getConfigValue("email_smtp_security");
			$mail->Port = getConfigValue("email_smtp_port");

			if (getConfigValue("email_smtp_domain") != "") {
				$mail->AuthType = 'NTLM';
				$mail->Realm = getConfigValue("email_smtp_domain");
			}

			//$mail->SMTPDebug = 2;

		}

		$mail->From = getConfigValue("email_from_address");
		$mail->FromName = getConfigValue("email_from_name");
		$mail->addAddress($to);
		foreach($ccs as $cc) { $mail->AddCC($cc); }
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->IsHTML(true);

		if(!$mail->send()) {
			logEmail($clientid,$peopleid,$to,$subject,$mail->ErrorInfo);
			return 0; //error
		}
		else {
			logEmail($clientid,$peopleid,$to,$subject,$message);
			return 1; //success
		}
	}

function sendSMS($mobile,$sms,$clientid="0",$peopleid="0") { //send sms
	$provider = getConfigValue("sms_provider");
	$user = getConfigValue("sms_user");
	$password = getConfigValue("sms_password");
	$api_id = getConfigValue("sms_api_id");
	$from = getConfigValue("sms_from");

	if ($provider == "smsglobal") {
		$url = 'http://www.smsglobal.com/http-api.php' . '?action=sendsms' . '&user=' . $user . '&password=' . $password . '&from=' . $from . '&to=' . $mobile . '&text=' . substr(rawurlencode($sms), 0, 153);
		$returnedData = file_get_contents($url);
	}
	if ($provider == "clickatell") {
		$url = 'http://api.clickatell.com/http/sendmsg?user=' . $user . '&password=' . $password . '&api_id=' . $api_id . '&to=' . $mobile . '&text=' . $sms;
		$returnedData = file_get_contents($url);
	}

	logSMS($clientid,$peopleid,$mobile,$sms);
}

// ----------------------------------------------------------------------------------------------
// DATA ENCRYPTION FUNCTIONS

// Encrypt Function
function mc_encrypt($encrypt){
	global $config;
	$key = $config['encryption_key'];
    $encrypt = serialize($encrypt);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    $key = pack('H*', $key);
    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    return $encoded;
}
// Decrypt Function
function mc_decrypt($decrypt){
	global $config;
	$key = $config['encryption_key'];
    $decrypt = explode('|', $decrypt.'|');
    $decoded = base64_decode($decrypt[0]);
    $iv = base64_decode($decrypt[1]);
    if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
    $key = pack('H*', $key);
    $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
    $mac = substr($decrypted, -64);
    $decrypted = substr($decrypted, 0, -64);
    $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
    if($calcmac!==$mac){ return false; }
    $decrypted = unserialize($decrypted);
    return $decrypted;
}


// ----------------------------------------------------------------------------------------------
// GRAPHS

function workHoursByMonth($month,$clientid=0) { //calculate how many hours were worked in a month
	global $database;
	$minutes = 0;
	$startDate = $month."-01 00:00:00";
	$endDate = $month."-31 00:00:00";;

	if ($clientid == 0) {
		$items = $database->select("issues", "*", [
			"dateadded[<>]" => [$startDate, $endDate]
		]);
	}
	else {
		$items = $database->select("issues", "*", [ "AND" => [
			"dateadded[<>]" => [$startDate, $endDate],
			"clientid" => $clientid
		]]);
	}

	foreach($items as $item) { $minutes = $minutes + $item['timespent']; }

	//$hours = round($minutes/60,2);

	$hours = floor($minutes / 60);
	$minutes = ($minutes % 60);
	//return sprintf("%02d:%02d", $hours, $minutes);

	return $hours . "." . $minutes;
}

function countAssetsByCategory($categoryid,$clientid=0) {
	global $database;
	$items = 0;
	if ($clientid == 0) {
		$items = $database->count("assets", "*", [
			"categoryid" => $categoryid
		]);
	}
	else {
		$items = $database->count("assets", "*", [ "AND" => [
			"categoryid" => $categoryid,
			"clientid" => $clientid
		]]);
	}

	return $items;

}

?>
