-- DATABASE UPGRADE FROM 1.2 to 1.3

ALTER TABLE `tickets` ADD `ccs` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `clients` ADD `asset_tag_prefix` VARCHAR( 255 ) NOT NULL ,
ADD `license_tag_prefix` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `suppliers` ADD `address` TEXT NOT NULL ,
ADD `contactname` VARCHAR( 255 ) NOT NULL ,
ADD `phone` VARCHAR( 255 ) NOT NULL ,
ADD `email` VARCHAR( 255 ) NOT NULL ,
ADD `web` VARCHAR( 255 ) NOT NULL ,
ADD `notes` TEXT NOT NULL ;


INSERT INTO `notificationtemplates` (`id`, `name`, `subject`, `message`, `info`, `sms`) VALUES
(9, 'Ticket Escalation (Admin)', 'Escalation Rule Processed #{ticketid}', '<p>Escalation rule processed for ticket #{ticketid}.<br>Subject: {subject}<br></p><p><br>{message}<br></p><p><br>Best regards,<br>{company}<br></p><p><br></p><p></p>', '', ''),
(10, 'Ticket Auto Close (User)', 'Support Ticket #{ticketid} Auto Closed', '<p>This is a notification to let you know that we are changing the status of your ticket #{ticketid}  to Closed as we have not received a response from you lately.<br></p><p><br>Ticket Subject: {subject}<br></p><p><br>Best regards,<br>{company}<br></p><p><br></p><p></p>', '', '');


INSERT INTO `config` (`name`, `value`) VALUES
('auto_close_tickets', '0'),
('auto_close_tickets_notify', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `tickets_rules`
--

CREATE TABLE IF NOT EXISTS `tickets_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticketid` int(11) NOT NULL,
  `executed` int(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cond_status` varchar(255) NOT NULL,
  `cond_priority` varchar(255) NOT NULL,
  `cond_timeelapsed` varchar(20) NOT NULL,
  `cond_datetime` datetime NOT NULL,
  `act_status` varchar(255) NOT NULL,
  `act_priority` varchar(255) NOT NULL,
  `act_assignto` int(11) NOT NULL,
  `act_notifyadmins` int(1) NOT NULL,
  `act_addreply` int(1) NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
