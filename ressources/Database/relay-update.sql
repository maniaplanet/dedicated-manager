ALTER TABLE `Servers` ADD COLUMN `login` VARCHAR(50) NOT NULL FIRST;

CREATE TABLE IF NOT EXISTS `Managers` (
	`hostname` VARCHAR(25) NOT NULL,
	`port` INT(11) NOT NULL,
	`login` VARCHAR(25) NOT NULL,
	PRIMARY KEY (`hostname`, `port`, `login`),
	CONSTRAINT `FK_Managers_Servers` FOREIGN KEY (`hostname`, `port`) REFERENCES `Servers` (`hostname`, `port`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
