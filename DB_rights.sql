-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 26, 2014 at 02:32 PM
-- Server version: 5.5.40-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `maxlan`
--

--
-- Dumping data for table `project_rights_rights`
--

INSERT INTO `project_rights_rights` (`id`, `bereich`, `recht`) VALUES
(1, 'kontakte', 'edit'),
(2, 'kontakte', 'del'),
(3, 'kontakte', 'view'),
(4, 'kontakte', 'add'),
(5, 'sponsoren', 'view'),
(6, 'sponsoren', 'add'),
(7, 'sponsoren', 'edit'),
(8, 'sponsoren', 'del'),
(9, 'rechteverwaltung', 'view'),
(10, 'rechteverwaltung', 'add'),
(11, 'rechteverwaltung', 'edit'),
(12, 'rechteverwaltung', 'del'),
(13, 'dienstplan', 'view'),
(14, 'dienstplan', 'add'),
(15, 'dienstplan', 'edit'),
(16, 'dienstplan', 'del'),
(17, 'equipment', 'view'),
(18, 'equipment', 'add'),
(19, 'equipment', 'edit'),
(20, 'equipment', 'del'),
(61, 'gameserver_webinterface', 'admin'),
(25, 'ipliste', 'view'),
(26, 'ipliste', 'add'),
(27, 'ipliste', 'edit'),
(28, 'ipliste', 'del'),
(29, 'leihsystem', 'view'),
(30, 'leihsystem', 'add'),
(31, 'leihsystem', 'edit'),
(32, 'leihsystem', 'del'),
(60, 'gameserver_webinterface', 'view'),
(59, 'mx_router', 'view'),
(58, 'tools', 'view'),
(37, 'notiz', 'view'),
(38, 'notiz', 'add'),
(39, 'notiz', 'edit'),
(40, 'notiz', 'del'),
(57, 'tools', 'freeze'),
(56, 'tools', 'platzzettel'),
(45, 'sts', 'view'),
(46, 'sts', 'add'),
(47, 'sts', 'edit'),
(48, 'sts', 'del'),
(55, 'tools', 'verlosungszettel'),
(54, 'tools', 'gaesteserver'),
(53, 'webmail', 'view'),
(62, 'anwesenheit', 'view'),
(64, 'anwesenheit', 'edit'),
(65, 'mx_router', 'admin'),
(66, 'tools', 'turnier_kopie'),
(67, 'tools', 'orga_konten'),
(72, 'meeting', 'view'),
(73, 'meeting', 'add'),
(74, 'meeting', 'edit'),
(75, 'meeting', 'del'),
(76, 'dienstplan', 'freeze'),
(77, 'dienstplan', 'edit_freeze'),
(78, 'media', 'view'),
(79, 'catering_order', 'view'),
(80, 'catering_order', 'print_order'),
(81, 'card', 'view'),
(82, 'card', 'create_cards'),
(83, 'card', 'print_cards'),
(84, 'visitors_list', 'edit'),
(85, 'visitors_list', 'add'),
(86, 'visitors_list', 'del'),
(87, 'todo', 'view'),
(88, 'todo', 'add'),
(89, 'todo', 'edit'),
(90, 'todo', 'del'),
(91, 'todo', 'remind');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
