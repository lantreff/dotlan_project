-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 26, 2014 at 02:22 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `project_anwesenheit`
--

CREATE TABLE IF NOT EXISTS `project_anwesenheit` (
  `event_id` int(11) NOT NULL,
  `tag` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `abwesend` tinyint(1) NOT NULL,
  `ab_0` tinyint(1) NOT NULL,
  `ab_1` tinyint(1) NOT NULL,
  `ab_2` tinyint(1) NOT NULL,
  `ab_3` tinyint(1) NOT NULL,
  `ab_4` tinyint(1) NOT NULL,
  `ab_5` tinyint(1) NOT NULL,
  `ab_6` tinyint(1) NOT NULL,
  `ab_7` tinyint(1) NOT NULL,
  `ab_8` tinyint(1) NOT NULL,
  `ab_9` tinyint(1) NOT NULL,
  `ab_10` tinyint(1) NOT NULL,
  `ab_11` tinyint(1) NOT NULL,
  `ab_12` tinyint(1) NOT NULL,
  `ab_13` tinyint(1) NOT NULL,
  `ab_14` tinyint(1) NOT NULL,
  `ab_15` tinyint(1) NOT NULL,
  `ab_16` tinyint(1) NOT NULL,
  `ab_17` tinyint(1) NOT NULL,
  `ab_18` tinyint(1) NOT NULL,
  `ab_19` tinyint(1) NOT NULL,
  `ab_20` tinyint(1) NOT NULL,
  `ab_21` tinyint(1) NOT NULL,
  `ab_22` tinyint(1) NOT NULL,
  `ab_23` tinyint(1) NOT NULL,
  PRIMARY KEY (`event_id`,`tag`,`user_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_card`
--

CREATE TABLE IF NOT EXISTS `project_card` (
  `card_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `pic_hash` text NOT NULL,
  `last_order_date` int(11) NOT NULL,
  `last_creation_date` int(11) NOT NULL,
  `card_status` varchar(2) NOT NULL,
  `card_info` text NOT NULL,
  PRIMARY KEY (`card_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_card_document`
--

CREATE TABLE IF NOT EXISTS `project_card_document` (
  `doc_ID` int(11) NOT NULL AUTO_INCREMENT,
  `doc_hash` text NOT NULL,
  `date_generated` int(11) NOT NULL,
  `print_status` varchar(1) NOT NULL DEFAULT '0',
  `doc_title` text NOT NULL,
  PRIMARY KEY (`doc_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_contact_contacts`
--

CREATE TABLE IF NOT EXISTS `project_contact_contacts` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_contact_contacts_groups`
--

CREATE TABLE IF NOT EXISTS `project_contact_contacts_groups` (
  `contacts_contactid` int(10) unsigned NOT NULL,
  `groups_groupid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`contacts_contactid`,`groups_groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_contact_groups`
--

CREATE TABLE IF NOT EXISTS `project_contact_groups` (
  `groupid` int(10) unsigned NOT NULL,
  `groupname` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_countryTable`
--

CREATE TABLE IF NOT EXISTS `project_countryTable` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ktz` char(255) NOT NULL,
  `name` char(255) NOT NULL,
  `officialName` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=895 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_dienstplan`
--

CREATE TABLE IF NOT EXISTS `project_dienstplan` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  `plan_name` varchar(200) NOT NULL DEFAULT '',
  `tag` varchar(11) NOT NULL DEFAULT '0',
  `doppelt_erlaubt` tinyint(1) NOT NULL DEFAULT '0',
  `id_01` varchar(100) NOT NULL DEFAULT '0',
  `id_02` varchar(100) NOT NULL DEFAULT '0',
  `id_03` varchar(100) NOT NULL DEFAULT '0',
  `id_04` varchar(100) NOT NULL DEFAULT '0',
  `id_05` varchar(100) NOT NULL DEFAULT '0',
  `id_06` varchar(100) NOT NULL DEFAULT '0',
  `id_07` varchar(100) NOT NULL DEFAULT '0',
  `id_08` varchar(100) NOT NULL DEFAULT '0',
  `id_09` varchar(100) NOT NULL DEFAULT '0',
  `id_10` varchar(100) NOT NULL DEFAULT '0',
  `id_11` varchar(100) NOT NULL DEFAULT '0',
  `id_12` varchar(100) NOT NULL DEFAULT '0',
  `id_13` varchar(100) NOT NULL DEFAULT '0',
  `id_14` varchar(100) NOT NULL DEFAULT '0',
  `id_15` varchar(100) NOT NULL DEFAULT '0',
  `id_16` varchar(100) NOT NULL DEFAULT '0',
  `id_17` varchar(100) NOT NULL DEFAULT '0',
  `id_18` varchar(100) NOT NULL DEFAULT '0',
  `id_19` varchar(100) NOT NULL DEFAULT '0',
  `id_20` varchar(100) NOT NULL DEFAULT '0',
  `id_21` varchar(100) NOT NULL DEFAULT '0',
  `id_22` varchar(100) NOT NULL DEFAULT '0',
  `id_23` varchar(100) NOT NULL DEFAULT '0',
  `id_24` varchar(100) NOT NULL DEFAULT '0',
  `freeze` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`,`plan_name`,`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_equipment`
--

CREATE TABLE IF NOT EXISTS `project_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invnr` varchar(255) NOT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  `artnr` varchar(255) NOT NULL,
  `hersteller` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `anzahl` varchar(255) NOT NULL,
  `besitzer` varchar(255) NOT NULL,
  `details` varchar(500) NOT NULL,
  `lagerort` varchar(255) NOT NULL,
  `kiste` varchar(255) NOT NULL,
  `ist_leihartikel` tinyint(1) NOT NULL,
  `ausleihe` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_equipment_equip_group`
--

CREATE TABLE IF NOT EXISTS `project_equipment_equip_group` (
  `id_group` int(11) NOT NULL,
  `id_equipment` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_equipment_groups`
--

CREATE TABLE IF NOT EXISTS `project_equipment_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  `ausleihe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ipliste`
--

CREATE TABLE IF NOT EXISTS `project_ipliste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  `mac` varchar(255) NOT NULL,
  `dns` varchar(255) NOT NULL,
  `lan` varchar(255) NOT NULL DEFAULT 'maxlan.de',
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=672 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_leih_article`
--

CREATE TABLE IF NOT EXISTS `project_leih_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  `u_id` varchar(255) NOT NULL,
  `v_id` varchar(255) NOT NULL,
  `kategorie` varchar(255) NOT NULL,
  `ausleihe` varchar(255) NOT NULL,
  `besitzer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_leih_leihe`
--

CREATE TABLE IF NOT EXISTS `project_leih_leihe` (
  `id` int(10) NOT NULL,
  `id_leih_user` int(10) NOT NULL,
  `id_leih_user_verleiher` int(10) NOT NULL,
  `id_leih_artikel` int(10) NOT NULL,
  `id_leih_gruppe` int(11) NOT NULL DEFAULT '0',
  `event_id` int(10) NOT NULL,
  `leih_datum` datetime NOT NULL,
  `rueckgabe_datum` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_leih_user`
--

CREATE TABLE IF NOT EXISTS `project_leih_user` (
  `id` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL DEFAULT '',
  `vorname` varchar(50) NOT NULL DEFAULT '',
  `nachname` varchar(50) NOT NULL DEFAULT '',
  `strasse` varchar(50) NOT NULL DEFAULT '',
  `plz` varchar(10) NOT NULL DEFAULT '',
  `wohnort` varchar(50) NOT NULL DEFAULT '',
  `geb` date NOT NULL DEFAULT '1970-01-01',
  `personr` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- --------------------------------------------------------

--
-- Table structure for table `project_meeting_anwesenheit`
--

CREATE TABLE IF NOT EXISTS `project_meeting_anwesenheit` (
  `meeting_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `wahrscheinlichkeit` int(11) NOT NULL DEFAULT '0',
  `anwesend` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_meeting_liste`
--

CREATE TABLE IF NOT EXISTS `project_meeting_liste` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `titel` text NOT NULL,
  `datum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `location` tinytext NOT NULL,
  `adresse` text NOT NULL,
  `geplant` longtext NOT NULL,
  `protokoll` int(11) NOT NULL,
  `gewesen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_notizen`
--

CREATE TABLE IF NOT EXISTS `project_notizen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `bezeichnung` text NOT NULL,
  `text` mediumtext NOT NULL,
  `kategorie` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `last_work` datetime NOT NULL,
  `global` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_notizen_historie`
--

CREATE TABLE IF NOT EXISTS `project_notizen_historie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notiz_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `user` text NOT NULL,
  `datum` datetime NOT NULL,
  `tmp` text NOT NULL,
  `tmp_bezeichnung` text NOT NULL,
  `tmp_kategorie` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_rights_rights`
--

CREATE TABLE IF NOT EXISTS `project_rights_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bereich` varchar(255) NOT NULL,
  `recht` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_rights_user_rights`
--

CREATE TABLE IF NOT EXISTS `project_rights_user_rights` (
  `user_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_sponsoren`
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
  `land` char(255) NOT NULL,
  `marke` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_sponsoren_artikel`
--

CREATE TABLE IF NOT EXISTS `project_sponsoren_artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `sp_art_marke` tinytext NOT NULL,
  `sp_art_anz` varchar(10) NOT NULL,
  `sp_art_name` varchar(255) NOT NULL,
  `sp_art_wert` varchar(255) NOT NULL,
  `event_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_sponsoren_stats`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_sponsoren_todo`
--

CREATE TABLE IF NOT EXISTS `project_sponsoren_todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `sp_todo_todo` text NOT NULL,
  `checked` int(1) NOT NULL,
  `event_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_agent_queue`
--

CREATE TABLE IF NOT EXISTS `project_ticket_agent_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `queueid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=180 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_antworten`
--

CREATE TABLE IF NOT EXISTS `project_ticket_antworten` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `erstellt` datetime NOT NULL,
  `titel` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `prio` int(10) NOT NULL,
  `type` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gelesen` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1356 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_globals`
--

CREATE TABLE IF NOT EXISTS `project_ticket_globals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `wert` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_prio`
--

CREATE TABLE IF NOT EXISTS `project_ticket_prio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_queue`
--

CREATE TABLE IF NOT EXISTS `project_ticket_queue` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_sperre`
--

CREATE TABLE IF NOT EXISTS `project_ticket_sperre` (
  `sperre_id` int(1) NOT NULL AUTO_INCREMENT,
  `sperre_name` tinytext NOT NULL,
  PRIMARY KEY (`sperre_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_status`
--

CREATE TABLE IF NOT EXISTS `project_ticket_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_std_antworten`
--

CREATE TABLE IF NOT EXISTS `project_ticket_std_antworten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `std_titel` varchar(100) NOT NULL,
  `std_antwort` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_ticket`
--

CREATE TABLE IF NOT EXISTS `project_ticket_ticket` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `erstellt` datetime NOT NULL,
  `user` int(10) NOT NULL,
  `titel` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(10) NOT NULL DEFAULT '3',
  `prio` int(5) NOT NULL DEFAULT '3',
  `sperre` varchar(1) NOT NULL DEFAULT '1',
  `agent` int(11) NOT NULL DEFAULT '0',
  `queue` int(10) NOT NULL DEFAULT '1',
  `text` mediumtext NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=330 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo`
--

CREATE TABLE IF NOT EXISTS `project_todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  `beschreibung` text NOT NULL,
  `prio` int(5) NOT NULL DEFAULT '3',
  `end` datetime NOT NULL,
  `gruppe` int(11) NOT NULL,
  `bearbeiter` int(11) NOT NULL DEFAULT '0',
  `ersteller` int(11) NOT NULL,
  `erstellt` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_g2u`
--

CREATE TABLE IF NOT EXISTS `project_todo_g2u` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_gruppen`
--

CREATE TABLE IF NOT EXISTS `project_todo_gruppen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_prio`
--

CREATE TABLE IF NOT EXISTS `project_todo_prio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_status`
--

CREATE TABLE IF NOT EXISTS `project_todo_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_vorlagen`
--

CREATE TABLE IF NOT EXISTS `project_todo_vorlagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  `beschreibung` text NOT NULL,
  `prio` int(5) NOT NULL DEFAULT '3',
  `end` datetime NOT NULL,
  `gruppe` int(11) NOT NULL,
  `bearbeiter` int(11) NOT NULL,
  `ersteller` int(11) NOT NULL,
  `erstellt` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_visitors_cards`
--

CREATE TABLE IF NOT EXISTS `project_visitors_cards` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nr` text NOT NULL,
  `bezeichnung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_visitors_list`
--

CREATE TABLE IF NOT EXISTS `project_visitors_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vorname` varchar(255) NOT NULL,
  `nachname` varchar(255) NOT NULL,
  `strasse` varchar(255) NOT NULL,
  `hnr` text NOT NULL,
  `plz` int(5) NOT NULL,
  `ort` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `kommt` datetime NOT NULL,
  `geht` datetime NOT NULL,
  `card_nr` int(10) NOT NULL,
  `bezahlt` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_visitors_log`
--

CREATE TABLE IF NOT EXISTS `project_visitors_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `vorname` text NOT NULL,
  `nachname` text NOT NULL,
  `kommt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `geht` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cardnr` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
