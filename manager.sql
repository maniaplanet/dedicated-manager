-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.20-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-08-06 13:11:27
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for Manager
CREATE DATABASE IF NOT EXISTS `Manager` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `Manager`;


-- Dumping structure for table Manager.Managers
CREATE TABLE IF NOT EXISTS `Managers` (
  `login` varchar(25) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `login_rpcHost_rpcPort` (`login`,`rpcHost`,`rpcPort`),
  KEY `managerServer` (`rpcHost`,`rpcPort`),
  CONSTRAINT `managerServer` FOREIGN KEY (`rpcHost`, `rpcPort`) REFERENCES `Servers` (`rpcHost`, `rpcPort`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table Manager.Maps
CREATE TABLE IF NOT EXISTS `Maps` (
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `uid` char(27) NOT NULL,
  `name` varchar(75) NOT NULL,
  `environment` varchar(15) NOT NULL,
  `mood` varchar(15) NOT NULL,
  `type` varchar(50) NOT NULL,
  `displayCost` int(10) unsigned NOT NULL,
  `nbLaps` int(10) unsigned NOT NULL DEFAULT '0',
  `authorLogin` varchar(25) NOT NULL,
  `authorNick` varchar(75) DEFAULT NULL,
  `authorZone` varchar(255) DEFAULT NULL,
  `authorTime` int(11) DEFAULT NULL,
  `goldTime` int(11) DEFAULT NULL,
  `silverTime` int(11) DEFAULT NULL,
  `bronzeTime` int(11) DEFAULT NULL,
  `authorScore` int(11) DEFAULT NULL,
  `size` int(10) unsigned NOT NULL,
  `mTime` datetime NOT NULL,
  PRIMARY KEY (`path`,`filename`),
  KEY `mTime` (`mTime`),
  KEY `size` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table Manager.Servers
CREATE TABLE IF NOT EXISTS `Servers` (
  `name` varchar(75) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` smallint(5) unsigned NOT NULL,
  `rpcPassword` varchar(50) NOT NULL,
  PRIMARY KEY (`rpcHost`,`rpcPort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
