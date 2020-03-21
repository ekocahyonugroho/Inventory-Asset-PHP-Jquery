-- DATABASE UPGRADE FROM 1.4 to 1.5



ALTER TABLE `people` ADD `avatar` MEDIUMBLOB NOT NULL ;

INSERT INTO `config` (`name`, `value`) VALUES ('timezone', 'UTC');
