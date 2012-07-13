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
  `login` varchar(50) NOT NULL,
  `name` varchar(75) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` int(11) NOT NULL,
  `rpcPassword` varchar(50) NOT NULL,
  `joinIp` varchar(15) NOT NULL,
  `joinPort` int(11) NOT NULL,
  `joinPassword` varchar(50) NOT NULL,
  `specPassword` varchar(50) NOT NULL,
  `isRelay` tinyint(4) NOT NULL,
  `lastLiveDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rpcHost`,`rpcPort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
