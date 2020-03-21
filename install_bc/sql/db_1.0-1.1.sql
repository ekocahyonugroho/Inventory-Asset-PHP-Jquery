-- DATABASE UPGRADE FROM 1.0 to 1.1


--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `description` text NOT NULL,
  `startdate` varchar(20) NOT NULL,
  `deadline` varchar(20) NOT NULL,
  `progress` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `clientid`, `tag`, `name`, `notes`, `description`, `startdate`, `deadline`, `progress`) VALUES
(1, 0, 'P-1', 'Test Project 1', 'Some notes here.<br>', 'Project description.<br>', '2016-02-01', '2017-01-30', 65);

-- --------------------------------------------------------

--
-- Table structure for table `projects_admins`
--

CREATE TABLE IF NOT EXISTS `projects_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



INSERT INTO `config` (`name`, `value`) VALUES ('project_tag_prefix', 'P-');
ALTER TABLE `tasks` RENAME `issues`;
ALTER TABLE `issues` ADD `projectid` INT(11) NOT NULL AFTER `assetid`;
ALTER TABLE `issues` ADD `issuetype` VARCHAR(15) NOT NULL AFTER `adminid`;
ALTER TABLE `issues` ADD `dateadded` DATETIME NOT NULL AFTER `timespent`;

UPDATE `issues` SET `issuetype` = 'Task';

UPDATE `issues` SET `status` = 'To Do' WHERE `status` = 'New';
UPDATE `issues` SET `status` = 'To Do' WHERE `status` = 'Pending';
UPDATE `issues` SET `status` = 'To Do' WHERE `status` = 'Postponed';
UPDATE `issues` SET `status` = 'Done' WHERE `status` = 'Completed';
