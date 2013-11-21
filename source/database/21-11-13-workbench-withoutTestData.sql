SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `smartparentalcontrol` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`action` (
  `AId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `RId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `points` DOUBLE NULL DEFAULT NULL,
  `controllerId` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`AId`),
  UNIQUE INDEX `AId` (`AId` ASC),
  INDEX `controllerId` (`controllerId` ASC),
  INDEX `RId` (`RId` ASC),
  CONSTRAINT `action_ibfk_1`
    FOREIGN KEY (`controllerId`)
    REFERENCES `smartparentalcontrol`.`controller` (`CSerieNo`),
  CONSTRAINT `action_ibfk_2`
    FOREIGN KEY (`RId`)
    REFERENCES `smartparentalcontrol`.`rules` (`RId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`chores` (
  `CId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CSId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `description` VARCHAR(50) NULL DEFAULT NULL,
  `defaultPoints` DOUBLE UNSIGNED NULL DEFAULT '0',
  PRIMARY KEY (`CId`),
  UNIQUE INDEX `CId` (`CId` ASC),
  INDEX `CSId` (`CSId` ASC),
  CONSTRAINT `chores_ibfk_1`
    FOREIGN KEY (`CSId`)
    REFERENCES `smartparentalcontrol`.`control_system` (`CSId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`cond_timeperiod` (
  `condTimepId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `condId` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `timeFrom` DATETIME NOT NULL,
  `timeTo` DATETIME NOT NULL,
  `weekdays` SET('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NULL DEFAULT NULL,
  `weekly` TINYINT(1) NULL DEFAULT '0',
  `ndWeekly` TINYINT(1) NULL DEFAULT '0',
  `rdWeekly` TINYINT(1) NULL DEFAULT '0',
  `firstInMonth` TINYINT(1) NULL DEFAULT '0',
  `lastInMonth` TINYINT(1) NULL DEFAULT '0',
  `weekNumber` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`condTimepId`),
  UNIQUE INDEX `condTimepId` (`condTimepId` ASC),
  INDEX `condId` (`condId` ASC),
  CONSTRAINT `cond_timeperiod_ibfk_1`
    FOREIGN KEY (`condId`)
    REFERENCES `smartparentalcontrol`.`rcondition` (`condId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`cond_timestamp` (
  `condTimesId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `condId` BIGINT(20) UNSIGNED NOT NULL,
  `onTimestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`condTimesId`),
  UNIQUE INDEX `condTimesId` (`condTimesId` ASC),
  INDEX `condId` (`condId` ASC),
  CONSTRAINT `cond_timestamp_ibfk_1`
    FOREIGN KEY (`condId`)
    REFERENCES `smartparentalcontrol`.`rcondition` (`condId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`control_system` (
  `CSId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NOT NULL,
  `street` VARCHAR(50) NULL DEFAULT NULL,
  `postcode` VARCHAR(30) NULL DEFAULT NULL,
  `phoneNo` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`CSId`),
  UNIQUE INDEX `CSId` (`CSId` ASC),
  UNIQUE INDEX `username` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`controller` (
  `CSerieNo` BIGINT(20) UNSIGNED NOT NULL,
  `CSId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `location` VARCHAR(30) NULL DEFAULT NULL,
  `status` ENUM('!','GREEN','RED') NULL DEFAULT '!',
  PRIMARY KEY (`CSerieNo`),
  UNIQUE INDEX `CSerieNo` (`CSerieNo` ASC),
  INDEX `CSId` (`CSId` ASC),
  CONSTRAINT `controller_ibfk_1`
    FOREIGN KEY (`CSId`)
    REFERENCES `smartparentalcontrol`.`control_system` (`CSId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`controller_used_by_tag` (
  `TSerieNo` BIGINT(20) UNSIGNED NOT NULL,
  `CSerieNo` BIGINT(20) UNSIGNED NOT NULL,
  `starttime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endtime` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`TSerieNo`, `CSerieNo`, `starttime`),
  INDEX `CSerieNo` (`CSerieNo` ASC),
  CONSTRAINT `controller_used_by_tag_ibfk_1`
    FOREIGN KEY (`TSerieNo`)
    REFERENCES `smartparentalcontrol`.`tag` (`TSerieNo`),
  CONSTRAINT `controller_used_by_tag_ibfk_2`
    FOREIGN KEY (`CSerieNo`)
    REFERENCES `smartparentalcontrol`.`controller` (`CSerieNo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`profile` (
  `PId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CSId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `points` DOUBLE NULL DEFAULT '0',
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(15) NULL DEFAULT NULL,
  `role` ENUM('user', 'manager') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`PId`),
  UNIQUE INDEX `PId` (`PId` ASC),
  INDEX `CSId` (`CSId` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  CONSTRAINT `profile_ibfk_1`
    FOREIGN KEY (`CSId`)
    REFERENCES `smartparentalcontrol`.`control_system` (`CSId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`profile_did_chores` (
  `PId` BIGINT(20) UNSIGNED NOT NULL,
  `CId` BIGINT(20) UNSIGNED NOT NULL,
  `actualPoints` DOUBLE NULL DEFAULT NULL,
  `timeOfCreation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PId`, `CId`, `timeOfCreation`),
  INDEX `CId` (`CId` ASC),
  CONSTRAINT `profile_did_chores_ibfk_2`
    FOREIGN KEY (`CId`)
    REFERENCES `smartparentalcontrol`.`chores` (`CId`),
  CONSTRAINT `profile_did_chores_ibfk_1`
    FOREIGN KEY (`PId`)
    REFERENCES `smartparentalcontrol`.`profile` (`PId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`profile_has_rules` (
  `PId` BIGINT(20) UNSIGNED NOT NULL,
  `RId` BIGINT(20) UNSIGNED NOT NULL,
  `validFromTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PId`, `RId`),
  INDEX `RId` (`RId` ASC),
  CONSTRAINT `profile_has_rules_ibfk_2`
    FOREIGN KEY (`RId`)
    REFERENCES `smartparentalcontrol`.`rules` (`RId`),
  CONSTRAINT `profile_has_rules_ibfk_1`
    FOREIGN KEY (`PId`)
    REFERENCES `smartparentalcontrol`.`profile` (`PId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`rcondition` (
  `condId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `RId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `controllerId` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`condId`),
  UNIQUE INDEX `condId` (`condId` ASC),
  INDEX `controllerId` (`controllerId` ASC),
  INDEX `RId` (`RId` ASC),
  CONSTRAINT `rcondition_ibfk_1`
    FOREIGN KEY (`controllerId`)
    REFERENCES `smartparentalcontrol`.`controller` (`CSerieNo`),
  CONSTRAINT `rcondition_ibfk_2`
    FOREIGN KEY (`RId`)
    REFERENCES `smartparentalcontrol`.`rules` (`RId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`rules` (
  `RId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CSId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `isPermission` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`RId`),
  UNIQUE INDEX `RId` (`RId` ASC),
  INDEX `CSId` (`CSId` ASC),
  CONSTRAINT `rules_ibfk_1`
    FOREIGN KEY (`CSId`)
    REFERENCES `smartparentalcontrol`.`control_system` (`CSId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `smartparentalcontrol`.`tag` (
  `TSerieNo` BIGINT(20) UNSIGNED NOT NULL,
  `CSId` BIGINT(20) UNSIGNED NOT NULL,
  `profileId` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(30) NULL DEFAULT NULL,
  `active` TINYINT(1) NULL DEFAULT '1',
  PRIMARY KEY (`TSerieNo`),
  UNIQUE INDEX `TSerieNo` (`TSerieNo` ASC),
  INDEX `CSId` (`CSId` ASC),
  INDEX `profileId` (`profileId` ASC),
  CONSTRAINT `tag_ibfk_1`
    FOREIGN KEY (`CSId`)
    REFERENCES `smartparentalcontrol`.`control_system` (`CSId`),
  CONSTRAINT `tag_ibfk_2`
    FOREIGN KEY (`profileId`)
    REFERENCES `smartparentalcontrol`.`profile` (`PId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
