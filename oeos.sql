-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 18, 2022 at 11:17 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE IF NOT EXISTS `assignments` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `boxalarms`
--

DROP TABLE IF EXISTS `boxalarms`;
CREATE TABLE IF NOT EXISTS `boxalarms` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `box` text NOT NULL,
  `unit` text NOT NULL,
  `forced` tinyint(1) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Table structure for table `hospitals`
--

DROP TABLE IF EXISTS `hospitals`;
CREATE TABLE IF NOT EXISTS `hospitals` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Address` text NOT NULL,
  `Contact` text NOT NULL,
  `diversion` text NOT NULL,
  `diversionNote` text NOT NULL,
  `diversionUpdate` timestamp NOT NULL,
  `Trauma` int(11) NOT NULL,
  `Burn` tinyint(1) NOT NULL,
  `Stroke` tinyint(1) NOT NULL,
  `STEMI` tinyint(1) NOT NULL,
  `Helipad` tinyint(1) NOT NULL,
  `uname` text NOT NULL,
  `upass` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incidenthazards`
--

DROP TABLE IF EXISTS `incidenthazards`;
CREATE TABLE IF NOT EXISTS `incidenthazards` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Incident` text NOT NULL,
  `file` text NOT NULL,
  `radius` int(11) NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incidentpoints`
--

DROP TABLE IF EXISTS `incidentpoints`;
CREATE TABLE IF NOT EXISTS `incidentpoints` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Incident` int(11) NOT NULL,
  `file` text NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
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
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  `type` text NOT NULL,
  `details` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `commandUnitID` text,
  `knox` tinyint(1) NOT NULL DEFAULT '0',
  `elec` tinyint(1) NOT NULL DEFAULT '0',
  `lwfloor` tinyint(1) NOT NULL DEFAULT '0',
  `lwroof` tinyint(1) NOT NULL DEFAULT '0',
  `truss` tinyint(1) NOT NULL DEFAULT '0',
  `hazmat` tinyint(1) NOT NULL DEFAULT '0',
  `abandoned` tinyint(1) NOT NULL DEFAULT '0',
  `nowater` tinyint(1) NOT NULL DEFAULT '0',
  `noradio` tinyint(1) NOT NULL DEFAULT '0',
  `sprinkler` tinyint(1) NOT NULL DEFAULT '0',
  `aed` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incidenttype`
--

DROP TABLE IF EXISTS `incidenttype`;
CREATE TABLE IF NOT EXISTS `incidenttype` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CadName` text NOT NULL,
  `ShortName` text NOT NULL,
  `pronunciation` text NOT NULL,
  `ITCode` text NOT NULL,
  `hasAlarms` tinyint(1) NOT NULL,
  `type` text NOT NULL,
  `icon` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `localgeocoder`
--

DROP TABLE IF EXISTS `localgeocoder`;
CREATE TABLE IF NOT EXISTS `localgeocoder` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `LocationName` text NOT NULL,
  `address` text NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `Incident` text NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `middleInitial` text NOT NULL,
  `age` int(11) NOT NULL,
  `dob` text NOT NULL,
  `gender` text NOT NULL,
  `height` text NOT NULL,
  `weight` int(11) NOT NULL,
  `chiefComplaint` text NOT NULL,
  `triage` int(11) NOT NULL,
  `triageTag` text,
  `status` text NOT NULL,
  `hospital` text,
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
  `providerLevel` text NOT NULL,
  `Unit` int(11) DEFAULT NULL,
  `perm.assign` tinyint(1) NOT NULL,
  `perm.selfassign` tinyint(1) NOT NULL,
  `perm.command` tinyint(1) NOT NULL,
  `perm.ems` tinyint(1) NOT NULL DEFAULT '0',
  `perm.manageusers` tinyint(1) NOT NULL,
  `perm.manageunits` tinyint(1) NOT NULL,
  `perm.manageperms` tinyint(1) NOT NULL,
  `perm.managedepts` tinyint(1) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `preplan`
--

DROP TABLE IF EXISTS `preplan`;
CREATE TABLE IF NOT EXISTS `preplan` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `elec` tinyint(1) NOT NULL,
  `knox` tinyint(1) NOT NULL,
  `lwfloor` tinyint(1) NOT NULL,
  `lwroof` tinyint(1) NOT NULL,
  `truss` tinyint(1) NOT NULL,
  `hazmat` tinyint(1) NOT NULL,
  `abandoned` tinyint(1) NOT NULL,
  `nowater` tinyint(1) NOT NULL,
  `noradio` tinyint(1) NOT NULL,
  `sprinkler` tinyint(1) NOT NULL,
  `aed` tinyint(1) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `preplanpoints`
--

DROP TABLE IF EXISTS `preplanpoints`;
CREATE TABLE IF NOT EXISTS `preplanpoints` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `preplan` text NOT NULL,
  `file` text NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

DROP TABLE IF EXISTS `providers`;
CREATE TABLE IF NOT EXISTS `providers` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient` text NOT NULL,
  `provider` text NOT NULL,
  `role` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `pronunciation` text NOT NULL,
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
  `pronunciation` text NOT NULL,
  `deptID` int(11) NOT NULL,
  `status` text NOT NULL,
  `incidentID` int(11) DEFAULT NULL,
  `assignable` tinyint(1) NOT NULL,
  `display` tinyint(1) NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  `lastPAR` timestamp NOT NULL,
  `PAR` tinyint(1) NOT NULL,
  `lastRehab` timestamp NOT NULL,
  `assignment` text NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `universalpoints`
--

DROP TABLE IF EXISTS `universalpoints`;
CREATE TABLE IF NOT EXISTS `universalpoints` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file` text NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
