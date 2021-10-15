-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 15, 2021 at 01:20 PM
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
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `ranklevel` int(11) NOT NULL,
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
  `crewCap` int(11) NOT NULL,
  `status` text NOT NULL,
  `incidentID` int(11) DEFAULT NULL,
  `assignable` tinyint(1) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
