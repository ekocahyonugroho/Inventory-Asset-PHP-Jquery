-- DATABASE UPGRADE FROM 1.6 to 1.7


ALTER TABLE `credentials` CHANGE `password` `password` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `licenses` CHANGE `serial` `serial` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `config` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `tickets` ADD `departmentid` INT(11) NOT NULL AFTER `ticket`;



INSERT INTO `config` (`name`, `value`) VALUES ('email_smtp_domain', '');
INSERT INTO `config` (`name`, `value`) VALUES ('app_url', '');
INSERT INTO `config` (`name`, `value`) VALUES ('tickets_defaultdepartment', '1');



--
-- Table structure for table `tickets_departments`
--

CREATE TABLE IF NOT EXISTS `tickets_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tickets_departments`
--

INSERT INTO `tickets_departments` (`id`, `name`) VALUES
(1, 'Default Department');
