-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: May 30, 2019 at 10:56 AM
-- Server version: 5.6.40
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `learn_every_day`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_posts`
--

CREATE TABLE `app_posts` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `ingress` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `content` blob,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `publish_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Truncate table before insert `app_posts`
--

TRUNCATE TABLE `app_posts`;
--
-- Dumping data for table `app_posts`
--

INSERT INTO `app_posts` (`id`, `uuid`, `user_id`, `status`, `title`, `ingress`, `content`, `deleted`, `publish_date`, `updated`, `created`) VALUES
(1, '', 1, 1, 'Test 1', 'Ingress 1', NULL, 0, '2019-05-30 06:55:54', '2019-05-30 06:55:54', '0000-00-00 00:00:00'),
(2, '601f82d2-9304-4368-a412-1a657f287a66', 1, 1, 'dsa', '', 0x647361, 0, '0000-00-00 00:00:00', '2019-05-30 08:02:32', '0000-00-00 00:00:00'),
(3, '0eef3615-3888-438d-aa5c-cc42c2557d41', 1, 1, 'dsa', '', 0x64617373, 0, '0000-00-00 00:00:00', '2019-05-30 08:07:18', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

CREATE TABLE `app_users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(63) COLLATE utf8mb4_bin NOT NULL,
  `user_type_id` tinyint(1) NOT NULL DEFAULT '3',
  `username` varchar(63) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(124) COLLATE utf8mb4_bin NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `auth_token` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `auth_token_expire` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `auth_refresh_token` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `country_id` tinyint(1) NOT NULL DEFAULT '1',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Truncate table before insert `app_users`
--

TRUNCATE TABLE `app_users`;
--
-- Dumping data for table `app_users`
--

INSERT INTO `app_users` (`id`, `uuid`, `user_type_id`, `username`, `password`, `first_name`, `last_name`, `address`, `zip_code`, `city`, `email`, `phone`, `disabled`, `banned`, `auth_token`, `auth_token_expire`, `auth_refresh_token`, `country_id`, `updated`, `created`) VALUES
(1, '519f5295-ae49-4704-9297-8213609ece22', 3, 'admin@led.com', '$2y$10$lZbNvbZHjLYKt1hDyBOJMuBM/lai/2fl4/.stNlkU/iMRPd8czAfu', 'Mikael', 'Nilsson', '', '', '', 'admin@led.com', '', 0, 0, '897129c71cc6634126758a6e93a3105c71e5b8c32216899f19b169e5b46c1d64339da0f2e7b988e9c0ae635832205cdc049ea7eab0cc350c18ec4206b0d03f31', '2019-06-01 20:06:59', '3242e2992a8dde3ad29f4c91c396dd1c16261d803e9a2f774a73b5e943e62a043685cec76180a7c3f9200aa807e972cc45d1003ea506e490f1e0457bdc7ea1d8', 1, '2019-05-29 20:28:11', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `app_user_type`
--

CREATE TABLE `app_user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Truncate table before insert `app_user_type`
--

TRUNCATE TABLE `app_user_type`;
--
-- Dumping data for table `app_user_type`
--

INSERT INTO `app_user_type` (`id`, `name`) VALUES
(1, 'superadmin'),
(2, 'admin'),
(3, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_posts`
--
ALTER TABLE `app_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uuid` (`uuid`(191));

--
-- Indexes for table `app_users`
--
ALTER TABLE `app_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_user_type`
--
ALTER TABLE `app_user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_posts`
--
ALTER TABLE `app_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `app_users`
--
ALTER TABLE `app_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `app_user_type`
--
ALTER TABLE `app_user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
