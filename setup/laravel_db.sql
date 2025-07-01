-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 01 juil. 2025 à 16:03
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `laravel_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `property_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reduction` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(8,2) NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_property_id_foreign` (`property_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `property_id`, `start_date`, `end_date`, `reduction`, `total_price`, `status`, `note`, `created_at`, `updated_at`) VALUES
(16, 1, 7, '2025-07-16', '2025-07-19', 0.00, 750.00, 'confirmed', 'dall.dev', '2025-07-01 11:54:11', '2025-07-01 11:54:11'),
(18, 1, 6, '2025-07-01', '2025-07-02', 0.00, 4500.00, 'pending', '0612345678 pour un soir merci', '2025-07-01 12:06:23', '2025-07-01 12:06:23'),
(19, 1, 7, '2025-07-05', '2025-07-16', 0.00, 2750.00, 'pending', 'contact.dall.dev', '2025-07-01 12:09:18', '2025-07-01 12:09:18'),
(15, 3, 7, '2025-07-01', '2025-07-05', 0.00, 2000.00, 'pending', 'Recontactez moi au 0612345678', '2025-06-30 12:40:53', '2025-06-30 12:40:53'),
(11, 1, 6, '2025-07-02', '2025-08-07', 0.00, 162000.00, 'pending', '0612345678 Pour une grosse fête merci', '2025-06-27 21:48:05', '2025-07-01 12:40:33'),
(22, 1, 7, '2025-07-20', '2025-07-25', 0.00, 1250.00, 'pending', '0612345678 Vacances', '2025-07-01 12:20:17', '2025-07-01 12:20:17');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2025_06_27_123831_create_properties_table', 1),
(4, '2025_06_27_123856_create_bookings_table', 1),
(5, '2025_06_27_205511_add_status_to_bookings_table', 1),
(6, '2025_06_27_212016_add_notes_to_bookings_table', 1),
(7, '2025_06_27_213731_alter_note_column_in_bookings_table', 1),
(8, '2025_06_27_232230_add_reduction_to_bookings_table', 1),
(9, '2025_07_01_135341_update_reduction_default_in_bookings_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_night` decimal(8,2) NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `properties`
--

INSERT INTO `properties` (`id`, `name`, `description`, `city`, `price_per_night`, `image`, `created_at`, `updated_at`) VALUES
(6, 'Villa', 'Villa avec piscine au bord de la plage', 'Nice', 4500.00, 'properties/01JZ38HTS62F4XYYZ7M36SX6YE.jpg', '2025-06-27 19:43:25', '2025-07-01 13:09:11'),
(7, 'Bungalow', 'Bungalow avec piscine', 'Martinique', 250.00, 'properties/01JYVDCX6J8SXYVVJA1QARSEMA.jpg', '2025-06-28 11:59:57', '2025-06-28 11:59:57'),
(8, 'Chalet', 'Chalet de luxe', 'Suisse', 500.00, 'properties/01JYVDDMRCX3EVN8R7MSA3DXHA.webp', '2025-06-28 12:00:21', '2025-06-28 12:00:21');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', NULL, '$2y$12$kIjSw.jBtR2R7VVLMLufZu78qws.Gb1XoBOOJBOL0zysJm2h0R6Ce', 'UNP0HpBZtGDsJktcq6bPZIrHD78nzBaPipy7mu7SUOVyExYxJikBiqXSGqNy', '2025-06-27 11:39:30', '2025-06-27 11:39:30'),
(3, 'client', 'client@client.com', NULL, '$2y$12$EieA5pjvXACb8Go5pvDNcOfBHzshnhWG/pa7UkEvCkOfg5dsLqQAW', NULL, '2025-06-28 13:06:43', '2025-06-28 13:06:43');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
