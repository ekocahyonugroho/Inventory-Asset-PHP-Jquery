<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

# FUNCTIONS


function randomString($chars=10) { //generate random string
	$characters = '0123456789abcdef';
	$randstring = '';
	for ($i = 0; $i < $chars; $i++) { $randstring .= $characters[rand(0, strlen($characters) -1)]; }
	return $randstring;
}
// Encrypt Function
function mc_encrypt($encrypt,$key){
	global $config;
    $encrypt = serialize($encrypt);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    $key = pack('H*', $key);
    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    return $encoded;
}




$latestversion = 1.7;
$status = 'ok';

# LOAD CONFIGURAGION FILE
if(file_exists("../config.php")) {
	require('../config.php');
	if(isset($DBconfig)) $config = $DBconfig;
}

else { $status = 'noconfig'; }

if($status == "ok") {
    if(!is_writable("../config.php")) $status = 'configunw';
}

if($status == 'ok') {
    # INITIALIZE MEDOO
    require('../vendor/classes/class.medoo.php');
    $database = new medoo($config);
    $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);

    // UPGRADE to 1.1
    if($currentversion == 1.0) {
        $sql = file_get_contents('sql/db_1.0-1.1.sql');
        $database->query($sql);
        sleep(2);

        $database->update("config", ["value" => "1.1"], ["name" => "db_version"]);
        $issues = $database->select("issues", "*");
        foreach($issues as $issue) { $database->update("issues", ["dateadded" => $issue['duedate'] . " 00:00:00"]); }
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }

    // UPGRADE TO 1.2
    if($currentversion == 1.1) {
        $sql = file_get_contents('sql/db_1.1-1.2.sql');
        $database->query($sql);
        sleep(2);

        $replies = $database->select("tickets_replies", "*");
        foreach($replies as $reply) {
            $peopleid = 0;
            if($reply['adminid'] != 0) $peopleid = $reply['adminid'];
            if($reply['userid'] != 0) $peopleid = $reply['userid'];
            $database->update("tickets_replies", ["peopleid" => $peopleid], ["id" => $reply['id']]);
        }

        $database->query("ALTER TABLE `tickets_replies` DROP `adminid` , DROP `userid` ;");
        $database->update("config", ["value" => "1.2"], ["name" => "db_version"]);
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.3
    if($currentversion == 1.2) {
        $sql = file_get_contents('sql/db_1.2-1.3.sql');
        $database->query($sql);
        sleep(2);

        $asset_tag_prefix = $database->get("config", "value", [ "name" => "asset_tag_prefix" ]);
        $license_tag_prefix = $database->get("config", "value", [ "name" => "license_tag_prefix" ]);

        $clients = $database->select("clients", "*");
        foreach($clients as $client) {
            $database->update("clients", ["asset_tag_prefix" => $asset_tag_prefix, "license_tag_prefix" => $license_tag_prefix], ["id" => $client['id']]);
        }

        $database->update("config", ["value" => "1.3"], ["name" => "db_version"]);
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.4
    if($currentversion == 1.3) {
        $sql = file_get_contents('sql/db_1.3-1.4.sql');
        $database->query($sql);
        sleep(2);

        $people = $database->select("people", "*");
        foreach($people as $item) {
            if($item['type'] == "admin") $database->update("people", ["roleid" => 1], ["id" => $item['id']]);
            if($item['type'] == "user") $database->update("people", ["roleid" => 2], ["id" => $item['id']]);
        }

        $database->update("config", ["value" => "1.4"], ["name" => "db_version"]);
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }



    // UPGRADE TO 1.5
    if($currentversion == 1.4) {
        $sql = file_get_contents('sql/db_1.4-1.5.sql');
        $database->query($sql);
        sleep(2);

        $database->update("config", ["value" => "1.5"], ["name" => "db_version"]);
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.6
    if($currentversion == 1.5) {
        $sql = file_get_contents('sql/db_1.5-1.6.sql');
        $database->query($sql);
        sleep(2);

        $assets = $database->select("assets", "*");
        foreach($assets as $asset) {
            if($asset['modelid'] != 0){
                $manufacturerid = $database->get("models", "manufacturerid", [ "id" => $asset['modelid'] ]);
                $database->update("assets", ["manufacturerid" => $manufacturerid], ["id" => $asset['id'] ]);
            }
        }

        $adminPerms = 'a:89:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:9:"addTicket";i:26;s:10:"editTicket";i:27;s:12:"deleteTicket";i:28;s:12:"manageTicket";i:29;s:17:"manageTicketRules";i:30;s:17:"manageTicketNotes";i:31;s:11:"viewTickets";i:32;s:8:"addIssue";i:33;s:9:"editIssue";i:34;s:11:"deleteIssue";i:35;s:11:"manageIssue";i:36;s:10:"viewIssues";i:37;s:10:"addComment";i:38;s:11:"editComment";i:39;s:13:"deleteComment";i:40;s:13:"assignComment";i:41;s:12:"viewComments";i:42;s:13:"addCredential";i:43;s:14:"editCredential";i:44;s:16:"deleteCredential";i:45;s:14:"viewCredential";i:46;s:15:"viewCredentials";i:47;s:5:"addKB";i:48;s:6:"editKB";i:49;s:8:"deleteKB";i:50;s:6:"viewKB";i:51;s:9:"addPReply";i:52;s:10:"editPReply";i:53;s:12:"deletePReply";i:54;s:12:"viewPReplies";i:55;s:10:"uploadFile";i:56;s:12:"downloadFile";i:57;s:10:"deleteFile";i:58;s:9:"viewFiles";i:59;s:7:"addHost";i:60;s:8:"editHost";i:61;s:10:"deleteHost";i:62;s:10:"manageHost";i:63;s:14:"viewMonitoring";i:64;s:7:"addUser";i:65;s:8:"editUser";i:66;s:10:"deleteUser";i:67;s:9:"viewUsers";i:68;s:8:"addStaff";i:69;s:9:"editStaff";i:70;s:11:"deleteStaff";i:71;s:9:"viewStaff";i:72;s:7:"addRole";i:73;s:8:"editRole";i:74;s:10:"deleteRole";i:75;s:9:"viewRoles";i:76;s:10:"addContact";i:77;s:11:"editContact";i:78;s:13:"deleteContact";i:79;s:12:"viewContacts";i:80;s:10:"manageData";i:81;s:14:"manageSettings";i:82;s:8:"viewLogs";i:83;s:10:"viewSystem";i:84;s:10:"viewPeople";i:85;s:11:"viewReports";i:86;s:11:"autorefresh";i:87;s:6:"search";i:88;s:4:"Null";}';
        $database->update("roles", ["perms" => $adminPerms], [ "id" => 1 ]);
        $database->query("ALTER TABLE `models` DROP `manufacturerid`;");

        $database->update("config", ["value" => "1.6"], ["name" => "db_version"]);
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.7
    if($currentversion == 1.6) {
        // update database
        $sql = file_get_contents('sql/db_1.6-1.7.sql');
        $database->query($sql);
        sleep(3);

        // add encryption key to config
        $encryption_key = randomString(64);
        $data = '<?php $config = array(
         "database_type"=>"mysql",
         "database_name"=>"'.$config['database_name'].'",
         "server"=>"'.$config['server'].'",
         "username"=>"'.$config['username'].'",
         "password"=>"'.$config['password'].'",
         "charset"=>"utf8",
         "port"=>3306,
         "encryption_key"=>"'.$encryption_key.'" ); ?>';
        $file = fopen("../config.php","w+");
        fwrite($file,$data);
        fclose($file);

        // db operations
        $adminPerms = 'a:90:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:9:"addTicket";i:26;s:10:"editTicket";i:27;s:12:"deleteTicket";i:28;s:12:"manageTicket";i:29;s:17:"manageTicketRules";i:30;s:17:"manageTicketNotes";i:31;s:22:"manageTicketAssignment";i:32;s:11:"viewTickets";i:33;s:8:"addIssue";i:34;s:9:"editIssue";i:35;s:11:"deleteIssue";i:36;s:11:"manageIssue";i:37;s:10:"viewIssues";i:38;s:10:"addComment";i:39;s:11:"editComment";i:40;s:13:"deleteComment";i:41;s:13:"assignComment";i:42;s:12:"viewComments";i:43;s:13:"addCredential";i:44;s:14:"editCredential";i:45;s:16:"deleteCredential";i:46;s:14:"viewCredential";i:47;s:15:"viewCredentials";i:48;s:5:"addKB";i:49;s:6:"editKB";i:50;s:8:"deleteKB";i:51;s:6:"viewKB";i:52;s:9:"addPReply";i:53;s:10:"editPReply";i:54;s:12:"deletePReply";i:55;s:12:"viewPReplies";i:56;s:10:"uploadFile";i:57;s:12:"downloadFile";i:58;s:10:"deleteFile";i:59;s:9:"viewFiles";i:60;s:7:"addHost";i:61;s:8:"editHost";i:62;s:10:"deleteHost";i:63;s:10:"manageHost";i:64;s:14:"viewMonitoring";i:65;s:7:"addUser";i:66;s:8:"editUser";i:67;s:10:"deleteUser";i:68;s:9:"viewUsers";i:69;s:8:"addStaff";i:70;s:9:"editStaff";i:71;s:11:"deleteStaff";i:72;s:9:"viewStaff";i:73;s:7:"addRole";i:74;s:8:"editRole";i:75;s:10:"deleteRole";i:76;s:9:"viewRoles";i:77;s:10:"addContact";i:78;s:11:"editContact";i:79;s:13:"deleteContact";i:80;s:12:"viewContacts";i:81;s:10:"manageData";i:82;s:14:"manageSettings";i:83;s:8:"viewLogs";i:84;s:10:"viewSystem";i:85;s:10:"viewPeople";i:86;s:11:"viewReports";i:87;s:11:"autorefresh";i:88;s:6:"search";i:89;s:4:"Null";}';
        $database->update("roles", ["perms" => $adminPerms], [ "id" => 1 ]);

        $credentials = $database->select("credentials", "*");
        foreach($credentials as $credential) {
            $database->update("credentials", [ "password" => mc_encrypt($credential['password'],$encryption_key) ], ["id" => $credential['id'] ]);
        }

        $licenses = $database->select("licenses", "*");
        foreach($licenses as $license) {
            $database->update("licenses", [ "serial" => mc_encrypt($license['serial'],$encryption_key) ], ["id" => $license['id'] ]);
        }


        $database->update("config", ["value" => "1.7"], ["name" => "db_version"]);
        $status = 'updated';

        sleep(2);
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }



}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>onTrack Update</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="../template/assets/icon.png"/>
        <link rel="apple-touch-icon image_src" href="../template/assets/icon-large.png"/>
        <link href="../template/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
		<link href="../template/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>on</b>Track Update
      </div><!-- /.login-logo -->
      <div class="login-box-body">

          <?php if($status == "ok"): ?>
                        <p class="login-box-msg">Nothing to do, database is already at latest version.</p>
          <?php endif; ?>
          <?php if($status == "noconfig"): ?>
                        <p class="login-box-msg">Configuration file is missing.</p>
          <?php endif; ?>
          <?php if($status == "configunw"): ?>
                        <p class="login-box-msg">Configuration file (config.php) must be writable for the upgrade process to complete.</p>
          <?php endif; ?>
          <?php if($status == "updated"): ?>
                        <p class="login-box-msg">Update complete!<br>Please delete the "install" folder.</p>
          <?php endif; ?>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->



    <!-- jQuery 2.1.3 -->
    <script src="../template/assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../template/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

  </body>


</html>
