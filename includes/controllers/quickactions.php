<?php

##################################
###       QUICK ACTIONS        ###
##################################


switch($_GET['qa']) {

	case "ticketClose":
        Ticket::updateStatus($_GET['id'],"Closed");
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']);
        break;

	case "ticketReopen":
        Ticket::updateStatus($_GET['id'],"Reopened");
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']);
        break;

	case "ticketAssignToMe":
        Ticket::assignTo($_GET['id'],$liu['id']);
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']);
        break;

	case "getTicketReply":
        echo getSingleValue("tickets_replies","message",$_GET['id']);
        break;

    case "getUserEmail":
        echo getSingleValue("people","email",$_GET['id']);
        break;

	case "setAutorefresh":
        Profile::setAutorefresh($liu['id'],$_GET['autorefresh']);
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']."&section=".$_GET['section']);
        break;

	case "removeAvatar":
        Profile::removeAvatar($liu['id']);
        header("Location:?route=profile");
        break;

    case "updateIssueStatus":
        Issue::updateStatus($_POST['issueid'],$_POST['status']);
        break;

    case "download":
        $file = getRowById("files",$_GET['id']);
        $targetfile = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $file['file'];
        if (file_exists($targetfile)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['file'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($targetfile));
            readfile($targetfile);
            exit;
            }
        else echo "File does not exist.";

} // end switch



?>
