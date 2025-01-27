-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 27 jan. 2025 à 19:03
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `vivarium_php`
--

-- --------------------------------------------------------

--
-- Structure de la table `serpent`
--

DROP TABLE IF EXISTS `serpent`;
CREATE TABLE IF NOT EXISTS `serpent` (
  `srp_id` int NOT NULL AUTO_INCREMENT,
  `srp_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `srp_weight` float NOT NULL,
  `srp_life_expect` int NOT NULL,
  `srp_birthdate` datetime NOT NULL,
  `srp_race` varchar(255) NOT NULL,
  `srp_sexe` enum('Mâle','Femelle') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `srp_mom` int DEFAULT NULL,
  `srp_dad` int DEFAULT NULL,
  `srp_alive` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'vivant (1) ou mort (0)',
  PRIMARY KEY (`srp_id`),
  KEY `srp_mere` (`srp_mom`),
  KEY `srp_pere` (`srp_dad`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `serpent`
--

INSERT INTO `serpent` (`srp_id`, `srp_name`, `srp_weight`, `srp_life_expect`, `srp_birthdate`, `srp_race`, `srp_sexe`, `srp_mom`, `srp_dad`, `srp_alive`) VALUES
(79, 'Offspring_67961afe39b57', 2.6, 19, '2025-01-26 11:22:38', 'Anaconda', 'Mâle', NULL, NULL, 1),
(80, 'Offspring_67961afe3a13b', 2.6, 19, '2025-01-26 11:22:38', 'Anaconda', 'Mâle', NULL, NULL, 1),
(81, 'Offspring_67961c15188c3', 10.7, 20, '2025-01-26 11:27:17', 'Anaconda', 'Mâle', NULL, 79, 1),
(82, 'Offspring_67961c1518eda', 10.7, 20, '2025-01-26 11:27:17', 'Anaconda', 'Femelle', NULL, 79, 1),
(83, 'Offspring_67961c1519485', 10.7, 20, '2025-01-26 11:27:17', 'Anaconda', 'Mâle', NULL, 79, 1),
(84, 'Offspring_67961c15199e7', 10.7, 20, '2025-01-26 11:27:17', 'Anaconda', 'Femelle', NULL, 79, 1),
(85, 'Offspring_67961cde983f6', 10.7, 20, '2025-01-26 11:30:38', 'Anaconda', 'Mâle', NULL, 83, 1),
(86, 'Offspring_67961cde98a0a', 10.7, 20, '2025-01-26 11:30:38', 'Anaconda', 'Femelle', NULL, 83, 1),
(87, 'Offspring_67961cde99131', 10.7, 20, '2025-01-26 11:30:38', 'Anaconda', 'Mâle', NULL, 83, 1),
(88, 'Offspring_67961e865700f', 10.7, 20, '2025-01-26 11:37:42', 'Anaconda', 'Femelle', 84, 85, 1),
(89, 'Offspring_67961e8657577', 10.7, 20, '2025-01-26 11:37:42', 'Anaconda', 'Femelle', 84, 85, 1),
(90, 'Offspring_67961e86579c9', 10.7, 20, '2025-01-26 11:37:42', 'Anaconda', 'Mâle', 84, 85, 1),
(91, 'Offspring_67961e86582b4', 10.7, 20, '2025-01-26 11:37:42', 'Anaconda', 'Femelle', 84, 85, 1),
(92, 'Offspring_67961e86589d5', 10.7, 20, '2025-01-26 11:37:42', 'Anaconda', 'Mâle', 84, 85, 1),
(93, 'Offspring_67961e8a5f1d9', 10.7, 20, '2025-01-26 11:37:46', 'Anaconda', 'Mâle', 91, 92, 1),
(94, 'Offspring_67961e8e5218d', 10.7, 20, '2025-01-26 11:37:50', 'Anaconda', 'Mâle', 91, 93, 1),
(95, 'Offspring_67961e8e52762', 10.7, 20, '2025-01-26 11:37:50', 'Anaconda', 'Mâle', 91, 93, 1),
(96, 'Offspring_67961e8e52bcb', 10.7, 20, '2025-01-26 11:37:50', 'Anaconda', 'Femelle', 91, 93, 1),
(97, 'Offspring_67961e8e5324c', 10.7, 20, '2025-01-26 11:37:50', 'Anaconda', 'Femelle', 91, 93, 1),
(98, 'Offspring_67961e8e5372e', 10.7, 20, '2025-01-26 11:37:50', 'Anaconda', 'Mâle', 91, 93, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `serpent`
--
ALTER TABLE `serpent`
  ADD CONSTRAINT `serpent_ibfk_1` FOREIGN KEY (`srp_mom`) REFERENCES `serpent` (`srp_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `serpent_ibfk_2` FOREIGN KEY (`srp_dad`) REFERENCES `serpent` (`srp_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
