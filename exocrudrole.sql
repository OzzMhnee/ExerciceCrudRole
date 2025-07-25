-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 25 juil. 2025 à 09:49
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `exocrudrole`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250723140152', '2025-07-25 07:31:24', 45),
('DoctrineMigrations\\Version20250723141835', '2025-07-25 07:31:24', 10),
('DoctrineMigrations\\Version20250723183935', '2025-07-25 07:31:25', 6);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `prenom`, `nom`, `photo`) VALUES
(1, 'ABBA@abba.fr', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$b1LpN1M6UGCONHKMi.uWheWk27xi878zKiZi7y/ijWh.mpBY58oCy', 'BA', 'AB', 'img/688334274a39a.png'),
(2, 'CAAC@caac.fr', '[\"ROLE_USER\"]', '$2y$13$PWFCboeDzKJ0eJTGq5pSWOspTaz0m8whpFJSPISDQCxFscV53sfuy', 'AC', 'CA', 'img/6883342db0ec7.png'),
(3, 'DAAD@daad.fr', '[\"ROLE_USER\"]', '$2y$13$KT9DCaSToqVlMdFsfOpEt.LNwvIg.SP0ogOyRFx8jph2/myf5SHa6', 'AD', 'DA', 'img/68833432e1466.png'),
(4, 'ACCA@acca.fr', '[\"ROLE_USER\"]', '$2y$13$lSvDmPfp1uZWPpOPfa601uTFn2yyqEZPpKwKdfcdGa3phehcP/th.', 'CA', 'AC', 'img/68833b960609d.png'),
(5, 'ADDA@adda.fr', '[\"ROLE_USER\"]', '$2y$13$GZGhcN/wbzWN0b4f0xVW6.AtqpPSXx1RCWBiYePYVbmc9OK2NwNcW', 'DA', 'AD', 'img/68833ba46d360.png'),
(6, 'AEEA@aeea.fr', '[\"ROLE_USER\"]', '$2y$13$gheAfycrncbiD4dzLMzZf.fBpX60fRR4a5n2hrXpA4cJnqaw3IZwC', 'EA', 'AE', 'img/68833c78b95c5.png'),
(7, 'EAAE@eaae.fr', '[\"ROLE_USER\"]', '$2y$13$hTr/IuFcLrNZp5AaejuQa.iNRu3uAQhecm6Ne/tNC/CGxzNN4N7Oq', 'AE', 'EA', 'img/68833c81a3108.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
