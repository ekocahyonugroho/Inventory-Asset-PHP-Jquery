<?php

##################################
###           ACTIONS          ###
##################################

switch($_POST['action']) {

	// assets
	case "addAsset":
		isAuthorized("addAsset"); $status = Asset::add($_POST);
        break;

	case "editAsset":
		isAuthorized("editAsset"); $status = Asset::edit($_POST);
        break;

	case "deleteAsset":
		isAuthorized("deleteAsset"); $status = Asset::delete($_POST['id']);
        break;

	case "generateQrCode":
		$status = Asset::generateQrCode($_POST['id']);
		break;

	case "updateBorrowStatus":
		isAuthorized("updateBorrowStatus");
		$status = Asset::updateBorrowStatus($_POST['id'], $_POST['value']);
		break;

	case "manualBorrowRecord":
		isAuthorized("addBorrowRecordManual");
		$status = Asset::manualBorrowRecord($_POST);
		break;

	case "manualReturnBorrowRecord":
		isAuthorized("addBorrowRecordManual");
		$status = Asset::manualReturnBorrowRecord($_POST);
		break;

    //non asset
	case "addNonAsset":
		isAuthorized("addNonAsset"); $status = NonAsset::add($_POST);
		break;

	case "addStock":
		isAuthorized("addStock"); $status = NonAsset::addStock($_POST);
		break;

	case "addStockOut":
		isAuthorized("addStockOut"); $status = NonAsset::addStockOut($_POST);
		break;

	case "editNonAsset":
		isAuthorized("editNonAsset"); $status = NonAsset::editNonAsset($_POST);
		break;

	case "deleteNonAssetCirculation":
		isAuthorized("deleteNonAssetCirculation"); $status = NonAsset::deleteNonAssetCirculation($_POST['id']);
		break;

	case "deleteNonAsset":
		isAuthorized("deleteNonAsset"); $status = NonAsset::deleteNonAsset($_POST['id']);
		break;


	// projects
	case "addProject":
		isAuthorized("addProject"); $status = Project::add($_POST);
        break;

	case "editProject":
		isAuthorized("editProject"); $status = Project::edit($_POST);
        break;

	case "deleteProject":
		isAuthorized("deleteProject"); $status = Project::delete($_POST['id']);
        break;

	case "addProjectAdmin":
		isAuthorized("adminsProject"); $status = Project::assignStaff($_POST);
		break;

	case "deleteProjectAdmin":
		isAuthorized("adminsProject"); $status = Project::unassignStaff($_POST['id']);
		break;

	case "saveProjectNotes":
		isAuthorized("manageProjectNotes"); $status = Project::saveNotes($_POST);
		break;


	// files
	case "uploadFile":
		isAuthorized("uploadFile"); $status = File::upload($_POST,$_FILES);
		break;

	case "deleteFile":
		isAuthorized("deleteFile"); $status = File::delete($_POST['id']);
		break;


	// issues
	case "addIssue":
		isAuthorized("addIssue"); $status = Issue::add($_POST);
		break;

	case "editIssue":
		isAuthorized("editIssue"); $status = Issue::edit($_POST);
        break;

	case "deleteIssue":
		isAuthorized("deleteIssue"); $status = Issue::delete($_POST['id']);
        break;


	// comments
	case "addComment":
		isAuthorized("addComment"); $status = Comment::add($_POST);
        break;

	case "editComment":
		isAuthorized("editComment"); $status = Comment::edit($_POST);
		break;

	case "deleteComment":
		isAuthorized("deleteComment"); $status = Comment::delete($_POST['id']);
		break;


	// tickets
	case "addTicket":
		isAuthorized("addTicket"); $status = Ticket::add($_POST);
		break;

	case "editTicket":
		isAuthorized("editTicket"); $status = Ticket::edit($_POST);
		break;

	case "deleteTicket":
		isAuthorized("deleteTicket"); $status = Ticket::delete($_POST['id']);
		break;

	case "updateTicketNotes":
		isAuthorized("manageTicketNotes"); $status = Ticket::updateNotes($_POST);
		break;

	case "addTicketReply":
		isAuthorized("manageTicket"); $status = Ticket::addReply($_POST);
		break;


	// credentials
	case "addCredential":
		isAuthorized("addCredential"); $status = Credential::add($_POST);
        break;

	case "editCredential":
		isAuthorized("editCredential"); $status = Credential::edit($_POST);
        break;

	case "deleteCredential":
		isAuthorized("deleteCredential"); $status = Credential::delete($_POST['id']);
        break;


	// licenses
	case "addLicense":
		isAuthorized("addLicense"); $status = License::add($_POST);
		break;

	case "editLicense":
		isAuthorized("editLicense"); $status = License::edit($_POST);
		break;

	case "deleteLicense":
		isAuthorized("deleteLicense"); $status = License::delete($_POST['id']);
		break;

	case "addLicenseAssignment":
		isAuthorized("assetLicense"); $status = License::assignAsset($_POST);
		break;

	case "deleteLicenseAssignment":
		isAuthorized("assetLicense"); $status = License::unassignAsset($_POST['id']);
		break;


	// clients
	case "addClient":
		isAuthorized("addClient"); $status = Client::add($_POST);
		break;

	case "editClient":
		isAuthorized("editClient"); $status = Client::edit($_POST);
		break;

	case "deleteClient":
		isAuthorized("deleteClient"); $status = Client::delete($_POST['id']);
        break;

	case "addAdminAssignment":
		isAuthorized("adminsClient"); $status = Client::assignStaff($_POST);
        break;

	case "deleteAdminAssignment":
		isAuthorized("adminsClient"); $status = Client::unassignStaff($_POST['id']);
        break;


	// monitoring
	case "addHost":
		isAuthorized("addHost"); $status = Monitoring::addHost($_POST);
		break;

	case "editHost":
		isAuthorized("editHost"); $status = Monitoring::editHost($_POST);
		break;

	case "deleteHost":
		isAuthorized("deleteHost"); $status = Monitoring::deleteHost($_POST['id']);
		break;

	case "addCheck":
		isAuthorized("manageHost"); $status = Monitoring::addCheck($_POST);
		break;

	case "editCheck":
		isAuthorized("manageHost"); $status = Monitoring::editCheck($_POST);
		break;

	case "deleteCheck":
		isAuthorized("manageHost"); $status = Monitoring::deleteCheck($_POST['id']);
		break;

	case "addHostPeople":
		isAuthorized("manageHost"); $status = Monitoring::addHostPeople($_POST);
		break;

	case "deleteHostPeople":
		isAuthorized("manageHost"); $status = Monitoring::deleteHostPeople($_POST['id']);
		break;


	// users
	case "addUser":
		isAuthorized("addUser"); $status = User::add($_POST);
		break;

	case "editUser":
		isAuthorized("editUser"); $status = User::edit($_POST);
		break;

	case "deleteUser":
		isAuthorized("deleteUser"); $status = User::delete($_POST['id']);
		break;


	// staff
	case "addAdmin":
		isAuthorized("addStaff"); $status = Staff::add($_POST);
		break;

	case "editAdmin":
		isAuthorized("editStaff"); $status = Staff::edit($_POST);
		break;

	case "deleteAdmin":
		isAuthorized("deleteStaff"); $status = Staff::delete($_POST['id']);
        break;


	// asset categories
	case "addAssetCategory":
		isAuthorized("manageData"); $status = Attribute::addAssetCategory($_POST);
        break;

	case "editAssetCategory":
		isAuthorized("manageData"); $status = Attribute::editAssetCategory($_POST);
        break;

	case "deleteAssetCategory":
		isAuthorized("manageData"); $status = Attribute::deleteAssetCategory($_POST['id']);
		break;


	// license categories
	case "addLicenseCategory":
		isAuthorized("manageData"); $status = Attribute::addLicenseCategory($_POST);
		break;

	case "editLicenseCategory":
		isAuthorized("manageData"); $status = Attribute::editLicenseCategory($_POST);
		break;

	case "deleteLicenseCategory":
		isAuthorized("manageData"); $status = Attribute::deleteLicenseCategory($_POST['id']);
		break;


	// status labels
	case "addLabel":
		isAuthorized("manageData"); $status = Attribute::addLabel($_POST);
		break;

	case "editLabel":
		isAuthorized("manageData"); $status = Attribute::editLabel($_POST);
		break;

	case "deleteLabel":
		isAuthorized("manageData"); $status = Attribute::deleteLabel($_POST['id']);
		break;


	// manufacturers
	case "addManufacturer":
		isAuthorized("manageData"); $status = Attribute::addManufacturer($_POST);
        break;

	case "editManufacturer":
		isAuthorized("manageData"); $status = Attribute::editManufacturer($_POST);
        break;

	case "deleteManufacturer":
		isAuthorized("manageData"); $status = Attribute::deleteManufacturer($_POST['id']);
        break;

	// locations
	case "addLocation":
		isAuthorized("manageData"); $status = Attribute::addLocation($_POST);
        break;

	case "editLocation":
		isAuthorized("manageData"); $status = Attribute::editLocation($_POST);
        break;

	case "deleteLocation":
		isAuthorized("manageData"); $status = Attribute::deleteLocation($_POST['id']);
        break;

	// asset models
	case "addModel":
		isAuthorized("manageData"); $status = Attribute::addModel($_POST);
		break;

	case "editModel":
		isAuthorized("manageData"); $status = Attribute::editModel($_POST);
		break;

	case "deleteModel":
		isAuthorized("manageData"); $status = Attribute::deleteModel($_POST['id']);
		break;


	// suppliers
	case "addSupplier":
		isAuthorized("manageData"); $status = Attribute::addSupplier($_POST);
		break;

	case "editSupplier":
		isAuthorized("manageData"); $status = Attribute::editSupplier($_POST);
		break;

	case "deleteSupplier":
		isAuthorized("manageData"); $status = Attribute::deleteSupplier($_POST['id']);
		break;


	// contacts
	case "addContact":
		isAuthorized("addContact"); $status = Contact::add($_POST);
		break;

	case "editContact":
		isAuthorized("editContact"); $status = Contact::edit($_POST);
		break;

	case "deleteContact":
		isAuthorized("deleteContact"); $status = Contact::delete($_POST['id']);
		break;


	// roles
	case "addRole":
		isAuthorized("addRole"); $status = Role::add($_POST);
		break;

	case "editRole":
		isAuthorized("editRole"); $status = Role::edit($_POST);
		break;

	case "deleteRole":
		isAuthorized("deleteRole"); $status = Role::delete($_POST['id']);
        break;


	// escalation rules
	case "addEscalationRule":
		$status = Ticket::addRule($_POST);
        break;

	case "editEscalationRule":
		$status = Ticket::editRule($_POST);
        break;

	case "deleteEscalationRule":
		$status = Ticket::deleteRule($_POST['id']);
		break;


	// predefined replies
	case "addPReply":
		isAuthorized("addPReply");
		$status = PReply::add($_POST);
		break;

	case "editPReply":
		isAuthorized("editPReply");
		$status = PReply::edit($_POST);
		break;

	case "deletePReply":
		isAuthorized("deletePReply");
		$status = PReply::delete($_POST['id']);
		break;

	// knowledge base
	case "addKB":
		isAuthorized("addKB");
		$status = Kb::add($_POST);
		break;

	case "editKB":
		isAuthorized("editKB");
		$status = Kb::edit($_POST);
		break;

	case "deleteKB":
		isAuthorized("deleteKB");
		$status = Kb::delete($_POST['id']);
		break;


	case "addKBCategory":
		isAuthorized("addKB");
		$status = Kb::addCategory($_POST);
		break;

	case "editKBCategory":
		isAuthorized("editKB");
		$status = Kb::editCategory($_POST);
		break;

	case "deleteKBCategory":
		isAuthorized("deleteKB");
		$status = Kb::deleteCategory($_POST['id']);
		break;


	// languages
	case "addLanguage":
		isAuthorized("manageSettings"); $status = Settings::addLanguage($_POST);
		break;

	case "deleteLanguage":
		isAuthorized("manageSettings"); $status = Settings::deleteLanguage($_POST['id']);
		break;

	// support departments
	case "addDepartment":
		isAuthorized("manageSettings"); $status = Settings::addDepartment($_POST);
		break;

	case "editDepartment":
		isAuthorized("manageSettings"); $status = Settings::editDepartment($_POST);
		break;

	case "deleteDepartment":
		isAuthorized("manageSettings"); $status = Settings::deleteDepartment($_POST['id']);
		break;


	// profile
	case "editProfile":
		$status = Profile::edit($_POST,$_FILES);
		break;


	//settings
	case "generalSettings":
		isAuthorized("manageSettings");
		Settings::update("app_name", $_POST['app_name']);
		Settings::update("app_url", $_POST['app_url']);
		Settings::update("company_name", $_POST['company_name']);
		Settings::update("company_details", $_POST['company_details']);
		Settings::update("log_retention", $_POST['log_retention']);
		Settings::update("table_records", $_POST['table_records']);
		Settings::update("license_tag_prefix", $_POST['license_tag_prefix']);
		Settings::update("asset_tag_prefix", $_POST['asset_tag_prefix']);
		Settings::update("password_generator_length", $_POST['password_generator_length']);
		$status = 40;
		break;

	case "localisationSettings":
		isAuthorized("manageSettings");
		Settings::update("week_start", $_POST['week_start']);
		Settings::update("default_lang", $_POST['default_lang']);
		Settings::update("timezone", $_POST['timezone']);
		$status = 40;
		break;

	case "emailSettings":
		isAuthorized("manageSettings");
		Settings::update("email_from_address", $_POST['email_from_address']);
		Settings::update("email_from_name", $_POST['email_from_name']);
		Settings::update("email_smtp_host", $_POST['email_smtp_host']);
		Settings::update("email_smtp_port", $_POST['email_smtp_port']);
		Settings::update("email_smtp_username", $_POST['email_smtp_username']);
		Settings::update("email_smtp_password", $_POST['email_smtp_password']);
		Settings::update("email_smtp_security", $_POST['email_smtp_security']);
		if (isset($_POST['email_smtp_auth'])) $email_smtp_auth = "true"; else $email_smtp_auth = "false";
		Settings::update("email_smtp_auth", $email_smtp_auth);
		if (isset($_POST['email_smtp_enable'])) $email_smtp_enable = "true"; else $email_smtp_enable = "false";
		Settings::update("email_smtp_enable", $email_smtp_enable);
		Settings::update("email_smtp_domain", $_POST['email_smtp_domain']);
		$status = 40;
		break;

	case "smsSettings":
		isAuthorized("manageSettings");
		Settings::update("sms_provider", $_POST['sms_provider']);
		Settings::update("sms_user", $_POST['sms_user']);
		Settings::update("sms_password", $_POST['sms_password']);
		Settings::update("sms_api_id", $_POST['sms_api_id']);
		Settings::update("sms_from", $_POST['sms_from']);
		$status = 40;
        break;

	case "ticketsSettings":
		isAuthorized("manageSettings");
		Settings::update("tickets_server", $_POST['tickets_server']);
		Settings::update("tickets_username", $_POST['tickets_username']);
		Settings::update("tickets_password", $_POST['tickets_password']);
		Settings::update("tickets_encrypton", $_POST['tickets_encrypton']);
		Settings::update("tickets_defaultdepartment", $_POST['tickets_defaultdepartment']);
		if (isset($_POST['tickets_notification'])) $tickets_notification = "true"; else $tickets_notification = "false";
		Settings::update("tickets_notification", $tickets_notification);
		Settings::update("auto_close_tickets", $_POST['auto_close_tickets']);
		if (isset($_POST['auto_close_tickets_notify'])) $auto_close_tickets_notify = "true"; else $auto_close_tickets_notify = "false";
		Settings::update("auto_close_tickets_notify", $auto_close_tickets_notify);
		$status = 40;
        break;

	case "editNotificationTemplate":
		isAuthorized("manageSettings"); $status = Settings::editNotification($_POST);
        break;


}


reroute($_POST,$status);

?>
