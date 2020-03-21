-- DATABASE UPGRADE FROM 1.1 to 1.2

ALTER TABLE `issues` CHANGE `duedate` `duedate` VARCHAR( 20 ) NOT NULL ;

ALTER TABLE `people` ADD `autorefresh` INT( 11 ) NOT NULL ;
ALTER TABLE `people` ADD `lang` VARCHAR( 2 ) NOT NULL ;
ALTER TABLE `people` ADD `ticketsnotification` INT( 1 ) NOT NULL ;

ALTER TABLE `files` ADD `ticketreplyid` INT( 11 ) NOT NULL AFTER `assetid` ;

ALTER TABLE `tickets_replies` ADD `peopleid` INT( 11 ) NOT NULL AFTER `ticketid` ;

ALTER TABLE `systemlog` DROP `clientid`;

INSERT INTO `config` (`name`, `value`) VALUES ('password_generator_length', '8');
INSERT INTO `config` (`name`, `value`) VALUES ('default_lang', 'en');



-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peopleid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`) VALUES
(1, 'en', 'English (System)');


-- --------------------------------------------------------

--
-- Dumping data for table `notificationtemplates`
--

INSERT INTO `notificationtemplates` (`id`, `name`, `subject`, `message`, `info`, `sms`) VALUES
(7, 'New Ticket (Admin)', 'New Support Ticket #{ticketid}', '<p>A new support ticket has been opened.</p>\r\n<p>Ticket ID:<b> #{ticketid}</b><br>Subject: {subject}</p>\r\n<p><br>{message}</p><br>\r\n<p>Best regards,<br>{company}</p>', '', ''),
(8, 'New Ticket Reply (Admin)', 'New Reply to Ticket #{ticketid}', '<p>A new reply has been added to ticket #{ticketid}.<br>Subject: {subject}<br></p><p><br>{message}<br></p><p><br>Best regards,<br>{company}<br></p><p><br></p><p></p>', '', '');

-- --------------------------------------------------------
