-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 04:12 PM
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

--
-- Dumping data for table `project_countryTable`
--

INSERT INTO `project_countryTable` (`id`, `ktz`, `name`, `officialName`) VALUES
(4, 'AFG', 'Afghanistan', 'Afghanistan'),
(8, 'ALB', 'Albania', 'Albania'),
(10, 'ATA', 'Antarctica', 'Antarctica'),
(12, 'DZA', 'Algeria', 'Algeria'),
(16, 'ASM', 'American Samoa', 'American Samoa'),
(20, 'AND', 'Andorra', 'Andorra'),
(24, 'AGO', 'Angola', 'Angola'),
(28, 'ATG', 'Antigua and Barbuda', 'Antigua and Barbuda'),
(31, 'AZE', 'Azerbaijan', 'Azerbaijan'),
(32, 'ARG', 'Argentina', 'Argentina'),
(36, 'AUS', 'Australia', 'Australia'),
(40, 'AUT', 'Austria', 'Austria'),
(44, 'BHS', 'Bahamas', 'Bahamas'),
(48, 'BHR', 'Bahrain', 'Bahrain'),
(50, 'BGD', 'Bangladesh', 'Bangladesh'),
(51, 'ARM', 'Armenia', 'Armenia'),
(52, 'BRB', 'Barbados', 'Barbados'),
(56, 'BEL', 'Belgium', 'Belgium'),
(60, 'BMU', 'Bermuda', 'Bermuda'),
(64, 'BTN', 'Bhutan', 'Bhutan'),
(68, 'BOL', 'Bolivia', 'Bolivia'),
(70, 'BIH', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina'),
(72, 'BWA', 'Botswana', 'Botswana'),
(74, 'BVT', 'Bouvet Island', 'Bouvet Island'),
(76, 'BRA', 'Brazil', 'Brazil'),
(84, 'BLZ', 'Belize', 'Belize'),
(86, 'IOT', 'British Indian Ocean Territory', 'British Indian Ocean Territory'),
(90, 'SLB', 'Solomon Islands', 'Solomon Islands'),
(92, 'VGB', 'Virgin Islands, British', 'Virgin Islands, British'),
(96, 'BRN', 'Brunei Darussalam', 'Brunei Darussalam'),
(100, 'BGR', 'Bulgaria', 'Bulgaria'),
(104, 'MMR', 'Myanmar', 'Myanmar'),
(108, 'BDI', 'Burundi', 'Burundi'),
(112, 'BLR', 'Belarus', 'Belarus'),
(116, 'KHM', 'Cambodia', 'Cambodia'),
(120, 'CMR', 'Cameroon', 'Cameroon'),
(124, 'CAN', 'Canada', 'Canada'),
(132, 'CPV', 'Cape Verde', 'Cape Verde'),
(136, 'CYM', 'Cayman Islands', 'Cayman Islands'),
(140, 'CAF', 'Central African Republic', 'Central African Republic'),
(144, 'LKA', 'Sri Lanka', 'Sri Lanka'),
(148, 'TCD', 'Chad', 'Chad'),
(152, 'CHL', 'Chile', 'Chile'),
(156, 'CHN', 'China', 'China'),
(158, 'TWN', 'Taiwan', 'Taiwan, Province of China'),
(162, 'CXR', 'Christmas Island', 'Christmas Island'),
(166, 'CCK', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands'),
(170, 'COL', 'Colombia', 'Colombia'),
(174, 'COM', 'Comoros', 'Comoros'),
(175, 'MYT', 'Mayotte', 'Mayotte'),
(178, 'COG', 'Congo', 'Congo'),
(180, 'COD', 'Congo, Democratic Republic of the', 'Congo, Democratic Republic of the'),
(184, 'COK', 'Cook Islands', 'Cook Islands'),
(188, 'CRI', 'Costa Rica', 'Costa Rica'),
(191, 'HRV', 'Croatia', 'Croatia'),
(192, 'CUB', 'Cuba', 'Cuba'),
(196, 'CYP', 'Cyprus', 'Cyprus'),
(203, 'CZE', 'Czech Republic', 'Czech Republic'),
(204, 'BEN', 'Benin', 'Benin'),
(208, 'DNK', 'Denmark', 'Denmark'),
(212, 'DMA', 'Dominica', 'Dominica'),
(214, 'DOM', 'Dominican Republic', 'Dominican Republic'),
(218, 'ECU', 'Ecuador', 'Ecuador'),
(222, 'SLV', 'El Salvador', 'El Salvador'),
(226, 'GNQ', 'Equatorial Guinea', 'Equatorial Guinea'),
(231, 'ETH', 'Ethiopia', 'Ethiopia'),
(232, 'ERI', 'Eritrea', 'Eritrea'),
(233, 'EST', 'Estonia', 'Estonia'),
(234, 'FRO', 'Faroe Islands', 'Faroe Islands'),
(238, 'FLK', 'Falkland Islands (Malvinas)', 'Falkland Islands (Malvinas)'),
(239, 'SGS', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands'),
(242, 'FJI', 'Fiji', 'Fiji'),
(246, 'FIN', 'Finland', 'Finland'),
(248, 'ALA', 'Ã…land Islands', 'Ã…land Islands'),
(250, 'FRA', 'France', 'France'),
(254, 'GUF', 'French Guiana', 'French Guiana'),
(258, 'PYF', 'French Polynesia', 'French Polynesia'),
(260, 'ATF', 'French Southern Territories', 'French Southern Territories'),
(262, 'DJI', 'Djibouti', 'Djibouti'),
(266, 'GAB', 'Gabon', 'Gabon'),
(268, 'GEO', 'Georgia', 'Georgia'),
(270, 'GMB', 'Gambia', 'Gambia'),
(275, 'PSE', 'Palestinian Territory, Occupied', 'Palestinian Territory, Occupied'),
(276, 'DEU', 'Germany', 'Germany'),
(288, 'GHA', 'Ghana', 'Ghana'),
(292, 'GIB', 'Gibraltar', 'Gibraltar'),
(296, 'KIR', 'Kiribati', 'Kiribati'),
(300, 'GRC', 'Greece', 'Greece'),
(304, 'GRL', 'Greenland', 'Greenland'),
(308, 'GRD', 'Grenada', 'Grenada'),
(312, 'GLP', 'Guadeloupe', 'Guadeloupe'),
(316, 'GUM', 'Guam', 'Guam'),
(320, 'GTM', 'Guatemala', 'Guatemala'),
(324, 'GIN', 'Guinea', 'Guinea'),
(328, 'GUY', 'Guyana', 'Guyana'),
(332, 'HTI', 'Haiti', 'Haiti'),
(334, 'HMD', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands'),
(336, 'VAT', 'Holy See (Vatican City State)', 'Holy See (Vatican City State)'),
(340, 'HND', 'Honduras', 'Honduras'),
(344, 'HKG', 'Hong Kong', 'Hong Kong'),
(348, 'HUN', 'Hungary', 'Hungary'),
(352, 'ISL', 'Iceland', 'Iceland'),
(356, 'IND', 'India', 'India'),
(360, 'IDN', 'Indonesia', 'Indonesia'),
(364, 'IRN', 'Iran', 'Iran, Islamic Republic of'),
(368, 'IRQ', 'Iraq', 'Iraq'),
(372, 'IRL', 'Ireland', 'Ireland'),
(376, 'ISR', 'Israel', 'Israel'),
(380, 'ITA', 'Italy', 'Italy'),
(384, 'CIV', 'CÃ´te d''Ivoire', 'CÃ´te d''Ivoire'),
(388, 'JAM', 'Jamaica', 'Jamaica'),
(392, 'JPN', 'Japan', 'Japan'),
(398, 'KAZ', 'Kazakhstan', 'Kazakhstan'),
(400, 'JOR', 'Jordan', 'Jordan'),
(404, 'KEN', 'Kenya', 'Kenya'),
(408, 'PRK', 'North Korea', 'Korea, Democratic People''s Republic of'),
(410, 'KOR', 'South Korea', 'Korea, Republic of'),
(414, 'KWT', 'Kuwait', 'Kuwait'),
(417, 'KGZ', 'Kyrgyzstan', 'Kyrgyzstan'),
(418, 'LAO', 'Laos', 'Lao People''s Democratic Republic'),
(422, 'LBN', 'Lebanon', 'Lebanon'),
(426, 'LSO', 'Lesotho', 'Lesotho'),
(428, 'LVA', 'Latvia', 'Latvia'),
(430, 'LBR', 'Liberia', 'Liberia'),
(434, 'LBY', 'Libyan Arab Jamahiriya', 'Libyan Arab Jamahiriya'),
(438, 'LIE', 'Liechtenstein', 'Liechtenstein'),
(440, 'LTU', 'Lithuania', 'Lithuania'),
(442, 'LUX', 'Luxembourg', 'Luxembourg'),
(446, 'MAC', 'Macao', 'Macao'),
(450, 'MDG', 'Madagascar', 'Madagascar'),
(454, 'MWI', 'Malawi', 'Malawi'),
(458, 'MYS', 'Malaysia', 'Malaysia'),
(462, 'MDV', 'Maldives', 'Maldives'),
(466, 'MLI', 'Mali', 'Mali'),
(470, 'MLT', 'Malta', 'Malta'),
(474, 'MTQ', 'Martinique', 'Martinique'),
(478, 'MRT', 'Mauritania', 'Mauritania'),
(480, 'MUS', 'Mauritius', 'Mauritius'),
(484, 'MEX', 'Mexico', 'Mexico'),
(492, 'MCO', 'Monaco', 'Monaco'),
(496, 'MNG', 'Mongolia', 'Mongolia'),
(498, 'MDA', 'Moldova', 'Moldova, Republic of'),
(499, 'MNE', 'Montenegro', 'Montenegro'),
(500, 'MSR', 'Montserrat', 'Montserrat'),
(504, 'MAR', 'Morocco', 'Morocco'),
(508, 'MOZ', 'Mozambique', 'Mozambique'),
(512, 'OMN', 'Oman', 'Oman'),
(516, 'NAM', 'Namibia', 'Namibia'),
(520, 'NRU', 'Nauru', 'Nauru'),
(524, 'NPL', 'Nepal', 'Nepal'),
(528, 'NLD', 'Netherlands', 'Netherlands'),
(530, 'ANT', 'Netherlands Antilles', 'Netherlands Antilles'),
(533, 'ABW', 'Aruba', 'Aruba'),
(540, 'NCL', 'New Caledonia', 'New Caledonia'),
(548, 'VUT', 'Vanuatu', 'Vanuatu'),
(554, 'NZL', 'New Zealand', 'New Zealand'),
(558, 'NIC', 'Nicaragua', 'Nicaragua'),
(562, 'NER', 'Niger', 'Niger'),
(566, 'NGA', 'Nigeria', 'Nigeria'),
(570, 'NIU', 'Niue', 'Niue'),
(574, 'NFK', 'Norfolk Island', 'Norfolk Island'),
(578, 'NOR', 'Norway', 'Norway'),
(580, 'MNP', 'Northern Mariana Islands', 'Northern Mariana Islands'),
(581, 'UMI', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands'),
(583, 'FSM', 'Micronesia', 'Micronesia, Federated States of'),
(584, 'MHL', 'Marshall Islands', 'Marshall Islands'),
(585, 'PLW', 'Palau', 'Palau'),
(586, 'PAK', 'Pakistan', 'Pakistan'),
(591, 'PAN', 'Panama', 'Panama'),
(598, 'PNG', 'Papua New Guinea', 'Papua New Guinea'),
(600, 'PRY', 'Paraguay', 'Paraguay'),
(604, 'PER', 'Peru', 'Peru'),
(608, 'PHL', 'Philippines', 'Philippines'),
(612, 'PCN', 'Pitcairn', 'Pitcairn'),
(616, 'POL', 'Poland', 'Poland'),
(620, 'PRT', 'Portugal', 'Portugal'),
(624, 'GNB', 'Guinea-Bissau', 'Guinea-Bissau'),
(626, 'TLS', 'Timor-Leste', 'Timor-Leste'),
(630, 'PRI', 'Puerto Rico', 'Puerto Rico'),
(634, 'QAT', 'Qatar', 'Qatar'),
(638, 'REU', 'RÃ©union', 'RÃ©union'),
(642, 'ROU', 'Romania', 'Romania'),
(643, 'RUS', 'Russian Federation', 'Russian Federation'),
(646, 'RWA', 'Rwanda', 'Rwanda'),
(652, 'BLM', 'Saint BarthÃ©lemy', 'Saint BarthÃ©lemy'),
(654, 'SHN', 'Saint Helena', 'Saint Helena'),
(659, 'KNA', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis'),
(660, 'AIA', 'Anguilla', 'Anguilla'),
(662, 'LCA', 'Saint Lucia', 'Saint Lucia'),
(663, 'MAF', 'Saint Martin (French part)', 'Saint Martin (French part)'),
(666, 'SPM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon'),
(670, 'VCT', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines'),
(674, 'SMR', 'San Marino', 'San Marino'),
(678, 'STP', 'Sao Tome and Principe', 'Sao Tome and Principe'),
(682, 'SAU', 'Saudi Arabia', 'Saudi Arabia'),
(686, 'SEN', 'Senegal', 'Senegal'),
(688, 'SRB', 'Serbia', 'Serbia'),
(690, 'SYC', 'Seychelles', 'Seychelles'),
(694, 'SLE', 'Sierra Leone', 'Sierra Leone'),
(702, 'SGP', 'Singapore', 'Singapore'),
(703, 'SVK', 'Slovakia', 'Slovakia'),
(704, 'VNM', 'Viet Nam', 'Viet Nam'),
(705, 'SVN', 'Slovenia', 'Slovenia'),
(706, 'SOM', 'Somalia', 'Somalia'),
(710, 'ZAF', 'South Africa', 'South Africa'),
(716, 'ZWE', 'Zimbabwe', 'Zimbabwe'),
(724, 'ESP', 'Spain', 'Spain'),
(732, 'ESH', 'Western Sahara', 'Western Sahara'),
(736, 'SDN', 'Sudan', 'Sudan'),
(740, 'SUR', 'Suriname', 'Suriname'),
(744, 'SJM', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen'),
(748, 'SWZ', 'Swaziland', 'Swaziland'),
(752, 'SWE', 'Sweden', 'Sweden'),
(756, 'CHE', 'Switzerland', 'Switzerland'),
(760, 'SYR', 'Syria', 'Syrian Arab Republic'),
(762, 'TJK', 'Tajikistan', 'Tajikistan'),
(764, 'THA', 'Thailand', 'Thailand'),
(768, 'TGO', 'Togo', 'Togo'),
(772, 'TKL', 'Tokelau', 'Tokelau'),
(776, 'TON', 'Tonga', 'Tonga'),
(780, 'TTO', 'Trinidad and Tobago', 'Trinidad and Tobago'),
(784, 'ARE', 'United Arab Emirates', 'United Arab Emirates'),
(788, 'TUN', 'Tunisia', 'Tunisia'),
(792, 'TUR', 'Turkey', 'Turkey'),
(795, 'TKM', 'Turkmenistan', 'Turkmenistan'),
(796, 'TCA', 'Turks and Caicos Islands', 'Turks and Caicos Islands'),
(798, 'TUV', 'Tuvalu', 'Tuvalu'),
(800, 'UGA', 'Uganda', 'Uganda'),
(804, 'UKR', 'Ukraine', 'Ukraine'),
(807, 'MKD', 'Macedonia', 'Macedonia, the former Yugoslav Republic of'),
(818, 'EGY', 'Egypt', 'Egypt'),
(826, 'GBR', 'United Kingdom', 'United Kingdom'),
(831, 'GGY', 'Guernsey', 'Guernsey'),
(832, 'JEY', 'Jersey', 'Jersey'),
(833, 'IMN', 'Isle of Man', 'Isle of Man'),
(834, 'TZA', 'Tanzania', 'Tanzania, United Republic of'),
(840, 'USA', 'United States', 'United States'),
(850, 'VIR', 'Virgin Islands, U.S.', 'Virgin Islands, U.S.'),
(854, 'BFA', 'Burkina Faso', 'Burkina Faso'),
(858, 'URY', 'Uruguay', 'Uruguay'),
(860, 'UZB', 'Uzbekistan', 'Uzbekistan'),
(862, 'VEN', 'Venezuela', 'Venezuela'),
(876, 'WLF', 'Wallis and Futuna', 'Wallis and Futuna'),
(882, 'WSM', 'Samoa', 'Samoa'),
(887, 'YEM', 'Yemen', 'Yemen'),
(894, 'ZMB', 'Zambia', 'Zambia');

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

  `hersteller` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,

  `besitzer` varchar(255) NOT NULL,
  `details` varchar(500) NOT NULL,
  `zusatzinfo` varchar(50) NOT NULL,
  `lagerort` varchar(255) NOT NULL,
  `kiste` int(20) NOT NULL,
  `ist_leihartikel` tinyint(1) NOT NULL,
  `ausleihe` tinyint(1) NOT NULL,
  `ist_kiste` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=206 ;

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
-- Table structure for table `project_equipment_lagerort`
--

CREATE TABLE IF NOT EXISTS `project_equipment_lagerort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(100) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=674 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1364 ;

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

--
-- Dumping data for table `project_ticket_prio`
--

INSERT INTO `project_ticket_prio` (`id`, `name`) VALUES
(1, '1 sehr niedrig'),
(2, '2 niedrig'),
(3, '3 normal'),
(4, '4 hoch'),
(5, '5 sehr hoch');

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_queue`
--

CREATE TABLE IF NOT EXISTS `project_ticket_queue` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `project_ticket_queue`
--

INSERT INTO `project_ticket_queue` (`id`, `name`) VALUES
(2, 'Allgemein'),
(3, 'Bezahlung'),
(4, 'Catering'),
(5, 'Sitzplatz'),
(6, 'Technik'),
(7, 'Turnier'),
(8, 'Internet'),
(9, 'VIP-PlÃ¤tze');

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_sperre`
--

CREATE TABLE IF NOT EXISTS `project_ticket_sperre` (
  `sperre_id` int(1) NOT NULL AUTO_INCREMENT,
  `sperre_name` tinytext NOT NULL,
  PRIMARY KEY (`sperre_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `project_ticket_sperre`
--

INSERT INTO `project_ticket_sperre` (`sperre_id`, `sperre_name`) VALUES
(1, 'frei'),
(2, 'In Bearbeitung');

-- --------------------------------------------------------

--
-- Table structure for table `project_ticket_status`
--

CREATE TABLE IF NOT EXISTS `project_ticket_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `project_ticket_status`
--

INSERT INTO `project_ticket_status` (`id`, `name`) VALUES
(1, 'erfolglos geschlossen'),
(2, 'erfolgreich geschlossen'),
(3, 'offen'),
(4, 'warten zur Erinnerung');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=333 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_prio`
--

CREATE TABLE IF NOT EXISTS `project_todo_prio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `project_todo_prio`
--

INSERT INTO `project_todo_prio` (`id`, `bezeichnung`) VALUES
(1, '1 sehr niedrig'),
(2, '2 niedrig'),
(3, '3 normal'),
(4, '4 hoch'),
(5, '5 sehr hoch');

-- --------------------------------------------------------

--
-- Table structure for table `project_todo_status`
--

CREATE TABLE IF NOT EXISTS `project_todo_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `project_todo_status`
--

INSERT INTO `project_todo_status` (`id`, `bezeichnung`) VALUES
(1, '0%'),
(2, '10%'),
(3, '20%'),
(4, '30%'),
(5, '40%'),
(6, '50%'),
(7, '60%'),
(8, '70%'),
(9, '80%'),
(10, '90%'),
(11, '100%');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

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
