-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 20, 2013 at 09:53 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `smartparentalcontrol`
--
CREATE DATABASE IF NOT EXISTS `smartparentalcontrol` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `smartparentalcontrol`;

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE IF NOT EXISTS `action` (
  `AId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `RId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `points` double DEFAULT NULL,
  `controllerId` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`AId`),
  UNIQUE KEY `AId` (`AId`),
  KEY `controllerId` (`controllerId`),
  KEY `RId` (`RId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chores`
--

CREATE TABLE IF NOT EXISTS `chores` (
  `CId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CSId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `defaultPoints` double unsigned DEFAULT '0',
  PRIMARY KEY (`CId`),
  UNIQUE KEY `CId` (`CId`),
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cond_timeperiod`
--

CREATE TABLE IF NOT EXISTS `cond_timeperiod` (
  `condTimepId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `condId` bigint(20) unsigned DEFAULT NULL,
  `timeFrom` datetime NOT NULL,
  `timeTo` datetime NOT NULL,
  `weekdays` set('monday','tuesday','wednesday','thursday','friday','saturday','sunday') DEFAULT NULL,
  `weekly` tinyint(1) DEFAULT '0',
  `ndWeekly` tinyint(1) DEFAULT '0',
  `rdWeekly` tinyint(1) DEFAULT '0',
  `firstInMonth` tinyint(1) DEFAULT '0',
  `lastInMonth` tinyint(1) DEFAULT '0',
  `weekNumber` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`condTimepId`),
  UNIQUE KEY `condTimepId` (`condTimepId`),
  KEY `condId` (`condId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cond_timestamp`
--

CREATE TABLE IF NOT EXISTS `cond_timestamp` (
  `condTimesId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `condId` bigint(20) unsigned NOT NULL,
  `onTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`condTimesId`),
  UNIQUE KEY `condTimesId` (`condTimesId`),
  KEY `condId` (`condId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `controller`
--

CREATE TABLE IF NOT EXISTS `controller` (
  `CSerieNo` bigint(20) unsigned NOT NULL,
  `CSId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `location` varchar(30) DEFAULT NULL,
  `status` enum('!','GREEN','RED') DEFAULT '!',
  PRIMARY KEY (`CSerieNo`),
  UNIQUE KEY `CSerieNo` (`CSerieNo`),
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `controller_used_by_tag`
--

CREATE TABLE IF NOT EXISTS `controller_used_by_tag` (
  `TSerieNo` bigint(20) unsigned NOT NULL,
  `CSerieNo` bigint(20) unsigned NOT NULL,
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`TSerieNo`,`CSerieNo`,`starttime`),
  KEY `CSerieNo` (`CSerieNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `control_system`
--

CREATE TABLE IF NOT EXISTS `control_system` (
  `CSId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `phoneNo` int(11) DEFAULT NULL,
  PRIMARY KEY (`CSId`),
  UNIQUE KEY `CSId` (`CSId`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `PId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CSId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `points` double DEFAULT '0',
  PRIMARY KEY (`PId`),
  UNIQUE KEY `PId` (`PId`),
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile_did_chores`
--

CREATE TABLE IF NOT EXISTS `profile_did_chores` (
  `PId` bigint(20) unsigned NOT NULL,
  `CId` bigint(20) unsigned NOT NULL,
  `actualPoints` double DEFAULT NULL,
  `timeOfCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PId`,`CId`,`timeOfCreation`),
  KEY `CId` (`CId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profile_has_rules`
--

CREATE TABLE IF NOT EXISTS `profile_has_rules` (
  `PId` bigint(20) unsigned NOT NULL,
  `RId` bigint(20) unsigned NOT NULL,
  `validFromTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PId`,`RId`),
  KEY `RId` (`RId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rcondition`
--

CREATE TABLE IF NOT EXISTS `rcondition` (
  `condId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `RId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `controllerId` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`condId`),
  UNIQUE KEY `condId` (`condId`),
  KEY `controllerId` (`controllerId`),
  KEY `RId` (`RId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `RId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CSId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `profileId` bigint(20) unsigned DEFAULT NULL,
  `isPermission` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`RId`),
  UNIQUE KEY `RId` (`RId`),
  KEY `CSId` (`CSId`),
  KEY `profileId` (`profileId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `TSerieNo` bigint(20) unsigned NOT NULL,
  `CSId` bigint(20) unsigned NOT NULL,
  `profileId` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`TSerieNo`),
  UNIQUE KEY `TSerieNo` (`TSerieNo`),
  KEY `CSId` (`CSId`),
  KEY `profileId` (`profileId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `action_ibfk_1` FOREIGN KEY (`controllerId`) REFERENCES `controller` (`CSerieNo`),
  ADD CONSTRAINT `action_ibfk_2` FOREIGN KEY (`RId`) REFERENCES `rules` (`RId`);

--
-- Constraints for table `chores`
--
ALTER TABLE `chores`
  ADD CONSTRAINT `chores_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`);

--
-- Constraints for table `cond_timeperiod`
--
ALTER TABLE `cond_timeperiod`
  ADD CONSTRAINT `cond_timeperiod_ibfk_1` FOREIGN KEY (`condId`) REFERENCES `rcondition` (`condId`);

--
-- Constraints for table `cond_timestamp`
--
ALTER TABLE `cond_timestamp`
  ADD CONSTRAINT `cond_timestamp_ibfk_1` FOREIGN KEY (`condId`) REFERENCES `rcondition` (`condId`);

--
-- Constraints for table `controller`
--
ALTER TABLE `controller`
  ADD CONSTRAINT `controller_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`);

--
-- Constraints for table `controller_used_by_tag`
--
ALTER TABLE `controller_used_by_tag`
  ADD CONSTRAINT `controller_used_by_tag_ibfk_1` FOREIGN KEY (`TSerieNo`) REFERENCES `tag` (`TSerieNo`),
  ADD CONSTRAINT `controller_used_by_tag_ibfk_2` FOREIGN KEY (`CSerieNo`) REFERENCES `controller` (`CSerieNo`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`);

--
-- Constraints for table `profile_did_chores`
--
ALTER TABLE `profile_did_chores`
  ADD CONSTRAINT `profile_did_chores_ibfk_1` FOREIGN KEY (`PId`) REFERENCES `profile` (`PId`),
  ADD CONSTRAINT `profile_did_chores_ibfk_2` FOREIGN KEY (`CId`) REFERENCES `chores` (`CId`);

--
-- Constraints for table `profile_has_rules`
--
ALTER TABLE `profile_has_rules`
  ADD CONSTRAINT `profile_has_rules_ibfk_1` FOREIGN KEY (`PId`) REFERENCES `profile` (`PId`),
  ADD CONSTRAINT `profile_has_rules_ibfk_2` FOREIGN KEY (`RId`) REFERENCES `rules` (`RId`);

--
-- Constraints for table `rcondition`
--
ALTER TABLE `rcondition`
  ADD CONSTRAINT `rcondition_ibfk_1` FOREIGN KEY (`controllerId`) REFERENCES `controller` (`CSerieNo`),
  ADD CONSTRAINT `rcondition_ibfk_2` FOREIGN KEY (`RId`) REFERENCES `rules` (`RId`);

--
-- Constraints for table `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rules_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`),
  ADD CONSTRAINT `rules_ibfk_2` FOREIGN KEY (`profileId`) REFERENCES `profile` (`PId`);

--
-- Constraints for table `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `tag_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`),
  ADD CONSTRAINT `tag_ibfk_2` FOREIGN KEY (`profileId`) REFERENCES `profile` (`PId`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;