-- DATABASE UPGRADE FROM 1.3 to 1.4


--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `perms` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `type`, `name`, `perms`) VALUES
(1, 'admin', 'Super Administrator', 'a:81:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:9:"addTicket";i:26;s:10:"editTicket";i:27;s:12:"deleteTicket";i:28;s:12:"manageTicket";i:29;s:17:"manageTicketRules";i:30;s:17:"manageTicketNotes";i:31;s:11:"viewTickets";i:32;s:8:"addIssue";i:33;s:9:"editIssue";i:34;s:11:"deleteIssue";i:35;s:11:"manageIssue";i:36;s:10:"viewIssues";i:37;s:10:"addComment";i:38;s:11:"editComment";i:39;s:13:"deleteComment";i:40;s:13:"assignComment";i:41;s:12:"viewComments";i:42;s:13:"addCredential";i:43;s:14:"editCredential";i:44;s:16:"deleteCredential";i:45;s:14:"viewCredential";i:46;s:15:"viewCredentials";i:47;s:10:"uploadFile";i:48;s:12:"downloadFile";i:49;s:10:"deleteFile";i:50;s:9:"viewFiles";i:51;s:7:"addHost";i:52;s:8:"editHost";i:53;s:10:"deleteHost";i:54;s:10:"manageHost";i:55;s:14:"viewMonitoring";i:56;s:7:"addUser";i:57;s:8:"editUser";i:58;s:10:"deleteUser";i:59;s:9:"viewUsers";i:60;s:8:"addStaff";i:61;s:9:"editStaff";i:62;s:11:"deleteStaff";i:63;s:9:"viewStaff";i:64;s:7:"addRole";i:65;s:8:"editRole";i:66;s:10:"deleteRole";i:67;s:9:"viewRoles";i:68;s:10:"addContact";i:69;s:11:"editContact";i:70;s:13:"deleteContact";i:71;s:12:"viewContacts";i:72;s:10:"manageData";i:73;s:14:"manageSettings";i:74;s:8:"viewLogs";i:75;s:10:"viewSystem";i:76;s:10:"viewPeople";i:77;s:11:"viewReports";i:78;s:11:"autorefresh";i:79;s:6:"search";i:80;s:4:"Null";}'),
(2, 'user', 'Standard User', 'a:18:{i:0;s:11:"manageAsset";i:1;s:10:"viewAssets";i:2;s:13:"manageLicense";i:3;s:12:"viewLicenses";i:4;s:12:"viewProjects";i:5;s:9:"addTicket";i:6;s:10:"editTicket";i:7;s:12:"manageTicket";i:8;s:11:"viewTickets";i:9;s:8:"addIssue";i:10;s:10:"viewIssues";i:11;s:10:"addComment";i:12;s:12:"viewComments";i:13;s:14:"viewMonitoring";i:14;s:9:"viewUsers";i:15;s:10:"viewPeople";i:16;s:11:"viewReports";i:17;s:4:"Null";}'),
(3, 'admin', 'Administrator', 'a:66:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"manageClient";i:3;s:12:"adminsClient";i:4;s:11:"viewClients";i:5;s:8:"addAsset";i:6;s:9:"editAsset";i:7;s:11:"manageAsset";i:8;s:12:"licenseAsset";i:9;s:10:"viewAssets";i:10;s:10:"addLicense";i:11;s:11:"editLicense";i:12;s:13:"manageLicense";i:13;s:12:"assetLicense";i:14;s:12:"viewLicenses";i:15;s:10:"addProject";i:16;s:11:"editProject";i:17;s:13:"manageProject";i:18;s:18:"manageProjectNotes";i:19;s:13:"adminsProject";i:20;s:12:"viewProjects";i:21;s:9:"addTicket";i:22;s:10:"editTicket";i:23;s:12:"manageTicket";i:24;s:17:"manageTicketRules";i:25;s:17:"manageTicketNotes";i:26;s:11:"viewTickets";i:27;s:8:"addIssue";i:28;s:9:"editIssue";i:29;s:11:"manageIssue";i:30;s:10:"viewIssues";i:31;s:10:"addComment";i:32;s:11:"editComment";i:33;s:13:"assignComment";i:34;s:12:"viewComments";i:35;s:13:"addCredential";i:36;s:14:"editCredential";i:37;s:14:"viewCredential";i:38;s:15:"viewCredentials";i:39;s:10:"uploadFile";i:40;s:12:"downloadFile";i:41;s:9:"viewFiles";i:42;s:7:"addHost";i:43;s:8:"editHost";i:44;s:10:"manageHost";i:45;s:14:"viewMonitoring";i:46;s:7:"addUser";i:47;s:8:"editUser";i:48;s:9:"viewUsers";i:49;s:8:"addStaff";i:50;s:9:"editStaff";i:51;s:9:"viewStaff";i:52;s:7:"addRole";i:53;s:8:"editRole";i:54;s:9:"viewRoles";i:55;s:10:"addContact";i:56;s:11:"editContact";i:57;s:12:"viewContacts";i:58;s:10:"manageData";i:59;s:8:"viewLogs";i:60;s:10:"viewSystem";i:61;s:10:"viewPeople";i:62;s:11:"viewReports";i:63;s:11:"autorefresh";i:64;s:6:"search";i:65;s:4:"Null";}'),
(4, 'admin', 'Technician', 'a:44:{i:0;s:9:"addClient";i:1;s:12:"manageClient";i:2;s:11:"viewClients";i:3;s:8:"addAsset";i:4;s:11:"manageAsset";i:5;s:10:"viewAssets";i:6;s:10:"addLicense";i:7;s:13:"manageLicense";i:8;s:12:"viewLicenses";i:9;s:10:"addProject";i:10;s:13:"manageProject";i:11;s:18:"manageProjectNotes";i:12;s:12:"viewProjects";i:13;s:9:"addTicket";i:14;s:12:"manageTicket";i:15;s:17:"manageTicketRules";i:16;s:17:"manageTicketNotes";i:17;s:11:"viewTickets";i:18;s:8:"addIssue";i:19;s:11:"manageIssue";i:20;s:10:"viewIssues";i:21;s:10:"addComment";i:22;s:12:"viewComments";i:23;s:13:"addCredential";i:24;s:14:"viewCredential";i:25;s:15:"viewCredentials";i:26;s:10:"uploadFile";i:27;s:12:"downloadFile";i:28;s:9:"viewFiles";i:29;s:7:"addHost";i:30;s:10:"manageHost";i:31;s:14:"viewMonitoring";i:32;s:7:"addUser";i:33;s:9:"viewUsers";i:34;s:10:"addContact";i:35;s:11:"editContact";i:36;s:12:"viewContacts";i:37;s:10:"manageData";i:38;s:10:"viewSystem";i:39;s:10:"viewPeople";i:40;s:11:"viewReports";i:41;s:11:"autorefresh";i:42;s:6:"search";i:43;s:4:"Null";}');

-- --------------------------------------------------------



ALTER TABLE `people` ADD `roleid` INT( 11 ) NOT NULL AFTER `type` ;

INSERT INTO `statuscodes` (`id`, `code`, `type`, `message`) VALUES (57, 1, 'danger', 'Unauthorized Access');
