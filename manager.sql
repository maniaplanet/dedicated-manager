-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.13-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-07-19 10:12:29
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for Manager
CREATE DATABASE IF NOT EXISTS `manager` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `Manager`;


-- Dumping structure for table Manager.Managers
CREATE TABLE IF NOT EXISTS `Managers` (
  `login` varchar(25) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` int(11) NOT NULL,
  UNIQUE KEY `login_rpcHost_rpcPort` (`login`,`rpcHost`,`rpcPort`),
  KEY `managerServer` (`rpcHost`,`rpcPort`),
  CONSTRAINT `managerServer` FOREIGN KEY (`rpcHost`, `rpcPort`) REFERENCES `Servers` (`rpcHost`, `rpcPort`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


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
  `name` varchar(75) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` int(11) NOT NULL,
  `rpcPassword` varchar(50) NOT NULL,
  `titleId` varchar(51) NOT NULL,
  PRIMARY KEY (`rpcHost`,`rpcPort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
