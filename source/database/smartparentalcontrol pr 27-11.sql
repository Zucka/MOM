-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2013 at 01:48 PM
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
  KEY `controllerId` (`controllerId`),
  KEY `RId` (`RId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `chores`
--

INSERT INTO `chores` (`CId`, `CSId`, `name`, `description`, `defaultPoints`) VALUES
(1, 1, 'dishwashing', NULL, 3),
(2, 1, 'vacuum room', 'the living room', 7),
(3, 1, 'garbage', NULL, 6),
(4, 1, 'clean own room', 'dust, vacuum,windows', 3);

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
  KEY `condId` (`condId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cond_timestamp`
--

CREATE TABLE IF NOT EXISTS `cond_timestamp` (
  `condTimesId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `condId` bigint(20) unsigned NOT NULL,
  `onTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`condTimesId`),
  KEY `condId` (`condId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `cost` int(11) DEFAULT '1',
  PRIMARY KEY (`CSerieNo`),
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `controller`
--

INSERT INTO `controller` (`CSerieNo`, `CSId`, `name`, `location`, `status`, `cost`) VALUES
(123, 1, 'TV', 'livingroom', 'GREEN', 1),
(124, 1, 'playstation', NULL, '!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `controller_used_by_tag`
--

CREATE TABLE IF NOT EXISTS `controller_used_by_tag` (
  `TSerieNo` bigint(20) unsigned NOT NULL,
  `CSerieNo` bigint(20) unsigned NOT NULL,
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`TSerieNo`,`CSerieNo`,`starttime`),
  KEY `CSerieNo` (`CSerieNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `controller_used_by_tag`
--

INSERT INTO `controller_used_by_tag` (`TSerieNo`, `CSerieNo`, `starttime`, `endtime`) VALUES
(234, 123, '2013-11-26 11:29:19', '2013-11-26 11:29:19'),
(234, 123, '2013-11-27 11:45:21', '2013-11-27 11:48:06'),
(234, 123, '2013-11-27 11:50:57', '2013-11-27 11:52:08'),
(234, 123, '2013-11-27 12:09:34', '2013-11-27 12:09:44'),
(234, 123, '2013-11-27 12:15:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `control_system`
--

CREATE TABLE IF NOT EXISTS `control_system` (
  `CSId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `street` varchar(50) DEFAULT NULL,
  `postcode` varchar(30) DEFAULT NULL,
  `phoneNo` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`CSId`),
  UNIQUE KEY `username` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `control_system`
--

INSERT INTO `control_system` (`CSId`, `name`, `street`, `postcode`, `phoneNo`) VALUES
(1, 'sys1', NULL, NULL, NULL),
(2, 'sys2', 'bakerstreet', '9000', '12345678');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `PId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CSId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `points` double DEFAULT '0',
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('user','manager') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`PId`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`PId`, `CSId`, `name`, `points`, `username`, `password`, `email`, `phone`, `role`) VALUES
(1, 1, 'Johan Sørensen', 4, 'johans', '$2a$10$i7eknel51kJMbofxMeagce5AxId6GNgiQej55stomKshOdxHWe22W', 'johan.soerensen6@gmail.com', '26136946', 'manager'),
(2, 1, 'Allan Hansen', 0, 'allan', '$2a$10$nW4o5dFai5wuv0uRKgWdoehYWyU/UPBCnidg5rPxrdef4HKfejgEy', 'allan@smartparentalcontrol.com', '12345678', 'user'),
(3, 1, 'Hermann Hansen', 0, 'hermann', '$2a$10$osOBcuybeXxjzhJVrjkpjuqbNpc4jDQUXWZkk0rr8LgUSuqlaDKeG', 'hermann@smartparentalcontrol.com', '87654321', 'manager'),
(4, 1, 'Jens Jensen', 0, 'jens', '$2a$10$EEAoUpBBHchJRt0Q36TKNufx5W0WKSIEAJZuuyJ4ShEIA9C9VBl0m', 'jens@smartparentalcontrol.com', '54688713', 'user'),
(5, 1, 'Asger Asgersen', 0, 'asger', '$2a$10$0rlIHvW4NBENygTLjJfN0.EgRgUAG0YAuIIfqa.6.KHK2egS5./eO', 'asger@smartparentalcontrol.com', '85263147', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `profile_did_chores`
--

CREATE TABLE IF NOT EXISTS `profile_did_chores` (
  `PId` bigint(20) unsigned NOT NULL,
  `CId` bigint(20) unsigned NOT NULL,
  `actualPoints` double DEFAULT NULL,
  `timeOfCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PId`,`CId`,`timeOfCreation`),
  KEY `CId` (`CId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_has_rules`
--

CREATE TABLE IF NOT EXISTS `profile_has_rules` (
  `PId` bigint(20) unsigned NOT NULL,
  `RId` bigint(20) unsigned NOT NULL,
  `validFromTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PId`,`RId`),
  KEY `RId` (`RId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rcondition`
--

CREATE TABLE IF NOT EXISTS `rcondition` (
  `condId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `RId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `controllerId` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`condId`),
  KEY `controllerId` (`controllerId`),
  KEY `RId` (`RId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `RId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CSId` bigint(20) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `isPermission` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`RId`),
  KEY `CSId` (`CSId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  KEY `CSId` (`CSId`),
  KEY `profileId` (`profileId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`TSerieNo`, `CSId`, `profileId`, `name`, `active`) VALUES
(234, 1, 1, 'ring', 1),
(235, 1, 2, NULL, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `action_ibfk_1` FOREIGN KEY (`controllerId`) REFERENCES `controller` (`CSerieNo`) ON DELETE CASCADE,
  ADD CONSTRAINT `action_ibfk_2` FOREIGN KEY (`RId`) REFERENCES `rules` (`RId`) ON DELETE CASCADE;

--
-- Constraints for table `chores`
--
ALTER TABLE `chores`
  ADD CONSTRAINT `chores_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`) ON DELETE CASCADE;

--
-- Constraints for table `cond_timeperiod`
--
ALTER TABLE `cond_timeperiod`
  ADD CONSTRAINT `cond_timeperiod_ibfk_1` FOREIGN KEY (`condId`) REFERENCES `rcondition` (`condId`) ON DELETE CASCADE;

--
-- Constraints for table `cond_timestamp`
--
ALTER TABLE `cond_timestamp`
  ADD CONSTRAINT `cond_timestamp_ibfk_1` FOREIGN KEY (`condId`) REFERENCES `rcondition` (`condId`) ON DELETE CASCADE;

--
-- Constraints for table `controller`
--
ALTER TABLE `controller`
  ADD CONSTRAINT `controller_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`) ON DELETE CASCADE;

