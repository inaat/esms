-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2023 at 06:17 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swatcollnew`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `default_campus_account` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type_id` int(11) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `system_settings_id`, `campus_id`, `default_campus_account`, `name`, `account_number`, `account_type_id`, `note`, `created_by`, `is_closed`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Main CampusFee collector', '58666', NULL, NULL, 1, 0, NULL, '2022-01-01 11:23:45', '2022-01-01 11:23:45');

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `type` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_type` enum('opening_balance','fund_transfer','deposit','debit') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(22,4) NOT NULL,
  `reff_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operation_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `expense_transaction_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `hrm_transaction_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `transfer_transaction_id` int(11) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_account_type_id` int(11) DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `parent_account_type_id`, `system_settings_id`, `created_at`, `updated_at`) VALUES
(4, 'inayat', NULL, 1, '2021-10-17 04:15:00', '2021-10-17 04:15:00'),
(5, 'sss', 4, 1, '2021-10-18 16:23:47', '2021-10-18 16:23:47'),
(6, 'Assest', NULL, 1, '2021-12-21 07:02:09', '2021-12-21 07:02:09'),
(7, 'Cash', 6, 1, '2021-12-21 07:02:28', '2021-12-21 07:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('present','late','absent','half_day','holiday','weekend','leave') COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `clock_in_time` datetime DEFAULT NULL,
  `clock_out_time` datetime DEFAULT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_out_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`id`, `title`, `description`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '555', NULL, 1, 1, NULL, '2021-10-27 17:06:16', '2021-10-27 17:06:16');

-- --------------------------------------------------------

--
-- Table structure for table `barcodes`
--

CREATE TABLE `barcodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` double(22,4) DEFAULT NULL,
  `height` double(22,4) DEFAULT NULL,
  `paper_width` double(22,4) DEFAULT NULL,
  `paper_height` double(22,4) DEFAULT NULL,
  `top_margin` double(22,4) DEFAULT NULL,
  `left_margin` double(22,4) DEFAULT NULL,
  `row_distance` double(22,4) DEFAULT NULL,
  `col_distance` double(22,4) DEFAULT NULL,
  `stickers_in_one_row` int(11) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_continuous` tinyint(1) NOT NULL DEFAULT 0,
  `stickers_in_one_sheet` int(11) DEFAULT NULL,
  `business_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcodes`
--

