-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.13-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-05-14 11:52:48
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for Manager
CREATE DATABASE IF NOT EXISTS `Manager` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `Manager`;


-- Dumping structure for table Manager.manialib_cache
CREATE TABLE IF NOT EXISTS `manialib_cache` (
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `ttl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`name`),
  KEY `ttl` (`ttl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table Manager.Servers
CREATE TABLE IF NOT EXISTS `Servers` (
  `hostname` varchar(25) NOT NULL,
  `port` int(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(75) NOT NULL,
  `lastLiveDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hostname`,`port`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
