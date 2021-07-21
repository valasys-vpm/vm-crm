-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2021 at 02:04 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vm_campaign_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `v_mail_campaign_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campaign_type_id` int(10) UNSIGNED NOT NULL,
  `campaign_filter_id` int(10) UNSIGNED NOT NULL,
  `campaign_status` enum('1','2','3','4','5') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Live,2-Paused,3-Cancelled,4-Delivered,5-Reactivated',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_filters`
--

CREATE TABLE `campaign_filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_types`
--

CREATE TABLE `campaign_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaign_types`
--

INSERT INTO `campaign_types` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 'TA', '1', '2021-03-09 12:01:29', '2021-03-09 12:01:29', NULL),
(8, 'INT', '1', '2021-03-09 12:33:28', '2021-03-09 12:56:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `permission_id` int(10) UNSIGNED DEFAULT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'json array',
  `action` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `user_id`, `permission_id`, `value`, `action`, `created_at`) VALUES
(1, 1, 3, '{\"id\":19,\"message\":\"Created new Role - Developer\"}', 'Created new role', '2021-03-04 15:39:46'),
(5, 1, 4, '{\"id\":19,\"data\":{\"name\":{\"new\":\"PHP Developer\",\"old\":\"Developer\"},\"status\":{\"new\":\"0\",\"old\":\"1\"}},\"message\":\"Updated Role - PHP Developer<br>Updated Fields are:<br>- Name: from <b>Developer<\\/b> to <b>PHP Developer<\\/b><br>- Status: from <b>Active<\\/b> to <b>Inactive<\\/b>\"}', 'Updated role', '2021-03-04 15:57:48'),
(7, 1, 5, '{\"id\":\"19\",\"message\":\"Deleted Role - PHP Developer\"}', 'Deleted Role', '2021-03-04 16:04:00'),
(13, 1, 11, '{\"id\":8,\"data\":{\"role_id\":{\"new\":\"3\",\"old\":1},\"email\":{\"new\":\"ibrahim@valaysys.com\",\"old\":\"ibrahim1@valaysys.com\"},\"status\":{\"new\":\"0\",\"old\":\"1\"},\"last_name\":{\"new\":\"Shaikh\",\"old\":\"Shaikhh\"},\"reporting_manager_id\":{\"new\":\"7\",\"old\":1}},\"message\":\"Updated User - Ibrahim Ibrahim<br>Updated Fields are:<br>- Role: from <b>Admin<\\/b> to <b>BDE<\\/b><br>- Email: from <b>ibrahim1@valaysys.com<\\/b> to <b>ibrahim@valaysys.com<\\/b><br>- Status: from <b>Active<\\/b> to <b>Inactive<\\/b><br>- Last Name: from <b>Shaikhh<\\/b> to <b>Shaikh<\\/b><br>- Reporting Manager: from <b>Admin User<\\/b> to <b>Alam Inamdar<\\/b>\"}', 'Updated user', '2021-03-05 11:05:26'),
(15, 1, 14, '{\"id\":\"Nw==\",\"message\":\"Logout User - Alam Inamdar(vbs051)\"}', 'Logout User', '2021-03-05 11:27:32'),
(16, 1, 14, '{\"id\":\"Nw==\",\"message\":\"Logout User - Alam Inamdar(VBS051)\"}', 'Logout User', '2021-03-05 11:28:27'),
(17, 1, 14, '{\"id\":\"Nw==\",\"message\":\"Logout User - Alam Inamdar (VBS051)\"}', 'Logout User', '2021-03-05 11:28:45'),
(18, 1, 14, '{\"id\":\"Nw==\",\"message\":\"Logout User - Alam Inamdar (VBS051)\"}', 'Logout User', '2021-03-05 11:29:13'),
(19, 7, 3, '{\"id\":20,\"message\":\"Created new Role - Designer\"}', 'Created new role', '2021-03-05 11:31:06'),
(20, 1, 11, '{\"id\":5,\"data\":{\"status\":{\"new\":\"1\",\"old\":\"0\"}},\"message\":\"Updated User - Sagar Sagar<br>Updated Fields are:<br>- Status: from <b>Inactive<\\/b> to <b>Active<\\/b>\"}', 'Updated user', '2021-03-05 12:46:10'),
(21, 1, 11, '{\"id\":8,\"data\":{\"role_id\":{\"new\":\"20\",\"old\":3},\"status\":{\"new\":\"1\",\"old\":\"0\"}},\"message\":\"Updated User - Ibrahim Ibrahim<br>Updated Fields are:<br>- Role: from <b>BDE<\\/b> to <b>Designer<\\/b><br>- Status: from <b>Inactive<\\/b> to <b>Active<\\/b>\"}', 'Updated user', '2021-03-05 12:46:25'),
(22, 1, 3, '{\"id\":21,\"message\":\"Created new Role - BDM\"}', 'Created new role', '2021-03-05 13:12:47'),
(23, 1, 12, '{\"id\":\"8\",\"message\":\"Deleted User - Ibrahim Shaikh\"}', 'Deleted User', '2021-03-05 15:05:33'),
(24, 1, 14, '{\"id\":\"Nw==\",\"message\":\"Logout User - Alam Inamdar (VBS051)\"}', 'Logout User', '2021-03-05 15:07:23'),
(27, 1, 23, '{\"id\":7,\"message\":\"Created new Campaign Type - TA\"}', 'Created new campaign type', '2021-03-09 12:01:29'),
(28, 1, 23, '{\"id\":8,\"message\":\"Created new Campaign Type - INT\"}', 'Created new campaign type', '2021-03-09 12:33:28'),
(29, 1, 24, '{\"id\":8,\"data\":{\"name\":{\"new\":\"INTT\",\"old\":\"INT\"}},\"message\":\"Updated Campaign Type - INTT<br>Updated Fields are:<br>- Name: from <b>INT<\\/b> to <b>INTT<\\/b>\"}', 'Updated campaign type', '2021-03-09 12:54:05'),
(30, 1, 24, '{\"id\":8,\"data\":{\"name\":{\"new\":\"INT\",\"old\":\"INTT\"},\"status\":{\"new\":\"0\",\"old\":\"1\"}},\"message\":\"Updated Campaign Type - INT<br>Updated Fields are:<br>- Name: from <b>INTT<\\/b> to <b>INT<\\/b><br>- Status: from <b>Active<\\/b> to <b>Inactive<\\/b>\"}', 'Updated campaign type', '2021-03-09 12:54:25'),
(31, 1, 24, '{\"id\":8,\"data\":{\"status\":{\"new\":\"1\",\"old\":\"0\"}},\"message\":\"Updated Campaign Type - INT<br>Updated Fields are:<br>- Status: from <b>Inactive<\\/b> to <b>Active<\\/b>\"}', 'Updated campaign type', '2021-03-09 12:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_02_26_151623_create_roles_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2021_02_26_153755_create_permissions_table', 1),
(5, '2021_02_26_154255_create_role_permissions_table', 1),
(6, '2021_03_02_142325_create_user_details_table', 2),
(7, '2021_03_04_124646_create_histories_table', 3),
(8, '2021_03_08_205618_create_campaign_types_table', 4),
(9, '2021_03_08_205732_create_campaign_filters_table', 4),
(10, '2021_03_08_205753_create_campaigns_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_visibility` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '1-Yes, 0-No',
  `priority` int(11) NOT NULL,
  `is_module` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `parent_id`, `route`, `icon`, `sidebar_visibility`, `priority`, `is_module`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dashboard', 'dashboard_browse', NULL, 'dashboard', 'feather icon-home', '1', 1, '1', '1', '2021-03-01 11:50:41', '2021-03-01 11:50:41', NULL),
(2, 'Role Management', 'role_management_browse', NULL, 'role', 'feather icon-lock', '1', 1, '1', '1', '2021-03-01 12:10:19', '2021-03-01 12:10:19', NULL),
(3, 'Create', 'role_management_create', 2, 'role.create', 'feather icon-plus', '0', 1, '0', '1', '2021-03-01 12:10:19', '2021-03-01 12:10:19', NULL),
(4, 'Edit', 'role_management_edit', 2, 'role.edit', 'feather icon-edit', '0', 1, '0', '1', '2021-03-01 12:10:19', '2021-03-01 12:10:19', NULL),
(5, 'Delete', 'role_management_delete', 2, 'role.destroy', 'feather icon-trash-2', '0', 1, '0', '1', '2021-03-01 12:10:19', '2021-03-01 12:10:19', NULL),
(6, 'Mudule Management', 'module_management_browse', NULL, 'permission', 'feather icon-list', '1', 1, '1', '0', '2021-03-01 12:10:19', '2021-03-01 12:10:19', NULL),
(8, 'Manage Permission', 'role_management_manage_permission', 2, 'role.manage_permission', 'feather icon-settings', '0', 1, '0', '1', '2021-03-01 12:10:19', '2021-03-01 12:10:19', NULL),
(9, 'User Management', 'user_management_browse', NULL, 'user', 'feather icon-users', '1', 1, '1', '1', '2021-03-02 09:15:21', '2021-03-02 09:15:21', NULL),
(10, 'Create', 'user_management_create', 9, 'user.create', 'feather icon-plus', '0', 3, '0', '1', '2021-03-02 09:15:21', '2021-03-02 09:15:21', NULL),
(11, 'Edit', 'user_management_edit', 9, 'user.edit', 'feather icon-edit', '0', 4, '0', '1', '2021-03-02 09:15:21', '2021-03-02 09:15:21', NULL),
(12, 'Delete', 'user_management_delete', 9, 'user.destroy', 'feather icon-trash-2', '0', 5, '0', '1', '2021-03-02 09:15:21', '2021-03-02 09:15:21', NULL),
(13, 'View', 'user_management_view', 9, 'user.show', 'feather icon-eye', '0', 2, '0', '1', '2021-03-02 09:15:21', '2021-03-02 09:15:21', NULL),
(14, 'Force Logout', 'user_management_logout_force', 9, 'user.logout.force', 'feather icon-logout', '0', 2, '0', '1', '2021-03-02 09:15:21', '2021-03-02 09:15:21', NULL),
(15, 'History', 'history_browse', NULL, 'history', 'feather icon-list', '1', 1, '1', '1', '2021-03-01 11:50:41', '2021-03-01 11:50:41', NULL),
(16, 'Campaign Management', 'campaign_management_browse', NULL, 'campaign', 'feather icon-book', '1', 1, '1', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(17, 'Create', 'campaign_management_create', 16, 'campaign.create', 'feather icon-plus', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(18, 'Edit', 'campaign_management_edit', 16, 'campaign.edit', 'feather icon-edit', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(19, 'Delete', 'campaign_management_delete', 16, 'campaign.destroy', 'feather icon-trash', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(20, 'View Details', 'campaign_management_view', 16, 'campaign.show', 'feather icon-eye', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(22, 'Campaign Type Management', 'campaign_type_browse', 16, 'campaign_type', 'feather icon-settings', '1', 1, '1', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(23, 'Create', 'campaign_type_create', 22, 'campaign_type.create', 'feather icon-plus', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(24, 'Edit', 'campaign_type_edit', 22, 'campaign_type.edit', 'feather icon-edit', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(25, 'Delete', 'campaign_type_delete', 22, 'campaign_type.destroy', 'feather icon-trash', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(26, 'Campaign Filter Management', 'campaign_filter_browse', 16, 'campaign_filter', 'feather icon-settings', '1', 1, '1', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(27, 'Create', 'campaign_filter_create', 26, 'campaign_filter.create', 'feather icon-plus', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(28, 'Edit', 'campaign_filter_edit', 26, 'campaign_filter.edit', 'feather icon-edit', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL),
(29, 'Delete', 'campaign_filter_delete', 26, 'campaign_filter.destroy', 'feather icon-trash', '0', 1, '0', '1', '2021-03-08 13:31:53', '2021-03-08 13:31:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', '1', '2021-02-26 16:00:39', '2021-02-26 16:00:39', NULL),
(2, 'Manager', '1', '2021-03-01 09:24:43', '2021-03-02 09:36:03', NULL),
(3, 'BDE', '1', '2021-03-03 09:42:42', '2021-03-03 09:42:42', NULL),
(19, 'PHP Developer', '0', '2021-03-04 15:39:46', '2021-03-04 16:04:00', '2021-03-04 16:04:00'),
(20, 'Designer', '1', '2021-03-05 11:31:06', '2021-03-05 11:31:06', NULL),
(21, 'BDM', '1', '2021-03-05 13:12:47', '2021-03-05 13:12:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(347, 2, 1, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(348, 2, 2, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(349, 2, 3, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(350, 2, 4, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(351, 2, 5, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(352, 2, 8, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(353, 2, 9, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(354, 2, 10, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(355, 2, 11, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(356, 2, 12, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(357, 2, 13, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(358, 2, 14, '2021-03-03 16:41:42', '2021-03-03 16:41:42'),
(620, 1, 1, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(621, 1, 2, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(622, 1, 3, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(623, 1, 4, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(624, 1, 5, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(625, 1, 8, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(626, 1, 9, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(627, 1, 10, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(628, 1, 11, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(629, 1, 12, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(630, 1, 13, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(631, 1, 14, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(632, 1, 15, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(633, 1, 16, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(634, 1, 17, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(635, 1, 18, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(636, 1, 19, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(637, 1, 20, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(638, 1, 22, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(639, 1, 23, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(640, 1, 24, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(641, 1, 25, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(642, 1, 26, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(643, 1, 27, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(644, 1, 28, '2021-03-09 12:31:17', '2021-03-09 12:31:17'),
(645, 1, 29, '2021-03-09 12:31:17', '2021-03-09 12:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logged_on` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `email`, `email_verified_at`, `password`, `logged_on`, `remember_token`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'admin@valasys.com', '2021-02-26 10:30:39', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2021-03-09 18:33:31', 'jvC1fxmpRtkfS2wRB8dKfOcp8ACdHmOEeMPudNJKBUe2WAMQ0CPS8ToYyJty', '1', '2021-02-26 16:00:39', '2021-03-09 13:03:31', NULL),
(5, 2, 'sagar@valasys.com', NULL, '$2y$10$loSRn8Q0548Fr6P9QwPErOewi0cdTYb26xy9Q4P5afpc5fjBJq8n6', NULL, NULL, '1', '2021-03-02 10:22:09', '2021-03-05 12:46:10', NULL),
(6, 2, 'sagar1@valasys.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2021-03-03 16:32:59', NULL, '1', '2021-03-03 05:11:07', '2021-03-03 11:02:59', NULL),
(7, 1, 'alam@valasys.com', NULL, '$2y$10$0zxghWA1bLju/.BaWXBhBOeOjNKIZMvj/gCsV4X2F/AKJdSds3I8m', '2021-03-05 22:08:20', NULL, '1', '2021-03-03 09:00:44', '2021-03-05 16:38:20', NULL),
(8, 20, 'ibrahim@valaysys.com', NULL, '$2y$10$Z69cV0ymQYpWYYDa.EzhIunCFUdlzt4w7H35EckYIx47z.oNP1kqm', NULL, NULL, '1', '2021-03-05 10:37:18', '2021-03-09 12:54:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporting_manager_id` int(10) UNSIGNED DEFAULT NULL,
  `profile_picture` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `first_name`, `last_name`, `emp_code`, `department`, `designation`, `reporting_manager_id`, `profile_picture`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Admin', 'User', 'VBS000', 'Administration', 'Administrator', NULL, NULL, '2021-03-02 15:19:07', '2021-03-03 07:15:05', NULL),
(2, 5, 'Sagar', 'Patil', 'VBS001', 'Development', 'PHP Developer', 1, NULL, '2021-03-02 10:22:09', '2021-03-02 11:13:13', NULL),
(3, 6, 'Sagar', 'Patil', 'VBS002', 'Development', 'PHP Developer', 1, NULL, '2021-03-03 05:11:07', '2021-03-03 05:15:56', NULL),
(4, 7, 'Alam', 'Inamdar', 'vbs051', 'Development', 'PHP Developer', NULL, NULL, '2021-03-03 09:00:44', '2021-03-03 09:00:44', NULL),
(5, 8, 'Ibrahim', 'Shaikh', 'VBS0056', 'Creative', 'Graphic Designer', 7, NULL, '2021-03-05 10:37:18', '2021-03-05 11:05:26', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_campaign_type_id_foreign` (`campaign_type_id`),
  ADD KEY `campaigns_campaign_filter_id_foreign` (`campaign_filter_id`);

--
-- Indexes for table `campaign_filters`
--
ALTER TABLE `campaign_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_types`
--
ALTER TABLE `campaign_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `histories_user_id_foreign` (`user_id`),
  ADD KEY `histories_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_details_user_id_foreign` (`user_id`),
  ADD KEY `user_details_reporting_manager_id_foreign` (`reporting_manager_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_filters`
--
ALTER TABLE `campaign_filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_types`
--
ALTER TABLE `campaign_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=646;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_campaign_filter_id_foreign` FOREIGN KEY (`campaign_filter_id`) REFERENCES `campaign_filters` (`id`),
  ADD CONSTRAINT `campaigns_campaign_type_id_foreign` FOREIGN KEY (`campaign_type_id`) REFERENCES `campaign_types` (`id`);

--
-- Constraints for table `histories`
--
ALTER TABLE `histories`
  ADD CONSTRAINT `histories_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_reporting_manager_id_foreign` FOREIGN KEY (`reporting_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
