SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
CREATE SCHEMA IF NOT EXISTS `cvtheque` DEFAULT CHARACTER SET utf8mb4;
USE `cvtheque`;

-- --------------------------------------------------------

--
-- Table `cvtheque`.`role`
--
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `role` (`id`, `name`) VALUES
(1, 'student'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Table `cvtheque`.`country`
--
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `country` (`id`, `name`) VALUES
(1, 'Afghanistan'),
(2, 'Afrique du Sud'),
(3, 'Albanie'),
(4, 'Algérie'),
(5, 'Allemagne'),
(6, 'Andorre'),
(7, 'Angola'),
(8, 'Anguilla'),
(9, 'Antarctique'),
(10, 'Antigua-et-Barbuda'),
(11, 'Antilles néerlandaises'),
(12, 'Arabie saoudite'),
(13, 'Argentine'),
(14, 'Arménie'),
(15, 'Aruba'),
(16, 'Australie'),
(17, 'Autriche'),
(18, 'Azerbaïdjan'),
(19, 'Bahamas'),
(20, 'Bahreïn'),
(21, 'Bangladesh'),
(22, 'Barbade'),
(23, 'Bélarus'),
(24, 'Belgique'),
(25, 'Belize'),
(26, 'Bénin'),
(27, 'Bermudes'),
(28, 'Bhoutan'),
(29, 'Bolivie'),
(30, 'Bosnie-Herzégovine'),
(31, 'Botswana'),
(32, 'Brésil'),
(33, 'Brunéi Darussalam'),
(34, 'Bulgarie'),
(35, 'Burkina Faso'),
(36, 'Burundi'),
(37, 'Cambodge'),
(38, 'Cameroun'),
(39, 'Canada'),
(40, 'Cap-Vert'),
(41, 'Ceuta et Melilla'),
(42, 'Chili'),
(43, 'Chine'),
(44, 'Chypre'),
(45, 'Colombie'),
(46, 'Comores'),
(47, 'Congo-Brazzaville'),
(48, 'Corée du Nord'),
(49, 'Corée du Sud'),
(50, 'Costa Rica'),
(51, 'Côte d’Ivoire'),
(52, 'Croatie'),
(53, 'Cuba'),
(54, 'Danemark'),
(55, 'Diego Garcia'),
(56, 'Djibouti'),
(57, 'Dominique'),
(58, 'Égypte'),
(59, 'El Salvador'),
(60, 'Émirats arabes unis'),
(61, 'Équateur'),
(62, 'Érythrée'),
(63, 'Espagne'),
(64, 'Estonie'),
(65, 'État de la Cité du Vatican'),
(66, 'États fédérés de Micronésie'),
(67, 'États-Unis'),
(68, 'Éthiopie'),
(69, 'Fidji'),
(70, 'Finlande'),
(71, 'France'),
(72, 'Gabon'),
(73, 'Gambie'),
(74, 'Géorgie'),
(75, 'Géorgie du Sud et les îles Sandwich du Sud'),
(76, 'Ghana'),
(77, 'Gibraltar'),
(78, 'Grèce'),
(79, 'Grenade'),
(80, 'Groenland'),
(81, 'Guadeloupe'),
(82, 'Guam'),
(83, 'Guatemala'),
(84, 'Guernesey'),
(85, 'Guinée'),
(86, 'Guinée équatoriale'),
(87, 'Guinée-Bissau'),
(88, 'Guyana'),
(89, 'Guyane française'),
(90, 'Haïti'),
(91, 'Honduras'),
(92, 'Hongrie'),
(93, 'Île Bouvet'),
(94, 'Île Christmas'),
(95, 'Île Clipperton'),
(96, 'Île de l\'Ascension'),
(97, 'Île de Man'),
(98, 'Île Norfolk'),
(99, 'Îles Åland'),
(100, 'Îles Caïmans'),
(101, 'Îles Canaries'),
(102, 'Îles Cocos - Keeling'),
(103, 'Îles Cook'),
(104, 'Îles Féroé'),
(105, 'Îles Heard et MacDonald'),
(106, 'Îles Malouines'),
(107, 'Îles Mariannes du Nord'),
(108, 'Îles Marshall'),
(109, 'Îles Mineures Éloignées des États-Unis'),
(110, 'Îles Salomon'),
(111, 'Îles Turks et Caïques'),
(112, 'Îles Vierges britanniques'),
(113, 'Îles Vierges des États-Unis'),
(114, 'Inde'),
(115, 'Indonésie'),
(116, 'Irak'),
(117, 'Iran'),
(118, 'Irlande'),
(119, 'Islande'),
(120, 'Israël'),
(121, 'Italie'),
(122, 'Jamaïque'),
(123, 'Japon'),
(124, 'Jersey'),
(125, 'Jordanie'),
(126, 'Kazakhstan'),
(127, 'Kenya'),
(128, 'Kirghizistan'),
(129, 'Kiribati'),
(130, 'Koweït'),
(131, 'Laos'),
(132, 'Lesotho'),
(133, 'Lettonie'),
(134, 'Liban'),
(135, 'Libéria'),
(136, 'Libye'),
(137, 'Liechtenstein'),
(138, 'Lituanie'),
(139, 'Luxembourg'),
(140, 'Macédoine'),
(141, 'Madagascar'),
(142, 'Malaisie'),
(143, 'Malawi'),
(144, 'Maldives'),
(145, 'Mali'),
(146, 'Malte'),
(147, 'Maroc'),
(148, 'Martinique'),
(149, 'Maurice'),
(150, 'Mauritanie'),
(151, 'Mayotte'),
(152, 'Mexique'),
(153, 'Moldavie'),
(154, 'Monaco'),
(155, 'Mongolie'),
(156, 'Monténégro'),
(157, 'Montserrat'),
(158, 'Mozambique'),
(159, 'Myanmar'),
(160, 'Namibie'),
(161, 'Nauru'),
(162, 'Népal'),
(163, 'Nicaragua'),
(164, 'Niger'),
(165, 'Nigéria'),
(166, 'Niue'),
(167, 'Norvège'),
(168, 'Nouvelle-Calédonie'),
(169, 'Nouvelle-Zélande'),
(170, 'Oman'),
(171, 'Ouganda'),
(172, 'Ouzbékistan'),
(173, 'Pakistan'),
(174, 'Palaos'),
(175, 'Panama'),
(176, 'Papouasie-Nouvelle-Guinée'),
(177, 'Paraguay'),
(178, 'Pays-Bas'),
(179, 'Pérou'),
(180, 'Philippines'),
(181, 'Pitcairn'),
(182, 'Pologne'),
(183, 'Polynésie française'),
(184, 'Porto Rico'),
(185, 'Portugal'),
(186, 'Qatar'),
(187, 'R.A.S. chinoise de Hong Kong'),
(188, 'R.A.S. chinoise de Macao'),
(189, 'régions éloignées de l’Océanie'),
(190, 'République centrafricaine'),
(191, 'République démocratique du Congo'),
(192, 'République dominicaine'),
(193, 'République tchèque'),
(194, 'Réunion'),
(195, 'Roumanie'),
(196, 'Royaume-Uni'),
(197, 'Russie'),
(198, 'Rwanda'),
(199, 'Sahara occidental'),
(200, 'Saint-Barthélémy'),
(201, 'Saint-Kitts-et-Nevis'),
(202, 'Saint-Marin'),
(203, 'Saint-Martin'),
(204, 'Saint-Pierre-et-Miquelon'),
(205, 'Saint-Vincent-et-les Grenadines'),
(206, 'Sainte-Hélène'),
(207, 'Sainte-Lucie'),
(208, 'Samoa'),
(209, 'Samoa américaines'),
(210, 'Sao Tomé-et-Principe'),
(211, 'Sénégal'),
(212, 'Serbie'),
(213, 'Serbie-et-Monténégro'),
(214, 'Seychelles'),
(215, 'Sierra Leone'),
(216, 'Singapour'),
(217, 'Slovaquie'),
(218, 'Slovénie'),
(219, 'Somalie'),
(220, 'Soudan'),
(221, 'Sri Lanka'),
(222, 'Suède'),
(223, 'Suisse'),
(224, 'Suriname'),
(225, 'Svalbard et Île Jan Mayen'),
(226, 'Swaziland'),
(227, 'Syrie'),
(228, 'Tadjikistan'),
(229, 'Taïwan'),
(230, 'Tanzanie'),
(231, 'Tchad'),
(232, 'Terres australes françaises'),
(233, 'Territoire britannique de l\'océan Indien'),
(234, 'Territoire palestinien'),
(235, 'Thaïlande'),
(236, 'Timor oriental'),
(237, 'Togo'),
(238, 'Tokelau'),
(239, 'Tonga'),
(240, 'Trinité-et-Tobago'),
(241, 'Tristan da Cunha'),
(242, 'Tunisie'),
(243, 'Turkménistan'),
(244, 'Turquie'),
(245, 'Tuvalu'),
(246, 'Ukraine'),
(247, 'Union européenne'),
(248, 'Uruguay'),
(249, 'Vanuatu'),
(250, 'Venezuela'),
(251, 'Viêt Nam'),
(252, 'Wallis-et-Futuna'),
(253, 'Yémen'),
(254, 'Zambie'),
(255, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table `cvtheque`.`user`
--
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pwd` varchar(256) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 1,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `job_title` longtext DEFAULT NULL,
  `picture` longtext DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `driver_licence` tinyint(4) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_user_role_idx` (`role_id`),
  KEY `fk_user_country1_idx` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` (`id`, `pwd`, `email`, `role_id`, `firstname`, `lastname`, `job_title`, `picture`, `phone`, `driver_licence`, `username`, `country_id`, `is_active`) VALUES
(1, 'admin', 'admin@cvtheque.com', 3, 'Super', 'Admin', 'Administrateur', NULL, '', NULL, 'admin', NULL, 1),
(2, 'student', 'student@cvtheque.com', 1, 'John', 'Student', 'Étudiant', NULL, '', NULL, 'student', NULL, 1);

-- --------------------------------------------------------

--
-- Table `cvtheque`.`address`
--
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `street_number` int(11) DEFAULT NULL,
  `street_name` longtext DEFAULT NULL,
  `area_code` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`, `user_id`),
  KEY `fk_address_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table `cvtheque`.`education`
--
CREATE TABLE IF NOT EXISTS `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `current_studies` varchar(100) DEFAULT NULL,
  `diploma` varchar(100) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table `cvtheque`.`experience`
--
CREATE TABLE IF NOT EXISTS `experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobtitle` longtext DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`, `user_id`),
  KEY `fk_experience_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table `cvtheque`.`skills`
--
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hard_skills` longtext DEFAULT NULL,
  `soft_skills` longtext DEFAULT NULL,
  `hobbies` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table `cvtheque`.`user_has_education`
--
CREATE TABLE IF NOT EXISTS `user_has_education` (
  `user_id` int(11) NOT NULL,
  `education_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`education_id`),
  KEY `fk_user_has_education_education1_idx` (`education_id`),
  KEY `fk_user_has_education_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table `cvtheque`.`user_has_skills`
--
CREATE TABLE IF NOT EXISTS `user_has_skills` (
  `user_id` int(11) NOT NULL,
  `skills_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`skills_id`),
  KEY `fk_user_has_skills_skills1_idx` (`skills_id`),
  KEY `fk_user_has_skills_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `address`
  ADD CONSTRAINT `fk_address_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `experience`
  ADD CONSTRAINT `fk_experience_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_has_education`
  ADD CONSTRAINT `fk_user_has_education_education1` FOREIGN KEY (`education_id`) REFERENCES `education` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_has_education_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_has_skills`
  ADD CONSTRAINT `fk_user_has_skills_skills1` FOREIGN KEY (`skills_id`) REFERENCES `skills` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_has_skills_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;