--
-- Constraints for table `controller_used_by_tag`
--
ALTER TABLE `controller_used_by_tag`
  ADD CONSTRAINT `controller_used_by_tag_ibfk_1` FOREIGN KEY (`TSerieNo`) REFERENCES `tag` (`TSerieNo`) ON DELETE CASCADE,
  ADD CONSTRAINT `controller_used_by_tag_ibfk_2` FOREIGN KEY (`CSerieNo`) REFERENCES `controller` (`CSerieNo`) ON DELETE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`) ON DELETE CASCADE;

--
-- Constraints for table `profile_did_chores`
--
ALTER TABLE `profile_did_chores`
  ADD CONSTRAINT `profile_did_chores_ibfk_2` FOREIGN KEY (`CId`) REFERENCES `chores` (`CId`) ON DELETE CASCADE,
  ADD CONSTRAINT `profile_did_chores_ibfk_1` FOREIGN KEY (`PId`) REFERENCES `profile` (`PId`) ON DELETE CASCADE;

--
-- Constraints for table `profile_has_rules`
--
ALTER TABLE `profile_has_rules`
  ADD CONSTRAINT `profile_has_rules_ibfk_2` FOREIGN KEY (`RId`) REFERENCES `rules` (`RId`) ON DELETE CASCADE,
  ADD CONSTRAINT `profile_has_rules_ibfk_1` FOREIGN KEY (`PId`) REFERENCES `profile` (`PId`) ON DELETE CASCADE;

--
-- Constraints for table `rcondition`
--
ALTER TABLE `rcondition`
  ADD CONSTRAINT `rcondition_ibfk_1` FOREIGN KEY (`controllerId`) REFERENCES `controller` (`CSerieNo`) ON DELETE CASCADE,
  ADD CONSTRAINT `rcondition_ibfk_2` FOREIGN KEY (`RId`) REFERENCES `rules` (`RId`) ON DELETE CASCADE;

--
-- Constraints for table `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rules_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`) ON DELETE CASCADE;

--
-- Constraints for table `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `tag_ibfk_1` FOREIGN KEY (`CSId`) REFERENCES `control_system` (`CSId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tag_ibfk_2` FOREIGN KEY (`profileId`) REFERENCES `profile` (`PId`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
