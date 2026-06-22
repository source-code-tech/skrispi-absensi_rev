-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for e_absensi_siswa
DROP DATABASE IF EXISTS `e_absensi_siswa`;
CREATE DATABASE IF NOT EXISTS `e_absensi_siswa` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `e_absensi_siswa`;

-- Dumping structure for table e_absensi_siswa.absences
DROP TABLE IF EXISTS `absences`;
CREATE TABLE IF NOT EXISTS `absences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `attendance_time` datetime NOT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `status` enum('Hadir','Terlambat','Sakit','Izin','Alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_duration` int DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorded_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_manual_corrected` tinyint(1) NOT NULL DEFAULT '0',
  `corrected_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correction_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `absences_student_id_attendance_time_unique` (`student_id`,`attendance_time`),
  CONSTRAINT `absences_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.absences: ~0 rows (approximately)
DELETE FROM `absences`;
INSERT INTO `absences` (`id`, `student_id`, `attendance_time`, `checkout_time`, `status`, `notes`, `latitude`, `longitude`, `ip_address`, `late_duration`, `reason`, `recorded_by`, `is_manual_corrected`, `corrected_by`, `correction_note`, `created_at`, `updated_at`) VALUES
	(1, 163, '2026-06-03 18:12:01', NULL, 'Hadir', NULL, NULL, NULL, NULL, NULL, NULL, 'Ustadz Ahmad Fauzi, S.Pd.I.', 0, NULL, NULL, '2026-06-03 11:12:02', '2026-06-03 11:12:02');

-- Dumping structure for table e_absensi_siswa.announcements
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` enum('all','class') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `target_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_target_id_foreign` (`target_id`),
  CONSTRAINT `announcements_target_id_foreign` FOREIGN KEY (`target_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.announcements: ~0 rows (approximately)
DELETE FROM `announcements`;
INSERT INTO `announcements` (`id`, `title`, `content`, `target_type`, `target_id`, `is_active`, `created_at`, `updated_at`) VALUES
	(2, 'PENERIMAAN PEMBAGIAN RAPORT KELAS 1A', '<p>Assalamualaikum Bapak/ibu wali murid&nbsp;</p>', 'class', NULL, 1, '2026-05-23 12:02:03', '2026-05-23 12:02:03');

-- Dumping structure for table e_absensi_siswa.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.cache: ~4 rows (approximately)
DELETE FROM `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-attendance_settings', 'O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:3:{s:19:"attendance_end_time";s:5:"19:00";s:21:"attendance_start_time";s:5:"18:10";s:22:"late_tolerance_minutes";s:1:"5";}s:28:"\0*\0escapeWhenCastingToString";b:0;}', 1780485123),
	('laravel-cache-max_absensi_settings_full', 'O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:1:{s:21:"attendance_start_time";s:5:"16:30";}s:28:"\0*\0escapeWhenCastingToString";b:0;}', 1780487846),
	('laravel-cache-wk1@test.com|::1', 'i:1;', 1780062681),
	('laravel-cache-wk1@test.com|::1:timer', 'i:1780062681;', 1780062681);

-- Dumping structure for table e_absensi_siswa.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table e_absensi_siswa.classes
DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `major` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dismissal_time` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `classes_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.classes: ~6 rows (approximately)
DELETE FROM `classes`;
INSERT INTO `classes` (`id`, `name`, `grade`, `major`, `description`, `status`, `created_at`, `updated_at`, `dismissal_time`) VALUES
	(1, '1A', '1', 'UMUM', NULL, 'active', '2026-04-15 09:23:24', '2026-05-29 10:52:44', '20:00:00'),
	(2, '1B', '1', 'UMUM', NULL, 'active', '2026-04-15 09:23:24', '2026-05-29 10:53:17', '20:00:00'),
	(3, '2A', '2', 'UMUM', NULL, 'active', '2026-04-15 09:23:24', '2026-05-29 10:53:37', '20:00:00'),
	(4, '3B', '3', 'UMUM', NULL, 'active', '2026-04-15 09:23:24', '2026-05-29 10:55:11', '20:00:00'),
	(5, '2B', '2', 'UMUM', NULL, 'active', '2026-04-15 09:23:24', '2026-05-29 10:54:06', '20:00:00'),
	(6, '3A', '3', 'UMUM', NULL, 'active', '2026-04-15 09:23:24', '2026-05-29 10:54:33', '20:00:00');

