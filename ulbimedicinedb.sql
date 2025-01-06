-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 06, 2025 at 04:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyek2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_room_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_rooms`
--

CREATE TABLE `chat_rooms` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konsultasi_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_room_users`
--

CREATE TABLE `chat_room_users` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_room_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int NOT NULL,
  `user_id` int NOT NULL,
  `spesialisasi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kualifikasi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pengalaman` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `spesialisasi`, `kualifikasi`, `pengalaman`) VALUES
(1, 12, 'Jantung', 'S3', '5 Tahun'),
(2, 14, 'Paru-paru', 'S2', '5 Tahun Dipenjara'),
(3, 8, 'Lambung', 'S2', '4 Tahun'),
(4, 15, 'jantung', 'phd', 'tempur');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_dokter`
--

CREATE TABLE `jadwal_dokter` (
  `jadwal_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `hari` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') COLLATE utf8mb4_general_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_dokter`
--

INSERT INTO `jadwal_dokter` (`jadwal_id`, `doctor_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(2, 1, 'rabu', '12:15:00', '14:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"4b2777a3-05c2-4c1d-b0ae-5b9327c9523c\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1dff6-c411-4946-9ed4-cf18c0c04617\\\";s:7:\\\"message\\\";s:13:\\\"apa kabar dok\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735934686, 1735934686),
(2, 'default', '{\"uuid\":\"44f4fddc-f714-4351-8f3e-545f82a51f0f\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1e12f-6abb-4dba-a7d5-2dd3eae1e9c4\\\";s:7:\\\"message\\\";s:5:\\\"haloo\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735934837, 1735934837),
(3, 'default', '{\"uuid\":\"e3a71d22-6528-4d22-981c-795006b703e1\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1dff6-c411-4946-9ed4-cf18c0c04617\\\";s:7:\\\"message\\\";s:9:\\\"halo juga\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735934851, 1735934851),
(4, 'default', '{\"uuid\":\"406f186e-e305-4caf-be69-98b0543fa547\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1e2e8-cb09-4c8a-9e04-707fbc853385\\\";s:7:\\\"message\\\";s:5:\\\"haloo\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735935008, 1735935008),
(5, 'default', '{\"uuid\":\"64096f59-c182-4545-9065-f4f8fa0b90a6\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1dff6-c411-4946-9ed4-cf18c0c04617\\\";s:7:\\\"message\\\";s:8:\\\"halo dok\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735958862, 1735958862),
(6, 'default', '{\"uuid\":\"39a8b7bf-7cd5-4784-91f1-4a0d42bd6398\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1dff6-c411-4946-9ed4-cf18c0c04617\\\";s:7:\\\"message\\\";s:8:\\\"halo dok\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735958863, 1735958863),
(7, 'default', '{\"uuid\":\"a18eadeb-5ba6-4865-8fa7-2df0c4a35997\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1e12f-6abb-4dba-a7d5-2dd3eae1e9c4\\\";s:7:\\\"message\\\";s:13:\\\"apa kabar dok\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735958877, 1735958877),
(8, 'default', '{\"uuid\":\"e7da069f-1fb4-4b0b-8dbc-0b46357b2a2e\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1e12f-6abb-4dba-a7d5-2dd3eae1e9c4\\\";s:7:\\\"message\\\";s:11:\\\"halo sayang\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735959438, 1735959438),
(9, 'default', '{\"uuid\":\"f7284eee-f7e3-4d93-b6c7-6203a311d684\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1e12f-6abb-4dba-a7d5-2dd3eae1e9c4\\\";s:7:\\\"message\\\";s:12:\\\"halo kmuuuuu\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735959811, 1735959811),
(10, 'default', '{\"uuid\":\"e747de1f-ac11-47ae-8a11-453dea428264\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1dff6-c411-4946-9ed4-cf18c0c04617\\\";s:7:\\\"message\\\";s:5:\\\"syahh\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735959825, 1735959825),
(11, 'default', '{\"uuid\":\"33222076-3fbd-415e-b516-d5a80ff9d924\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de1e12f-6abb-4dba-a7d5-2dd3eae1e9c4\\\";s:7:\\\"message\\\";s:17:\\\"kamu lagi ngapain\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735960452, 1735960452),
(12, 'default', '{\"uuid\":\"200ae5ae-5cb5-4e73-8a52-a03aa09c0c90\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de326a6-8d62-4f46-b821-f45b06e335b2\\\";s:7:\\\"message\\\";s:8:\\\"halo dok\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735989766, 1735989766),
(13, 'default', '{\"uuid\":\"bf62c848-4ff9-4687-bd43-a9dceb3e2413\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de326a6-8d62-4f46-b821-f45b06e335b2\\\";s:7:\\\"message\\\";s:8:\\\"halo dok\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735989768, 1735989768),
(14, 'default', '{\"uuid\":\"fd967389-8438-45d4-b757-c0cf90a38a1b\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de326a6-8d62-4f46-b821-f45b06e335b2\\\";s:7:\\\"message\\\";s:10:\\\"iya kenapa\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735990116, 1735990116),
(15, 'default', '{\"uuid\":\"9db746f2-72e8-49fe-9e8f-6e5d417f50b3\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de326a6-8d62-4f46-b821-f45b06e335b2\\\";s:7:\\\"message\\\";s:14:\\\"saya lapar dok\\\";s:6:\\\"sender\\\";i:17;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1735990133, 1735990133),
(16, 'default', '{\"uuid\":\"9065b7fe-782b-42a2-8d9f-c39a419b0077\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de519a0-4618-4ac5-af8f-bddd96bf2b0c\\\";s:7:\\\"message\\\";s:4:\\\"halo\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1736073046, 1736073046),
(17, 'default', '{\"uuid\":\"694e15e5-cbaa-4575-9d9d-b0b1556189b5\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de536de-c124-4597-a249-f2370b37a4ac\\\";s:7:\\\"message\\\";s:4:\\\"halo\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1736080948, 1736080948),
(18, 'default', '{\"uuid\":\"54e4e95d-0301-412b-a1f7-dd0b38a796ae\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de536de-c124-4597-a249-f2370b37a4ac\\\";s:7:\\\"message\\\";s:3:\\\"hai\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1736081929, 1736081929),
(19, 'default', '{\"uuid\":\"b847bf38-d9ac-4a71-bbbc-797fe34199a6\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de536de-c124-4597-a249-f2370b37a4ac\\\";s:7:\\\"message\\\";s:14:\\\"saya sakit dok\\\";s:6:\\\"sender\\\";i:18;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1736081943, 1736081943),
(20, 'default', '{\"uuid\":\"ad39c0ce-fbc2-4f3b-9867-88257b85d1a8\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de536de-c124-4597-a249-f2370b37a4ac\\\";s:7:\\\"message\\\";s:4:\\\"halo\\\";s:6:\\\"sender\\\";i:15;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1736136143, 1736136143),
(21, 'default', '{\"uuid\":\"93bfcc17-785a-4f89-8d4f-3eb8adc222db\",\"displayName\":\"App\\\\Events\\\\ChatEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:20:\\\"App\\\\Events\\\\ChatEvent\\\":3:{s:4:\\\"room\\\";s:36:\\\"9de536de-c124-4597-a249-f2370b37a4ac\\\";s:7:\\\"message\\\";s:3:\\\"iya\\\";s:6:\\\"sender\\\";i:18;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1736136165, 1736136165);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `konsultasi_id` int NOT NULL,
  `pasien_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `tanggal_konsultasi` datetime NOT NULL,
  `status` enum('terjawab','belum dijawab','reviewed') COLLATE utf8mb4_general_ci NOT NULL,
  `keluhan_pasien` text COLLATE utf8mb4_general_ci NOT NULL,
  `balasan_dokter` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_06_10_143526_add_username_to_users_table', 2),
(5, '2024_06_10_144039_add_multiple_fields_to_users_table', 3),
(6, '2024_06_10_144645_remove_id_role_from_users_table', 4),
(7, '2024_06_10_145400_remove_email_verified_at_from_users_table', 5),
(8, '2024_06_16_005634_add_gender_to_users_table', 6),
(9, '2025_01_02_152306_create_chat_rooms_table', 7),
(10, '2025_01_02_152312_create_chat_room_users_table', 7),
(11, '2025_01_02_152318_create_chats_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `pasien_id` int NOT NULL,
  `user_id` int NOT NULL,
  `riwayat_medis` text COLLATE utf8mb4_general_ci NOT NULL,
  `asuransi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`pasien_id`, `user_id`, `riwayat_medis`, `asuransi`) VALUES
(1, 1, 'testing', 'test update lagi'),
(2, 13, 'test', 'test'),
(5, 15, 'tidak ada', '1243214351'),
(6, 17, 'lambung', '1243214352'),
(7, 18, 'lambung', '1243214355');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int NOT NULL,
  `konsultasi_id` int NOT NULL,
  `rating` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7PQMukx0PqYYtIlVWfkGJkMVpPxmgKciJsDCwoRV', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMExxcDhMNVN3N3Izc21va29jeldOOUVFUEQ2VmkwV1RuMmpldmMxQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYXNpZW4vZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTg7fQ==', 1736136379),
('9eszoAe4jMi5VGoYFDzpXakdUSLlNJxwjUpa0e6f', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTzB5dFZiSHpkN2VMSTB4TTM5dlZheDlaOVZUUVc5a1NzSzFVVjQyWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb2t0ZXIvZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTU7fQ==', 1736136337),
('aFjt17P5TsV4fMfJGqwEDp7Ke1ws6NqSRtxw6Nsz', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUZvSExiVVQ1RFhVVm5WeFJwYkt2WlJSNjdaN1NLTENTVEpSVkVOdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE4O30=', 1736135725);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_pengguna` enum('Dokter','Pasien','Admin') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `username`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_telepon`, `tipe_pengguna`, `status`) VALUES
(1, 'Nesya Salma Ramadhani', 'nesyasr@gmail.com', '$2y$12$f3udVd7AwUrQgZFrjPQmbu.zNZFTEuG3V.5evGhU5bDdKPtx3dd9y', '2024-05-29 02:50:10', '2024-05-29 02:50:10', 'nesyasalma', 'Perempuan', '2005-10-05', 'Sarijadi', '6287758748345', 'Pasien', 'approved'),
(2, 'Valen Rionald', 'valenrio@ulbi.ac.id', '$2y$12$3.QVljfUiLV0dJsZsVBV2OJbwmN6UtAODId2Yzi6J1vL6.DVcqpFC', '2024-05-29 03:05:50', '2024-05-29 03:05:50', 'valenrionald', 'Laki-Laki', '2002-08-06', 'Sarijadi', '6289522910966', 'Admin', 'approved'),
(8, 'Rahma', 'rahma@email.com', '$2y$12$RvdhnoUxFbMvPtTj5G5oLOQHOvzp8XW2xoa.KZfsFLn/zMFFf14qe', '2024-05-29 14:10:04', '2024-05-29 14:10:04', 'rahma', 'Perempuan', NULL, 'Bandung', '6287758748345', 'Dokter', 'approved'),
(12, 'Richard Parker', 'richard@email.com', '$2y$12$qQTXkiX7G5pwbGxej1vYeeQBnvO7gSHM96oyqOa7XfVYPFNAsprjC', '2024-06-10 01:22:11', '2024-06-10 01:22:11', 'richard', 'Laki-Laki', '2024-06-10', 'Sarimanah', NULL, 'Dokter', 'approved'),
(13, 'Sofia the first', 'sofia@gmail.com', '$2y$12$CD7hBdJkqDmomAaGHBRUF.IJeFWajFmhEyYQQ.ul1Ay0GfWj7kCga', '2024-06-12 23:31:45', '2024-06-13 00:47:40', 'Sofiani', 'Perempuan', '2024-06-13', 'test', '09090', 'Pasien', 'approved'),
(14, 'Jasmin Firzani Audya', 'mamahdawuh@gmail.com', '$2y$12$xGNRnkFBtaggikRVCuAbpeQMmpe881J0z06dSochRXWILjH8ckjyy', '2024-06-16 12:59:05', '2024-06-16 13:00:48', 'audy', 'Perempuan', '2024-06-17', 'bekasi', '087752400171', 'Dokter', 'approved'),
(15, 'adila', 'dila@gmail.com', '$2y$12$di1xCoqWil8uL/IcIGUWUeahMpVmxs9MQ/CiT.f.vl28KSE46clLG', '2025-01-02 11:16:34', '2025-01-02 11:19:00', 'dila', 'Perempuan', '2006-01-30', 'buton', '081111111111', 'Dokter', 'approved'),
(16, 'ode andi alamsyah', 'odeandialamsyah@gmail.com', '$2y$12$vSq00IdlsVcPKrbKrDml8eqtFBYJCpKbm/J.X4f6Hz1VXpqoVeCgm', '2025-01-02 11:21:14', '2025-01-02 11:21:32', 'ode_andiw', 'Laki-Laki', '2005-03-02', 'buton', '081355621802', 'Pasien', 'approved'),
(17, 'ode andi', 'ode@gmail.com', '$2y$12$qxyAx6Ectf8xB7YavPy4GutlIXhmh1YrW4IqjAntaDyIRbCDwqqmy', '2025-01-03 07:01:33', '2025-01-03 07:02:18', 'ode', 'Laki-Laki', '2005-02-03', 'buton\r\n5', '081355621802', 'Pasien', 'approved'),
(18, 'aldi', 'aldi@gmail.com', '$2y$12$RUn39cGZqSQLMcWCHrsNC.kkFDaYVTM6gy8Ti2qolmfbcKRy2Q1qC', '2025-01-05 03:25:41', '2025-01-05 03:27:15', 'aldi', 'Laki-Laki', '2025-01-05', 'buton', '081355621802', 'Pasien', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_konsultasi_id` (`konsultasi_id`);

--
-- Indexes for table `chat_room_users`
--
ALTER TABLE `chat_room_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  ADD PRIMARY KEY (`jadwal_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`konsultasi_id`),
  ADD KEY `pasien_id` (`pasien_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`pasien_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `konsultasi_id` (`konsultasi_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `chat_room_users`
--
ALTER TABLE `chat_room_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  MODIFY `jadwal_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `konsultasi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `pasien_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD CONSTRAINT `fk_konsultasi_id` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`konsultasi_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
