<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$scriptpath = dirname(__DIR__);

// LOAD FUNCTIONS
require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php');

// AUTOLOAD CLASSES
spl_autoload_register('vendorClassAutoload');
spl_autoload_register('appClassAutoload');

# LOAD CONFIGURAGION FILE
require($scriptpath . DIRECTORY_SEPARATOR . 'config.php');

# INITIALIZE MEDOO
$database = new medoo($config);

# DATE & TIME
date_default_timezone_set(getConfigValue("timezone"));

$mailbox = getConfigValue("tickets_server");
$username = getConfigValue("tickets_username");
$password = getConfigValue("tickets_password");
$encryption = getConfigValue("tickets_encrypton");

if(empty($mailbox) || empty($username)) echo "Please configure your IMAP server in System > System Settings > Tickets.";
else {
    $imap = new Imap($mailbox, $username, $password, $encryption);
    if($imap->isConnected()===false) echo "Could not authenticate to IMAP server. Please check your IMAP server configuration in System > System Settings > Tickets.";
    else {
        $imap->selectFolder('INBOX');
        $overallMessages = $imap->countMessages();
        if($overallMessages > 0) {

            $emails = $imap->getMessages();

            foreach($emails as $email) {

                $ccs = array();
                if(isset($email['cc'])) { $ccs = $email['cc']; }

                $replyid = Ticket::emailToTicket($email['from'], $email['subject'], $email['body'], $email['importance'], $ccs);

                if(!empty($email['attachments'])) {
                    $index = 0;
                    foreach($email['attachments'] as $attachment){
                        $file = $imap->getAttachment($email['uid'],$index);

                        $max = $database->max("files","id");
                        $max = $max + 1;
                        $filename = $max . "-" . $file['name'];

                        $fileid = $database->insert("files", [
                            "clientid" => 0,
                            "projectid" => 0,
                            "assetid" => 0,
                            "ticketreplyid" => $replyid,
                            "name" => $file['name'],
                            "file" => $filename
                        ]);

                        $newfile = fopen($scriptpath . "/uploads/" . $filename ,"x");
                        fwrite($newfile,$file['content']);
                        fclose($newfile);


                        $index++;
                    }
                }

                $imap->deleteMessage($email['uid']);

            }

            echo "Imported " . count($emails) . " emails.";
            //$imap->purge();

        }
        else echo "Nothing to import";
    }
}

// process escalation rules
echo "<br>Processing Escalation Rules";

Ticket::processRules();

echo "<br>Auto Closing Tickets";
Ticket::autoClose();

?>