INSERT INTO `barcodes` (`id`, `name`, `description`, `width`, `height`, `paper_width`, `paper_height`, `top_margin`, `left_margin`, `row_distance`, `col_distance`, `stickers_in_one_row`, `is_default`, `is_continuous`, `stickers_in_one_sheet`, `business_id`, `created_at`, `updated_at`) VALUES
(7, 'Gatsby', NULL, 31.0000, 24.0000, 70.6000, 28.0000, 0.0000, 1.0000, 0.0000, 5.0000, 2, 1, 0, 2147483647, 1, '2020-12-10 13:51:35', '2020-12-13 15:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `campuses`
--

CREATE TABLE `campuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campuses`
--

INSERT INTO `campuses` (`id`, `campus_name`, `mobile`, `phone`, `email`, `address`, `registration_code`, `registration_date`, `system_settings_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Main Campus', '03428927305', '03469487994', NULL, 'Swat PAkistan', '58666', '2022-01-01', 1, 1, '2022-01-01 11:23:45', '2022-03-16 03:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `cat_name`, `description`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'RBS', 'RBS', 1, 1, NULL, '2022-01-19 10:02:37', '2022-01-19 10:02:37'),
(2, 'Free Orphan', 'Free Orphan', 1, 1, NULL, '2022-01-19 10:03:10', '2022-01-19 10:03:10'),
(3, 'Free (Teacher Son)', 'Free (Teacher Son)', 1, 1, NULL, '2022-01-19 10:03:34', '2022-01-19 10:03:34'),
(4, 'Normal Tuition Fee', 'Normal Tuition Fee', 1, 1, NULL, '2022-01-19 10:04:54', '2022-01-19 10:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_issues`
--

CREATE TABLE `certificate_issues` (
  `id` int(10) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `certificate_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_type_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_types`
--

CREATE TABLE `certificate_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `school_logo` tinyint(4) NOT NULL DEFAULT 0,
  `qrcode` tinyint(4) NOT NULL DEFAULT 0,
  `builtin` tinyint(4) NOT NULL DEFAULT 0,
  `school_name` tinyint(4) NOT NULL DEFAULT 0,
  `footer_left_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_middle_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_right_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificate_types`
--

INSERT INTO `certificate_types` (`id`, `name`, `description`, `background_image`, `status`, `school_logo`, `qrcode`, `builtin`, `school_name`, `footer_left_text`, `footer_middle_text`, `footer_right_text`, `created_at`, `updated_at`) VALUES
(1, 'SLC Certificate', '_', '', 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL),
(2, 'Birth Certificate', '--', '', 1, 1, 1, 1, 1, NULL, NULL, NULL, '2022-04-30 12:39:14', '2022-04-30 12:39:14'),
(3, 'Character Certificate', '--', '', 1, 1, 1, 1, 1, NULL, NULL, NULL, '2022-04-30 12:39:14', '2022-04-30 12:39:14'),
(4, 'Provisional Certificate', '--', '', 1, 1, 1, 1, 1, NULL, NULL, NULL, '2022-04-30 12:39:14', '2022-04-30 12:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `province_id` int(10) UNSIGNED NOT NULL,
  `district_id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `country_id`, `province_id`, `district_id`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Khwaza Khela', 91, 1, 1, 1, 1, NULL, '2021-10-23 19:20:48', '2021-10-23 19:37:07'),
(2, 'Matta', 91, 1, 1, 1, 1, NULL, '2021-10-23 19:22:29', '2021-10-23 19:22:29'),
(3, 'Mingora', 91, 1, 1, 1, 1, NULL, '2021-10-23 19:23:33', '2021-10-23 19:23:33'),
(4, '111', 91, 1, 1, 1, 1, NULL, NULL, NULL),
(5, 'Charbagh', 91, 1, 1, 1, 1, NULL, '2022-01-10 06:45:39', '2022-01-10 06:45:39');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tuition_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `admission_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `transport_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `security_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `prospectus_fee` decimal(22,4) DEFAULT 0.0000,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `class_level_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_levels`
--

CREATE TABLE `class_levels` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_levels`
--

INSERT INTO `class_levels` (`id`, `title`, `description`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Primary', NULL, 1, 1, NULL, '2022-01-01 18:03:43', '2022-01-01 18:03:43'),
(2, 'Middle', NULL, 1, 1, NULL, '2022-01-28 14:27:33', '2022-01-28 14:27:33'),
(3, 'High', NULL, 1, 1, NULL, '2022-01-28 14:27:44', '2022-01-28 14:27:44'),
(4, 'Higher Secondary', NULL, 1, 1, NULL, '2022-01-28 14:31:03', '2022-01-28 14:31:03'),
(5, 'Pre-Primary', NULL, 1, 1, NULL, '2022-05-16 08:36:06', '2022-05-16 08:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `section_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `whatsapp_group_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theory_mark` int(11) NOT NULL DEFAULT 0,
  `parc_mark` int(11) NOT NULL DEFAULT 0,
  `total` int(11) NOT NULL DEFAULT 0,
  `passing_percentage` tinyint(4) NOT NULL,
  `subject_input` enum('eng','ur') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_lessons`
--

CREATE TABLE `class_subject_lessons` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chapter_id` int(10) NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_progress`
--

CREATE TABLE `class_subject_progress` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED DEFAULT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `lesson_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `teacher_by` int(10) UNSIGNED DEFAULT NULL,
  `session_id` int(10) UNSIGNED DEFAULT NULL,
  `chapter_id` int(10) NOT NULL,
  `status` enum('completed','pending','reading') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `reading_date` date DEFAULT NULL,
  `complete_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_timetables`
--

CREATE TABLE `class_timetables` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED DEFAULT NULL,
  `multi_subject_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_id` int(10) DEFAULT NULL,
  `multi_teacher` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_id` int(10) UNSIGNED NOT NULL,
  `other` enum('drill','nazira','written','oral','nazira_drill','spoken','religious_study','religious_study_spoken') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_timetable_periods`
--

CREATE TABLE `class_timetable_periods` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_time` decimal(5,2) DEFAULT NULL,
  `type` enum('prayer_time','study_period','lunch_break') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'study_period',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thousand_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency`, `code`, `short_name`, `symbol`, `thousand_separator`, `decimal_separator`, `created_at`, `updated_at`) VALUES
(1, 'Albania', 'Leke', 'ALL', NULL, 'Lek', ',', '.', NULL, NULL),
(2, 'America', 'Dollars', 'USD', NULL, '$', ',', '.', NULL, NULL),
(3, 'Afghanistan', 'Afghanis', 'AF', NULL, '؋', ',', '.', NULL, NULL),
(4, 'Argentina', 'Pesos', 'ARS', NULL, '$', ',', '.', NULL, NULL),
(5, 'Aruba', 'Guilders', 'AWG', NULL, 'ƒ', ',', '.', NULL, NULL),
(6, 'Australia', 'Dollars', 'AUD', NULL, '$', ',', '.', NULL, NULL),
(7, 'Azerbaijan', 'New Manats', 'AZ', NULL, 'ман', ',', '.', NULL, NULL),
(8, 'Bahamas', 'Dollars', 'BSD', NULL, '$', ',', '.', NULL, NULL),
(9, 'Barbados', 'Dollars', 'BBD', NULL, '$', ',', '.', NULL, NULL),
(10, 'Belarus', 'Rubles', 'BYR', NULL, 'p.', ',', '.', NULL, NULL),
(11, 'Belgium', 'Euro', 'EUR', NULL, '€', ',', '.', NULL, NULL),
(12, 'Beliz', 'Dollars', 'BZD', NULL, 'BZ$', ',', '.', NULL, NULL),
(13, 'Bermuda', 'Dollars', 'BMD', NULL, '$', ',', '.', NULL, NULL),
(14, 'Bolivia', 'Bolivianos', 'BOB', NULL, '$b', ',', '.', NULL, NULL),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', NULL, 'KM', ',', '.', NULL, NULL),
(16, 'Botswana', 'Pula\'s', 'BWP', NULL, 'P', ',', '.', NULL, NULL),
(17, 'Bulgaria', 'Leva', 'BG', NULL, 'лв', ',', '.', NULL, NULL),
(18, 'Brazil', 'Reais', 'BRL', NULL, 'R$', ',', '.', NULL, NULL),
(19, 'Britain [United Kingdom]', 'Pounds', 'GBP', NULL, '£', ',', '.', NULL, NULL),
(20, 'Brunei Darussalam', 'Dollars', 'BND', NULL, '$', ',', '.', NULL, NULL),
(21, 'Cambodia', 'Riels', 'KHR', NULL, '៛', ',', '.', NULL, NULL),
(22, 'Canada', 'Dollars', 'CAD', NULL, '$', ',', '.', NULL, NULL),
(23, 'Cayman Islands', 'Dollars', 'KYD', NULL, '$', ',', '.', NULL, NULL),
(24, 'Chile', 'Pesos', 'CLP', NULL, '$', ',', '.', NULL, NULL),
(25, 'China', 'Yuan Renminbi', 'CNY', NULL, '¥', ',', '.', NULL, NULL),
(26, 'Colombia', 'Pesos', 'COP', NULL, '$', ',', '.', NULL, NULL),
(27, 'Costa Rica', 'Colón', 'CRC', NULL, '₡', ',', '.', NULL, NULL),
(28, 'Croatia', 'Kuna', 'HRK', NULL, 'kn', ',', '.', NULL, NULL),
(29, 'Cuba', 'Pesos', 'CUP', NULL, '₱', ',', '.', NULL, NULL),
(30, 'Cyprus', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(31, 'Czech Republic', 'Koruny', 'CZK', NULL, 'Kč', ',', '.', NULL, NULL),
(32, 'Denmark', 'Kroner', 'DKK', NULL, 'kr', ',', '.', NULL, NULL),
(33, 'Dominican Republic', 'Pesos', 'DOP ', NULL, 'RD$', ',', '.', NULL, NULL),
(34, 'East Caribbean', 'Dollars', 'XCD', NULL, '$', ',', '.', NULL, NULL),
(35, 'Egypt', 'Pounds', 'EGP', NULL, '£', ',', '.', NULL, NULL),
(36, 'El Salvador', 'Colones', 'SVC', NULL, '$', ',', '.', NULL, NULL),
(37, 'England [United Kingdom]', 'Pounds', 'GBP', NULL, '£', ',', '.', NULL, NULL),
(38, 'Euro', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(39, 'Falkland Islands', 'Pounds', 'FKP', NULL, '£', ',', '.', NULL, NULL),
(40, 'Fiji', 'Dollars', 'FJD', NULL, '$', ',', '.', NULL, NULL),
(41, 'France', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(42, 'Ghana', 'Cedis', 'GHS', NULL, '¢', ',', '.', NULL, NULL),
(43, 'Gibraltar', 'Pounds', 'GIP', NULL, '£', ',', '.', NULL, NULL),
(44, 'Greece', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(45, 'Guatemala', 'Quetzales', 'GTQ', NULL, 'Q', ',', '.', NULL, NULL),
(46, 'Guernsey', 'Pounds', 'GGP', NULL, '£', ',', '.', NULL, NULL),
(47, 'Guyana', 'Dollars', 'GYD', NULL, '$', ',', '.', NULL, NULL),
(48, 'Holland [Netherlands]', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(49, 'Honduras', 'Lempiras', 'HNL', NULL, 'L', ',', '.', NULL, NULL),
(50, 'Hong Kong', 'Dollars', 'HKD', NULL, '$', ',', '.', NULL, NULL),
(51, 'Hungary', 'Forint', 'HUF', NULL, 'Ft', ',', '.', NULL, NULL),
(52, 'Iceland', 'Kronur', 'ISK', NULL, 'kr', ',', '.', NULL, NULL),
(53, 'India', 'Rupees', 'INR', NULL, '₹', ',', '.', NULL, NULL),
(54, 'Indonesia', 'Rupiahs', 'IDR', NULL, 'Rp', ',', '.', NULL, NULL),
(55, 'Iran', 'Rials', 'IRR', NULL, '﷼', ',', '.', NULL, NULL),
(56, 'Ireland', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(57, 'Isle of Man', 'Pounds', 'IMP', NULL, '£', ',', '.', NULL, NULL),
(58, 'Israel', 'New Shekels', 'ILS', NULL, '₪', ',', '.', NULL, NULL),
(59, 'Italy', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(60, 'Jamaica', 'Dollars', 'JMD', NULL, 'J$', ',', '.', NULL, NULL),
(61, 'Japan', 'Yen', 'JPY', NULL, '¥', ',', '.', NULL, NULL),
(62, 'Jersey', 'Pounds', 'JEP', NULL, '£', ',', '.', NULL, NULL),
(63, 'Kazakhstan', 'Tenge', 'KZT', NULL, 'лв', ',', '.', NULL, NULL),
(64, 'Korea [North]', 'Won', 'KPW', NULL, '₩', ',', '.', NULL, NULL),
(65, 'Korea [South]', 'Won', 'KRW', NULL, '₩', ',', '.', NULL, NULL),
(66, 'Kyrgyzstan', 'Soms', 'KGS', NULL, 'лв', ',', '.', NULL, NULL),
(67, 'Laos', 'Kips', 'LAK', NULL, '₭', ',', '.', NULL, NULL),
(68, 'Latvia', 'Lati', 'LVL', NULL, 'Ls', ',', '.', NULL, NULL),
(69, 'Lebanon', 'Pounds', 'LBP', NULL, '£', ',', '.', NULL, NULL),
(70, 'Liberia', 'Dollars', 'LRD', NULL, '$', ',', '.', NULL, NULL),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', NULL, 'CHF', ',', '.', NULL, NULL),
(72, 'Lithuania', 'Litai', 'LTL', NULL, 'Lt', ',', '.', NULL, NULL),
(73, 'Luxembourg', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(74, 'Macedonia', 'Denars', 'MKD', NULL, 'ден', ',', '.', NULL, NULL),
(75, 'Malaysia', 'Ringgits', 'MYR', NULL, 'RM', ',', '.', NULL, NULL),
(76, 'Malta', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(77, 'Mauritius', 'Rupees', 'MUR', NULL, '₨', ',', '.', NULL, NULL),
(78, 'Mexico', 'Pesos', 'MXN', NULL, '$', ',', '.', NULL, NULL),
(79, 'Mongolia', 'Tugriks', 'MNT', NULL, '₮', ',', '.', NULL, NULL),
(80, 'Mozambique', 'Meticais', 'MZ', NULL, 'MT', ',', '.', NULL, NULL),
(81, 'Namibia', 'Dollars', 'NAD', NULL, '$', ',', '.', NULL, NULL),
(82, 'Nepal', 'Rupees', 'NPR', NULL, '₨', ',', '.', NULL, NULL),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', NULL, 'ƒ', ',', '.', NULL, NULL),
(84, 'Netherlands', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(85, 'New Zealand', 'Dollars', 'NZD', NULL, '$', ',', '.', NULL, NULL),
(86, 'Nicaragua', 'Cordobas', 'NIO', NULL, 'C$', ',', '.', NULL, NULL),
(87, 'Nigeria', 'Nairas', 'NGN', NULL, '₦', ',', '.', NULL, NULL),
(88, 'North Korea', 'Won', 'KPW', NULL, '₩', ',', '.', NULL, NULL),
(89, 'Norway', 'Krone', 'NOK', NULL, 'kr', ',', '.', NULL, NULL),
(90, 'Oman', 'Rials', 'OMR', NULL, '﷼', ',', '.', NULL, NULL),
(91, 'Pakistan', 'Rupees', 'PKR', NULL, '₨', ',', '.', NULL, NULL),
(92, 'Panama', 'Balboa', 'PAB', NULL, 'B/.', ',', '.', NULL, NULL),
(93, 'Paraguay', 'Guarani', 'PYG', NULL, 'Gs', ',', '.', NULL, NULL),
(94, 'Peru', 'Nuevos Soles', 'PE', NULL, 'S/.', ',', '.', NULL, NULL),
(95, 'Philippines', 'Pesos', 'PHP', NULL, 'Php', ',', '.', NULL, NULL),
(96, 'Poland', 'Zlotych', 'PL', NULL, 'zł', ',', '.', NULL, NULL),
(97, 'Qatar', 'Rials', 'QAR', NULL, '﷼', ',', '.', NULL, NULL),
(98, 'Romania', 'New Lei', 'RO', NULL, 'lei', ',', '.', NULL, NULL),
(99, 'Russia', 'Rubles', 'RUB', NULL, 'руб', ',', '.', NULL, NULL),
(100, 'Saint Helena', 'Pounds', 'SHP', NULL, '£', ',', '.', NULL, NULL),
(101, 'Saudi Arabia', 'Riyals', 'SAR', NULL, '﷼', ',', '.', NULL, NULL),
(102, 'Serbia', 'Dinars', 'RSD', NULL, 'Дин.', ',', '.', NULL, NULL),
(103, 'Seychelles', 'Rupees', 'SCR', NULL, '₨', ',', '.', NULL, NULL),
(104, 'Singapore', 'Dollars', 'SGD', NULL, '$', ',', '.', NULL, NULL),
(105, 'Slovenia', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(106, 'Solomon Islands', 'Dollars', 'SBD', NULL, '$', ',', '.', NULL, NULL),
(107, 'Somalia', 'Shillings', 'SOS', NULL, 'S', ',', '.', NULL, NULL),
(108, 'South Africa', 'Rand', 'ZAR', NULL, 'R', ',', '.', NULL, NULL),
(109, 'South Korea', 'Won', 'KRW', NULL, '₩', ',', '.', NULL, NULL),
(110, 'Spain', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(111, 'Sri Lanka', 'Rupees', 'LKR', NULL, '₨', ',', '.', NULL, NULL),
(112, 'Sweden', 'Kronor', 'SEK', NULL, 'kr', ',', '.', NULL, NULL),
(113, 'Switzerland', 'Francs', 'CHF', NULL, 'CHF', ',', '.', NULL, NULL),
(114, 'Suriname', 'Dollars', 'SRD', NULL, '$', ',', '.', NULL, NULL),
(115, 'Syria', 'Pounds', 'SYP', NULL, '£', ',', '.', NULL, NULL),
(116, 'Taiwan', 'New Dollars', 'TWD', NULL, 'NT$', ',', '.', NULL, NULL),
(117, 'Thailand', 'Baht', 'THB', NULL, '฿', ',', '.', NULL, NULL),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', NULL, 'TT$', ',', '.', NULL, NULL),
(119, 'Turkey', 'Lira', 'TRY', NULL, 'TL', ',', '.', NULL, NULL),
(120, 'Turkey', 'Liras', 'TRL', NULL, '£', ',', '.', NULL, NULL),
(121, 'Tuvalu', 'Dollars', 'TVD', NULL, '$', ',', '.', NULL, NULL),
(122, 'Ukraine', 'Hryvnia', 'UAH', NULL, '₴', ',', '.', NULL, NULL),
(123, 'United Kingdom', 'Pounds', 'GBP', NULL, '£', ',', '.', NULL, NULL),
(124, 'United States of America', 'Dollars', 'USD', NULL, '$', ',', '.', NULL, NULL),
(125, 'Uruguay', 'Pesos', 'UYU', NULL, '$U', ',', '.', NULL, NULL),
(126, 'Uzbekistan', 'Sums', 'UZS', NULL, 'лв', ',', '.', NULL, NULL),
(127, 'Vatican City', 'Euro', 'EUR', NULL, '€', '.', ',', NULL, NULL),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', NULL, 'Bs', ',', '.', NULL, NULL),
(129, 'Vietnam', 'Dong', 'VND', NULL, '₫', ',', '.', NULL, NULL),
(130, 'Yemen', 'Rials', 'YER', NULL, '﷼', ',', '.', NULL, NULL),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', NULL, 'Z$', ',', '.', NULL, NULL),
(132, 'Iraq', 'Iraqi dinar', 'IQD', NULL, 'د.ع', ',', '.', NULL, NULL),
(133, 'Kenya', 'Kenyan shilling', 'KES', NULL, 'KSh', ',', '.', NULL, NULL),
(134, 'Bangladesh', 'Taka', 'BDT', NULL, '৳', ',', '.', NULL, NULL),
(135, 'Algerie', 'Algerian dinar', 'DZD', NULL, 'د.ج', ' ', '.', NULL, NULL),
(136, 'United Arab Emirates', 'United Arab Emirates dirham', 'AED', NULL, 'د.إ', ',', '.', NULL, NULL),
(137, 'Uganda', 'Uganda shillings', 'UGX', NULL, 'USh', ',', '.', NULL, NULL),
(138, 'Tanzania', 'Tanzanian shilling', 'TZS', NULL, 'TSh', ',', '.', NULL, NULL),
(139, 'Angola', 'Kwanza', 'AOA', NULL, 'Kz', ',', '.', NULL, NULL),
(140, 'Kuwait', 'Kuwaiti dinar', 'KWD', NULL, 'KD', ',', '.', NULL, NULL),
(141, 'Bahrain', 'Bahraini dinar', 'BHD', NULL, 'BD', ',', '.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `discount_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `discount_name`, `system_settings_id`, `campus_id`, `discount_type`, `discount_amount`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '100% Dis', 1, 1, 'percentage', '100.0000', 1, NULL, '2022-01-19 10:06:50', '2022-01-19 10:16:48'),
(2, 'Rs 100', 1, 1, 'fixed', '100.0000', 1, NULL, '2022-01-19 10:16:04', '2022-01-19 10:16:04');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `province_id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `country_id`, `province_id`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Swat', 91, 1, 1, 1, NULL, '2021-10-23 18:12:16', '2021-10-23 18:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `exam_allocations`
--

CREATE TABLE `exam_allocations` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_create_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `roll_type` enum('default_roll_no','custom_roll_no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_roll_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_mark` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `obtain_mark` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `final_percentage` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `grade_id` int(10) UNSIGNED DEFAULT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_position` int(10) UNSIGNED DEFAULT NULL,
  `class_section_position` int(10) UNSIGNED DEFAULT NULL,
  `merit_rank_in_school` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_creates`
--

CREATE TABLE `exam_creates` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_term_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `roll_no_type` enum('default_roll_no','custom_roll_no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('descending','ascending') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_from` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `class_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_date_sheets`
--

CREATE TABLE `exam_date_sheets` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_create_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `type` enum('written','oral','written_oral') COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_grades`
--

CREATE TABLE `exam_grades` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` decimal(22,1) NOT NULL DEFAULT 0.0,
  `percentage_from` int(11) NOT NULL DEFAULT 0,
  `percentage_to` int(11) NOT NULL DEFAULT 0,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_subject_results`
--

CREATE TABLE `exam_subject_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_allocation_id` int(10) UNSIGNED NOT NULL,
  `exam_create_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(10) UNSIGNED DEFAULT NULL,
  `theory_mark` int(11) NOT NULL DEFAULT 0,
  `obtain_theory_mark` int(11) NOT NULL DEFAULT 0,
  `parc_mark` int(11) NOT NULL DEFAULT 0,
  `obtain_parc_mark` int(11) NOT NULL DEFAULT 0,
  `total_mark` int(11) NOT NULL DEFAULT 0,
  `total_obtain_mark` int(11) NOT NULL DEFAULT 0,
  `is_attend` tinyint(4) DEFAULT NULL,
  `pass_percentage` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `obtain_percentage` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `grade_id` int(10) UNSIGNED DEFAULT NULL,
  `position_in_subject` int(10) UNSIGNED DEFAULT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_terms`
--

CREATE TABLE `exam_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_transactions`
--

CREATE TABLE `expense_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `type` enum('expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_category_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_status` enum('paid','due','partial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_for` int(10) UNSIGNED NOT NULL,
  `vehicle_id` int(10) UNSIGNED DEFAULT NULL,
  `ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `final_total` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `additional_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_transaction_payments`
--

CREATE TABLE `expense_transaction_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `expense_transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Used during adjustment to return the change',
  `session_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `method` enum('cash','card','cheque','bank_transfer','other','advance_pay','student_advance_amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` enum('visa','master') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_security` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `payment_for` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `payment_ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_heads`
--

CREATE TABLE `fee_heads` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `class_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_heads`
--

INSERT INTO `fee_heads` (`id`, `description`, `campus_id`, `class_id`, `amount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Admission', NULL, NULL, '500.0000', NULL, '2021-11-08 06:11:11', NULL),
(2, 'Tuition', NULL, NULL, '0.0000', NULL, '2021-11-08 01:11:11', NULL),
(3, 'Transport', NULL, NULL, '0.0000', NULL, '2021-11-08 01:11:11', NULL),
(4, 'Prospectus', NULL, NULL, '500.0000', NULL, '2021-11-08 01:11:11', NULL),
(5, 'Security', NULL, NULL, '500.0000', NULL, '2021-11-08 01:11:11', NULL),
(6, 'Exam ', 1, 1, '500.0000', NULL, NULL, NULL),
(7, 'Exam', 1, 2, '1000.0000', '2021-11-20 17:33:00', '2021-11-20 17:13:33', '2021-11-20 17:33:00'),
(8, 'Exam', 1, 2, '300.0000', NULL, '2021-11-21 05:29:57', '2022-03-24 04:39:35'),
(9, 'Exam', 1, 3, '300.0000', NULL, '2021-11-22 03:12:58', '2022-03-24 04:47:06'),
(10, 'Exam', 1, 22, '300.0000', NULL, '2022-01-27 11:25:07', '2022-01-27 11:25:07'),
(11, 'Exam', 1, 18, '300.0000', NULL, '2022-01-27 11:52:54', '2022-01-27 11:52:54'),
(12, 'Exam', 1, 14, '300.0000', NULL, '2022-01-28 13:12:25', '2022-01-28 13:12:25'),
(13, 'Exam', 1, 13, '300.0000', NULL, '2022-01-31 09:09:25', '2022-01-31 09:09:25'),
(14, 'Exam', 1, 12, '300.0000', NULL, '2022-01-31 09:36:49', '2022-01-31 09:36:49'),
(15, 'Exam', 1, 10, '300.0000', NULL, '2022-01-31 10:44:56', '2022-01-31 10:44:56'),
(16, 'Exam', 1, 7, '300.0000', NULL, '2022-01-31 11:10:30', '2022-01-31 13:13:18'),
(17, 'Exam', 1, 6, '300.0000', NULL, '2022-01-31 14:56:03', '2022-01-31 14:56:03'),
(18, 'Exam', 1, 5, '300.0000', NULL, '2022-02-01 16:45:07', '2022-02-01 16:45:07'),
(19, 'Exam', 1, 4, '300.0000', NULL, '2022-02-01 18:05:27', '2022-02-01 18:05:27'),
(20, 'Re Admission', 1, 18, '2000.0000', NULL, '2022-02-16 17:35:18', '2022-02-16 17:35:18'),
(21, 'Exam', 1, 24, '200.0000', NULL, '2022-02-28 17:34:03', '2022-02-28 17:34:03'),
(22, 'Exam', 1, 25, '200.0000', NULL, '2022-02-28 17:34:14', '2022-02-28 17:34:14'),
(23, 'Other charges', 1, 7, '0.0000', NULL, '2022-03-19 07:50:21', '2022-03-19 07:50:21'),
(24, 'Other charges', 1, 25, '0.0000', NULL, '2022-10-04 06:35:05', '2022-10-04 06:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `fee_increment_decrement`
--

CREATE TABLE `fee_increment_decrement` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED DEFAULT NULL,
  `tuition_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `transport_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_increment_decrements`
--

CREATE TABLE `fee_increment_decrements` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED DEFAULT NULL,
  `tuition_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `transport_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_transactions`
--

CREATE TABLE `fee_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `type` enum('opening_balance','fee','admission_fee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('paid','due','partial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `due_date` date DEFAULT NULL,
  `month` enum('1','2','3','4','5','6','7','8','9','10','11','12') COLLATE utf8mb4_unicode_ci NOT NULL,
  `before_discount_total` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `discount_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `final_total` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_transaction_lines`
--

CREATE TABLE `fee_transaction_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `fee_transaction_id` int(10) UNSIGNED NOT NULL,
  `fee_head_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_transaction_payments`
--

CREATE TABLE `fee_transaction_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `fee_transaction_id` int(11) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED DEFAULT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Used during adjustment to return the change',
  `discount_amount` decimal(22,4) DEFAULT 0.0000,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `method` enum('cash','card','cheque','bank_transfer','other','advance_pay','student_advance_amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` enum('visa','master') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_security` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `payment_for` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `payment_ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_about_us`
--

CREATE TABLE `front_about_us` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('publish','not_publish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_about_us`
--

INSERT INTO `front_about_us` (`id`, `slug`, `status`, `title`, `home_title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Mr. Farman Ali Khan', 'publish', 'Principal\'s Message', 'Mr. Farman Ali Khan', '<p><strong>Education is a journey to explore experiment and explain the meaning of life.</strong></p>\n<p style=\"text-align: left;\">&nbsp; Each movement unfolds into a beautiful expression o<em>f life&rsquo;s bounty in laughter, cheer, conc</em>ern and dream of our students as they move towards&nbsp; &nbsp; &nbsp; &nbsp;excellence in Education and all around development.</p>\n<p style=\"padding-left: 40px;\">Dear students, you are the central point of the whole exercise of running the school.&nbsp; We at school believe in developing a person with integrity, good values, positive attitude and strength of character, a person with strong will and determination to succeed using fair means and make the personal significant contribution towards positive growth of community, society and the nation.&nbsp; The school would help you with full opportunity to move on the path of self development &amp; success by providing you a unique teaching learning environment&nbsp; in pursuit of all round excellence.</p>\n<p style=\"padding-left: 40px;\">At Swat Collegiate School, Kalindi, We not only believe in having students&nbsp; to be trailblazer in academics but also in topping and show-casing their boundless talents.</p>\nDear students,\n\nYou are the central point of the whole exercise of running the school. We, at school believe in developing a person with integrity, good values, positive attitude and strength of character, a person with strong will and determination to succeed using fair means and make the personal significant contribution towards positive growth of community, society and the nation. Swat Collegiate School & College would help you with full opportunity to move on the path of self-development & success by providing you a unique teaching learning environment in pursuit of all round excellence. At Swat Collegiate School & College, We not only believe in having students to be trailblazer in academics but also in topping and show-casing their boundless talents.', 'speendad.jpg', '2023-01-10 13:08:34', '2023-01-10 13:08:34');

-- --------------------------------------------------------

--
-- Table structure for table `front_events`
--

CREATE TABLE `front_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('publish','not_publish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_gallery_categories`
--

CREATE TABLE `front_gallery_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_gallery_categories`
--

INSERT INTO `front_gallery_categories` (`id`, `slug`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Photo', 'Photo', '2023-01-11 15:30:13', '2023-01-11 15:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `front_gallery_contents`
--

CREATE TABLE `front_gallery_contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('publish','not_publish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `elements` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_gallery_contents`
--

INSERT INTO `front_gallery_contents` (`id`, `slug`, `thumb_image`, `category_id`, `title`, `description`, `status`, `elements`, `created_at`, `updated_at`) VALUES
(1, 'sdsf', 'silde3.jfif', 1, 'swat', 'UPDATE `front_gallery_contents` SET `thumb_image` = \'silde3.jfif\' WHERE `front_gallery_contents`.`id` = 1;\n', 'publish', '{\"1\":{\"image\":\"slide1.jfif\",\"type\":\"1\",\"video_url\":null,\"date\":\"2023-01-12 20:52:20\"}}', '2023-01-11 15:34:11', NULL),
(2, 'LAtest', 'slide2.jfif', 1, 'dsfsdf', 'UPDATE `front_gallery_contents` SET `thumb_image` = \'silde3.jfif\' WHERE `front_gallery_contents`.`id` = 1;\r\n', 'publish', '[]', '2023-01-11 15:34:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `front_news`
--

CREATE TABLE `front_news` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` enum('publish','not_publish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_news`
--

INSERT INTO `front_news` (`id`, `status`, `date`, `title`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'publish', '2023-01-10', 'Top Star Rating Schools Award', 'Top Star Rating Schools Award', 'Top Star Rating Schools Award', '2023-01-10 05:55:07', '2023-01-10 05:55:07'),
(2, 'publish', '2023-01-10', 'Myuuu', 'Top Star Rating Schools Award', 'Top Star Rating Schools Award', '2023-01-10 05:55:07', '2023-01-10 05:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `front_notices`
--

CREATE TABLE `front_notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('publish','not_publish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_sliders`
--

CREATE TABLE `front_sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` enum('publish','not_publish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slider_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `btn_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_sliders`
--

INSERT INTO `front_sliders` (`id`, `status`, `title`, `description`, `slider_image`, `btn_name`, `btn_url`, `created_at`, `updated_at`) VALUES
(1, 'publish', 'Better education for', '\r\n Lorem ipsum dolor sit amet, consectetuer adipiscingl elit sed diam nonumm nibhy euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. GET STARTED NOW VIEW COURSES', 'silde3.jfif', 'Read More', 'http://localhost/phpmyadmin/', '2023-01-09 17:21:16', '2023-01-09 17:21:16'),
(2, 'publish', '452', '\r\n Lorem ipsum dolor sit amet, consectetuer adipiscingl elit sed diam nonumm nibhy euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. GET STARTED NOW VIEW COURSES', 'slide1.jfif', NULL, NULL, '2023-01-09 17:21:16', '2023-01-09 17:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE `global_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `school_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `school_type` enum('boys','girls','boys_and_girls') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'boys',
  `school_level` enum('primary','middle','high','inter') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inter',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `date_format` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'm/d/Y',
  `time_format` enum('12','24') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '24',
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Karachi',
  `start_month` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biometric` int(11) DEFAULT 0,
  `biometric_device` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_rtl` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'disabled',
  `is_duplicate_fees_invoice` int(11) DEFAULT 0,
  `session_id` int(10) UNSIGNED DEFAULT NULL,
  `cron_secret_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol_placement` enum('before','after') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before',
  `attendence_type` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_small_logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_due_days` int(11) DEFAULT 0,
  `adm_auto_insert` tinyint(1) NOT NULL DEFAULT 1,
  `adm_prefix` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ssadm19/20',
  `adm_start_from` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adm_no_digit` int(11) NOT NULL DEFAULT 6,
  `adm_update_status` tinyint(1) NOT NULL DEFAULT 0,
  `staffid_auto_insert` int(11) NOT NULL DEFAULT 1,
  `staffid_prefix` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staffss/19/20',
  `staffid_start_from` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staffid_no_digit` int(11) NOT NULL DEFAULT 6,
  `staffid_update_status` int(11) NOT NULL DEFAULT 0,
  `is_active` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `online_admission` int(11) DEFAULT 0,
  `is_blood_group` tinyint(1) NOT NULL DEFAULT 1,
  `is_student_house` tinyint(1) NOT NULL DEFAULT 1,
  `roll_no` tinyint(1) NOT NULL DEFAULT 1,
  `category` tinyint(1) NOT NULL DEFAULT 1,
  `religion` tinyint(1) NOT NULL DEFAULT 1,
  `cast` tinyint(1) NOT NULL DEFAULT 1,
  `mobile_no` tinyint(1) NOT NULL DEFAULT 1,
  `student_email` tinyint(1) NOT NULL DEFAULT 1,
  `admission_date` tinyint(1) NOT NULL DEFAULT 1,
  `lastname` tinyint(1) NOT NULL DEFAULT 1,
  `middlename` tinyint(1) NOT NULL DEFAULT 1,
  `student_photo` tinyint(1) NOT NULL DEFAULT 1,
  `student_height` tinyint(1) NOT NULL DEFAULT 1,
  `student_weight` tinyint(1) NOT NULL DEFAULT 1,
  `measurement_date` tinyint(1) NOT NULL DEFAULT 1,
  `father_name` tinyint(1) NOT NULL DEFAULT 1,
  `father_phone` tinyint(1) NOT NULL DEFAULT 1,
  `father_occupation` tinyint(1) NOT NULL DEFAULT 1,
  `father_pic` tinyint(1) NOT NULL DEFAULT 1,
  `mother_name` tinyint(1) NOT NULL DEFAULT 1,
  `mother_phone` tinyint(1) NOT NULL DEFAULT 1,
  `mother_occupation` tinyint(1) NOT NULL DEFAULT 1,
  `mother_pic` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_name` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_relation` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_phone` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_email` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_pic` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_occupation` tinyint(1) NOT NULL DEFAULT 1,
  `guardian_address` tinyint(1) NOT NULL DEFAULT 1,
  `current_address` tinyint(1) NOT NULL DEFAULT 1,
  `permanent_address` tinyint(1) NOT NULL DEFAULT 1,
  `route_list` tinyint(1) NOT NULL DEFAULT 1,
  `hostel_id` tinyint(1) NOT NULL DEFAULT 1,
  `bank_account_no` tinyint(1) NOT NULL DEFAULT 1,
  `ifsc_code` tinyint(1) NOT NULL DEFAULT 1,
  `bank_name` tinyint(1) NOT NULL DEFAULT 1,
  `national_identification_no` tinyint(1) NOT NULL DEFAULT 1,
  `local_identification_no` tinyint(1) NOT NULL DEFAULT 1,
  `rte` tinyint(1) NOT NULL DEFAULT 1,
  `previous_school_details` tinyint(1) NOT NULL DEFAULT 1,
  `student_note` tinyint(1) NOT NULL DEFAULT 1,
  `upload_documents` tinyint(1) NOT NULL DEFAULT 1,
  `staff_designation` tinyint(1) NOT NULL DEFAULT 1,
  `staff_department` tinyint(1) NOT NULL DEFAULT 1,
  `staff_last_name` tinyint(1) NOT NULL DEFAULT 1,
  `staff_father_name` tinyint(1) NOT NULL DEFAULT 1,
  `staff_mother_name` tinyint(1) NOT NULL DEFAULT 1,
  `staff_date_of_joining` tinyint(1) NOT NULL DEFAULT 1,
  `staff_phone` tinyint(1) NOT NULL DEFAULT 1,
  `staff_emergency_contact` tinyint(1) NOT NULL DEFAULT 1,
  `staff_marital_status` tinyint(1) NOT NULL DEFAULT 1,
  `staff_photo` tinyint(1) NOT NULL DEFAULT 1,
  `staff_current_address` tinyint(1) NOT NULL DEFAULT 1,
  `staff_permanent_address` tinyint(1) NOT NULL DEFAULT 1,
  `staff_qualification` tinyint(1) NOT NULL DEFAULT 1,
  `staff_work_experience` tinyint(1) NOT NULL DEFAULT 1,
  `staff_note` tinyint(1) NOT NULL DEFAULT 1,
  `staff_epf_no` tinyint(1) NOT NULL DEFAULT 1,
  `staff_basic_salary` tinyint(1) NOT NULL DEFAULT 1,
  `staff_contract_type` tinyint(1) NOT NULL DEFAULT 1,
  `staff_work_shift` tinyint(1) NOT NULL DEFAULT 1,
  `staff_work_location` tinyint(1) NOT NULL DEFAULT 1,
  `staff_leaves` tinyint(1) NOT NULL DEFAULT 1,
  `staff_account_details` tinyint(1) NOT NULL DEFAULT 1,
  `staff_social_media` tinyint(1) NOT NULL DEFAULT 1,
  `staff_upload_documents` tinyint(1) NOT NULL DEFAULT 1,
  `mobile_api_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_primary_color_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_secondary_color_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_logo` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_profile_edit` tinyint(1) NOT NULL DEFAULT 0,
  `start_week` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_question` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE `guardians` (
  `id` int(10) UNSIGNED NOT NULL,
  `guardian_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_relation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_occupation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_cnic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_allowances`
--

CREATE TABLE `hrm_allowances` (
  `id` int(10) UNSIGNED NOT NULL,
  `allowance` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_allowances`
--

INSERT INTO `hrm_allowances` (`id`, `allowance`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Allowance', 1, NULL, '2022-03-02 06:40:55', '2022-03-02 06:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_allowance_transaction_lines`
--

CREATE TABLE `hrm_allowance_transaction_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `hrm_transaction_id` int(10) UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `divider` int(11) NOT NULL DEFAULT 0,
  `allowance_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_attendances`
--

CREATE TABLE `hrm_attendances` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('present','late','absent','half_day','holiday','weekend','leave') COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `clock_in_time` datetime DEFAULT NULL,
  `clock_out_time` datetime DEFAULT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_out_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_deductions`
--

CREATE TABLE `hrm_deductions` (
  `id` int(10) UNSIGNED NOT NULL,
  `deduction` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_deductions`
--

INSERT INTO `hrm_deductions` (`id`, `deduction`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Absentees', 1, NULL, '2022-03-02 06:40:45', '2022-03-02 06:40:45'),
(2, 'Assembly Absentee', 1, NULL, '2022-09-01 07:51:32', '2022-09-01 07:51:32');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_deduction_transaction_lines`
--

CREATE TABLE `hrm_deduction_transaction_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `hrm_transaction_id` int(10) UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `deduction_id` int(10) UNSIGNED NOT NULL,
  `divider` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_departments`
--

CREATE TABLE `hrm_departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `department` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_designations`
--

CREATE TABLE `hrm_designations` (
  `id` int(10) UNSIGNED NOT NULL,
  `designation` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_designations`
--

INSERT INTO `hrm_designations` (`id`, `designation`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(21, 'Teacher', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(22, 'Watch-man', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(23, 'NAIB QASID', 1, NULL, '2022-01-03 04:38:29', '2022-01-29 08:03:08'),
(24, 'Pet', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(25, 'Accountant', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(26, 'Director', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(27, 'Vice Princpal', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(28, 'Principal', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(29, 'Driver', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(30, 'Deriver', 1, NULL, '2022-01-03 04:38:29', '2022-01-03 04:38:29'),
(31, 'Chief Proctor', 1, NULL, '2022-11-26 05:42:39', '2022-11-26 05:42:39');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_education`
--

CREATE TABLE `hrm_education` (
  `id` int(10) UNSIGNED NOT NULL,
  `education` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_education`
--

INSERT INTO `hrm_education` (`id`, `education`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'M.Sc Botony', 1, NULL, '2022-12-19 03:53:33', '2022-12-19 03:53:33'),
(2, 'M.Sc Physics', 1, NULL, '2022-12-19 03:53:46', '2022-12-19 03:53:46'),
(3, 'M.Sc Chemistry', 1, NULL, '2022-12-19 03:54:02', '2022-12-19 03:54:02'),
(4, 'M.Sc Economics', 1, NULL, '2022-12-19 03:54:15', '2022-12-19 03:54:15'),
(5, 'M.Sc Pak Studies', 1, NULL, '2022-12-19 03:54:36', '2022-12-19 03:54:36'),
(6, 'M.A (ISLAMYAT)', 1, NULL, '2022-12-19 03:54:56', '2022-12-19 03:54:56'),
(7, 'M.A (URDU)', 1, NULL, '2022-12-19 03:55:11', '2022-12-19 03:55:11'),
(8, 'M.A (ENGLISH)', 1, NULL, '2022-12-19 03:55:32', '2022-12-19 03:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_employees`
--

CREATE TABLE `hrm_employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `employeeID` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_EmpID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(22,4) DEFAULT NULL,
  `pay_period` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_cycle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `designation_id` int(10) UNSIGNED DEFAULT NULL,
  `education_id` int(10) UNSIGNED DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `employee_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `province_id` int(10) UNSIGNED DEFAULT NULL,
  `district_id` int(10) UNSIGNED DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `region_id` int(10) UNSIGNED DEFAULT NULL,
  `current_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_tongue` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annual_leave` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','resign') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `religion` enum('Islam','Hinduism','Christianity','Sikhism','Buddhism','Secular/Nonreligious/Agnostic/Atheist','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Islam',
  `cnic_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` enum('O+','O-','A+','A-','B+','B-','AB+','AB-') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resign_remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `M_Status` tinyint(4) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exit_date` date DEFAULT NULL,
  `reset_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_employees`
--

INSERT INTO `hrm_employees` (`id`, `user_id`, `campus_id`, `employeeID`, `old_EmpID`, `first_name`, `last_name`, `email`, `password`, `gender`, `father_name`, `mobile_no`, `basic_salary`, `pay_period`, `pay_cycle`, `birth_date`, `department_id`, `designation_id`, `education_id`, `joining_date`, `employee_image`, `country_id`, `province_id`, `district_id`, `city_id`, `region_id`, `current_address`, `permanent_address`, `nationality`, `mother_tongue`, `annual_leave`, `status`, `religion`, `cnic_no`, `blood_group`, `bank_details`, `resign_remark`, `remark`, `M_Status`, `last_login`, `remember_token`, `exit_date`, `reset_code`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Em-0001', '', 'AMJAD', 'KHAN', 'amjadkhaneconomist@gmail.com', '$2y$10$u5QsqbE/fuwUz1ZYmH11k.g5RiD3e2wRKyXeRY5rfRj4I36a/Uof.', 'male', 'Noor Muhamamd', '+923469487994', '21500.0000', 'month', NULL, '1985-02-15', NULL, 25, 4, '2010-09-15', 'Em-0001.jpg', 91, 1, 1, 1, 2, 'Mohallah Tehsil K,khela Swat', 'Mohallah tehsil k,khela swat', 'Pakistan', 'Pashto', 0, 'active', 'Islam', '15602-8755133-7', 'B+', '{\"account_name\":\"admin@test.com\",\"account_number\":\"282895475\",\"bank\":\"UBL\",\"bin\":null,\"branch\":\"Khwaza khela Swat\",\"tax_payer_id\":null}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-01-02 23:38:29', '2022-12-18 22:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_employee_documents`
--

CREATE TABLE `hrm_employee_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_employee_shifts`
--

CREATE TABLE `hrm_employee_shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `hrm_shift_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leave_categories`
--

CREATE TABLE `hrm_leave_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_leave_count` int(11) DEFAULT NULL,
  `leave_count_interval` enum('month','year') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_notification_templates`
--

CREATE TABLE `hrm_notification_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_for` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bcc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_send` tinyint(1) NOT NULL DEFAULT 0,
  `auto_send_sms` tinyint(1) NOT NULL DEFAULT 0,
  `auto_send_wa_notif` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_notification_templates`
--

INSERT INTO `hrm_notification_templates` (`id`, `template_for`, `email_body`, `sms_body`, `whatsapp_text`, `subject`, `cc`, `bcc`, `auto_send`, `auto_send_sms`, `auto_send_wa_notif`, `created_at`, `updated_at`) VALUES
(1, 'attendance_check_in', NULL, 'ASSALAMU ALAIKUM\nGood Morning And  Welcome To Collegiate Sir {employee_name}  /n    \nArrival Timing: {clock_in_time}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-27 02:45:44', '2021-11-27 02:45:44'),
(2, 'attendance_check_out', NULL, '\nAllah Hafiz Sir {employee_name}  /n \nDeparture Timing: {clock_out_time}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 21:45:44', '2021-11-26 21:45:44'),
(3, 'student_attendance_check_in', NULL, 'ASSALAMU ALAIKUM\r\nGood Morning And  Welcome To Collegiate Mr {student_name}  /n\r\nArrival Timing: {clock_in_time}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 21:45:44', '2021-11-26 21:45:44'),
(4, 'student_attendance_check_out', NULL, 'Allah Hafiz Mr/Miss {student_name}  /n    \n    Departure Timing: {clock_out_time}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 16:45:44', '2021-11-26 16:45:44'),
(5, 'shift_is_not_over', NULL, '  {org_name}/nshift_is_not_over ', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 21:45:44', '2021-11-26 21:45:44'),
(6, 'student_vacation', NULL, 'Dear Parents, /nDue to winter vacation  Swat Collegiate will remain closed from /n14-01-2022 until 26-01-2022/n (Both Days Included )/nIn Sha ALLAH School will reopen on thursday/n27th january 2022/nRegards/nPrincipal/nSwat Collegiate School/nKhwaza Khela\n', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 21:45:44', '2021-11-26 21:45:44'),
(7, 'owner_payment_received', NULL, 'Student Name : {student_name}/n\r\nPayment Ref No: {payment_ref_no}/n\r\nPaid Amount: {paid_amount}/n\r\nPaid On :{paid_on} /n\r\nRemaining Balance: {total_due}\r\n', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 16:45:44', '2021-11-26 16:45:44'),
(8, 'fee_due_sms', NULL, 'Dear parents, \nKindly pay the Pending \nFee Rs: {total_due} of your\nChild {student_name} Class {current_class}  ,to avoid inconvenience as due date is  over .\nAccounts Manager: \n   Swat Collegiate', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 11:45:44', '2021-11-26 11:45:44'),
(9, 'student_attendance_absent_sms', NULL, 'Dear Parrents\nMr/Miss {student_name}  Class\n{current_class} is absent from school today\nDate: {clock_in_time}.\n\nPrincipal\n      Collegiate  \n', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 1, 0, '2021-11-26 21:45:44', '2021-11-26 21:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_shifts`
--

CREATE TABLE `hrm_shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed_shift','flexible_shift') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed_shift',
  `created_by` int(10) UNSIGNED NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `holidays` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_shifts`
--

INSERT INTO `hrm_shifts` (`id`, `name`, `type`, `created_by`, `start_time`, `end_time`, `holidays`, `created_at`, `updated_at`) VALUES
(1, 'Morning duty', 'fixed_shift', 1, '06:00:00', '17:00:00', '[\"sunday\"]', '2022-01-05 17:03:31', '2023-01-16 07:04:15'),
(2, 'School Shift', 'fixed_shift', 1, '09:57:00', '11:00:00', '[\"sunday\"]', '2022-02-12 04:57:35', '2022-09-29 08:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_transactions`
--

CREATE TABLE `hrm_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `payroll_group_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('opening_balance','pay_roll','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('paid','due','partial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `month` enum('1','2','3','4','5','6','7','8','9','10','11','12') COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `allowances_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `deductions_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `final_total` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_by` int(10) UNSIGNED NOT NULL,
  `allowances` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deductions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_transaction_payments`
--

CREATE TABLE `hrm_transaction_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `hrm_transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Used during adjustment to return the change',
  `session_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `method` enum('cash','card','cheque','bank_transfer','other','advance_pay','student_advance_amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` enum('visa','master') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_security` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `payment_for` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `payment_ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_application_employees`
--

CREATE TABLE `leave_application_employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `apply_date` date NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` enum('pending','approve','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `approve_by` int(10) UNSIGNED DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_application_students`
--

CREATE TABLE `leave_application_students` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `apply_date` date NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` enum('pending','approve','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `approve_by` int(10) UNSIGNED DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(11) NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `model_media_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2020_03_09_135529_create_permission_tables', 1),
(11, '2021_07_23_164405_create_currencies_table', 1),
(12, '2021_07_23_173301_create_sessions_table', 1),
(13, '2021_07_26_181420_create_global_settings_table', 1),
(14, '2021_10_12_184959_create_system_settings_table', 1),
(15, '2021_10_14_074722_add_system_setting_id_users_table', 1),
(16, '2021_10_15_062718_add_system_setting_id_roles_table', 1),
(17, '2021_10_15_070250_add_system_setting_sms_settings_email_settings_table', 1),
(18, '2021_10_15_115259_create_designations_table', 1),
(19, '2021_10_15_133143_create_campuses_table', 1),
(20, '2021_10_16_162557_create_account_types_table', 1),
(21, '2021_10_16_173002_create_accounts_table', 1),
(22, '2021_10_16_190042_create_account_transactions_table', 1),
(23, '2021_10_17_102018_create_media_table', 1),
(24, '2021_10_19_101654_add_campus_id_accounts_table', 1),
(25, '2021_10_19_152901_create_discounts_table', 1),
(26, '2021_10_20_060239_create_awards_table', 1),
(27, '2021_10_20_072904_create_class_levels_table', 1),
(28, '2021_10_20_093045_create_classes_table', 1),
(29, '2021_10_20_153050_create_class_sections_table', 1),
(30, '2021_10_21_081629_create_categories_table', 1),
(31, '2021_10_21_081718_create_regions_table', 1),
(32, '2021_10_22_080717_create_students_table', 1),
(33, '2021_10_22_135846_create_reference_counts_table', 1),
(34, '2021_10_27_151619_create_guardians_table', 1),
(37, '2021_11_05_140028_create_fee_transactions_table', 3),
(38, '2021_11_02_140626_create_fee_heads_table', 4),
(39, '2021_11_05_142413_create_fee_transaction_lines_table', 4),
(41, '2021_11_12_165150_create_fee_transaction_payments_table', 5),
(43, '2021_11_14_131551_add_transaction_payment_id_account_transactions_table', 6),
(51, '2021_11_22_071845_create_hrm_departments_table', 7),
(53, '2021_11_22_072935_create_hrm_educations_table', 7),
(59, '2021_11_22_072618_create_hrm_designations_table', 8),
(60, '2021_11_22_102557_create_hrm_shifts_table', 9),
(61, '2021_11_22_101145_create_hrm_leave_categories_table', 10),
(67, '2021_11_25_112534_create_hrm_employee_documents_table', 13),
(68, '2021_11_25_112141_create_hrm_employees_table', 14),
(69, '2021_11_29_204512_create_hrm_transactions_table', 15),
(70, '2021_12_01_134203_create_hrm_transaction_payments_table', 16),
(73, '2021_12_04_103349_create_hrm_allowances_table', 17),
(74, '2021_12_04_103524_create_hrm_deductions_table', 17),
(76, '2021_12_04_205900_create_deduction_transaction_lines_table', 18),
(81, '2021_12_09_212608_create_class_subject_lessons_table', 20),
(84, '2021_12_09_212733_create_class_subject_progress_table', 21),
(86, '2021_12_13_183542_create_class_subject_question_banks_table', 22),
(87, '2021_12_20_155432_create_class_timetable_periods_table', 23),
(89, '2021_12_20_192926_create_class_timetable_table', 24),
(91, '2021_12_28_165319_create_hrm_attendances', 26),
(92, '2021_12_31_170125_create_hrm_notification_templates_table', 27),
(93, '2022_02_15_115800_create_weekend_holidays_table', 28),
(96, '2021_12_09_192012_create_class_subjects_table', 29),
(97, '2022_04_04_214352_create_subject_teachers_table', 29),
(104, '2021_12_28_183807_create_exam_terms', 30),
(105, '2021_12_28_184221_create_exam_grades', 30),
(106, '2022_04_12_223528_create_exam_creates_table', 30),
(107, '2022_04_12_223624_create_exam_allocations_table', 30),
(108, '2022_04_12_223640_create_exam_subject_results_table', 30),
(109, '2022_04_14_004529_create_exam_date_sheets_table', 30),
(110, '2022_04_26_215757_create_withdrawal_registers_table', 31),
(111, '2022_04_28_230646_create_certificate_types', 31),
(112, '2022_04_28_233747_create_certificate_issues', 31),
(113, '2022_07_12_131416_create_sms_table', 32),
(114, '2022_07_19_211949_create_subject_chapters_table', 33),
(115, '2022_07_19_212351_create_subject_question_banks_table', 33),
(116, '2022_07_28_225120_create_sims_table', 34),
(117, '2022_08_23_223424_create_expense_categories_table', 35),
(118, '2022_08_23_232516_create_expense_transactions_table', 35),
(119, '2022_08_23_232752_create_expense_transaction_payments_table', 35),
(120, '2022_08_25_202947_create_vehicles_table', 35),
(122, '2022_08_29_114412_add_extra_column', 36),
(123, '2022_10_08_201724_create_syllabus_mangers_table', 37),
(124, '2022_10_26_121356_create_note_book_status_table', 37),
(125, '2022_11_28_134531_create_jobs_table', 37),
(126, '2022_12_20_113435_create_leave_application_students_table', 37),
(127, '2022_12_20_114343_create_leave_application_employees_table', 37),
(128, '2022_12_28_220646_create_fee_increment_decrement_table', 37),
(129, '2023_01_06_220043_add_uuid_field_to_failed_jobs_table', 38),
(130, '2023_01_07_184852_create_student_documents_table', 39),
(131, '2023_01_09_220202_create_front_sliders_table', 39),
(132, '2023_01_09_232359_create_front_news_table', 40),
(133, '2023_01_10_175241_create_front_about_us_table', 41),
(134, '2023_01_10_180218_create_front_notices_table', 42),
(135, '2023_01_10_180223_create_front_events_table', 42),
(136, '2023_01_11_182200_create_front_gallery_categories_table', 42),
(137, '2023_01_11_182246_create_front_galleries_content_table', 42);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 159),
(1, 'App\\Models\\User', 161),
(1, 'App\\Models\\User', 163),
(1, 'App\\Models\\User', 171),
(1, 'App\\Models\\User', 176),
(2, 'App\\Models\\User', 168),
(2, 'App\\Models\\User', 175),
(2, 'App\\Models\\User', 176),
(2, 'App\\Models\\User', 177),
(2, 'App\\Models\\User', 178),
(2, 'App\\Models\\User', 179),
(2, 'App\\Models\\User', 204),
(2, 'App\\Models\\User', 205),
(2, 'App\\Models\\User', 213),
(3, 'App\\Models\\User', 36),
(3, 'App\\Models\\User', 42),
(3, 'App\\Models\\User', 129),
(3, 'App\\Models\\User', 130),
(3, 'App\\Models\\User', 141),
(3, 'App\\Models\\User', 143),
(3, 'App\\Models\\User', 145),
(3, 'App\\Models\\User', 172),
(3, 'App\\Models\\User', 179),
(3, 'App\\Models\\User', 180),
(3, 'App\\Models\\User', 182),
(3, 'App\\Models\\User', 184),
(3, 'App\\Models\\User', 188),
(3, 'App\\Models\\User', 190),
(3, 'App\\Models\\User', 193),
(3, 'App\\Models\\User', 195),
(3, 'App\\Models\\User', 198),
(3, 'App\\Models\\User', 200),
(3, 'App\\Models\\User', 202),
(3, 'App\\Models\\User', 206),
(3, 'App\\Models\\User', 208),
(3, 'App\\Models\\User', 210),
(3, 'App\\Models\\User', 214),
(3, 'App\\Models\\User', 216),
(3, 'App\\Models\\User', 218),
(3, 'App\\Models\\User', 220),
(3, 'App\\Models\\User', 222),
(3, 'App\\Models\\User', 224),
(3, 'App\\Models\\User', 226),
(3, 'App\\Models\\User', 228),
(3, 'App\\Models\\User', 232),
(3, 'App\\Models\\User', 233),
(3, 'App\\Models\\User', 235),
(4, 'App\\Models\\User', 169),
(4, 'App\\Models\\User', 177),
(4, 'App\\Models\\User', 187),
(5, 'App\\Models\\User', 180),
(5, 'App\\Models\\User', 181),
(5, 'App\\Models\\User', 183),
(5, 'App\\Models\\User', 185),
(5, 'App\\Models\\User', 186),
(5, 'App\\Models\\User', 187),
(5, 'App\\Models\\User', 188),
(5, 'App\\Models\\User', 189),
(5, 'App\\Models\\User', 191),
(5, 'App\\Models\\User', 192),
(5, 'App\\Models\\User', 194),
(5, 'App\\Models\\User', 196),
(5, 'App\\Models\\User', 197),
(5, 'App\\Models\\User', 199),
(5, 'App\\Models\\User', 201),
(5, 'App\\Models\\User', 203),
(5, 'App\\Models\\User', 207),
(5, 'App\\Models\\User', 209),
(5, 'App\\Models\\User', 211),
(5, 'App\\Models\\User', 212),
(5, 'App\\Models\\User', 215),
(5, 'App\\Models\\User', 217),
(5, 'App\\Models\\User', 219),
(5, 'App\\Models\\User', 221),
(5, 'App\\Models\\User', 223),
(5, 'App\\Models\\User', 225),
(5, 'App\\Models\\User', 227),
(5, 'App\\Models\\User', 229),
(5, 'App\\Models\\User', 231),
(5, 'App\\Models\\User', 234),
(5, 'App\\Models\\User', 236);

-- --------------------------------------------------------

--
-- Table structure for table `note_book_status`
--

CREATE TABLE `note_book_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `check_date` date NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `status` enum('complete','incomplete','Missing','not_found') COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'session.view', 'web', '2021-10-15 00:33:35', NULL),
(2, 'session.create', 'web', '2021-10-15 00:33:35', NULL),
(3, 'session.update', 'web', '2021-10-15 00:33:35', NULL),
(4, 'session.delete', 'web', '2021-10-15 00:33:35', NULL),
(5, 'chapter.view', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(6, 'chapter.create', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(7, 'chapter.update', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(8, 'chapter.delete', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(9, 'lesson.view', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(10, 'lesson.create', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(11, 'lesson.update', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(12, 'lesson.delete', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(13, 'lesson_progress.view', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(14, 'lesson_progress.create', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(15, 'lesson_progress.update', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(16, 'lesson_progress.delete', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(17, 'question_bank.view', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(18, 'question_bank.create', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(19, 'question_bank.update', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(20, 'question_bank.delete', 'web', '2022-12-08 07:04:40', '2022-12-08 07:04:40'),
(21, 'exam_mark_entry.create', 'web', '2022-12-08 07:04:41', '2022-12-08 07:04:41'),
(22, 'mark_entry_print.print', 'web', '2022-12-08 07:04:41', '2022-12-08 07:04:41'),
(23, 'employee.profile', 'web', '2022-12-21 05:56:39', '2022-12-21 05:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `country_id`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'KPK', 91, 1, 1, NULL, '2021-10-23 11:31:33', '2021-10-23 13:53:34'),
(2, 'Punjab', 91, 1, 1, NULL, '2021-10-23 12:29:04', '2021-10-23 12:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `reference_counts`
--

CREATE TABLE `reference_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `ref_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_count` int(11) NOT NULL,
  `session_id` int(10) UNSIGNED DEFAULT NULL,
  `session_close` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reference_counts`
--

INSERT INTO `reference_counts` (`id`, `ref_type`, `ref_count`, `session_id`, `session_close`, `system_settings_id`, `created_at`, `updated_at`) VALUES
(1, 'roll_no', 773, 1, 'open', 1, '2022-01-01 12:47:58', '2023-01-07 06:17:30'),
(2, 'admission_no', 773, NULL, NULL, 1, '2022-01-01 12:48:20', '2023-01-07 06:17:30'),
(4, 'opening_balance', 618, NULL, NULL, 1, '2022-01-01 17:31:22', '2022-12-17 05:40:40'),
(5, 'employee_no', 49, NULL, NULL, 1, '2022-01-02 17:33:01', '2022-11-01 06:26:04'),
(6, 'challan', 6362, NULL, NULL, 1, '2022-01-04 12:10:49', '2023-01-07 06:18:25'),
(7, 'fee_payment', 11100, NULL, NULL, 1, '2022-01-20 06:15:23', '2023-01-16 07:07:50'),
(8, 'student_advance_payment', 1, NULL, NULL, 1, '2022-02-03 15:15:15', '2022-02-03 15:15:15'),
(9, 'payroll', 446, NULL, NULL, 1, '2022-03-02 06:46:47', '2023-01-03 04:39:37'),
(10, 'pay_roll_payment', 541, NULL, NULL, 1, '2022-03-02 08:12:04', '2023-01-09 11:38:57'),
(11, 'slc_no', 45, NULL, NULL, 1, '2022-05-09 09:03:15', '2023-01-07 03:49:40'),
(12, 'certificate_no', 224, NULL, NULL, 1, '2022-05-09 09:05:39', '2023-01-07 03:49:58'),
(13, 'expense', 155, NULL, NULL, 1, '2022-09-20 08:50:51', '2023-01-16 07:10:54'),
(14, 'expense_payment', 154, NULL, NULL, 1, '2022-10-01 05:45:24', '2023-01-16 07:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `country_id` int(10) UNSIGNED NOT NULL,
  `province_id` int(10) UNSIGNED NOT NULL,
  `district_id` int(10) UNSIGNED NOT NULL,
  `city_id` int(10) UNSIGNED NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `transport_fee`, `country_id`, `province_id`, `district_id`, `city_id`, `system_settings_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Barakaly', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2021-10-23 20:22:55', '2022-01-03 10:02:10'),
(2, 'Kozkalay', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:02:52', '2022-01-19 09:33:47'),
(3, 'Bandai', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:03:22', '2022-10-05 07:29:30'),
(4, 'Landikas', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:04:03', '2022-10-05 07:31:25'),
(5, 'Berarai', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:04:34', '2022-01-19 09:33:26'),
(6, 'Babo', '2000.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:05:18', '2022-10-05 07:45:36'),
(7, 'Chamtalai', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:06:21', '2022-10-05 07:45:51'),
(8, 'Langar', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:06:41', '2022-10-05 07:31:30'),
(9, 'Asala bala', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:07:07', '2022-10-05 07:33:05'),
(10, 'Shalpin', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-03 10:07:44', '2022-10-05 07:41:58'),
(11, 'Bamakhela', '0.0000', 91, 1, 1, 2, 1, 1, NULL, '2022-01-04 07:25:34', '2022-01-04 07:25:34'),
(12, 'Chalyar', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-10 06:38:20', '2022-10-05 07:32:13'),
(13, 'Main Road Charbagh', '2000.0000', 91, 1, 1, 5, 1, 1, NULL, '2022-01-10 06:46:15', '2022-10-05 07:37:30'),
(14, 'Jano', '0.0000', 91, 1, 1, 1, 1, 1, '2022-01-19 09:53:49', '2022-01-19 09:39:41', '2022-01-19 09:53:49'),
(15, 'Kachigram', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:40:10', '2022-01-19 09:40:10'),
(16, 'Shalpin', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:40:33', '2022-10-05 07:42:05'),
(17, 'Alaabad', '0.0000', 91, 1, 1, 5, 1, 1, NULL, '2022-01-19 09:41:05', '2022-01-19 09:41:05'),
(18, 'Guli bagh', '2000.0000', 91, 1, 1, 5, 1, 1, NULL, '2022-01-19 09:41:46', '2022-10-05 07:37:45'),
(19, 'Alamganj', '1800.0000', 91, 1, 1, 5, 1, 1, NULL, '2022-01-19 09:42:11', '2022-10-05 07:31:05'),
(20, 'Karam Dherai', '0.0000', 91, 1, 1, 5, 1, 1, NULL, '2022-01-19 09:42:49', '2022-01-19 09:42:49'),
(21, 'Gashkor', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:45:26', '2022-10-05 07:29:43'),
(22, 'Ghar Shin', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:46:04', '2022-10-05 07:33:43'),
(23, 'Nalai Qala', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:46:41', '2022-10-05 07:33:32'),
(24, 'Bagh Dherai', '2300.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:47:10', '2022-10-05 07:37:57'),
(25, 'Nawakalay Bagh dherai', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:47:59', '2022-10-05 07:38:13'),
(26, 'Fathepur', '2300.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:48:22', '2022-10-05 07:38:59'),
(27, 'Fatehpur Tehsil', '2300.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:48:56', '2022-10-05 07:39:04'),
(28, 'Shin', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:49:20', '2022-10-05 07:33:48'),
(29, 'Gul Dherai', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:49:41', '2022-01-19 09:49:41'),
(30, 'Farhat Abad', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:50:18', '2022-01-19 09:50:18'),
(31, 'Kotanai', '2000.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:50:44', '2022-10-05 07:33:21'),
(32, 'Koza Asala', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:51:09', '2022-10-05 07:33:10'),
(33, 'Wach Khwar', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:51:33', '2022-01-19 09:51:33'),
(34, 'Doop', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:51:58', '2022-10-05 07:32:33'),
(35, 'Titabut', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:52:19', '2022-10-05 07:29:09'),
(36, 'Tikdarai', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:52:41', '2022-10-05 07:29:16'),
(37, 'Qala K.Khela', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:53:04', '2022-10-05 07:28:53'),
(38, 'Jano', '1800.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:53:22', '2022-10-05 07:39:17'),
(39, 'Mashkomai', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 09:54:11', '2022-10-05 07:46:59'),
(40, 'Chinkolai', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 12:11:58', '2022-01-19 12:11:58'),
(41, 'Baidara', '0.0000', 91, 1, 1, 2, 1, 1, NULL, '2022-01-19 12:28:57', '2022-01-19 12:28:57'),
(42, 'Topsin', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-19 13:16:53', '2022-01-19 13:16:53'),
(43, 'Alamganj', '0.0000', 91, 1, 1, 5, 1, 1, '2022-10-05 07:31:10', '2022-01-19 14:47:30', '2022-10-05 07:31:10'),
(44, 'Chinarbaba', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-23 12:12:56', '2022-01-23 12:12:56'),
(45, 'Manpetai', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-01-24 07:46:10', '2022-01-24 07:46:10'),
(46, 'Sambat', '0.0000', 91, 1, 1, 2, 1, 1, NULL, '2022-02-03 16:34:03', '2022-02-03 16:34:03'),
(47, 'Nawaykalay Shin', '2200.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-02-15 12:11:27', '2022-10-05 07:35:38'),
(48, 'Gulbahar', '0.0000', 91, 1, 1, 1, 1, 1, NULL, '2022-10-03 07:35:31', '2022-10-03 07:35:31');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `system_settings_id`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Admin#1', 'web', 1, 1, '2021-10-15 01:33:46', '2021-10-15 01:33:46'),
(2, 'Teacher#1', 'web', 1, 0, '2022-12-07 19:24:03', '2022-12-07 19:24:03'),
(3, 'Student#1', 'web', 1, 0, '2022-12-10 09:16:53', '2022-12-10 09:16:53'),
(4, 'Staff#1', 'web', 1, 0, '2022-12-10 09:26:01', '2022-12-10 09:26:01'),
(5, 'Guardian#1', 'web', 1, 0, '2022-12-15 07:19:53', '2022-12-15 07:19:53');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(23, 3),
(23, 5),
(26, 4);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'UPCOMING',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `title`, `prefix`, `status`, `start_date`, `end_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2022-2023', 'F22', 'PASSED', '2022-01-01', '2022-12-28', NULL, '2022-01-01 12:47:58', '2022-12-28 18:25:46'),
(2, '2023-2024', 'F23', 'ACTIVE', '2022-12-28', NULL, NULL, '2022-12-28 06:50:00', '2022-12-28 18:25:59');

-- --------------------------------------------------------

--
-- Table structure for table `sims`
--

CREATE TABLE `sims` (
  `id` int(10) UNSIGNED NOT NULL,
  `sim_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(10) UNSIGNED NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sms_body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('send','not_send') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_send',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `advance_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `adm_session_id` int(10) UNSIGNED DEFAULT NULL,
  `cur_session_id` int(10) UNSIGNED DEFAULT NULL,
  `admission_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admission_date` date NOT NULL,
  `roll_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_roll_no` int(255) DEFAULT NULL,
  `adm_class_id` int(10) UNSIGNED NOT NULL,
  `current_class_id` int(10) UNSIGNED NOT NULL,
  `adm_class_section_id` int(10) UNSIGNED NOT NULL,
  `current_class_section_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `BirthPlace` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `domicile_id` int(10) UNSIGNED DEFAULT NULL,
  `religion` enum('Islam','Hinduism','Christianity','Sikhism','Buddhism','Secular/Nonreligious/Agnostic/Atheist','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Islam',
  `mobile_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` enum('O+','O-','A+','A-','B+','B-','AB+','AB-') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_tongue` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `medical_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','pass_out','struck_up','took_slc') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `father_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_occupation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_cnic_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_occupation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_cnic_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `province_id` int(10) UNSIGNED DEFAULT NULL,
  `district_id` int(10) UNSIGNED DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `region_id` int(10) UNSIGNED DEFAULT NULL,
  `std_current_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `std_permanent_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_school_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_grade` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_tuition_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `is_transport` tinyint(4) NOT NULL DEFAULT 0,
  `student_transport_fee` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `vehicle_id` int(10) UNSIGNED DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED NOT NULL,
  `discount_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_guardians`
--

CREATE TABLE `student_guardians` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED DEFAULT NULL,
  `guardian_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_chapters`
--

CREATE TABLE `subject_chapters` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `chapter_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_question_banks`
--

CREATE TABLE `subject_question_banks` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `chapter_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hint` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('mcq','fill_in_the_blanks','true_and_false','column_matching','short_question','words_and_use','paraphrase','stanza','passage','long_question','translation_to_urdu','translation_to_english','contextual','singular_and_plural','masculine_and_feminine','grammar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_b` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_c` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_d` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `column_a` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `column_b` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marks` decimal(5,2) NOT NULL DEFAULT 0.00,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_teachers`
--

CREATE TABLE `subject_teachers` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `syllabus_mangers`
--

CREATE TABLE `syllabus_mangers` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL,
  `exam_term_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `chapter_id` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `org_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_short_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_contact_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_email` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_website` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_line` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_header_logo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card_logo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_favicon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `currency_symbol_placement` enum('before','after') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before',
  `start_date` date DEFAULT NULL,
  `date_format` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'm/d/Y',
  `time_format` enum('12','24') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '24',
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Karachi',
  `start_month` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_edit_days` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `email_settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_no_prefixes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_tooltip` tinyint(1) NOT NULL DEFAULT 1,
  `theme_color` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `common_settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pdf` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `org_name`, `org_short_name`, `org_address`, `org_contact_number`, `org_email`, `org_website`, `org_logo`, `tag_line`, `page_header_logo`, `id_card_logo`, `org_favicon`, `currency_id`, `currency_symbol_placement`, `start_date`, `date_format`, `time_format`, `time_zone`, `start_month`, `transaction_edit_days`, `email_settings`, `sms_settings`, `ref_no_prefixes`, `enable_tooltip`, `theme_color`, `common_settings`, `created_at`, `updated_at`, `pdf`) VALUES
(1, 'swat collegiate school khwaza khela', 'admin@test.com', 'Khwaza Khela', '03428927305', NULL, NULL, '1673247273_1664987422_logo.jpg', 'SCS', '1673247273_1673165072_1670615107_logo.jpeg', '1673247273_id.png', '1673247273_1664987443_logo.jpg', 91, 'before', '2021-10-14', 'd-m-Y', '12', 'Asia/Karachi', '', 30, '{\"mail_driver\":\"smtp\",\"mail_host\":null,\"mail_port\":null,\"mail_username\":null,\"mail_password\":\"123456789\",\"mail_encryption\":null,\"mail_from_address\":null,\"mail_from_name\":null}', '{\"sms_service\":\"other\",\"nexmo_key\":null,\"nexmo_secret\":null,\"nexmo_from\":null,\"twilio_sid\":null,\"twilio_token\":null,\"twilio_from\":null,\"url\":\"http:\\/\\/localhost\\/django-admin\\/api\\/sms\",\"send_to_param_name\":\"to\",\"msg_param_name\":\"text\",\"request_method\":\"post\",\"header_1\":null,\"header_val_1\":null,\"header_2\":null,\"header_val_2\":null,\"header_3\":null,\"header_val_3\":null,\"param_1\":null,\"param_val_1\":null,\"param_2\":null,\"param_val_2\":null,\"param_3\":null,\"param_val_3\":null,\"param_4\":null,\"param_val_4\":null,\"param_5\":null,\"param_val_5\":null,\"param_6\":null,\"param_val_6\":null,\"param_7\":null,\"param_val_7\":null,\"param_8\":null,\"param_val_8\":null,\"param_9\":null,\"param_val_9\":null,\"param_10\":null,\"param_val_10\":null}', '{\"student\":\"std1\",\"employee\":\"Em\",\"fee_payment\":\"FeeP\",\"expenses_payment\":\"FeeP\",\"admission\":\"Adm\"}', 1, 'blue', '{\"default_datatable_page_entries\":\"25\"}', '2021-10-26 17:55:27', '2023-01-09 06:54:33', 'data:image/jpeg;base64,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');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('admin','other','teacher','guardian','staff','student') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hook_id` int(10) UNSIGNED DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_settings_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `surname`, `user_type`, `hook_id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `system_settings_id`, `campus_id`, `image`, `language`, `created_at`, `updated_at`) VALUES
(1, '', 'admin', 1, 'AMJAD', 'KHAN', 'amjadkhaneconomist@gmail.com', NULL, '$2y$10$u5QsqbE/fuwUz1ZYmH11k.g5RiD3e2wRKyXeRY5rfRj4I36a/Uof.', '97aQF8Rm6rW5Y728Bivkzn93CQtkXxI1vZeXBxzC0Q9OZbiHLpZ8rx4pg6JR', 1, NULL, 'uploads/employee_image/Em-0001.jpg', 'en', '2021-10-13 16:07:27', '2022-12-18 22:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `driver_license` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_made` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekend_holidays`
--

CREATE TABLE `weekend_holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `sms` tinyint(1) NOT NULL DEFAULT 0,
  `employee_include` tinyint(1) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_section` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_registers`
--

CREATE TABLE `withdrawal_registers` (
  `id` int(10) UNSIGNED NOT NULL,
  `adm_session_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `admission_class_id` int(10) UNSIGNED NOT NULL,
  `leaving_session_id` int(10) UNSIGNED DEFAULT NULL,
  `leaving_class_id` int(10) UNSIGNED DEFAULT NULL,
  `withdraw_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_leaving` date DEFAULT NULL,
  `slc_issue_date` date DEFAULT NULL,
  `slc_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `any_remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `co_curricular_activities` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `local_withdrawal_register_detail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `accounts_created_by_foreign` (`created_by`),
  ADD KEY `accounts_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_transactions_account_id_index` (`account_id`),
  ADD KEY `account_transactions_transaction_id_index` (`transaction_id`),
  ADD KEY `account_transactions_transfer_transaction_id_index` (`transfer_transaction_id`),
  ADD KEY `account_transactions_created_by_index` (`created_by`),
  ADD KEY `account_transactions_type_index` (`type`),
  ADD KEY `account_transactions_sub_type_index` (`sub_type`),
  ADD KEY `account_transactions_transaction_payment_id_foreign` (`transaction_payment_id`),
  ADD KEY `account_transactions_hrm_transaction_payment_id_foreign` (`hrm_transaction_payment_id`),
  ADD KEY `account_transactions_expense_transaction_payment_id_foreign` (`expense_transaction_payment_id`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_types_parent_account_type_id_index` (`parent_account_type_id`),
  ADD KEY `account_types_system_settings_id_index` (`system_settings_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_student_id_index` (`student_id`),
  ADD KEY `attendances_session_id_index` (`session_id`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `awards_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `awards_created_by_foreign` (`created_by`);

--
-- Indexes for table `barcodes`
--
ALTER TABLE `barcodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcodes_business_id_foreign` (`business_id`);

--
-- Indexes for table `campuses`
--
ALTER TABLE `campuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campuses_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `campuses_created_by_foreign` (`created_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `categories_created_by_foreign` (`created_by`);

--
-- Indexes for table `certificate_issues`
--
ALTER TABLE `certificate_issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificate_issues_certificate_type_id_foreign` (`certificate_type_id`),
  ADD KEY `certificate_issues_student_id_foreign` (`student_id`),
  ADD KEY `certificate_issues_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `certificate_types`
--
ALTER TABLE `certificate_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_country_id_foreign` (`country_id`),
  ADD KEY `cities_province_id_foreign` (`province_id`),
  ADD KEY `cities_district_id_foreign` (`district_id`),
  ADD KEY `cities_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `cities_created_by_foreign` (`created_by`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `classes_campus_id_foreign` (`campus_id`),
  ADD KEY `classes_class_level_id_foreign` (`class_level_id`),
  ADD KEY `classes_created_by_foreign` (`created_by`);

--
-- Indexes for table `class_levels`
--
ALTER TABLE `class_levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_levels_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `class_levels_created_by_foreign` (`created_by`);

--
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_sections_class_id_foreign` (`class_id`),
  ADD KEY `class_sections_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `class_sections_campus_id_foreign` (`campus_id`),
  ADD KEY `class_sections_created_by_foreign` (`created_by`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subjects_campus_id_foreign` (`campus_id`),
  ADD KEY `class_subjects_class_id_foreign` (`class_id`),
  ADD KEY `class_subjects_created_by_foreign` (`created_by`);

--
-- Indexes for table `class_subject_lessons`
--
ALTER TABLE `class_subject_lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subject_lessons_campus_id_foreign` (`campus_id`),
  ADD KEY `class_subject_lessons_subject_id_foreign` (`subject_id`),
  ADD KEY `class_subject_lessons_created_by_foreign` (`created_by`);

--
-- Indexes for table `class_subject_progress`
--
ALTER TABLE `class_subject_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subject_progress_campus_id_foreign` (`campus_id`),
  ADD KEY `class_subject_progress_subject_id_foreign` (`subject_id`),
  ADD KEY `class_subject_progress_lesson_id_foreign` (`lesson_id`),
  ADD KEY `class_subject_progress_created_by_foreign` (`created_by`),
  ADD KEY `class_subject_progress_teacher_by_foreign` (`teacher_by`),
  ADD KEY `class_subject_progress_session_id_foreign` (`session_id`);

--
-- Indexes for table `class_timetables`
--
ALTER TABLE `class_timetables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_timetables_campus_id_foreign` (`campus_id`),
  ADD KEY `class_timetables_class_id_foreign` (`class_id`),
  ADD KEY `class_timetables_class_section_id_foreign` (`class_section_id`),
  ADD KEY `class_timetables_subject_id_foreign` (`subject_id`),
  ADD KEY `class_timetables_period_id_foreign` (`period_id`);

--
-- Indexes for table `class_timetable_periods`
--
ALTER TABLE `class_timetable_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_timetable_periods_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designations_system_settings_id_foreign` (`system_settings_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `discounts_campus_id_foreign` (`campus_id`),
  ADD KEY `discounts_created_by_foreign` (`created_by`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `districts_country_id_foreign` (`country_id`),
  ADD KEY `districts_province_id_foreign` (`province_id`),
  ADD KEY `districts_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `districts_created_by_foreign` (`created_by`);

--
-- Indexes for table `exam_allocations`
--
ALTER TABLE `exam_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_allocations_exam_create_id_foreign` (`exam_create_id`),
  ADD KEY `exam_allocations_session_id_foreign` (`session_id`),
  ADD KEY `exam_allocations_campus_id_foreign` (`campus_id`),
  ADD KEY `exam_allocations_class_id_foreign` (`class_id`),
  ADD KEY `exam_allocations_class_section_id_foreign` (`class_section_id`),
  ADD KEY `exam_allocations_student_id_foreign` (`student_id`),
  ADD KEY `exam_allocations_grade_id_foreign` (`grade_id`);

--
-- Indexes for table `exam_creates`
--
ALTER TABLE `exam_creates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_creates_exam_term_id_foreign` (`exam_term_id`),
  ADD KEY `exam_creates_session_id_foreign` (`session_id`),
  ADD KEY `exam_creates_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `exam_date_sheets`
--
ALTER TABLE `exam_date_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_date_sheets_exam_create_id_foreign` (`exam_create_id`),
  ADD KEY `exam_date_sheets_session_id_foreign` (`session_id`),
  ADD KEY `exam_date_sheets_campus_id_foreign` (`campus_id`),
  ADD KEY `exam_date_sheets_class_id_foreign` (`class_id`),
  ADD KEY `exam_date_sheets_class_section_id_foreign` (`class_section_id`),
  ADD KEY `exam_date_sheets_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `exam_grades`
--
ALTER TABLE `exam_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_subject_results`
--
ALTER TABLE `exam_subject_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_subject_results_exam_allocation_id_foreign` (`exam_allocation_id`),
  ADD KEY `exam_subject_results_exam_create_id_foreign` (`exam_create_id`),
  ADD KEY `exam_subject_results_session_id_foreign` (`session_id`),
  ADD KEY `exam_subject_results_student_id_foreign` (`student_id`),
  ADD KEY `exam_subject_results_campus_id_foreign` (`campus_id`),
  ADD KEY `exam_subject_results_class_id_foreign` (`class_id`),
  ADD KEY `exam_subject_results_class_section_id_foreign` (`class_section_id`),
  ADD KEY `exam_subject_results_subject_id_foreign` (`subject_id`),
  ADD KEY `exam_subject_results_teacher_id_foreign` (`teacher_id`),
  ADD KEY `exam_subject_results_grade_id_foreign` (`grade_id`);

--
-- Indexes for table `exam_terms`
--
ALTER TABLE `exam_terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_transactions`
--
ALTER TABLE `expense_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_transactions_campus_id_foreign` (`campus_id`),
  ADD KEY `expense_transactions_session_id_foreign` (`session_id`),
  ADD KEY `expense_transactions_expense_category_id_foreign` (`expense_category_id`),
  ADD KEY `expense_transactions_expense_for_foreign` (`expense_for`),
  ADD KEY `expense_transactions_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `expense_transactions_created_by_foreign` (`created_by`);

--
-- Indexes for table `expense_transaction_payments`
--
ALTER TABLE `expense_transaction_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_transaction_payments_expense_transaction_id_foreign` (`expense_transaction_id`),
  ADD KEY `expense_transaction_payments_session_id_foreign` (`session_id`),
  ADD KEY `expense_transaction_payments_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fee_heads`
--
ALTER TABLE `fee_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_heads_campus_id_foreign` (`campus_id`),
  ADD KEY `fee_heads_class_id_foreign` (`class_id`);

--
-- Indexes for table `fee_increment_decrement`
--
ALTER TABLE `fee_increment_decrement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_increment_decrements`
--
ALTER TABLE `fee_increment_decrements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_increment_decrement_session_id_foreign` (`session_id`),
  ADD KEY `fee_increment_decrement_campus_id_foreign` (`campus_id`),
  ADD KEY `fee_increment_decrement_class_id_foreign` (`class_id`),
  ADD KEY `fee_increment_decrement_class_section_id_foreign` (`class_section_id`);

--
-- Indexes for table `fee_transactions`
--
ALTER TABLE `fee_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_transactions_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `fee_transactions_campus_id_foreign` (`campus_id`),
  ADD KEY `fee_transactions_session_id_foreign` (`session_id`),
  ADD KEY `fee_transactions_class_id_foreign` (`class_id`),
  ADD KEY `fee_transactions_section_id_foreign` (`class_section_id`),
  ADD KEY `fee_transactions_student_id_foreign` (`student_id`),
  ADD KEY `fee_transactions_created_by_foreign` (`created_by`);

--
-- Indexes for table `fee_transaction_lines`
--
ALTER TABLE `fee_transaction_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_transaction_lines_fee_transaction_id_foreign` (`fee_transaction_id`),
  ADD KEY `fee_transaction_lines_fee_head_id_foreign` (`fee_head_id`);

--
-- Indexes for table `fee_transaction_payments`
--
ALTER TABLE `fee_transaction_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_transaction_payments_fee_transaction_id_foreign` (`fee_transaction_id`),
  ADD KEY `fee_transaction_payments_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `fee_transaction_payments_session_id_foreign` (`session_id`),
  ADD KEY `fee_transaction_payments_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `front_about_us`
--
ALTER TABLE `front_about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_events`
--
ALTER TABLE `front_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_gallery_categories`
--
ALTER TABLE `front_gallery_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_gallery_contents`
--
ALTER TABLE `front_gallery_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `front_galleries_content_category_id_foreign` (`category_id`);

--
-- Indexes for table `front_news`
--
ALTER TABLE `front_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_notices`
--
ALTER TABLE `front_notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_sliders`
--
ALTER TABLE `front_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `global_settings_currency_id_foreign` (`currency_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `guardians`
--
ALTER TABLE `guardians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_allowances`
--
ALTER TABLE `hrm_allowances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_allowances_allowance_unique` (`allowance`),
  ADD KEY `hrm_allowances_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_allowance_transaction_lines`
--
ALTER TABLE `hrm_allowance_transaction_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_allowance_transaction_lines_hrm_transaction_id_foreign` (`hrm_transaction_id`),
  ADD KEY `hrm_allowance_transaction_lines_allowance_id_foreign` (`allowance_id`);

--
-- Indexes for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_attendances_employee_id_index` (`employee_id`),
  ADD KEY `hrm_attendances_session_id_index` (`session_id`);

--
-- Indexes for table `hrm_deductions`
--
ALTER TABLE `hrm_deductions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_deductions_deduction_unique` (`deduction`),
  ADD KEY `hrm_deductions_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_deduction_transaction_lines`
--
ALTER TABLE `hrm_deduction_transaction_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_deduction_transaction_lines_hrm_transaction_id_foreign` (`hrm_transaction_id`),
  ADD KEY `hrm_deduction_transaction_lines_deduction_id_foreign` (`deduction_id`);

--
-- Indexes for table `hrm_departments`
--
ALTER TABLE `hrm_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_departments_department_unique` (`department`),
  ADD KEY `hrm_departments_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_designations_designation_unique` (`designation`),
  ADD KEY `hrm_designations_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_education`
--
ALTER TABLE `hrm_education`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_educations_education_name_unique` (`education`),
  ADD KEY `hrm_educations_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_employees`
--
ALTER TABLE `hrm_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_employees_employeeid_unique` (`employeeID`),
  ADD UNIQUE KEY `hrm_employees_email_unique` (`email`),
  ADD KEY `hrm_employees_campus_id_foreign` (`campus_id`),
  ADD KEY `hrm_employees_country_id_foreign` (`country_id`),
  ADD KEY `hrm_employees_province_id_foreign` (`province_id`),
  ADD KEY `hrm_employees_district_id_foreign` (`district_id`),
  ADD KEY `hrm_employees_city_id_foreign` (`city_id`),
  ADD KEY `hrm_employees_region_id_foreign` (`region_id`),
  ADD KEY `hrm_employees_department_id_foreign` (`department_id`),
  ADD KEY `hrm_employees_designation_id_foreign` (`designation_id`),
  ADD KEY `hrm_employees_education_id_foreign` (`education_id`),
  ADD KEY `hrm_employees_user_id_foreign` (`user_id`);

--
-- Indexes for table `hrm_employee_documents`
--
ALTER TABLE `hrm_employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_employee_documents_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hrm_employee_shifts`
--
ALTER TABLE `hrm_employee_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_employee_shifts_employee_id_index` (`employee_id`),
  ADD KEY `hrm_employee_shifts_hrm_shift_id_index` (`hrm_shift_id`);

--
-- Indexes for table `hrm_leave_categories`
--
ALTER TABLE `hrm_leave_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hrm_leave_categories_leave_category_unique` (`leave_category`),
  ADD KEY `hrm_leave_categories_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_notification_templates`
--
ALTER TABLE `hrm_notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_shifts_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_transactions`
--
ALTER TABLE `hrm_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_transactions_campus_id_foreign` (`campus_id`),
  ADD KEY `hrm_transactions_session_id_foreign` (`session_id`),
  ADD KEY `hrm_transactions_employee_id_foreign` (`employee_id`),
  ADD KEY `hrm_transactions_created_by_foreign` (`created_by`);

--
-- Indexes for table `hrm_transaction_payments`
--
ALTER TABLE `hrm_transaction_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_transaction_payments_hrm_transaction_id_foreign` (`hrm_transaction_id`),
  ADD KEY `hrm_transaction_payments_session_id_foreign` (`session_id`),
  ADD KEY `hrm_transaction_payments_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `leave_application_employees`
--
ALTER TABLE `leave_application_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_application_employees_session_id_foreign` (`session_id`),
  ADD KEY `leave_application_employees_campus_id_foreign` (`campus_id`),
  ADD KEY `leave_application_employees_employee_id_foreign` (`employee_id`),
  ADD KEY `leave_application_employees_approve_by_foreign` (`approve_by`);

--
-- Indexes for table `leave_application_students`
--
ALTER TABLE `leave_application_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_application_students_session_id_foreign` (`session_id`),
  ADD KEY `leave_application_students_campus_id_foreign` (`campus_id`),
  ADD KEY `leave_application_students_class_id_foreign` (`class_id`),
  ADD KEY `leave_application_students_class_section_id_foreign` (`class_section_id`),
  ADD KEY `leave_application_students_student_id_foreign` (`student_id`),
  ADD KEY `leave_application_students_approve_by_foreign` (`approve_by`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_system_settings_id_index` (`system_settings_id`),
  ADD KEY `media_uploaded_by_index` (`uploaded_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `note_book_status`
--
ALTER TABLE `note_book_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_book_status_campus_id_foreign` (`campus_id`),
  ADD KEY `note_book_status_class_id_foreign` (`class_id`),
  ADD KEY `note_book_status_class_section_id_foreign` (`class_section_id`),
  ADD KEY `note_book_status_subject_id_foreign` (`subject_id`),
  ADD KEY `note_book_status_student_id_foreign` (`student_id`),
  ADD KEY `note_book_status_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provinces_country_id_foreign` (`country_id`),
  ADD KEY `provinces_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `provinces_created_by_foreign` (`created_by`);

--
-- Indexes for table `reference_counts`
--
ALTER TABLE `reference_counts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reference_counts_session_id_foreign` (`session_id`),
  ADD KEY `reference_counts_system_settings_id_foreign` (`system_settings_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regions_country_id_foreign` (`country_id`),
  ADD KEY `regions_province_id_foreign` (`province_id`),
  ADD KEY `regions_district_id_foreign` (`district_id`),
  ADD KEY `regions_city_id_foreign` (`city_id`),
  ADD KEY `regions_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `regions_created_by_foreign` (`created_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_system_settings_id_foreign` (`system_settings_id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sims`
--
ALTER TABLE `sims`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_admission_no_unique` (`admission_no`),
  ADD UNIQUE KEY `students_roll_no_unique` (`roll_no`),
  ADD KEY `students_campus_id_foreign` (`campus_id`),
  ADD KEY `students_adm_session_id_foreign` (`adm_session_id`),
  ADD KEY `students_cur_session_id_foreign` (`cur_session_id`),
  ADD KEY `students_admission_class_id_foreign` (`adm_class_id`),
  ADD KEY `students_current_class_id_foreign` (`current_class_id`),
  ADD KEY `students_adm_class_section_id_foreign` (`adm_class_section_id`),
  ADD KEY `students_current_class_section_id_foreign` (`current_class_section_id`),
  ADD KEY `students_category_id_foreign` (`category_id`),
  ADD KEY `students_domicile_id_foreign` (`domicile_id`),
  ADD KEY `students_country_id_foreign` (`country_id`),
  ADD KEY `students_province_id_foreign` (`province_id`),
  ADD KEY `students_district_id_foreign` (`district_id`),
  ADD KEY `students_city_id_foreign` (`city_id`),
  ADD KEY `students_region_id_foreign` (`region_id`),
  ADD KEY `students_system_settings_id_foreign` (`system_settings_id`),
  ADD KEY `students_discount_id_foreign` (`discount_id`),
  ADD KEY `students_created_by_foreign` (`created_by`),
  ADD KEY `students_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_documents_student_id_foreign` (`student_id`);

--
-- Indexes for table `student_guardians`
--
ALTER TABLE `student_guardians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_guardians_student_id_foreign` (`student_id`),
  ADD KEY `student_guardians_guardian_id_foreign` (`guardian_id`);

--
-- Indexes for table `subject_chapters`
--
ALTER TABLE `subject_chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_chapters_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `subject_question_banks`
--
ALTER TABLE `subject_question_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_question_banks_subject_id_foreign` (`subject_id`),
  ADD KEY `subject_question_banks_chapter_id_foreign` (`chapter_id`),
  ADD KEY `subject_question_banks_created_by_foreign` (`created_by`);

--
-- Indexes for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_teachers_campus_id_foreign` (`campus_id`),
  ADD KEY `subject_teachers_class_id_foreign` (`class_id`),
  ADD KEY `subject_teachers_class_section_id_foreign` (`class_section_id`),
  ADD KEY `subject_teachers_subject_id_foreign` (`subject_id`),
  ADD KEY `subject_teachers_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `syllabus_mangers`
--
ALTER TABLE `syllabus_mangers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `syllabus_mangers_campus_id_foreign` (`campus_id`),
  ADD KEY `syllabus_mangers_class_id_foreign` (`class_id`),
  ADD KEY `syllabus_mangers_exam_term_id_foreign` (`exam_term_id`),
  ADD KEY `syllabus_mangers_subject_id_foreign` (`subject_id`),
  ADD KEY `syllabus_mangers_chapter_id_foreign` (`chapter_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_settings_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_system_settings_id_foreign` (`system_settings_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicles_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `weekend_holidays`
--
ALTER TABLE `weekend_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawal_registers`
--
ALTER TABLE `withdrawal_registers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withdrawal_registers_student_id_unique` (`student_id`),
  ADD KEY `withdrawal_registers_adm_session_id_foreign` (`adm_session_id`),
  ADD KEY `withdrawal_registers_campus_id_foreign` (`campus_id`),
  ADD KEY `withdrawal_registers_admission_class_id_foreign` (`admission_class_id`),
  ADD KEY `withdrawal_registers_leaving_session_id_foreign` (`leaving_session_id`),
  ADD KEY `withdrawal_registers_leaving_class_id_foreign` (`leaving_class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barcodes`
--
ALTER TABLE `barcodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `campuses`
--
ALTER TABLE `campuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `certificate_issues`
--
ALTER TABLE `certificate_issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_types`
--
ALTER TABLE `certificate_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_levels`
--
ALTER TABLE `class_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_subject_lessons`
--
ALTER TABLE `class_subject_lessons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_subject_progress`
--
ALTER TABLE `class_subject_progress`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_timetables`
--
ALTER TABLE `class_timetables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_timetable_periods`
--
ALTER TABLE `class_timetable_periods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_allocations`
--
ALTER TABLE `exam_allocations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_creates`
--
ALTER TABLE `exam_creates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_date_sheets`
--
ALTER TABLE `exam_date_sheets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_grades`
--
ALTER TABLE `exam_grades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_subject_results`
--
ALTER TABLE `exam_subject_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_terms`
--
ALTER TABLE `exam_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_transactions`
--
ALTER TABLE `expense_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_transaction_payments`
--
ALTER TABLE `expense_transaction_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_heads`
--
ALTER TABLE `fee_heads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `fee_increment_decrement`
--
ALTER TABLE `fee_increment_decrement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_increment_decrements`
--
ALTER TABLE `fee_increment_decrements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_transactions`
--
ALTER TABLE `fee_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_transaction_lines`
--
ALTER TABLE `fee_transaction_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_transaction_payments`
--
ALTER TABLE `fee_transaction_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_about_us`
--
ALTER TABLE `front_about_us`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `front_events`
--
ALTER TABLE `front_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_gallery_categories`
--
ALTER TABLE `front_gallery_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `front_gallery_contents`
--
ALTER TABLE `front_gallery_contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `front_news`
--
ALTER TABLE `front_news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `front_notices`
--
ALTER TABLE `front_notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_sliders`
--
ALTER TABLE `front_sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `global_settings`
--
ALTER TABLE `global_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guardians`
--
ALTER TABLE `guardians`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_allowances`
--
ALTER TABLE `hrm_allowances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_allowance_transaction_lines`
--
ALTER TABLE `hrm_allowance_transaction_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_deductions`
--
ALTER TABLE `hrm_deductions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_deduction_transaction_lines`
--
ALTER TABLE `hrm_deduction_transaction_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_departments`
--
ALTER TABLE `hrm_departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `hrm_education`
--
ALTER TABLE `hrm_education`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hrm_employees`
--
ALTER TABLE `hrm_employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_employee_documents`
--
ALTER TABLE `hrm_employee_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_employee_shifts`
--
ALTER TABLE `hrm_employee_shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_leave_categories`
--
ALTER TABLE `hrm_leave_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_notification_templates`
--
ALTER TABLE `hrm_notification_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_transactions`
--
ALTER TABLE `hrm_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_transaction_payments`
--
ALTER TABLE `hrm_transaction_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `leave_application_employees`
--
ALTER TABLE `leave_application_employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_application_students`
--
ALTER TABLE `leave_application_students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `note_book_status`
--
ALTER TABLE `note_book_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reference_counts`
--
ALTER TABLE `reference_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sims`
--
ALTER TABLE `sims`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_guardians`
--
ALTER TABLE `student_guardians`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_chapters`
--
ALTER TABLE `subject_chapters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_question_banks`
--
ALTER TABLE `subject_question_banks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `syllabus_mangers`
--
ALTER TABLE `syllabus_mangers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekend_holidays`
--
ALTER TABLE `weekend_holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawal_registers`
--
ALTER TABLE `withdrawal_registers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD CONSTRAINT `account_transactions_expense_transaction_payment_id_foreign` FOREIGN KEY (`expense_transaction_payment_id`) REFERENCES `expense_transaction_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_transactions_hrm_transaction_payment_id_foreign` FOREIGN KEY (`hrm_transaction_payment_id`) REFERENCES `hrm_transaction_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_transactions_transaction_payment_id_foreign` FOREIGN KEY (`transaction_payment_id`) REFERENCES `fee_transaction_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_types`
--
ALTER TABLE `account_types`
  ADD CONSTRAINT `account_types_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `awards`
--
ALTER TABLE `awards`
  ADD CONSTRAINT `awards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `awards_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `barcodes`
--
ALTER TABLE `barcodes`
  ADD CONSTRAINT `barcodes_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campuses`
--
ALTER TABLE `campuses`
  ADD CONSTRAINT `campuses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campuses_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categories_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificate_issues`
--
ALTER TABLE `certificate_issues`
  ADD CONSTRAINT `certificate_issues_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificate_issues_certificate_type_id_foreign` FOREIGN KEY (`certificate_type_id`) REFERENCES `certificate_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificate_issues_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `cities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cities_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `cities_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_levels`
--
ALTER TABLE `class_levels`
  ADD CONSTRAINT `class_levels_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_levels_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD CONSTRAINT `class_sections_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_sections_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_sections_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_sections_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD CONSTRAINT `class_subjects_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subjects_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subjects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_subject_lessons`
--
ALTER TABLE `class_subject_lessons`
  ADD CONSTRAINT `class_subject_lessons_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_lessons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_lessons_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_subject_progress`
--
ALTER TABLE `class_subject_progress`
  ADD CONSTRAINT `class_subject_progress_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_progress_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `class_subject_lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_progress_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_progress_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_progress_teacher_by_foreign` FOREIGN KEY (`teacher_by`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_timetables`
--
ALTER TABLE `class_timetables`
  ADD CONSTRAINT `class_timetables_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_timetables_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_timetables_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_timetables_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `class_timetable_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_timetables_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_timetable_periods`
--
ALTER TABLE `class_timetable_periods`
  ADD CONSTRAINT `class_timetable_periods_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `districts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `districts_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `districts_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_allocations`
--
ALTER TABLE `exam_allocations`
  ADD CONSTRAINT `exam_allocations_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_allocations_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_allocations_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_allocations_exam_create_id_foreign` FOREIGN KEY (`exam_create_id`) REFERENCES `exam_creates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_allocations_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `exam_grades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_allocations_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_allocations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_creates`
--
ALTER TABLE `exam_creates`
  ADD CONSTRAINT `exam_creates_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_creates_exam_term_id_foreign` FOREIGN KEY (`exam_term_id`) REFERENCES `exam_terms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_creates_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_date_sheets`
--
ALTER TABLE `exam_date_sheets`
  ADD CONSTRAINT `exam_date_sheets_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_date_sheets_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_date_sheets_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_date_sheets_exam_create_id_foreign` FOREIGN KEY (`exam_create_id`) REFERENCES `exam_creates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_date_sheets_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_date_sheets_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_subject_results`
--
ALTER TABLE `exam_subject_results`
  ADD CONSTRAINT `exam_subject_results_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_exam_allocation_id_foreign` FOREIGN KEY (`exam_allocation_id`) REFERENCES `exam_allocations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_exam_create_id_foreign` FOREIGN KEY (`exam_create_id`) REFERENCES `exam_creates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `exam_grades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_subject_results_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_transactions`
--
ALTER TABLE `expense_transactions`
  ADD CONSTRAINT `expense_transactions_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `expense_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_transactions_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_transactions_expense_for_foreign` FOREIGN KEY (`expense_for`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_transactions_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_transactions_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_transaction_payments`
--
ALTER TABLE `expense_transaction_payments`
  ADD CONSTRAINT `expense_transaction_payments_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `expense_transaction_payments_expense_transaction_id_foreign` FOREIGN KEY (`expense_transaction_id`) REFERENCES `expense_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_transaction_payments_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_heads`
--
ALTER TABLE `fee_heads`
  ADD CONSTRAINT `fee_heads_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_heads_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_increment_decrements`
--
ALTER TABLE `fee_increment_decrements`
  ADD CONSTRAINT `fee_increment_decrement_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `fee_increment_decrement_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_increment_decrement_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_increment_decrement_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_transactions`
--
ALTER TABLE `fee_transactions`
  ADD CONSTRAINT `fee_transactions_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `fee_transactions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transactions_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transactions_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transactions_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_transaction_lines`
--
ALTER TABLE `fee_transaction_lines`
  ADD CONSTRAINT `fee_transaction_lines_fee_head_id_foreign` FOREIGN KEY (`fee_head_id`) REFERENCES `fee_heads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transaction_lines_fee_transaction_id_foreign` FOREIGN KEY (`fee_transaction_id`) REFERENCES `fee_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_transaction_payments`
--
ALTER TABLE `fee_transaction_payments`
  ADD CONSTRAINT `fee_transaction_payments_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `fee_transaction_payments_fee_transaction_id_foreign` FOREIGN KEY (`fee_transaction_id`) REFERENCES `fee_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transaction_payments_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_transaction_payments_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `front_gallery_contents`
--
ALTER TABLE `front_gallery_contents`
  ADD CONSTRAINT `front_galleries_content_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `front_gallery_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD CONSTRAINT `global_settings_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `global_settings_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`);

--
-- Constraints for table `hrm_allowances`
--
ALTER TABLE `hrm_allowances`
  ADD CONSTRAINT `hrm_allowances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_allowance_transaction_lines`
--
ALTER TABLE `hrm_allowance_transaction_lines`
  ADD CONSTRAINT `hrm_allowance_transaction_lines_allowance_id_foreign` FOREIGN KEY (`allowance_id`) REFERENCES `hrm_allowances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_allowance_transaction_lines_hrm_transaction_id_foreign` FOREIGN KEY (`hrm_transaction_id`) REFERENCES `hrm_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD CONSTRAINT `hrm_attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_attendances_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_deductions`
--
ALTER TABLE `hrm_deductions`
  ADD CONSTRAINT `hrm_deductions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_deduction_transaction_lines`
--
ALTER TABLE `hrm_deduction_transaction_lines`
  ADD CONSTRAINT `hrm_deduction_transaction_lines_deduction_id_foreign` FOREIGN KEY (`deduction_id`) REFERENCES `hrm_deductions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_deduction_transaction_lines_hrm_transaction_id_foreign` FOREIGN KEY (`hrm_transaction_id`) REFERENCES `hrm_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_departments`
--
ALTER TABLE `hrm_departments`
  ADD CONSTRAINT `hrm_departments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  ADD CONSTRAINT `hrm_designations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_education`
--
ALTER TABLE `hrm_education`
  ADD CONSTRAINT `hrm_educations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_employees`
--
ALTER TABLE `hrm_employees`
  ADD CONSTRAINT `hrm_employees_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_employees_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_employees_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hrm_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hrm_employees_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `hrm_designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hrm_employees_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_employees_education_id_foreign` FOREIGN KEY (`education_id`) REFERENCES `hrm_education` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hrm_employees_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_employees_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_employee_documents`
--
ALTER TABLE `hrm_employee_documents`
  ADD CONSTRAINT `hrm_employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hrm_leave_categories`
--
ALTER TABLE `hrm_leave_categories`
  ADD CONSTRAINT `hrm_leave_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  ADD CONSTRAINT `hrm_shifts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_transactions`
--
ALTER TABLE `hrm_transactions`
  ADD CONSTRAINT `hrm_transactions_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `hrm_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_transactions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_transactions_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_transaction_payments`
--
ALTER TABLE `hrm_transaction_payments`
  ADD CONSTRAINT `hrm_transaction_payments_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `hrm_transaction_payments_hrm_transaction_id_foreign` FOREIGN KEY (`hrm_transaction_id`) REFERENCES `hrm_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_transaction_payments_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_application_employees`
--
ALTER TABLE `leave_application_employees`
  ADD CONSTRAINT `leave_application_employees_approve_by_foreign` FOREIGN KEY (`approve_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_application_employees_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `leave_application_employees_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_application_employees_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_application_students`
--
ALTER TABLE `leave_application_students`
  ADD CONSTRAINT `leave_application_students_approve_by_foreign` FOREIGN KEY (`approve_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_application_students_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `leave_application_students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_application_students_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_application_students_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_application_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `note_book_status`
--
ALTER TABLE `note_book_status`
  ADD CONSTRAINT `note_book_status_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `note_book_status_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_book_status_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_book_status_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_book_status_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_book_status_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provinces`
--
ALTER TABLE `provinces`
  ADD CONSTRAINT `provinces_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `provinces_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provinces_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reference_counts`
--
ALTER TABLE `reference_counts`
  ADD CONSTRAINT `reference_counts_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reference_counts_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `regions`
--
ALTER TABLE `regions`
  ADD CONSTRAINT `regions_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `regions_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `regions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `regions_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `regions_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `regions_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_adm_class_section_id_foreign` FOREIGN KEY (`adm_class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_adm_session_id_foreign` FOREIGN KEY (`adm_session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_admission_class_id_foreign` FOREIGN KEY (`adm_class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_cur_session_id_foreign` FOREIGN KEY (`cur_session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_current_class_id_foreign` FOREIGN KEY (`current_class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_current_class_section_id_foreign` FOREIGN KEY (`current_class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_domicile_id_foreign` FOREIGN KEY (`domicile_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD CONSTRAINT `student_documents_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_guardians`
--
ALTER TABLE `student_guardians`
  ADD CONSTRAINT `student_guardians_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_guardians_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subject_chapters`
--
ALTER TABLE `subject_chapters`
  ADD CONSTRAINT `subject_chapters_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subject_question_banks`
--
ALTER TABLE `subject_question_banks`
  ADD CONSTRAINT `subject_question_banks_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `subject_chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_question_banks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_question_banks_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  ADD CONSTRAINT `subject_teachers_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_teachers_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_teachers_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_teachers_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `syllabus_mangers`
--
ALTER TABLE `syllabus_mangers`
  ADD CONSTRAINT `syllabus_mangers_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `syllabus_mangers_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `subject_chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `syllabus_mangers_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `syllabus_mangers_exam_term_id_foreign` FOREIGN KEY (`exam_term_id`) REFERENCES `exam_terms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `syllabus_mangers_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD CONSTRAINT `system_settings_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_system_settings_id_foreign` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawal_registers`
--
ALTER TABLE `withdrawal_registers`
  ADD CONSTRAINT `withdrawal_registers_adm_session_id_foreign` FOREIGN KEY (`adm_session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `withdrawal_registers_admission_class_id_foreign` FOREIGN KEY (`admission_class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `withdrawal_registers_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `withdrawal_registers_leaving_class_id_foreign` FOREIGN KEY (`leaving_class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `withdrawal_registers_leaving_session_id_foreign` FOREIGN KEY (`leaving_session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `withdrawal_registers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
