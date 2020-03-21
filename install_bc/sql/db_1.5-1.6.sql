-- DATABASE UPGRADE FROM 1.5 to 1.6


ALTER TABLE `assets` ADD `manufacturerid` INT( 11 ) NOT NULL AFTER `userid` ;


ALTER TABLE `assets` ADD `locationid` INT( 11 ) NOT NULL ,
ADD `purchase_order` VARCHAR( 255 ) NOT NULL ,
ADD `value` VARCHAR( 255 ) NOT NULL ,
ADD `condition` VARCHAR( 255 ) NOT NULL ,
ADD `removal_date` VARCHAR( 100 ) NOT NULL ;



--
-- Table structure for table `tickets_pr`
--

CREATE TABLE IF NOT EXISTS `tickets_pr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tickets_pr`
--

INSERT INTO `tickets_pr` (`id`, `name`, `content`) VALUES
(1, 'Demo Predefined Reply', '<div><p>Predefined reply body.<br></p></div>');

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `kb_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryid` int(11) NOT NULL,
  `clients` text NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `kb_articles`
--

INSERT INTO `kb_articles` (`id`, `categoryid`, `clients`, `name`, `content`) VALUES
(1, 1, 'a:1:{i:0;s:1:"0";}', 'Test Article', '<p>Article body.<br></p>');

-- --------------------------------------------------------

--
-- Table structure for table `kb_categories`
--

CREATE TABLE IF NOT EXISTS `kb_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients` text NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `kb_categories`
--

INSERT INTO `kb_categories` (`id`, `clients`, `name`) VALUES
(1, 'a:1:{i:0;s:1:"0";}', 'Test Category');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` INT( 11 ) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `locations` (`id`, `clientid`, `name`) VALUES
(1, 0, 'Test Location');
