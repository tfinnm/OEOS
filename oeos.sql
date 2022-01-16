-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 14, 2022 at 11:53 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oeos`
--
CREATE DATABASE IF NOT EXISTS `oeos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `oeos`;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Color` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Incident` int(11) NOT NULL,
  `Event` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Display` tinyint(1) DEFAULT '1',
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `icsforms`
--

DROP TABLE IF EXISTS `icsforms`;
CREATE TABLE IF NOT EXISTS `icsforms` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `formcode` text NOT NULL,
  `incident` int(11) NOT NULL,
  `period` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `data` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

DROP TABLE IF EXISTS `incidents`;
CREATE TABLE IF NOT EXISTS `incidents` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timeOut` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address` text NOT NULL,
  `type` text NOT NULL,
  `details` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `commandUnitID` text NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `maydays`
--

DROP TABLE IF EXISTS `maydays`;
CREATE TABLE IF NOT EXISTS `maydays` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Incident` text NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Incident` int(11) NOT NULL,
  `Content` text NOT NULL,
  `Tone` int(11) NOT NULL,
  `Issued` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `middleInitial` text NOT NULL,
  `age` int(11) NOT NULL,
  `dob` text NOT NULL,
  `gender` text NOT NULL,
  `height` text NOT NULL,
  `weight` int(11) NOT NULL,
  `triage` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `personel`
--

DROP TABLE IF EXISTS `personel`;
CREATE TABLE IF NOT EXISTS `personel` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uname` text NOT NULL,
  `upass` text NOT NULL,
  `name` text NOT NULL,
  `rankname` text NOT NULL,
  `Unit` int(11) DEFAULT NULL,
  `perm.assign` tinyint(1) NOT NULL,
  `perm.selfassign` tinyint(1) NOT NULL,
  `perm.command` tinyint(1) NOT NULL,
  `perm.manageusers` tinyint(1) NOT NULL,
  `perm.manageunits` tinyint(1) NOT NULL,
  `perm.manageperms` tinyint(1) NOT NULL,
  `perm.managedepts` tinyint(1) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `radiocomms`
--

DROP TABLE IF EXISTS `radiocomms`;
CREATE TABLE IF NOT EXISTS `radiocomms` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IncidentID` int(11) DEFAULT NULL,
  `Name` text NOT NULL,
  `Talkgroup` text NOT NULL,
  `Channel` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tacticalworksheets`
--

DROP TABLE IF EXISTS `tacticalworksheets`;
CREATE TABLE IF NOT EXISTS `tacticalworksheets` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Incident` text NOT NULL,
  `Name` text NOT NULL,
  `SketchData` text NOT NULL,
  `SideForm` text NOT NULL,
  `MainForm` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uname` text NOT NULL,
  `upass` text NOT NULL,
  `longName` text NOT NULL,
  `shortName` text NOT NULL,
  `deptID` int(11) NOT NULL,
  `status` text NOT NULL,
  `incidentID` int(11) DEFAULT NULL,
  `assignable` tinyint(1) NOT NULL,
  `lastPAR` timestamp NOT NULL,
  `PAR` tinyint(1) NOT NULL,
  `lastRehab` timestamp NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `worksheettemplates`
--

DROP TABLE IF EXISTS `worksheettemplates`;
CREATE TABLE IF NOT EXISTS `worksheettemplates` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `SideForm` text NOT NULL,
  `MainForm` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DELIMITER $$
--
-- Events
--
DROP EVENT `clearNotifications`$$
CREATE DEFINER=`tfinnm`@`%` EVENT `clearNotifications` ON SCHEDULE EVERY 5 MINUTE STARTS '2021-11-18 11:42:33' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM notification WHERE Issued < DATE_SUB(NOW(), INTERVAL 10 MINUTE)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
