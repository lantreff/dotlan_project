-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: 
-- Erstellungszeit: 28. Apr 2013 um 22:47
-- Server Version: 5.5.28-nmm3-log
-- PHP-Version: 5.4.9-nmm1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `d00e8abd`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `project_rights_rights`
--

CREATE TABLE IF NOT EXISTS `project_rights_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bereich` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Daten für Tabelle `project_rights_rights`
--

INSERT INTO `project_rights_rights` (`id`, `name`, `bereich`) VALUES
(1, 'projekt_kontakte_edit', 'kontakte'),
(2, 'projekt_kontakte_del', 'kontakte'),
(3, 'projekt_kontakte_view', 'kontakte'),
(4, 'projekt_kontakte_add', 'kontakte'),
(5, 'projekt_sponsoren_view', 'sponsoren'),
(6, 'projekt_sponsoren_add', 'sponsoren'),
(7, 'projekt_sponsoren_edit', 'sponsoren'),
(8, 'projekt_sponsoren_del', 'sponsoren'),
(9, 'projekt_rechteverwaltung_view', 'rechteverwaltung'),
(10, 'projekt_rechteverwaltung_add', 'rechteverwaltung'),
(11, 'projekt_rechteverwaltung_edit', 'rechteverwaltung'),
(12, 'projekt_rechteverwaltung_del', 'rechteverwaltung'),
(13, 'projekt_dienstplan_view', 'dienstplan'),
(14, 'projekt_dienstplan_add', 'dienstplan'),
(15, 'projekt_dienstplan_edit', 'dienstplan'),
(16, 'projekt_dienstplan_del', 'dienstplan'),
(17, 'projekt_equipment_view', 'equipment'),
(18, 'projekt_equipment_add', 'equipment'),
(19, 'projekt_equipment_edit', 'equipment'),
(20, 'projekt_equipment_del', 'equipment'),
(21, 'projekt_freeze_view', 'freeze'),
(22, 'projekt_freeze_add', 'freeze'),
(23, 'projekt_freeze_edit', 'freeze'),
(24, 'projekt_freeze_del', 'freeze'),
(25, 'projekt_ipliste_view', 'ipliste'),
(26, 'projekt_ipliste_add', 'ipliste'),
(27, 'projekt_ipliste_edit', 'ipliste'),
(28, 'projekt_ipliste_del', 'ipliste'),
(29, 'projekt_leihsystem_view', 'leihsystem'),
(30, 'projekt_leihsystem_add', 'leihsystem'),
(31, 'projekt_leihsystem_edit', 'leihsystem'),
(32, 'projekt_leihsystem_del', 'leihsystem'),
(33, 'projekt_meeting_view', 'meeting'),
(34, 'projekt_meeting_add', 'meeting'),
(35, 'projekt_meeting_edit', 'meeting'),
(36, 'projekt_meeting_del', 'meeting'),
(37, 'projekt_notiz_view', 'notiz'),
(38, 'projekt_notiz_add', 'notiz'),
(39, 'projekt_notiz_edit', 'notiz'),
(40, 'projekt_notiz_del', 'notiz'),
(41, 'projekt_rating_view', 'rating'),
(42, 'projekt_rating_add', 'rating'),
(43, 'projekt_rating_edit', 'rating'),
(44, 'projekt_rating_del', 'rating'),
(45, 'projekt_sts_view', 'sts'),
(46, 'projekt_sts_add', 'sts'),
(47, 'projekt_sts_edit', 'sts'),
(48, 'projekt_sts_del', 'sts'),
(49, 'projekt_verlosung_view', 'verlosung'),
(50, 'projekt_verlosung_add', 'verlosung'),
(51, 'projekt_verlosung_edit', 'verlosung'),
(52, 'projekt_verlosung_del', 'verlosung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `project_rights_user_rights`
--

CREATE TABLE IF NOT EXISTS `project_rights_user_rights` (
  `user_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Tabellenstruktur für Tabelle `project_sponsoren`
--

CREATE TABLE IF NOT EXISTS `project_sponsoren` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'Bitte Namen eintragen ...',
  `str` varchar(255) NOT NULL DEFAULT 'hier die Strasse eintragen....',
  `hnr` varchar(10) NOT NULL,
  `plz` varchar(10) NOT NULL DEFAULT '0',
  `ort` varchar(255) NOT NULL DEFAULT 'Hier den Ort eintragen ..',
  `kommentar` varchar(255) NOT NULL DEFAULT 'Hier ein Komentar eitragen...',
  `homepage` varchar(255) NOT NULL DEFAULT 'www.hompage.de',
  `wert` varchar(20) NOT NULL,
  `admin` varchar(255) NOT NULL DEFAULT 'Bitte Namen eintragen ...',
  `email` varchar(255) NOT NULL DEFAULT 'e-mail eintrateg ...',
  `tel` varchar(255) NOT NULL,
  `formular` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `project_sponsoren`
--

--
-- Tabellenstruktur für Tabelle `project_sponsoren_artikel`
--

CREATE TABLE IF NOT EXISTS `project_sponsoren_artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `sp_art_anz` varchar(10) NOT NULL,
  `sp_art_name` varchar(255) NOT NULL,
  `sp_art_wert` varchar(255) NOT NULL,
  `event_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `project_sponsoren_stats`
--

CREATE TABLE IF NOT EXISTS `project_sponsoren_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` varchar(255) NOT NULL,
  `s_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `event_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projekt_contact_contacts`
--

CREATE TABLE IF NOT EXISTS `projekt_contact_contacts` (
  `contactid` int(10) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(255) NOT NULL,
  `p_vorname` varchar(255) NOT NULL,
  `p_geb` date NOT NULL COMMENT 'JJJJ-MM-DD',
  `p_geb_tag` int(10) NOT NULL,
  `p_geb_monat` int(10) NOT NULL,
  `p_geb_jahr` int(10) NOT NULL,
  `p_geschlecht` text NOT NULL,
  `p_email` varchar(255) NOT NULL,
  `p_mobil` text NOT NULL,
  `p_tel` text NOT NULL,
  `p_str` varchar(255) NOT NULL,
  `p_hnr` text NOT NULL,
  `p_plz` text NOT NULL,
  `p_ort` varchar(255) NOT NULL,
  `fa_name` varchar(255) NOT NULL,
  `fa_funktion` text NOT NULL,
  `fa_email` varchar(255) NOT NULL,
  `fa_mobil` text NOT NULL,
  `fa_tel` text NOT NULL,
  `fa_str` varchar(255) NOT NULL,
  `fa_hnr` text NOT NULL,
  `fa_plz` text NOT NULL,
  `land` varchar(255) NOT NULL,
  `fa_ort` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `fa_formular` text NOT NULL,
  `sponsor_id` int(10) NOT NULL,
  PRIMARY KEY (`contactid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
