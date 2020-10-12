-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 11 juin 2020 à 18:44
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `mydb`
--

use mydb;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` longtext DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_user1_idx` (`user_id`),
  KEY `fk_comment_video1_idx` (`video_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `body`, `user_id`, `video_id`) VALUES
(1, 'Comment for video id 6', 3, 6),
(2, 'Comment for video id 7', 3, 7);

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(320) NOT NULL,
  `expired_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `fk_token_user1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `pseudo` varchar(45) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `pseudo`, `password`, `created_at`, `updated_at`) VALUES
(3, 'test1', 'test1@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(4, 'toto1', 'test3@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(5, 'test4', 'test4@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(6, 'toto', 'test5@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(7, 'test6', 'test6@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(8, 'test7', 'test7@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(9, 'test8', 'test8@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(10, 'test9', 'test9@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00'),
(11, 'test10', 'test10@test.com', NULL, '$2y$10$I3uh1SfTvsbbj1ocz7E08OxXFUiBnc5l73TT.dJAhaEzQXSaPQYZa', '2020-06-04 15:47:05', '2020-06-08 16:49:00');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `view` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_video_user_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `name`, `duration`, `user_id`, `source`, `created_at`, `view`, `enabled`) VALUES
(3, 'video_test', NULL, 3, 'C:\\Users\\richine\\Desktop\\API\\group-779678\\myAPI\\public\\video\\1591882162.mp4', '2020-06-11 15:29:22', 0, 0),
(6, 'boshi', NULL, 6, 'C:\\Users\\richine\\Desktop\\API\\group-779678\\myAPI\\public\\video\\1591885613.mp4', '2020-06-11 16:26:53', 0, 0),
(7, 'sisi', NULL, 5, 'C:\\Users\\richine\\Desktop\\API\\group-779678\\myAPI\\public\\video\\1591885635.mp4', '2020-06-11 16:27:15', 0, 0),
(8, 'bien ou bien', NULL, 6, 'C:\\Users\\richine\\Desktop\\API\\group-779678\\myAPI\\public\\video\\1591885642.mp4', '2020-06-11 16:27:22', 0, 0),
(9, 'clair', NULL, 3, 'C:\\Users\\richine\\Desktop\\API\\group-779678\\myAPI\\public\\video\\1591885650.mp4', '2020-06-11 16:27:30', 0, 0),
(10, 'clair', NULL, 3, 'C:\\Users\\richine\\Desktop\\API\\group-779678\\myAPI\\public\\video\\1591885686.mp4', '2020-06-11 16:28:06', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `video_format`
--

DROP TABLE IF EXISTS `video_format`;
CREATE TABLE IF NOT EXISTS `video_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `uri` varchar(45) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_id` (`video_id`),
  KEY `fk_video_format_video1_idx` (`video_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `video_format`
--

INSERT INTO `video_format` (`id`, `code`, `uri`, `video_id`) VALUES
(9, '1080', '/tmp/xxxxxx', 6);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comment_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_token_user1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `fk_video_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `video_format`
--
ALTER TABLE `video_format`
  ADD CONSTRAINT `fk_video_format_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
