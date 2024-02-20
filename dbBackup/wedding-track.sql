-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2024 at 11:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wedding-track`
--

-- --------------------------------------------------------

--
-- Table structure for table `check_ins`
--

CREATE TABLE `check_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guest_invitation_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `check_ins`
--

INSERT INTO `check_ins` (`id`, `guest_invitation_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-02-20 22:20:36', '2024-02-20 22:20:36'),
(2, 3, '2024-02-20 22:20:36', '2024-02-20 22:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `guest_invitations`
--

CREATE TABLE `guest_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guest_id` varchar(255) NOT NULL,
  `unique_identifier` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guest_invitations`
--

INSERT INTO `guest_invitations` (`id`, `guest_id`, `unique_identifier`, `name`, `company_name`, `gender`, `created_at`, `updated_at`) VALUES
(1, '101', '764240', 'Abu Motaleb', 'Dbug Station Limited', 'Male', '2024-02-20 22:17:29', '2024-02-20 22:17:29'),
(2, '102', '703263', 'Bokul Khan', 'Saidpur Dev', 'Male', '2024-02-20 22:17:57', '2024-02-20 22:17:57'),
(3, '103', '959131', 'Arfa', 'Sylhet Tech', 'Female', '2024-02-20 22:20:05', '2024-02-20 22:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_02_16_215618_create_check_ins_table', 2),
(6, '2024_02_16_215547_create_guest_invitations_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_ins_guest_invitation_id_foreign` (`guest_invitation_id`);

--
-- Indexes for table `guest_invitations`
--
ALTER TABLE `guest_invitations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guest_invitations`
--
ALTER TABLE `guest_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `check_ins_guest_invitation_id_foreign` FOREIGN KEY (`guest_invitation_id`) REFERENCES `guest_invitations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
