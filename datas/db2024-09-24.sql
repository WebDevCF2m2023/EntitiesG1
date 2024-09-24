-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 24 sep. 2024 à 13:28
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `entitiesg1`
--
CREATE DATABASE IF NOT EXISTS `entitiesg1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `entitiesg1`;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
                                         `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                         `post_id` int UNSIGNED NOT NULL,
                                         `comment_text` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
    `comment_date` datetime NOT NULL,
    `comment_visible` tinyint(1) NOT NULL DEFAULT '0',
    `user_id` int UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `IDX_9474526C4B89032C` (`post_id`),
    KEY `IDX_9474526CA76ED395` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
                                                                                           ('DoctrineMigrations\\Version20240920075128', '2024-09-20 09:52:13', 5931),
                                                                                           ('DoctrineMigrations\\Version20240920082923', '2024-09-20 10:29:35', 3629);

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
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
                                      `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                      `post_title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
    `post_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `post_date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `post_date_published` datetime DEFAULT NULL,
    `post_is_published` tinyint(1) NOT NULL DEFAULT '0',
    `user_id` int UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `IDX_5A8A6C8DA76ED395` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post_section`
--

DROP TABLE IF EXISTS `post_section`;
CREATE TABLE IF NOT EXISTS `post_section` (
                                              `post_id` int UNSIGNED NOT NULL,
                                              `section_id` int UNSIGNED NOT NULL,
                                              PRIMARY KEY (`post_id`,`section_id`),
    KEY `IDX_109BCDDC4B89032C` (`post_id`),
    KEY `IDX_109BCDDCD823E37A` (`section_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post_tag`
--

DROP TABLE IF EXISTS `post_tag`;
CREATE TABLE IF NOT EXISTS `post_tag` (
                                          `post_id` int UNSIGNED NOT NULL,
                                          `tag_id` int UNSIGNED NOT NULL,
                                          PRIMARY KEY (`post_id`,`tag_id`),
    KEY `IDX_5ACE3AF04B89032C` (`post_id`),
    KEY `IDX_5ACE3AF0BAD26311` (`tag_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
                                         `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                         `section_title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
    `section_description` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
                                     `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                     `tag_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
    `tag_slug` varchar(65) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
                                      `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                      `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
    `roles` json NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_mail` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_real_name` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `user_active` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `user_mail`, `user_real_name`, `user_active`) VALUES
                                                                                                             (1, 'adminLee', '[\"ROLE_ADMIN\", \"ROLE_REDAC\", \"ROLE_USER\"]', '$2y$13$x5jTbanQl82Oqrw0VgzYd.M8FYd4AU6wlbXl6S2Ro3/sbI3ElxT4.', 'lee@lee.com', NULL, 1),
                                                                                                             (2, 'redacGuy', '[\"ROLE_REDAC\", \"ROLE_USER\"]', '$2y$13$RBBocZYh.pXk3KzdvbbpmeM9dCm4/72sdIsyiOyQgNM4vbvoKvCC2', 'guy@guy.com', NULL, 1),
                                                                                                             (3, 'userEr', '[]', '$2y$13$bGOqPi0zpU/M4qAhbIe5jO1ESN8Yh5GixvN8pRrCb3BUoEtMz3V8.', 'er@er.be', NULL, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
    ADD CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
    ADD CONSTRAINT `FK_5A8A6C8DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `post_section`
--
ALTER TABLE `post_section`
    ADD CONSTRAINT `FK_109BCDDC4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_109BCDDCD823E37A` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post_tag`
--
ALTER TABLE `post_tag`
    ADD CONSTRAINT `FK_5ACE3AF04B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5ACE3AF0BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