-- Dumping structure for table e_absensi_siswa.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table e_absensi_siswa.holidays
DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `holidays_date_unique` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.holidays: ~27 rows (approximately)
DELETE FROM `holidays`;
INSERT INTO `holidays` (`id`, `date`, `name`, `created_at`, `updated_at`) VALUES
	(5, '2026-01-01', 'New Year\'s Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(6, '2026-01-16', 'Ascension of the Prophet Muhammad', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(7, '2026-02-16', 'Chinese New Year Joint Holiday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(8, '2026-02-17', 'Chinese New Year\'s Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(9, '2026-02-19', 'Ramadan Start', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(10, '2026-03-18', 'Joint Holiday for Bali\'s Day of Silence and Hindu New Year (Nyepi)', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(11, '2026-03-19', 'Bali\'s Day of Silence and Hindu New Year (Nyepi)', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(12, '2026-03-20', 'Idul Fitri Joint Holiday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(13, '2026-03-21', 'Idul Fitri', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(14, '2026-03-22', 'Idul Fitri Holiday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(15, '2026-03-23', 'Idul Fitri Joint Holiday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(16, '2026-03-24', 'Idul Fitri Joint Holiday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(17, '2026-04-03', 'Good Friday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(18, '2026-04-05', 'Easter Sunday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(19, '2026-05-01', 'International Labor Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(20, '2026-05-14', 'Ascension Day of Jesus Christ', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(21, '2026-05-15', 'Joint Holiday after Ascension Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(22, '2026-05-27', 'Idul Adha', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(23, '2026-05-28', 'Joint Holiday for Idul Adha', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(24, '2026-05-31', 'Waisak Day (Buddha\'s Anniversary) (tentative)', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(25, '2026-06-01', 'Pancasila Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(26, '2026-06-16', 'Muharram / Islamic New Year (tentative)', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(27, '2026-08-17', 'Indonesian Independence Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(28, '2026-08-25', 'Maulid Nabi Muhammad (tentative)', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(29, '2026-12-24', 'Christmas Eve Joint Holiday', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(30, '2026-12-25', 'Christmas Day', '2026-05-29 17:41:24', '2026-05-29 17:41:24'),
	(31, '2026-12-31', 'New Year\'s Eve', '2026-05-29 17:41:24', '2026-05-29 17:41:24');

-- Dumping structure for table e_absensi_siswa.homeroom_teachers
DROP TABLE IF EXISTS `homeroom_teachers`;
CREATE TABLE IF NOT EXISTS `homeroom_teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `scan_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `homeroom_teachers_user_id_class_id_unique` (`user_id`,`class_id`),
  UNIQUE KEY `homeroom_teachers_class_id_unique` (`class_id`),
  UNIQUE KEY `homeroom_teachers_scan_token_unique` (`scan_token`),
  CONSTRAINT `homeroom_teachers_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `homeroom_teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.homeroom_teachers: ~6 rows (approximately)
DELETE FROM `homeroom_teachers`;
INSERT INTO `homeroom_teachers` (`id`, `user_id`, `scan_token`, `class_id`, `created_at`, `updated_at`) VALUES
	(1, 9, 'soDmnIAO98BhARoaI9e6TfLCg9DYMdGS', 1, '2026-04-15 09:23:27', '2026-05-23 16:08:33'),
	(2, 10, '9eyw3UYvPTmmJaGdIPG3iL1hZcWHpV91', 2, '2026-04-15 09:23:29', '2026-05-23 16:09:24'),
	(3, 11, 'u78Uw3p9qmELGZOyERpVW0Lkgz0sR45m', 3, '2026-04-15 09:23:32', '2026-05-23 16:09:35'),
	(4, 12, 'VAZ12mZvf7vbchvATblrfhYRSTTM7ttq', 4, '2026-04-15 09:23:34', '2026-05-23 16:10:02'),
	(5, 13, 'e0mJiAn1LFqvil5B0tYD7udR3gfyEhx7', 5, '2026-04-15 09:23:36', '2026-05-23 16:09:45'),
	(6, 14, 'WcrPuSM0gabyAjyphYokwWPAfrLov9Yn', 6, '2026-04-15 09:23:38', '2026-05-23 16:09:53');

-- Dumping structure for table e_absensi_siswa.izin_requests
DROP TABLE IF EXISTS `izin_requests`;
CREATE TABLE IF NOT EXISTS `izin_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `request_date` date NOT NULL,
  `type` enum('Sakit','Izin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_date_unique` (`student_id`,`request_date`),
  KEY `izin_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `izin_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `izin_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.izin_requests: ~1 rows (approximately)
DELETE FROM `izin_requests`;
INSERT INTO `izin_requests` (`id`, `student_id`, `request_date`, `type`, `reason`, `attachment_path`, `status`, `approved_by`, `created_at`, `updated_at`) VALUES
	(1, 158, '2026-05-29', 'Sakit', 'sakit', NULL, 'Approved', 10, '2026-05-29 13:04:33', '2026-05-29 13:07:32');

-- Dumping structure for table e_absensi_siswa.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table e_absensi_siswa.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table e_absensi_siswa.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.migrations: ~23 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_11_02_125622_add_role_to_users_table', 1),
	(5, '2025_11_02_140334_create_personal_access_tokens_table', 1),
	(6, '2025_11_02_144311_create_class_models_table', 1),
	(7, '2025_11_02_145023_create_students_table', 1),
	(8, '2025_11_03_005701_create_homeroom_teachers_table', 1),
	(9, '2025_11_03_010534_create_parent_models_table', 1),
	(10, '2025_11_03_010749_create_parent_student_table', 1),
	(11, '2025_11_03_021930_create_absences_table', 1),
	(12, '2025_11_03_021954_create_settings_table', 1),
	(13, '2025_11_03_110810_add_status_to_classes_table', 1),
	(14, '2025_11_03_115348_add_status_to_students_table', 1),
	(15, '2025_11_03_235337_add_checkout_time_to_absences_table', 1),
	(16, '2025_11_04_030820_add_photo_to_students_table', 1),
	(17, '2025_11_04_044407_add_address_to_students_table', 1),
	(18, '2025_11_04_104306_add_email_to_students_table', 1),
	(19, '2025_11_04_105051_add_major_and_desc_to_classes_table', 1),
	(20, '2025_11_08_111315_add_birth_fields_to_students_table', 1),
	(21, '2025_11_08_152427_add_is_approved_to_users_table', 1),
	(22, '2025_11_11_094522_change_late_duration_to_integer_in_absences_table', 1),
	(23, '2025_11_11_101502_add_attendance_end_time_to_settings_table', 1),
	(24, '2025_11_11_141055_add_audit_fields_to_absences_table', 1),
	(25, '2025_11_11_153717_create_izin_requests_table', 1),
	(26, '2025_11_12_211635_make_class_id_nullable_on_homeroom_teachers_table', 1),
	(27, '2025_12_10_202521_create_announcements_table', 1),
	(28, '2025_12_10_211845_create_subjects_table', 1),
	(29, '2025_12_10_211850_create_schedules_table', 1),
	(30, '2025_12_11_130332_make_phone_number_nullable_in_parents_table', 1),
	(31, '2025_12_11_130631_fix_parents_nullable_sql', 1),
	(32, '2026_01_30_214210_add_geo_and_ip_to_absences_table', 2),
	(33, '2026_01_31_194651_add_teacher_id_to_schedules_table', 2),
	(34, '2026_01_31_194657_create_teaching_journals_table', 2),
	(35, '2026_01_31_194701_create_subject_attendances_table', 2),
	(36, '2026_01_31_201712_modify_role_enum_in_users_table', 2),
	(37, '2026_02_01_130811_modify_role_column_in_users_table', 2),
	(38, '2026_02_03_222913_add_is_demo_to_users_table', 2),
	(39, '2026_02_03_230000_add_is_demo_to_users_table', 2),
	(40, '2026_02_10_213810_create_teacher_attendances_table', 2),
	(41, '2026_02_10_221000_seed_school_location_settings', 2),
	(42, '2026_02_11_170756_add_nip_to_users_table', 2),
	(43, '2026_04_15_154009_add_photo_to_users_table', 3),
	(44, '2026_04_15_193731_fix_status_enum_alpha_in_absences_table', 3),
	(45, '2026_05_12_170851_add_dismissal_time_to_classes_table', 4),
	(46, '2026_05_17_225433_create_holidays_table', 5),
	(47, '2026_05_18_152505_add_notes_to_absences_table', 6),
	(48, '2026_05_23_212524_add_scan_token_to_homeroom_teachers_table', 7);

-- Dumping structure for table e_absensi_siswa.parents
DROP TABLE IF EXISTS `parents`;
CREATE TABLE IF NOT EXISTS `parents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relation_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parents_phone_number_unique` (`phone_number`),
  KEY `parents_user_id_foreign` (`user_id`),
  CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.parents: ~29 rows (approximately)
DELETE FROM `parents`;
INSERT INTO `parents` (`id`, `user_id`, `name`, `relation_status`, `phone_number`, `created_at`, `updated_at`) VALUES
	(52, 74, 'Parent 1', 'Ayah', '6285697760499', '2026-04-15 19:43:59', '2026-05-29 09:08:04'),
	(53, 75, 'Parent 2', 'Ayah', '628561182801', '2026-04-15 19:44:45', '2026-05-29 09:08:31'),
	(54, 76, 'Parent 3', 'Ayah', '085211982776', '2026-04-16 10:40:55', '2026-05-29 09:05:31'),
	(55, 78, 'Parent 4', 'Ayah', '08561182801', '2026-05-13 17:37:45', '2026-05-29 09:09:22'),
	(56, 79, 'Parent 5', 'Ayah', '081234567801', '2026-05-29 09:11:20', '2026-05-29 09:11:20'),
	(57, 80, 'Parent 6', 'Ayah', '081234567802', '2026-05-29 09:12:41', '2026-05-29 09:12:41'),
	(58, 81, 'Parent 7', 'Ayah', '081234567803', '2026-05-29 09:13:24', '2026-05-29 09:13:24'),
	(59, 82, 'Parent 8', 'Ayah', '081234567804', '2026-05-29 09:14:10', '2026-05-29 09:14:10'),
	(60, 83, 'Parent 9', 'Ayah', '081234567805', '2026-05-29 09:15:00', '2026-05-29 09:15:00'),
	(61, 84, 'Parent 10', 'Ayah', '081234567806', '2026-05-29 09:15:52', '2026-05-29 09:15:52'),
	(62, 85, 'Parent 11', 'Ayah', '081234567807', '2026-05-29 09:16:30', '2026-05-29 09:16:30'),
	(63, 86, 'Parent 12', 'Ayah', '081401785206', '2026-05-29 09:17:03', '2026-05-29 12:09:15'),
	(64, 87, 'Parent 13', 'Ayah', '081234567809', '2026-05-29 09:17:40', '2026-05-29 09:17:40'),
	(65, 88, 'Parent 14', 'Ayah', '081234567810', '2026-05-29 09:18:32', '2026-05-29 09:18:32'),
	(66, 89, 'Parent 15', 'Ayah', '085718631664', '2026-05-29 09:19:11', '2026-05-29 12:31:11'),
	(67, 90, 'Parent 16', 'Ayah', '081234567812', '2026-05-29 09:20:02', '2026-05-29 09:20:02'),
	(68, 91, 'Parent 17', 'Ayah', '081234567815', '2026-05-29 09:20:54', '2026-05-29 09:20:54'),
	(69, 92, 'Parent 18', 'Ayah', '081234567816', '2026-05-29 09:21:34', '2026-05-29 09:21:34'),
	(70, 93, 'Parent 19', 'Ayah', '081234567817', '2026-05-29 09:22:12', '2026-05-29 09:22:12'),
	(71, 94, 'Parent 20', 'Ayah', '081234567818', '2026-05-29 09:22:53', '2026-05-29 09:22:53'),
	(72, 95, 'Parent 21', 'Ayah', '081234567819', '2026-05-29 09:23:28', '2026-05-29 09:23:28'),
	(73, 96, 'Parent 22', 'Ayah', '081234567820', '2026-05-29 09:24:00', '2026-05-29 09:24:00'),
	(74, 97, 'Parent 23', 'Ayah', '081234567821', '2026-05-29 09:24:52', '2026-05-29 09:24:52'),
	(75, 98, 'Parent 24', 'Ayah', '081234567822', '2026-05-29 09:25:32', '2026-05-29 09:25:32'),
	(76, 99, 'Parent 25', 'Ayah', '081234567823', '2026-05-29 09:26:14', '2026-05-29 09:26:14'),
	(77, 100, 'Parent 26', 'Ayah', '081234567824', '2026-05-29 09:26:57', '2026-05-29 09:26:57'),
	(78, 101, 'Parent 27', 'Ayah', '081234567825', '2026-05-29 09:27:34', '2026-05-29 09:27:34'),
	(79, 102, 'Parent 28', 'Ayah', '081234567826', '2026-05-29 09:28:49', '2026-05-29 09:28:49'),
	(80, 103, 'Parent 29', 'Ayah', '081234567827', '2026-05-29 09:29:33', '2026-05-29 09:29:33'),
	(81, 104, 'Parent 30', 'Ayah', '081234567828', '2026-05-29 09:30:02', '2026-05-29 09:30:02'),
	(82, 105, 'Parent 31', 'Ayah', '081234567829', '2026-05-29 09:30:48', '2026-05-29 09:30:48');

-- Dumping structure for table e_absensi_siswa.parent_student
DROP TABLE IF EXISTS `parent_student`;
CREATE TABLE IF NOT EXISTS `parent_student` (
  `parent_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`parent_id`,`student_id`),
  KEY `parent_student_student_id_foreign` (`student_id`),
  CONSTRAINT `parent_student_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `parent_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.parent_student: ~29 rows (approximately)
DELETE FROM `parent_student`;
INSERT INTO `parent_student` (`parent_id`, `student_id`) VALUES
	(52, 152),
	(53, 153),
	(54, 154),
	(55, 155),
	(56, 156),
	(57, 157),
	(58, 158),
	(59, 159),
	(60, 160),
	(61, 161),
	(62, 162),
	(63, 163),
	(64, 164),
	(65, 165),
	(66, 166),
	(67, 167),
	(68, 168),
	(69, 169),
	(70, 170),
	(71, 171),
	(72, 172),
	(73, 173),
	(74, 174),
	(75, 175),
	(76, 176),
	(77, 177),
	(78, 178),
	(79, 179),
	(80, 180),
	(81, 181),
	(82, 182);

-- Dumping structure for table e_absensi_siswa.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
	('admin@gmail.com', '$2y$12$Ii.Jfykx9mbmYjQXve.yW.4vz3JdaJUxOoZm.Z9lbVX586hk2SfHC', '2026-05-13 17:27:58');

-- Dumping structure for table e_absensi_siswa.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table e_absensi_siswa.schedules
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_class_id_foreign` (`class_id`),
  KEY `schedules_subject_id_foreign` (`subject_id`),
  KEY `schedules_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `schedules_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.schedules: ~12 rows (approximately)
DELETE FROM `schedules`;
INSERT INTO `schedules` (`id`, `class_id`, `subject_id`, `day`, `start_time`, `end_time`, `created_at`, `updated_at`, `teacher_id`) VALUES
	(4, 1, 6, 'Senin', '08:30:00', '09:30:00', '2026-05-29 10:22:29', '2026-05-29 10:22:29', NULL),
	(5, 1, 5, 'Senin', '09:35:00', '10:35:00', '2026-05-29 10:23:01', '2026-05-29 10:23:01', NULL),
	(6, 1, 10, 'Senin', '10:40:00', '11:40:00', '2026-05-29 10:23:51', '2026-05-29 10:23:51', NULL),
	(7, 1, 12, 'Selasa', '08:30:00', '10:30:00', '2026-05-29 10:26:41', '2026-05-29 10:26:41', NULL),
	(8, 1, 9, 'Selasa', '10:45:00', '11:40:00', '2026-05-29 10:34:03', '2026-05-29 10:35:40', NULL),
	(9, 1, 4, 'Rabu', '08:30:00', '09:30:00', '2026-05-29 10:36:47', '2026-05-29 10:36:47', NULL),
	(10, 1, 11, 'Rabu', '09:40:00', '10:40:00', '2026-05-29 10:37:27', '2026-05-29 10:37:27', NULL),
	(11, 1, 8, 'Rabu', '10:45:00', '11:40:00', '2026-05-29 10:38:12', '2026-05-29 10:38:12', NULL),
	(12, 1, 9, 'Kamis', '08:30:00', '09:30:00', '2026-05-29 10:39:07', '2026-05-29 10:39:07', NULL),
	(13, 1, 5, 'Kamis', '09:30:00', '10:40:00', '2026-05-29 10:39:44', '2026-05-29 10:39:44', NULL),
	(14, 1, 7, 'Jumat', '08:30:00', '10:30:00', '2026-05-29 10:40:25', '2026-05-29 10:40:25', NULL),
	(15, 1, 13, 'Jumat', '10:40:00', '11:40:00', '2026-05-29 10:40:56', '2026-05-29 10:40:56', NULL);

-- Dumping structure for table e_absensi_siswa.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table e_absensi_siswa.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.settings: ~22 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'school_name', 'E-ABSENSI SISWA', NULL, '2026-04-15 09:25:31', '2026-05-16 16:07:44'),
	(2, 'attendance_start_time', '18:10', NULL, '2026-04-15 09:25:31', '2026-06-03 11:03:41'),
	(3, 'late_tolerance_minutes', '5', NULL, '2026-04-15 09:25:31', '2026-05-20 07:08:24'),
	(4, 'attendance_end_time', '19:00', NULL, '2026-04-15 09:25:31', '2026-06-03 11:03:42'),
	(5, 'wa_api_endpoint', 'https://api.fonnte.com/send', NULL, '2026-04-15 09:25:31', '2026-04-15 10:18:50'),
	(6, 'wa_api_key', 'GjXvpZ6RzsYXywixspND', NULL, '2026-04-15 09:25:31', '2026-04-15 14:29:33'),
	(7, 'school_logo', 'logo/IrFxicRyxNh6UnwX3JpuzGB7JUUqSH3mPHdzjV5n.png', NULL, '2026-04-15 09:25:31', '2026-05-21 12:41:44'),
	(8, 'app_version', 'v1.0.0', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(9, 'school_address', 'Jl. raya dadap cengin RT 001/003', NULL, '2026-04-15 10:18:50', '2026-04-15 18:12:51'),
	(10, 'attendance_teacher_enter_start', '06:00', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(11, 'attendance_teacher_late_limit', '07:15', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(12, 'attendance_teacher_exit_start', '14:00', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(13, 'school_latitude', '', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(14, 'school_longitude', '', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(15, 'school_radius', '100', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(16, 'school_email', 'nurulamin@gmail.com', NULL, '2026-04-15 10:18:50', '2026-05-12 13:30:50'),
	(17, 'school_phone', '085697760499', NULL, '2026-04-15 10:18:50', '2026-05-12 13:30:50'),
	(18, 'social_facebook', 'https://facebook.com/minurulamin', NULL, '2026-04-15 10:18:50', '2026-05-12 13:30:50'),
	(19, 'social_instagram', 'https://instagram.com/mi_nurulamin', NULL, '2026-04-15 10:18:50', '2026-05-12 13:30:50'),
	(20, 'enable_location_check', 'false', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(21, 'enable_ip_check', 'false', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50'),
	(22, 'allowed_ip_addresses', '', NULL, '2026-04-15 10:18:50', '2026-04-15 10:18:50');

-- Dumping structure for table e_absensi_siswa.students
DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default_avatar.png',
  `barcode_data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_place` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_nisn_unique` (`nisn`),
  UNIQUE KEY `students_barcode_data_unique` (`barcode_data`),
  UNIQUE KEY `students_nis_unique` (`nis`),
  UNIQUE KEY `students_email_unique` (`email`),
  KEY `students_class_id_foreign` (`class_id`),
  CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.students: ~30 rows (approximately)
DELETE FROM `students`;
INSERT INTO `students` (`id`, `nisn`, `nis`, `name`, `email`, `gender`, `class_id`, `photo`, `barcode_data`, `phone_number`, `address`, `birth_place`, `birth_date`, `status`, `created_at`, `updated_at`) VALUES
	(152, '1234567801', '123401', 'Siswa 1', 'siswa1@gmail.com', 'Perempuan', 2, 'default_avatar.png', '006b56ad-baad-41d9-aafe-2b3d78df74aa', NULL, NULL, 'Jakarta', '2023-08-17', 'active', '2026-04-15 18:52:38', '2026-04-16 17:07:44'),
	(153, '1234567802', '123402', 'Siswa 2', 'siswa2@gmail.com', 'Laki-laki', 3, 'default_avatar.png', '3fe83729-4d54-466d-af0c-fedf2b88dbdf', NULL, NULL, 'Jakarta', '2023-08-18', 'active', '2026-04-15 18:52:38', '2026-04-16 17:07:44'),
	(154, '1234567803', '123403', 'Siswa 3', 'siswa3@gmail.com', 'Perempuan', 4, 'default_avatar.png', 'fec3de12-0ea0-4782-99d7-f6587b151dfe', NULL, NULL, 'Jakarta', '2023-08-19', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(155, '1234567804', '123404', 'Siswa 4', 'siswa4@gmail.com', 'Laki-laki', 5, 'default_avatar.png', '11cd71bc-463c-4ea3-9dd2-7fe566c217ea', NULL, NULL, 'Jakarta', '2023-08-20', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(156, '1234567805', '123405', 'Siswa 5', 'siswa5@gmail.com', 'Perempuan', 6, 'default_avatar.png', 'de888bdb-de9a-41f8-bc8b-6c347a2c7b5f', NULL, NULL, 'Jakarta', '2023-08-21', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(157, '1234567806', '123406', 'Siswa 6', 'siswa6@gmail.com', 'Laki-laki', 1, 'default_avatar.png', 'e21abcf3-0345-48b4-9d82-a6a96a12e22e', NULL, NULL, 'Jakarta', '2023-08-22', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(158, '1234567807', '123407', 'Siswa 7', 'siswa7@gmail.com', 'Perempuan', 2, 'default_avatar.png', 'c96a4e65-1ddd-4f77-b209-f27022a72f63', NULL, NULL, 'Jakarta', '2023-08-23', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(159, '1234567808', '123408', 'Siswa 8', 'siswa8@gmail.com', 'Laki-laki', 3, 'default_avatar.png', '3d6f0d76-46a5-4aa3-bdd7-b3cbdf9c8018', NULL, NULL, 'Jakarta', '2023-08-24', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(160, '1234567809', '123409', 'Siswa 9', 'siswa9@gmail.com', 'Perempuan', 4, 'default_avatar.png', 'a297f803-e7a2-4cf1-8549-da467c6f609f', NULL, NULL, 'Jakarta', '2023-08-25', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(161, '1234567810', '123410', 'Siswa 10', 'siswa10@gmail.com', 'Laki-laki', 5, 'default_avatar.png', '734f5ac0-3550-45ca-aaf5-f37e321570e1', NULL, NULL, 'Jakarta', '2023-08-26', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(162, '1234567811', '123411', 'Siswa 11', 'siswa11@gmail.com', 'Perempuan', 6, 'default_avatar.png', '33eafb40-d34b-4f51-8dd8-321f3082c644', NULL, NULL, 'Jakarta', '2023-08-27', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(163, '1234567812', '123412', 'Siswa 12', 'siswa12@gmail.com', 'Laki-laki', 1, 'photos/students/BQguiCJtF4YfBua7Uz7rntdoZWpshqzXpXtqgcHp.png', 'bee60fca-9220-482b-b4a8-2146846f6245', NULL, NULL, 'Jakarta', '2023-08-28', 'active', '2026-04-15 18:52:38', '2026-04-15 19:00:56'),
	(164, '1234567813', '123413', 'Siswa 13', 'siswa13@gmail.com', 'Perempuan', 2, 'default_avatar.png', '0b700ba7-c0eb-4a73-916b-88536ef1c9af', NULL, NULL, 'Jakarta', '2023-08-29', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(165, '1234567814', '123414', 'Siswa 14', 'siswa14@gmail.com', 'Laki-laki', 3, 'default_avatar.png', '31811cc9-0d33-4c8e-8a0c-3b47e5135a3c', NULL, NULL, 'Jakarta', '2023-08-30', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(166, '1234567815', '123415', 'Siswa 15', 'siswa15@gmail.com', 'Perempuan', 4, 'default_avatar.png', 'ad30572e-117d-43fc-a5a5-9f609b99561c', NULL, NULL, 'Jakarta', '2023-08-31', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(167, '1234567816', '123416', 'Siswa 16', 'siswa16@gmail.com', 'Laki-laki', 5, 'default_avatar.png', 'dc19b97e-9428-41a4-8496-154d49ef055d', NULL, NULL, 'Jakarta', '2023-09-01', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(168, '1234567817', '123417', 'Siswa 17', 'siswa17@gmail.com', 'Perempuan', 6, 'default_avatar.png', 'b0f89c57-e184-41d7-997b-9425ff3410db', NULL, NULL, 'Jakarta', '2023-09-02', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(169, '1234567818', '123418', 'Siswa 18', 'siswa18@gmail.com', 'Laki-laki', 1, 'photos/students/OU3sVAJtv982TxjPaJS4Mx3ztjTdAADlUhgvzcVj.png', 'e77f450f-bf23-462b-aee5-e87401629758', NULL, NULL, 'Jakarta', '2023-09-03', 'active', '2026-04-15 18:52:38', '2026-04-15 19:09:03'),
	(170, '1234567819', '123419', 'Siswa 19', 'siswa19@gmail.com', 'Perempuan', 2, 'default_avatar.png', '3c957343-b0d8-4755-8f72-412c8d334629', NULL, NULL, 'Jakarta', '2023-09-04', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(171, '1234567820', '123420', 'Siswa 20', 'siswa20@gmail.com', 'Laki-laki', 3, 'default_avatar.png', '6d2f9a85-4972-4c26-822d-894a05beedda', NULL, NULL, 'Jakarta', '2023-09-05', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(172, '1234567821', '123421', 'Siswa 21', 'siswa21@gmail.com', 'Perempuan', 4, 'default_avatar.png', '0d259834-1b0c-47fc-a497-96e7fd7d73e7', NULL, NULL, 'Jakarta', '2023-09-06', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(173, '1234567822', '123422', 'Siswa 22', 'siswa22@gmail.com', 'Laki-laki', 5, 'default_avatar.png', '724fef74-0ce6-48e6-92a7-b49d44c4357a', NULL, NULL, 'Jakarta', '2023-09-07', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(174, '1234567823', '123423', 'Siswa 23', 'siswa23@gmail.com', 'Perempuan', 6, 'default_avatar.png', '65ae9897-60c1-4fe0-94ee-0fa6ffebc223', NULL, NULL, 'Jakarta', '2023-09-08', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(175, '1234567824', '123424', 'Siswa 24', 'siswa24@gmail.com', 'Laki-laki', 1, 'photos/students/13U4dixnJuMmOxPyFz8GvAbrSSVqksju0JqYwYwH.png', '3a826ab9-ecdb-404b-9cc4-e7ac92439089', NULL, NULL, 'Jakarta', '2023-09-09', 'active', '2026-04-15 18:52:38', '2026-04-15 19:09:43'),
	(176, '1234567825', '123425', 'Siswa 25', 'siswa25@gmail.com', 'Perempuan', 2, 'default_avatar.png', '02faa45d-cb1a-487d-9079-933f2bcec74e', NULL, NULL, 'Jakarta', '2023-09-10', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(177, '1234567826', '123426', 'Siswa 26', 'siswa26@gmail.com', 'Laki-laki', 3, 'default_avatar.png', '4f585e91-e2de-4fd2-b2a6-f826c3b95d5e', NULL, NULL, 'Jakarta', '2023-09-11', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(178, '1234567827', '123427', 'Siswa 27', 'siswa27@gmail.com', 'Perempuan', 4, 'default_avatar.png', '50b819ad-f5db-4a8b-9d23-f57375e2a91d', NULL, NULL, 'Jakarta', '2023-09-12', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(179, '1234567828', '123428', 'Siswa 28', 'siswa28@gmail.com', 'Laki-laki', 5, 'default_avatar.png', 'bd5a7209-995c-4a73-9c07-1c91d33e3e37', NULL, NULL, 'Jakarta', '2023-09-13', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(180, '1234567829', '123429', 'Siswa 29', 'siswa29@gmail.com', 'Perempuan', 6, 'default_avatar.png', '11fae932-e56d-4519-879a-38469fe87b5c', NULL, NULL, 'Jakarta', '2023-09-14', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(181, '1234567830', '123430', 'Siswa 30', 'siswa30@gmail.com', 'Laki-laki', 1, 'default_avatar.png', '0792d994-8480-484d-937f-d2f879c6419f', NULL, NULL, 'Jakarta', '2023-09-15', 'active', '2026-04-15 18:52:38', '2026-04-15 18:52:38'),
	(182, '1234567831', NULL, 'Siswa 31', 'siswa31@gmail.com', 'Perempuan', 1, 'photos/students/tDfzEnXuC0NV05qs9v46kdnUFrQkhKRTRTJfN8Zy.png', 'ba14d7f8-ab32-4a1b-ac9d-bf21b2f19afa', '085697760499', NULL, 'Tangerang', '2003-08-02', 'active', '2026-04-16 10:38:54', '2026-05-29 09:35:59');

-- Dumping structure for table e_absensi_siswa.subjects
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.subjects: ~9 rows (approximately)
DELETE FROM `subjects`;
INSERT INTO `subjects` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
	(4, 'Pendidikan Agama Islam (PAI)', 'PAI-001', '2026-05-29 10:05:31', '2026-05-29 10:05:31'),
	(5, 'Al-Qur\'an Hadist', 'AQH-002', '2026-05-29 10:06:00', '2026-05-29 10:06:00'),
	(6, 'Akidah Akhlak', 'AKL-003', '2026-05-29 10:06:26', '2026-05-29 10:06:26'),
	(7, 'Fikih', 'FIK-004', '2026-05-29 10:06:53', '2026-05-29 10:06:53'),
	(8, 'Sejarah Kebudayaan Islam (SKI)', 'SKI-005', '2026-05-29 10:07:35', '2026-05-29 10:07:35'),
	(9, 'Matematika', 'MTK-006', '2026-05-29 10:08:14', '2026-05-29 10:09:03'),
	(10, 'Bahasa Indonesia', 'BI-007', '2026-05-29 10:08:42', '2026-05-29 10:09:17'),
	(11, 'Pendidikan Pancasila', 'PPK-008', '2026-05-29 10:09:43', '2026-05-29 10:10:40'),
	(12, 'Bahasa Inggris', 'BING-009', '2026-05-29 10:10:17', '2026-05-29 10:10:54'),
	(13, 'Pendidikan Jasmani, Olahraga & Kesehatan', 'PJOK-010', '2026-05-29 10:11:16', '2026-05-29 10:11:16');

-- Dumping structure for table e_absensi_siswa.subject_attendances
DROP TABLE IF EXISTS `subject_attendances`;
CREATE TABLE IF NOT EXISTS `subject_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teaching_journal_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha','Terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_attendances_teaching_journal_id_foreign` (`teaching_journal_id`),
  KEY `subject_attendances_student_id_foreign` (`student_id`),
  CONSTRAINT `subject_attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_attendances_teaching_journal_id_foreign` FOREIGN KEY (`teaching_journal_id`) REFERENCES `teaching_journals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.subject_attendances: ~0 rows (approximately)
DELETE FROM `subject_attendances`;

-- Dumping structure for table e_absensi_siswa.teacher_attendances
DROP TABLE IF EXISTS `teacher_attendances`;
CREATE TABLE IF NOT EXISTS `teacher_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  `status` enum('present','late','permission','sick','alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alpha',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_attendances_user_id_date_unique` (`user_id`,`date`),
  CONSTRAINT `teacher_attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.teacher_attendances: ~0 rows (approximately)
DELETE FROM `teacher_attendances`;

-- Dumping structure for table e_absensi_siswa.teaching_journals
DROP TABLE IF EXISTS `teaching_journals`;
CREATE TABLE IF NOT EXISTS `teaching_journals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teaching_journals_schedule_id_foreign` (`schedule_id`),
  KEY `teaching_journals_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `teaching_journals_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_journals_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.teaching_journals: ~0 rows (approximately)
DELETE FROM `teaching_journals`;

-- Dumping structure for table e_absensi_siswa.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('super_admin','guru','wali_kelas','orang_tua','kepala_sekolah','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'orang_tua',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `is_demo` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nip_unique` (`nip`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table e_absensi_siswa.users: ~42 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `nip`, `email`, `photo`, `role`, `email_verified_at`, `password`, `remember_token`, `is_approved`, `is_demo`, `created_at`, `updated_at`) VALUES
	(4, 'Admin Nufus', NULL, 'adminnufus@gmail.com', NULL, 'super_admin', NULL, '$2y$12$KbPxMshVED0e6kM5N1DvEeL9QqxFzOjTQvXxmjVqPu86e7cH32JD.', NULL, 1, 0, '2026-04-14 14:17:11', '2026-04-14 14:17:11'),
	(5, 'Khalish', NULL, 'khalish@gmail.com', NULL, 'super_admin', NULL, '$2y$12$lO33/NYSCIGZjlYWQga8Juj7QfxTDZYvgGT6lC1AHWSjckGa3YtSW', NULL, 1, 0, '2026-04-14 14:52:14', '2026-04-14 14:52:14'),
	(9, 'Ustadz Ahmad Fauzi, S.Pd.I.', NULL, 'wk1a@test.com', NULL, 'wali_kelas', '2026-04-15 09:23:27', '$2y$12$B/CPRd5B/HCTkad47lYBK.xNkC44w1OiQtGz2rxofPKID0R1pLg9i', NULL, 1, 0, '2026-04-15 09:23:27', '2026-05-29 09:58:37'),
	(10, 'Ustadzah Siti Aminah, S.Pd.', NULL, 'wk1b@test.com', NULL, 'wali_kelas', '2026-04-15 09:23:29', '$2y$12$PUNnO0c2yegGprZZ6MJXc.kCRqaEvXLqHRtNYghl7LhYSe89FNubO', NULL, 1, 0, '2026-04-15 09:23:29', '2026-05-29 09:58:13'),
	(11, 'Ustadzah Fatimah, M.Pd.', NULL, 'wk2a@test.com', NULL, 'wali_kelas', '2026-04-15 09:23:32', '$2y$12$hsNBU55RY0vAawpjXik20.eQJsZvfHy5wiakuT4FlWqq92pi5RvOe', NULL, 1, 0, '2026-04-15 09:23:32', '2026-05-29 09:59:23'),
	(12, 'Ratna Sari, S.Pd.', NULL, 'wk3b@test.com', NULL, 'wali_kelas', '2026-04-15 09:23:34', '$2y$12$.FLHnCzWMvB2ZD9eF5YkE.RgatBiUQIZxx7d6r.UgfSpj1K0ISPh2', NULL, 1, 0, '2026-04-15 09:23:34', '2026-05-29 10:01:42'),
	(13, 'Ustadz Muhammad Yusuf, S.Pd.I.', NULL, 'wk2b@test.com', NULL, 'wali_kelas', '2026-04-15 09:23:36', '$2y$12$x.IEKE3N6mfrDcXKrlvOb.70WJONurxlafFYXFpNw.gXvaM1tp0NW', NULL, 1, 0, '2026-04-15 09:23:36', '2026-05-29 10:00:07'),
	(14, 'Budi Santoso, S.Pd.', NULL, 'wk3a@test.com', NULL, 'wali_kelas', '2026-04-15 09:23:38', '$2y$12$J5MTO3L7SwjoY9W121txcOybzIzfcQbYNR.ms54KYq3l5UJIMlgK6', NULL, 1, 0, '2026-04-15 09:23:38', '2026-05-29 10:01:06'),
	(65, 'Nufus', NULL, 'nufus@gmail.com', NULL, 'super_admin', NULL, '$2y$12$I2hQdo/NmdIUwds3chVZHeUQSYx6oDB6euiEhVO5188LwC23PA70K', NULL, 1, 0, '2026-04-15 09:50:54', '2026-04-15 09:50:54'),
	(68, 'Nufus', NULL, 'dhytnnfs@gmail.com', NULL, 'super_admin', NULL, '$2y$12$0UuLOKWXHG9BlkSp9Dk7x.aYAWh8rML4auQrNPZnLBBSeXN6/9bKi', NULL, 1, 0, '2026-04-15 14:13:32', '2026-04-15 14:13:32'),
	(74, 'Parent 1', NULL, 'parent1@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$FI7IolpEVBNsdiThbeiR4usrqlefNqNxRlw4hDxdXNLTmGtyutdTG', NULL, 1, 0, '2026-04-15 19:43:59', '2026-05-29 09:08:04'),
	(75, 'Parent 2', NULL, 'parent2@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$mSuiMF0yaCNfC/NBQxapgeMrEzl78wVRisCHhEu.xd0vBK7Yhfipe', NULL, 1, 0, '2026-04-15 19:44:45', '2026-05-29 09:08:31'),
	(76, 'Parent 3', NULL, 'parent3@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$Nd58JFbh1egGrwvo7RjvruCic17J2ImWnRmHeX03gKKCsui2HjSru', NULL, 1, 0, '2026-04-16 10:40:55', '2026-05-29 09:07:26'),
	(77, 'Admin Baru', NULL, 'admin@gmail.com', NULL, 'super_admin', NULL, '$2y$12$z9t0pqMsqp6RNexFsFPa.uxbbEURaOyHLJV7OE2hcdmYsUwPpUlnO', 'oXJSulu5o9gFYNwAycca9qaHrLtR83gRWiit3snqVa5g4BfSyMyKvNzeCn7G', 1, 0, '2026-05-08 11:12:03', '2026-05-08 11:12:03'),
	(78, 'Parent 4', NULL, 'parent4@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$C24AUi/F5qIMgNheK/S8o./6aJU38osywKazlHrIf1fcikar5WeIG', NULL, 1, 0, '2026-05-13 17:37:45', '2026-05-29 09:09:22'),
	(79, 'Parent 5', NULL, 'parent5@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$Il/XZcR1dNPP8IxEjttn9uK.JLUWAA5yVCn0Lu5GOGohwJ2QfJjQ.', NULL, 1, 0, '2026-05-29 09:11:20', '2026-05-29 09:11:20'),
	(80, 'Parent 6', NULL, 'parent6@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$0sTMLjDDHJIjdtQhKKaww.bRFRDdolk1AAeAp7XHWpOqupyS2ahVa', NULL, 1, 0, '2026-05-29 09:12:41', '2026-05-29 09:12:41'),
	(81, 'Parent 7', NULL, 'parent7@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$idGNhQK4ETf0P1ss.yZpp.lFBxl1bYixjXBkF4a5pJY8yid/xPr9y', NULL, 1, 0, '2026-05-29 09:13:24', '2026-05-29 09:13:24'),
	(82, 'Parent 8', NULL, 'parent8@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$D0RTrT5kQuXhBGvTRDQ.E.h/R/IYGC2EAGg6r1XY3f9tr/D5sntge', NULL, 1, 0, '2026-05-29 09:14:10', '2026-05-29 09:14:10'),
	(83, 'Parent 9', NULL, 'parent9@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$c34P1zTl5kIWxwc9ZADQtOtARHMzfwlTFLRWgJhJWf79AUkTfhzmC', NULL, 1, 0, '2026-05-29 09:15:00', '2026-05-29 09:15:00'),
	(84, 'Parent 10', NULL, 'parent10@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$exoyPptXtFep316ZVofMNObM5j3k09c7w7QEh2UYp.z.rr0yC8gRu', NULL, 1, 0, '2026-05-29 09:15:52', '2026-05-29 09:15:52'),
	(85, 'Parent 11', NULL, 'parent11@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$V1YC/.9yp4A12PZxd6jvNeRlpVpxopVcq85QSDeLSzs2E/3BeQldG', NULL, 1, 0, '2026-05-29 09:16:30', '2026-05-29 09:16:30'),
	(86, 'Parent 12', NULL, 'parent12@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$GqdXnyhKXcnKNaTG43Bko.QRiL4qS/dSmADinI1ARKems8xfH0yN.', NULL, 1, 0, '2026-05-29 09:17:03', '2026-05-29 09:17:03'),
	(87, 'Parent 13', NULL, 'parent13@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$8PPAXPSe98fZ7MGzPw/Raup2Ud8moxDh.B.vRsIAZaMP5/fb47aM6', NULL, 1, 0, '2026-05-29 09:17:40', '2026-05-29 09:17:40'),
	(88, 'Parent 14', NULL, 'parent14@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$Yt7VRGTbE4Ix7MbmPeawMeSzICsPcNGNQ/P7K6z47LT9nkWrqdpgu', NULL, 1, 0, '2026-05-29 09:18:32', '2026-05-29 09:18:32'),
	(89, 'Parent 15', NULL, 'parent15@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$VphntwGpEC0txZNWNCgwZOFkD5d70juW.k1Dyim5B9UAIbC3AiLjK', NULL, 1, 0, '2026-05-29 09:19:11', '2026-05-29 09:19:11'),
	(90, 'Parent 16', NULL, 'parent16@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$17M1VtSmmXTKhA7dn95tNOZBtaMCNg5MscOW1xd36zdeSmf3.PzCC', NULL, 1, 0, '2026-05-29 09:20:02', '2026-05-29 09:20:02'),
	(91, 'Parent 17', NULL, 'parent17@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$2T9psQxX29aD9ArhlXBUlegSAYZKrINnJXDavyAkK4HBaskGvMip2', NULL, 1, 0, '2026-05-29 09:20:54', '2026-05-29 09:20:54'),
	(92, 'Parent 18', NULL, 'parent18@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$0Qk1V35KuY94mF1Plvv3dukV2uXaylu4QyCpkCJ4I/2tBIlM6t5YW', NULL, 1, 0, '2026-05-29 09:21:34', '2026-05-29 09:21:34'),
	(93, 'Parent 19', NULL, 'parent19@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$xMokTOfIEyk2FKqQZHY05uifPoMbjJXjJj5/GImO9mGBT.2nF06e6', NULL, 1, 0, '2026-05-29 09:22:12', '2026-05-29 09:22:12'),
	(94, 'Parent 20', NULL, 'parent20@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$r0DARw12cHkfvUILQRaz8uKyP6ULCE0nXIdWmGXH0gHlKPoQ4Pzga', NULL, 1, 0, '2026-05-29 09:22:53', '2026-05-29 09:22:53'),
	(95, 'Parent 21', NULL, 'parent21@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$myyUjwb1mdmfwgh9m9qqperbCEK0GMptmSOuxdA5pIctNLjrtv5wq', NULL, 1, 0, '2026-05-29 09:23:28', '2026-05-29 09:23:28'),
	(96, 'Parent 22', NULL, 'parent22@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$yW68EDKNMn/I5Nkd.e7X7uQ7fPepijc/9jJmjB6RYhbQZRse9K6li', NULL, 1, 0, '2026-05-29 09:24:00', '2026-05-29 09:24:00'),
	(97, 'Parent 23', NULL, 'parent23@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$Avp9HDqKdba9Fspc8ZJKuOqVc.HdQSlKzuUgk8L/Ir/2kVh7Aogo2', NULL, 1, 0, '2026-05-29 09:24:52', '2026-05-29 09:24:52'),
	(98, 'Parent 24', NULL, 'parent24@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$df9gxlCj7SIGQxovy987s.RkpvUtq2pe6AZLuURz.1VvNxBVbrhFi', NULL, 1, 0, '2026-05-29 09:25:32', '2026-05-29 09:25:32'),
	(99, 'Parent 25', NULL, 'parent25@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$WulB7jQGsNTGyRKW0jiwduP/JgXSTSZASz05qEdO9ifG5o.6/k5JG', NULL, 1, 0, '2026-05-29 09:26:14', '2026-05-29 09:26:14'),
	(100, 'Parent 26', NULL, 'parent26@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$IqMxee1lAtzlzbaEwn.b/eWqTfIRC9v/VUL3XcShvTfMNiV4ACn7q', NULL, 1, 0, '2026-05-29 09:26:57', '2026-05-29 09:26:57'),
	(101, 'Parent 27', NULL, 'parent27@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$.X44b1Y7aXnxSEeh7kVBu.6nBDJeb4/dtnzjeH0gvTI4kYp2AOwte', NULL, 1, 0, '2026-05-29 09:27:34', '2026-05-29 09:27:34'),
	(102, 'Parent 28', NULL, 'parent28@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$9TgJZkIxHEh3IWC2Yz.p2utJC7UuZ7A2b06igGDJjIib2W0nEBj.y', NULL, 1, 0, '2026-05-29 09:28:49', '2026-05-29 09:28:49'),
	(103, 'Parent 29', NULL, 'parent29@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$M192beGOnrKEvd.wWAh4mOWGHbPwR36ud.bEt.L5FdCm2BT6phsGO', NULL, 1, 0, '2026-05-29 09:29:33', '2026-05-29 09:29:33'),
	(104, 'Parent 30', NULL, 'parent30@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$mPwg0NuGEr03djAjWVhJR.GKrL2dpWjoSb0jFpdao7n6yIhPJSqii', NULL, 1, 0, '2026-05-29 09:30:02', '2026-05-29 09:30:02'),
	(105, 'Parent 31', NULL, 'parent31@gmail.com', NULL, 'orang_tua', NULL, '$2y$12$XJebGJNh68Z6UanZib.Bx.Xn41ipAPsaFcqFcSEIEbs6YPrB8H90O', NULL, 1, 0, '2026-05-29 09:30:48', '2026-05-29 09:30:48');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
