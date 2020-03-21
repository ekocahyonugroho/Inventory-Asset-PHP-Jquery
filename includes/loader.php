<?php

##################################
###       LOAD FUNCTIONS       ###
##################################

require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php');


##################################
###      LOAD CONFIG FILE      ###
##################################

if(file_exists($scriptpath . DIRECTORY_SEPARATOR . "config.php")) {
    require($scriptpath . DIRECTORY_SEPARATOR . 'config.php');
    if(isset($DBconfig)) $config = $DBconfig;
    if(isset($DBMainconfig)) $mainSystemConfig = $DBMainconfig;
    }

else { // redirect to install if config.php file does not exist
    header("Location:install/index.php");
    exit;
}


##################################
###      REGISTER CLASSES      ###
##################################

spl_autoload_register('vendorClassAutoload');
spl_autoload_register('appClassAutoload');


##################################
###          APP INIT          ###
##################################

### INITIALIZE DATABSE CLASS ###
$database = new medoo($config);
$mainDatabase = new medoo($mainSystemConfig);

### START THE SESSION ###
session_start();

### DATE & TIME ###
date_default_timezone_set(getConfigValue("timezone"));
$datetime = date("Y-m-d H:i:s");
$date = date("Y-m-d");

### GET PAGE ROUTE (DEFAULTS TO DASHBOARD IF NOT SET) ###
if (empty($_GET['route'])) $route = "dashboard"; else $route = $_GET['route'];

### GET PAGE SECTION (IF ISSET) ###
if (isset($_GET['section'])) $section = $_GET['section']; else $section = "";

### LOAD STATUS MESSAGE FOR DISPLAY AND CLEAR IT ###
if (!empty($_SESSION['statuscode'])) {
    $statuscode = $_SESSION['statuscode'];
    $status = array(); $statusmessage = $database->get("statuscodes", "*", ["code" => $statuscode]);
    clearStatus();
    }

### CHECK IF USER IS SIGNED IN, EXCEPT ON SIGNIN OR RECOVER PASSWORD PAGE ###
if ($route != "signin" && $route != "forgot") isSignedIn();

### INITIALIZE LOGGED IN USER (LIU) ARRAY & PERMISSIONS ###
if ($route != "signin" && $route != "forgot") {
    $liu = $database->get("people", "*", ["sessionid" => session_id() ]);
    $perms = unserialize(getSingleValue("roles","perms",$liu['roleid']));
    $isAdmin = false; if($liu['type'] == "admin") $isAdmin = true;
}



##################################
###        LOAD LANGUAGE       ###
##################################

// get default app language
$lang = getConfigValue("default_lang");

// overwrite default lang if liu has one defined
if(isset($liu)) {
    if($liu['lang'] != "") $lang = $liu['lang'];
    }

// define language file path
$langfile = $scriptpath . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . $lang . ".mo";

// define overriden language file path
$orlangfile = $scriptpath . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . "override" . DIRECTORY_SEPARATOR . $lang . ".mo";

// load overriden language file (if exists)
if(file_exists($orlangfile)) {
    $streamer = new FileReader($orlangfile);
    $t = new gettext_reader($streamer);
}
// if overridden lang file does not exist, try to load normal language file (if exists)
else {
    if(file_exists($langfile)) {
        $streamer = new FileReader($langfile);
        $t = new gettext_reader($streamer);
    }
}


##################################
###   LOAD APP CONTROLLERS     ###
##################################

// general controller (always loads)
require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'general.php');

// modals controller (loads only if a modal is requested)
if(isset($_GET['modal'])) require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'modals.php');

// quick actions controller (loads only if a quick action is requested)
if(isset($_GET['qa'])) require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'quickactions.php');

// actions controller (loads only if an action is requested)
if(isset($_POST['action'])) require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'actions.php');

// data controller (loads only if someone is logged in)
if(isset($liu)) require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'data.php');


?>
