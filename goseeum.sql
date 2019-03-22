-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2019 at 06:53 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goseeum`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` bigint(20) NOT NULL,
  `payload` text NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `sender_id` bigint(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  `is_selected` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_selected_1` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `payload`, `user_id`, `sender_id`, `type`, `is_read`, `is_selected`, `created_at`, `updated_at`, `is_selected_1`) VALUES
(1, '{\"title\":\"Friend Request Recieved\",\"message\":\"Ameer Hamza sent you friend request\",\"type\":\"FriendRequestRecieved\"}', 8, 2, 'FriendRequestRecieved', 0, 0, '2019-02-19 01:42:01', '2019-02-19 01:42:01', 0),
(2, '{\"title\":\"Friend Request Recieved\",\"message\":\"Ameer Hamza sent you friend request\",\"type\":\"FriendRequestRecieved\"}', 12, 2, 'FriendRequestRecieved', 0, 0, '2019-02-19 01:43:16', '2019-02-19 01:43:16', 0),
(3, '{\"title\":\"Friend Request Accepted\",\"message\":\"Ali Hamza 1 accepted your friend request\",\"type\":\"FriendRequestAccepted\"}', 2, 8, 'FriendRequestAccepted', 0, 1, '2019-02-19 01:46:04', '2019-02-27 03:18:32', 0),
(5, '{\"title\":\"Friend Request Rejected\",\"message\":\"Ali Hamza2 rejected your friend request\",\"type\":\"FriendRequestRejected\"}', 2, 12, 'FriendRequestRejected', 0, 1, '2019-02-19 01:48:31', '2019-02-27 03:18:32', 0),
(7, '{\"title\":\"Goseeum Alert\",\"message\":\"Your location named Ilyas Dumba Karahi has been rejected by admin\",\"type\":\"LocationAlert\"}', 2, 0, 'LocationAlert', 0, 1, '2019-02-19 01:51:25', '2019-02-27 03:20:56', 0),
(8, '{\"title\":\"Friend Request Recieved\",\"message\":\"Ameer Hamza sent you friend request\",\"type\":\"FriendRequestRecieved\"}', 14, 2, 'FriendRequestRecieved', 0, 0, '2019-02-28 03:47:24', '2019-02-28 03:47:24', 0),
(9, '{\"title\":\"Friend Request Recieved\",\"message\":\"Ameer Hamza sent you friend request\",\"type\":\"FriendRequestRecieved\"}', 14, 2, 'FriendRequestRecieved', 0, 0, '2019-02-28 03:50:26', '2019-02-28 03:50:26', 0),
(10, '{\"title\":\"Friend Request Accepted\",\"message\":\"Ameer Hamza accepted your friend request\",\"type\":\"FriendRequestAccepted\"}', 14, 2, 'FriendRequestAccepted', 0, 0, '2019-02-28 03:52:39', '2019-02-28 03:52:39', 0),
(11, '{\"title\":\"Friend Request Rejected\",\"message\":\"Ameer Hamza rejected your friend request\",\"type\":\"FriendRequestRejected\"}', 14, 2, 'FriendRequestRejected', 0, 0, '2019-02-28 03:53:02', '2019-02-28 03:53:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` bigint(20) NOT NULL,
  `city_name` varchar(160) DEFAULT NULL,
  `city_lat` varchar(160) DEFAULT NULL,
  `city_lang` varchar(160) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`, `city_lat`, `city_lang`, `created_at`, `updated_at`) VALUES
(1, 'Khanewal', '30.286415', '71.932030', '2018-05-07 17:41:47', NULL),
(2, 'Multan', '30.181459', '71.492157', '2018-05-07 17:45:09', NULL),
(3, 'Narowal', '32.099476', '74.874733', '2018-05-07 17:45:10', NULL),
(4, 'Lahore', '31.582045', '74.32937', '2018-05-07 17:45:10', NULL),
(5, 'Karachi', '24.926294', '67.022095', '2018-05-07 17:45:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conv_id` bigint(20) NOT NULL,
  `user1_id` bigint(20) NOT NULL,
  `user2_id` bigint(20) NOT NULL,
  `last_updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`conv_id`, `user1_id`, `user2_id`, `last_updated`, `created_at`, `updated_at`) VALUES
(1, 2, 3, NULL, '2018-01-29 15:42:08', '2018-01-29 15:42:08'),
(2, 6, 2, NULL, '2018-02-07 18:05:38', '2018-02-07 18:05:38'),
(8, 10, 2, NULL, '2018-10-16 11:43:10', '2018-10-16 11:43:10'),
(5, 2, 7, NULL, '2018-07-30 09:46:50', '2018-07-30 09:46:50'),
(9, 2, 8, NULL, '2019-02-19 01:44:48', '2019-02-19 01:44:48'),
(10, 2, 12, NULL, '2019-02-19 01:47:56', '2019-02-19 01:47:56'),
(11, 14, 2, NULL, '2019-02-28 03:52:39', '2019-02-28 03:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `frd_id` bigint(20) NOT NULL,
  `friend1_id` bigint(20) NOT NULL,
  `friend2_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_read` int(11) NOT NULL DEFAULT '0',
  `is_read_back` int(11) NOT NULL DEFAULT '0',
  `is_selected` int(11) NOT NULL DEFAULT '0',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`frd_id`, `friend1_id`, `friend2_id`, `status`, `is_read`, `is_read_back`, `is_selected`, `is_deleted`, `created_at`, `updated_at`) VALUES
(19, 14, 2, 0, 1, 0, 1, 0, '2019-02-28 03:50:26', '2019-02-28 03:59:58'),
(8, 10, 2, 1, 1, 0, 1, 0, '2018-09-14 13:32:45', '2018-10-16 11:43:10'),
(12, 2, 3, 0, 0, 0, 1, 0, '2018-09-15 13:09:09', '2018-09-17 12:29:40'),
(14, 2, 7, 1, 0, 0, 1, 0, '2018-09-17 12:34:33', '2018-09-17 12:34:39'),
(16, 2, 8, 1, 1, 0, 1, 0, '2019-02-19 01:42:01', '2019-02-19 01:46:04'),
(17, 2, 12, 2, 0, 0, 1, 0, '2019-02-19 01:43:16', '2019-02-19 01:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_ar_view` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_description` text COLLATE utf8mb4_unicode_ci,
  `location_type` enum('historical','buisness') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address` text COLLATE utf8mb4_unicode_ci,
  `location_tags` text COLLATE utf8mb4_unicode_ci,
  `google_place_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_lat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_lang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`, `location_ar_view`, `location_description`, `location_type`, `location_address`, `location_tags`, `google_place_id`, `location_lat`, `location_lang`, `user_id`, `status`, `reject_reason`, `created_at`, `updated_at`) VALUES
(1, 'Sozo Water Park', NULL, 'It does not matter if you are fifty people or five thousand, we can cater to any need you might have. Luxus Grand Hotel which is a sister concern and is our food and beverage partner for larger bookings. For Lunch boxes we have Sozo Grill which is an in house restaurant at Sozo Water Park. Our event Lawn has the capacity', 'historical', 'Lahore, Punjab, Pakistan', 'chill', 'ChIJJarNvuIRGTkRqXGPy5vO96U', '31.58135679999999', '74.48553289999995', 0, 0, NULL, '2018-05-07 01:00:00', '2018-05-22 09:32:28'),
(2, 'Jinnah Hospital Lahore', NULL, 'It does not matter if you are fifty people or five thousand, we can cater to any need you might have. Luxus Grand Hotel which is a sister concern and is our food and beverage partner for larger bookings. For Lunch boxes we have Sozo Grill which is an in house restaurant at Sozo Water Park. Our event Lawn has the capacity', 'historical', 'Faisal Town Usmani Road، Punjab University New Campus, Lahore, Punjab 54550, Pakistan', 'health, emergency', 'ChIJGZ5hhu4DGTkR88TG6XMM8h0', '31.4845414', '74.29738859999998', 0, 1, '', '2018-05-07 01:00:00', '2019-03-21 03:24:10'),
(3, 'Savour Foods', NULL, 'It does not matter if you are fifty people or five thousand, we can cater to any need you might have. Luxus Grand Hotel which is a sister concern and is our food and beverage partner for larger bookings. For Lunch boxes we have Sozo Grill which is an in house restaurant at Sozo Water Park. Our event Lawn has the capacity', 'buisness', 'Ferozpur Road, Shama Chowk, Near Naz Hospital، Link Shadman Road، Shershah Colony Ichra, Lahore, Punjab 54000, Pakistan', NULL, 'ChIJyewNiqIEGTkRezlWt9E6REA', '31.5391528', '74.32066439999994', 0, 0, NULL, '2018-05-07 01:00:00', '2018-05-22 09:35:26'),
(4, 'Pearl Continental Hotel Lahore', NULL, 'It does not matter if you are fifty people or five thousand, we can cater to any need you might have. Luxus Grand Hotel which is a sister concern and is our food and beverage partner for larger bookings. For Lunch boxes we have Sozo Grill which is an in house restaurant at Sozo Water Park. Our event Lawn has the capacity', 'historical', 'Mall Rd, G.O.R. - I, Lahore, Punjab, Pakistan', NULL, 'ChIJ6adtYMgEGTkR1yB3j7goCtc', '31.5526078', '74.33836599999995', 0, 0, NULL, '2018-05-07 01:00:00', '2018-05-22 09:36:17'),
(5, 'PureLogics', NULL, 'It does not matter if you are fifty people or five thousand, we can cater to any need you might have. Luxus Grand Hotel which is a sister concern and is our food and beverage partner for larger bookings. For Lunch boxes we have Sozo Grill which is an in house restaurant at Sozo Water Park. Our event Lawn has the capacity', 'historical', '75 R1Johar Town، Block R1 Block R 1 Phase 2 Johar Town, Lahore, Punjab, Pakistan', 'it, software house, good food', 'ChIJAQBAclkEGTkR5-KryGpUZnA', '31.4589332', '74.27574600000003', 0, 0, NULL, '2018-05-07 01:00:00', '2018-05-22 09:31:33'),
(6, 'Ilyas Dumba Karahi', NULL, NULL, 'buisness', 'Qila Lachman Singh, Lahore, Punjab, Pakistan', NULL, 'ChIJzYvhiX4cGTkRF_f7lWAQO3Y', '31.6068613', '74.3073028', 2, 2, 'Its false location details', '2018-05-21 08:06:42', '2019-02-19 01:51:25'),
(7, 'Techverx', NULL, 'some technology firms', 'historical', NULL, NULL, NULL, '31.459839', '74.27550199999996', 0, 2, 'Its not good for users', '2018-05-22 09:42:19', '2018-12-19 01:07:53'),
(11, 'Gulshan Iqbal Park', '5c914dce0afc5.mp4', 'entertaining place for kids and adults also', 'buisness', 'Gulshan Block Allama Iqbal Town, Lahore, Punjab 54000, Pakistan', NULL, 'ChIJ2dqK3J8DGTkRuISJ_G9ieis', '31.51293009999999', '74.28899049999995', 2, 1, '', '2018-06-04 09:44:39', '2019-03-19 15:15:10'),
(12, 'api test location', NULL, NULL, 'historical', NULL, NULL, NULL, '73.8737834', '73.8737834', 0, 1, '', '2018-06-21 11:11:40', '2018-12-18 01:26:28'),
(13, 'api test location', NULL, NULL, 'historical', NULL, NULL, NULL, '73.8737834', '73.8737834', 0, 1, NULL, '2018-06-21 11:12:09', '2018-12-11 10:27:57'),
(14, 'Krados Restaurant', NULL, 'Some food point', 'historical', NULL, 'food, resturant, spicy', NULL, '31.4757167', '74.30630989999997', 0, 1, NULL, '2018-12-11 10:24:02', '2018-12-11 10:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `locations_checkins`
--

CREATE TABLE `locations_checkins` (
  `lc_id` bigint(20) NOT NULL,
  `lc_user_id` bigint(20) NOT NULL,
  `lc_location_id` bigint(20) NOT NULL,
  `lc_status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations_checkins`
--

INSERT INTO `locations_checkins` (`lc_id`, `lc_user_id`, `lc_location_id`, `lc_status`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1, '2018-10-12 10:18:01', '2018-10-12 10:18:01'),
(2, 2, 11, 1, '2019-03-20 13:39:45', '2019-03-20 13:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `locations_rating`
--

CREATE TABLE `locations_rating` (
  `lr_id` bigint(20) NOT NULL,
  `lr_user_id` bigint(20) DEFAULT NULL,
  `lr_location_id` bigint(11) DEFAULT NULL,
  `lr_rating` double(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations_rating`
--

INSERT INTO `locations_rating` (`lr_id`, `lr_user_id`, `lr_location_id`, `lr_rating`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 1.70, '2018-05-18 11:15:54', '2018-05-18 11:15:54'),
(2, 2, 2, 1.80, '2018-05-19 03:57:36', '2018-05-19 03:57:36'),
(6, 2, 3, 3.50, '2018-06-09 08:11:58', '2018-06-09 08:11:58'),
(7, 2, 4, 2.50, '2018-09-12 09:39:24', '2018-09-12 09:39:24'),
(8, 2, 11, 3.50, '2019-03-20 13:22:50', '2019-03-20 13:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `locations_saved`
--

CREATE TABLE `locations_saved` (
  `ls_id` bigint(20) NOT NULL,
  `ls_user_id` bigint(20) DEFAULT NULL,
  `ls_location_id` bigint(20) DEFAULT '1',
  `ls_status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations_saved`
--

INSERT INTO `locations_saved` (`ls_id`, `ls_user_id`, `ls_location_id`, `ls_status`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 1, '2018-05-16 11:18:19', '2018-05-19 08:07:49'),
(2, 2, 2, 0, '2018-05-19 03:57:18', '2019-03-21 04:09:26'),
(3, 2, 4, 0, '2018-05-22 10:21:41', '2018-09-12 08:57:15'),
(4, 2, 3, 0, '2018-06-09 09:59:01', '2018-06-09 10:16:51'),
(5, 2, 11, 0, '2019-03-20 13:05:22', '2019-03-20 13:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `locations_subscribed`
--

CREATE TABLE `locations_subscribed` (
  `lsb_id` bigint(20) NOT NULL,
  `lsb_user_id` bigint(20) DEFAULT NULL,
  `lsb_location_id` bigint(20) DEFAULT NULL,
  `lsb_status` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations_subscribed`
--

INSERT INTO `locations_subscribed` (`lsb_id`, `lsb_user_id`, `lsb_location_id`, `lsb_status`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 0, '2018-05-16 20:12:40', '2018-05-19 08:07:39'),
(2, 2, 2, 1, '2018-05-19 03:56:16', '2018-05-19 04:32:09'),
(3, 2, 4, 0, '2018-05-22 10:21:46', '2018-09-12 08:56:59'),
(4, 2, 3, 0, '2018-06-09 09:44:31', '2018-06-09 09:48:23'),
(5, 2, 11, 1, '2019-03-20 13:23:01', '2019-03-20 13:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `location_comments`
--

CREATE TABLE `location_comments` (
  `comment_id` bigint(20) NOT NULL,
  `comment_body` text,
  `comment_parent_type` int(11) DEFAULT '1',
  `comment_parent_id` bigint(20) DEFAULT NULL,
  `comment_user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location_comments`
--

INSERT INTO `location_comments` (`comment_id`, `comment_body`, `comment_parent_type`, `comment_parent_id`, `comment_user_id`, `created_at`, `updated_at`) VALUES
(1, 'Test comment', 1, NULL, 2, '2018-05-16 09:58:59', '2018-05-16 09:58:59'),
(2, 'New comment', 1, NULL, 2, '2018-05-16 10:12:33', '2018-05-16 10:12:33'),
(3, 'Another comment', 1, NULL, 2, '2018-05-16 10:16:01', '2018-05-16 10:16:01'),
(4, 'Another comment', 1, 5, 2, '2018-05-16 10:19:17', '2018-05-16 10:19:17'),
(5, 'Another comment again', 1, 5, 2, '2018-05-16 10:25:34', '2018-05-16 10:25:34'),
(6, 'New comment here', 1, 5, 2, '2018-05-16 10:26:08', '2018-05-16 10:26:08'),
(7, 'What about new fucntionality', 1, 5, 2, '2018-05-16 10:26:26', '2018-05-16 10:26:26'),
(8, 'New comment again', 1, 5, 2, '2018-05-16 10:33:23', '2018-05-16 10:33:23'),
(9, 'Hi', 1, 5, 2, '2018-05-18 11:26:10', '2018-05-18 11:26:10'),
(10, 'First comment', 1, 2, 2, '2018-05-19 03:59:00', '2018-05-19 03:59:00'),
(11, 'Another comment', 1, 2, 2, '2018-05-19 03:59:37', '2018-05-19 03:59:37'),
(12, 'kjsfjksd', 1, 2, 2, '2018-05-19 04:10:37', '2018-05-19 04:10:37'),
(13, 'New comment here', 1, 4, 2, '2018-05-22 10:09:10', '2018-05-22 10:09:10'),
(14, 'Test api comment', 1, 3, 2, '2018-06-09 07:12:19', '2018-06-09 07:12:19'),
(15, 'Test api comment', 1, 3, 2, '2018-06-09 07:36:20', '2018-06-09 07:36:20'),
(16, 'Test api comment', 1, 3, 2, '2018-06-09 07:47:42', '2018-06-09 07:47:42'),
(17, 'Test api comment', 1, 3, 2, '2018-06-09 07:48:27', '2018-06-09 07:48:27'),
(18, 'Test api comment', 1, 3, 2, '2018-06-09 07:48:53', '2018-06-09 07:48:53'),
(19, 'Test api comment', 1, 3, 2, '2018-06-09 07:49:24', '2018-06-09 07:49:24'),
(20, 'Hello', 1, 4, 2, '2018-09-12 09:46:31', '2018-09-12 09:46:31');

-- --------------------------------------------------------

--
-- Table structure for table `location_hours`
--

CREATE TABLE `location_hours` (
  `lh_id` bigint(20) NOT NULL,
  `lh_day` int(11) DEFAULT NULL,
  `lh_open` varchar(156) DEFAULT NULL,
  `lh_close` varchar(156) DEFAULT NULL,
  `lh_is_holiday` int(11) DEFAULT '0',
  `lh_location_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location_hours`
--

INSERT INTO `location_hours` (`lh_id`, `lh_day`, `lh_open`, `lh_close`, `lh_is_holiday`, `lh_location_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 1, 6, '2018-05-21 12:13:32', '2018-05-21 12:44:02'),
(2, 2, NULL, NULL, 1, 6, '2018-05-21 12:13:32', '2018-05-21 12:13:32'),
(3, 3, '08:38 AM', '07:39 PM', 0, 6, '2018-05-21 12:13:32', '2018-05-22 09:39:06'),
(4, 4, '03:39 PM', '10:39 PM', 0, 6, '2018-05-21 12:13:32', '2018-05-21 12:39:43'),
(5, 5, NULL, NULL, 1, 6, '2018-05-21 12:13:33', '2018-05-21 12:13:33'),
(6, 6, '05:39 PM', '10:39 PM', 0, 6, '2018-05-21 12:13:33', '2018-05-21 12:39:43'),
(7, 7, NULL, NULL, 1, 6, '2018-05-21 12:13:33', '2018-05-21 12:13:33'),
(8, 1, '08:00 AM', '08:00 PM', 0, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(9, 2, '08:00 AM', '08:00 PM', 0, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(10, 3, '08:00 AM', '06:30 PM', 0, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(11, 4, NULL, NULL, 1, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(12, 5, NULL, NULL, 1, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(13, 6, NULL, NULL, 1, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(14, 7, NULL, NULL, 1, 3, '2018-05-22 09:35:26', '2018-05-22 09:35:26'),
(15, 1, NULL, NULL, 1, 11, '2018-06-04 09:54:28', '2018-06-04 09:54:28'),
(16, 2, NULL, NULL, 1, 11, '2018-06-04 09:54:28', '2018-06-04 09:54:28'),
(17, 3, NULL, NULL, 1, 11, '2018-06-04 09:54:29', '2018-06-04 09:54:29'),
(18, 4, NULL, NULL, 1, 11, '2018-06-04 09:54:29', '2018-06-04 09:54:29'),
(19, 5, NULL, NULL, 1, 11, '2018-06-04 09:54:29', '2018-06-04 09:54:29'),
(20, 6, NULL, NULL, 1, 11, '2018-06-04 09:54:29', '2018-06-04 09:54:29'),
(21, 7, NULL, NULL, 1, 11, '2018-06-04 09:54:29', '2018-06-04 09:54:29'),
(22, 2, '12:00', '06:00', 0, 14, '2018-06-21 11:48:48', '2018-06-21 11:48:48'),
(23, 1, '12:00', '04:00', 0, 14, '2018-06-21 11:50:28', '2018-06-21 11:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `location_images`
--

CREATE TABLE `location_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_image_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poster_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_images`
--

INSERT INTO `location_images` (`id`, `location_id`, `location_image_url`, `location_caption`, `poster_id`, `created_at`, `updated_at`) VALUES
(2, '1', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-09 09:46:01', '2018-05-09 09:46:01'),
(3, '2', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-09 09:46:26', '2018-05-09 09:46:26'),
(4, '2', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-09 09:46:26', '2018-05-09 09:46:26'),
(5, '3', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-09 09:46:32', '2018-05-09 09:46:32'),
(6, '5', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-09 09:46:26', '2018-05-09 09:46:26'),
(7, '4', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-22 09:37:51', '2018-05-22 09:37:51'),
(8, '6', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-22 09:38:38', '2018-05-22 09:38:38'),
(9, '7', '5bdc3a8e4fc90.jpg', '', 2, '2018-05-22 09:42:19', '2018-05-22 09:42:19'),
(15, '11', '5bdc3a8e4fc90.jpg', '', 2, '2018-06-04 09:44:39', '2018-06-04 09:44:39'),
(16, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-20 12:02:18', '2018-06-20 12:02:18'),
(17, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-20 12:02:18', '2018-06-20 12:02:18'),
(18, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-20 12:02:18', '2018-06-20 12:02:18'),
(19, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-20 12:03:39', '2018-06-20 12:03:39'),
(20, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:37:11', '2018-06-22 09:37:11'),
(21, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:37:11', '2018-06-22 09:37:11'),
(22, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:38:42', '2018-06-22 09:38:42'),
(23, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:38:42', '2018-06-22 09:38:42'),
(24, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:39:53', '2018-06-22 09:39:53'),
(25, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:39:53', '2018-06-22 09:39:53'),
(26, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 09:39:53', '2018-06-22 09:39:53'),
(27, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 10:36:25', '2018-06-22 10:36:25'),
(28, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 10:36:26', '2018-06-22 10:36:26'),
(29, '3', '5bdc77f7a5ec7.jpg', '', 2, '2018-06-22 10:36:26', '2018-06-22 10:36:26'),
(30, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:36:26', '2018-06-22 10:36:26'),
(31, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:40:41', '2018-06-22 10:40:41'),
(32, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:40:42', '2018-06-22 10:40:42'),
(33, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:43:36', '2018-06-22 10:43:36'),
(34, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:44:47', '2018-06-22 10:44:47'),
(35, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:46:33', '2018-06-22 10:46:33'),
(36, '3', '5c7662fb85e22.jpg', '', 2, '2018-06-22 10:47:02', '2018-06-22 10:47:02'),
(37, '4', '5c7662fb85e22.jpg', 'Where are you ?', 2, '2018-09-13 10:26:41', '2018-09-13 10:26:41'),
(38, '4', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 10:29:17', '2018-09-13 10:29:17'),
(39, '4', '5c7662fb85e22.jpg', 'multiple images with caption', 2, '2018-09-13 10:29:52', '2018-09-13 10:29:52'),
(40, '4', '5c7662fb85e22.jpg', 'multiple images with caption', 2, '2018-09-13 10:29:52', '2018-09-13 10:29:52'),
(41, '4', '5c7662fb85e22.jpg', 'multiple images with caption', 2, '2018-09-13 10:29:52', '2018-09-13 10:29:52'),
(42, '4', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 10:37:58', '2018-09-13 10:37:58'),
(43, '6', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 10:54:59', '2018-09-13 10:54:59'),
(44, '6', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 10:55:22', '2018-09-13 10:55:22'),
(45, '6', '5c7662fb85e22.jpg', 'New Caption Image', 2, '2018-09-13 10:55:53', '2018-09-13 10:55:53'),
(46, '4', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 11:49:20', '2018-09-13 11:49:20'),
(47, '4', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 11:50:39', '2018-09-13 11:50:39'),
(48, '4', '5c7662fb85e22.jpg', NULL, 2, '2018-09-13 11:51:43', '2018-09-13 11:51:43'),
(49, '6', '5c7662fb85e22.jpg', 'New Caption Image', 2, '2018-09-13 12:41:38', '2018-09-13 12:41:38'),
(50, '11', '5c9284e4e98e1.jpg', 'God of war :)', 2, '2019-03-20 13:22:28', '2019-03-20 13:22:28');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` bigint(20) NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `conversation_id` bigint(20) NOT NULL,
  `sender_id` bigint(20) NOT NULL,
  `message` text,
  `media` varchar(255) DEFAULT NULL,
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  `sender_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `reciever_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `is_admin`, `conversation_id`, `sender_id`, `message`, `media`, `is_read`, `sender_deleted`, `reciever_deleted`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 2, 'fag', '', 1, 0, 0, '2018-01-29 15:42:08', '2018-03-08 12:25:20'),
(2, 0, 1, 2, 'fag', '', 1, 0, 0, '2018-01-29 15:42:16', '2018-03-08 12:25:20'),
(3, 0, 1, 3, 'Hello', '', 1, 0, 0, '2018-01-29 15:43:04', '2018-10-02 10:22:34'),
(4, 0, 1, 2, 'fag', '', 1, 0, 0, '2018-01-29 15:43:14', '2018-03-08 12:25:20'),
(5, 0, 1, 3, 'I have', '', 1, 0, 0, '2018-01-29 15:43:27', '2018-10-02 10:22:34'),
(6, 0, 1, 2, 'fag', '', 1, 0, 0, '2018-01-29 15:43:40', '2018-03-08 12:25:20'),
(7, 0, 1, 2, 'hello Test', '', 1, 0, 0, '2018-01-29 15:49:29', '2018-03-08 12:25:20'),
(8, 0, 1, 3, 'Noor', '', 1, 0, 0, '2018-01-29 15:50:59', '2018-10-02 10:22:34'),
(9, 0, 1, 2, 'Some random messages', '', 1, 0, 0, '2018-01-29 15:53:32', '2018-03-08 12:25:20'),
(10, 0, 1, 2, 'Some random messages', '', 1, 0, 0, '2018-01-29 15:55:24', '2018-03-08 12:25:20'),
(11, 0, 1, 2, 'fag', '', 1, 0, 0, '2018-01-29 15:56:27', '2018-03-08 12:25:20'),
(12, 0, 1, 2, 'hi beautiful', '', 1, 0, 0, '2018-01-29 15:56:28', '2018-03-08 12:25:20'),
(13, 0, 1, 2, 'the', '', 1, 0, 0, '2018-01-29 15:56:29', '2018-03-08 12:25:20'),
(14, 0, 1, 2, 'the following', '', 1, 0, 0, '2018-01-29 15:56:30', '2018-03-08 12:25:20'),
(15, 0, 1, 2, 'hello Test', '', 1, 0, 0, '2018-01-29 15:56:30', '2018-03-08 12:25:20'),
(16, 0, 1, 2, 'ja oy', '', 1, 0, 0, '2018-01-29 15:56:31', '2018-03-08 12:25:20'),
(17, 0, 1, 2, 'Some random messages', '', 1, 0, 0, '2018-01-29 15:56:45', '2018-03-08 12:25:20'),
(18, 0, 1, 3, 'Some random messages', '', 1, 0, 0, '2018-01-29 15:59:26', '2018-10-02 10:22:34'),
(19, 0, 1, 3, 'Some random messages', '', 1, 0, 0, '2018-01-29 16:00:19', '2018-10-02 10:22:34'),
(20, 0, 1, 2, 'Some Test Messages', '', 1, 0, 0, '2018-01-29 16:01:52', '2018-03-08 12:25:20'),
(21, 0, 1, 2, 'Hello', NULL, 0, 0, 0, '2018-07-26 10:47:21', '2018-07-26 10:47:21'),
(22, 0, 1, 3, 'Hi', NULL, 1, 0, 0, '2018-07-26 12:19:42', '2018-10-02 10:22:34'),
(23, 0, 1, 2, 'How are you man ?', NULL, 1, 0, 0, '2018-07-26 12:20:53', '2018-07-26 12:20:57'),
(24, 0, 1, 3, 'I am fine. What about you ?', NULL, 1, 0, 0, '2018-07-26 12:22:45', '2018-10-02 10:22:34'),
(25, 0, 1, 2, 'I am fine too', NULL, 1, 0, 0, '2018-07-26 12:22:58', '2018-07-26 12:23:00'),
(26, 0, 1, 2, 'So what about monday plan ?', NULL, 1, 0, 0, '2018-07-26 12:23:16', '2018-07-26 12:23:20'),
(27, 0, 5, 7, 'Hey test api message', '', 1, 1, 0, '2018-07-30 10:39:17', '2018-07-30 12:43:00'),
(28, 0, 5, 7, 'Hey test api message again', '', 1, 1, 0, '2018-07-30 10:42:08', '2018-07-30 12:43:00'),
(29, 0, 5, 7, 'Hey test api message again', 'message_730741face0d62cF5X6XlDPvn.jpg', 1, 1, 0, '2018-07-30 10:43:30', '2018-07-30 12:43:00'),
(30, 0, 5, 7, 'Hey test api message again', '', 0, 0, 0, '2018-09-01 00:17:58', '2018-09-01 00:17:58');

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
(33, '2014_10_12_000000_create_users_table', 1),
(34, '2014_10_12_100000_create_password_resets_table', 1),
(35, '2018_04_17_152656_locations', 1),
(36, '2018_04_17_153514_roles', 1),
(37, '2018_04_20_142304_location_images', 1),
(38, '2018_04_26_152511_posts', 1),
(39, '2018_04_26_152600_posts_meta', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `noti_id` bigint(20) NOT NULL,
  `noti_body` text,
  `noti_url` varchar(600) DEFAULT NULL,
  `is_read` int(11) DEFAULT '0',
  `is_selected` int(11) DEFAULT '0',
  `user_id` bigint(20) NOT NULL,
  `reciever_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`noti_id`, `noti_body`, `noti_url`, `is_read`, `is_selected`, `user_id`, `reciever_id`, `created_at`, `updated_at`) VALUES
(7, '<a href=\'http://localhost:8000/user/profile/7\' class=\'notification-friend\'>Ali Hamza</a> accepted your friend request', 'http://localhost:8000/user/profile/7', 0, 1, 7, 2, '2018-09-17 12:34:39', '2018-09-17 12:34:41'),
(8, '<a href=\'http://localhost:8000/user/profile/2\' class=\'notification-friend\'>James Roffeld</a> accepted your friend request', 'http://localhost:8000/user/profile/2', 0, 0, 2, 10, '2018-10-16 11:43:10', '2018-10-16 11:43:10'),
(9, '<a href=\'http://localhost:8000/user/profile/8\' class=\'notification-friend\'>Ali Hamza 1</a> accepted your friend request', 'http://localhost:8000/user/profile/8', 0, 1, 8, 2, '2019-02-19 01:44:48', '2019-02-27 01:14:17'),
(10, '<a href=\'http://localhost:8000/user/profile/8\' class=\'notification-friend\'>Ali Hamza 1</a> accepted your friend request', 'http://localhost:8000/user/profile/8', 0, 1, 8, 2, '2019-02-19 01:46:04', '2019-02-27 01:14:17'),
(11, '<a href=\'http://localhost:8000/user/profile/12\' class=\'notification-friend\'>Ali Hamza2</a> accepted your friend request', 'http://localhost:8000/user/profile/12', 0, 1, 12, 2, '2019-02-19 01:47:56', '2019-02-27 01:14:17'),
(12, '<a href=\'http://localhost:8000/user/profile/12\' class=\'notification-friend\'>Ali Hamza2</a> declined your friend request', 'http://localhost:8000/user/profile/12', 0, 1, 12, 2, '2019-02-19 01:48:31', '2019-02-27 01:14:17'),
(13, '<a href=\'http://localhost:8000/user/profile/2\' class=\'notification-friend\'>Ameer Hamza</a> declined your friend request', 'http://localhost:8000/user/profile/2', 0, 0, 2, 3, '2019-02-27 04:14:36', '2019-02-27 04:14:36');

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_content`, `user_id`, `created_at`, `updated_at`) VALUES
(8, 'My test image post updated', 2, '2018-04-30 12:49:17', '2018-09-06 12:25:33'),
(9, 'Test Post', 7, '2018-09-06 09:47:58', '2018-09-06 12:25:00'),
(12, 'Test Post Api', 2, '2018-10-15 10:19:29', '2018-10-15 10:19:29'),
(15, 'Test Post Api', 2, '2018-10-15 10:39:34', '2018-10-15 10:39:34'),
(16, 'Test Post Api', 2, '2018-10-15 10:44:42', '2018-10-15 10:44:42'),
(17, 'Test Post Api', 2, '2018-10-15 10:45:21', '2018-10-15 10:45:21'),
(18, '', 2, '2018-10-15 10:53:58', '2018-10-25 10:19:11'),
(19, 'Updated profile photo', 2, '2018-10-16 11:53:34', '2018-10-16 11:53:34'),
(20, 'Upated cover photo', 2, '2018-10-16 11:54:52', '2018-10-16 11:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `posts_meta`
--

CREATE TABLE `posts_meta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `meta_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts_meta`
--

INSERT INTO `posts_meta` (`meta_id`, `meta_type`, `meta_url`, `post_id`, `created_at`, `updated_at`) VALUES
(8, NULL, '5ade13fad6e4d.jpg', 8, '2018-09-05 11:10:44', '2018-09-05 11:10:44'),
(11, NULL, '5ade13fad6e4d.jpg', 9, '2018-09-06 09:47:58', '2018-09-06 09:47:58'),
(23, NULL, '5ade143b7c0da.png', 8, '2018-09-06 12:15:36', '2018-09-06 12:15:36'),
(24, NULL, '5ade143b7c0da.png', 9, '2018-09-06 12:24:58', '2018-09-06 12:24:58'),
(25, NULL, '5adf67e433cc7.jpg', 9, '2018-09-06 12:24:58', '2018-09-06 12:24:58'),
(28, NULL, '5adf67e433cc7.jpg', 14, '2018-10-15 10:20:46', '2018-10-15 10:20:46'),
(29, NULL, '5adf67e433cc7.jpg', 14, '2018-10-15 10:20:46', '2018-10-15 10:20:46'),
(30, NULL, '5adf67e433cc7.jpg', 15, '2018-10-15 10:39:34', '2018-10-15 10:39:34'),
(31, NULL, '5adf67e433cc7.jpg', 15, '2018-10-15 10:39:34', '2018-10-15 10:39:34'),
(32, NULL, '5adf67e433cc7.jpg', 16, '2018-10-15 10:44:42', '2018-10-15 10:44:42'),
(33, NULL, '5adf67e399280.jpg', 16, '2018-10-15 10:44:42', '2018-10-15 10:44:42'),
(34, NULL, '5adf67e399280.jpg', 17, '2018-10-15 10:45:21', '2018-10-15 10:45:21'),
(35, NULL, '5adf67e399280.jpg', 17, '2018-10-15 10:45:21', '2018-10-15 10:45:21'),
(38, NULL, '5ae220a1b75ee.jpg', 19, '2018-10-16 11:53:34', '2018-10-16 11:53:34'),
(39, NULL, '5b2e3b1040a2d.jpg', 20, '2018-10-16 11:54:52', '2018-10-16 11:54:52'),
(43, NULL, '5b2e3b5097f11.jpg', 18, '2018-10-25 10:19:12', '2018-10-25 10:19:12');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `comment_id` bigint(20) NOT NULL,
  `comment_body` text,
  `comment_parent_type` int(11) DEFAULT '1',
  `comment_parent_id` bigint(20) DEFAULT NULL,
  `comment_user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`comment_id`, `comment_body`, `comment_parent_type`, `comment_parent_id`, `comment_user_id`, `created_at`, `updated_at`) VALUES
(10, 'Another comment edit', 1, 6, 2, '2018-04-30 09:57:30', '2018-04-30 10:48:03'),
(11, 'How are you man again ?', 1, 6, 2, '2018-04-30 09:58:51', '2018-04-30 10:44:09'),
(12, 'Test count', 1, 6, 2, '2018-04-30 10:00:28', '2018-04-30 10:00:28'),
(13, 'Hello new comment', 1, 6, 2, '2018-04-30 10:44:27', '2018-04-30 10:44:27'),
(14, 'New comment again edited', 1, 6, 2, '2018-04-30 10:48:13', '2018-04-30 10:48:28'),
(17, 'End user 1 comment edited good one', 1, 6, 3, '2018-04-30 12:41:11', '2018-04-30 12:47:40'),
(18, 'First comment', 1, 8, 3, '2018-04-30 12:49:34', '2018-04-30 12:49:34'),
(19, 'First comment here', 1, 7, 2, '2018-05-02 12:26:19', '2018-05-02 12:26:19'),
(20, 'Hello', 1, 9, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(22, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(23, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(24, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(25, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(26, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(27, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(28, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(29, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(30, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(31, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(32, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(33, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(34, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(35, 'Hello', 1, 18, 2, '2018-09-17 10:07:34', '2018-09-17 10:07:34'),
(36, 'HI', 1, 18, 2, '2019-03-08 01:16:29', '2019-03-08 01:16:29'),
(37, 'Hi', 1, 20, 2, '2019-03-08 01:26:12', '2019-03-08 01:26:12'),
(38, 'Hello', 1, 18, 2, '2019-03-08 01:30:02', '2019-03-08 01:30:02'),
(39, 'Hello', 1, 20, 2, '2019-03-08 01:55:16', '2019-03-08 01:55:16'),
(40, 'What are you doing ?', 1, 18, 2, '2019-03-08 01:55:43', '2019-03-08 01:55:43'),
(41, 'Hi', 1, 19, 2, '2019-03-08 02:58:32', '2019-03-08 02:58:32'),
(42, 'How are you ?', 1, 19, 2, '2019-03-08 02:58:37', '2019-03-08 02:58:37'),
(43, 'Whats up ?', 1, 19, 2, '2019-03-08 02:58:42', '2019-03-08 02:58:42'),
(44, 'well done', 1, 19, 2, '2019-03-08 02:58:48', '2019-03-08 02:58:48'),
(45, 'good day', 1, 19, 2, '2019-03-08 02:58:53', '2019-03-08 02:58:53'),
(46, 'Hahahaha', 1, 19, 2, '2019-03-08 02:58:58', '2019-03-08 02:58:58'),
(47, 'Hi', 1, 18, 2, '2019-03-08 03:07:16', '2019-03-08 03:07:16'),
(48, 'hi', 1, 20, 2, '2019-03-08 03:35:00', '2019-03-08 03:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `like_id` bigint(20) NOT NULL,
  `like_status` int(11) DEFAULT '1',
  `like_parent_type` int(11) DEFAULT '1',
  `like_parent_id` bigint(20) DEFAULT NULL,
  `like_user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`like_id`, `like_status`, `like_parent_type`, `like_parent_id`, `like_user_id`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 7, 2, '2018-04-30 11:08:53', '2018-04-30 12:20:47'),
(2, 0, 1, 6, 2, '2018-04-30 12:00:06', '2018-04-30 12:21:40'),
(3, 1, 1, 5, 2, '2018-04-30 12:21:35', '2018-04-30 12:21:35'),
(4, 1, 1, 8, 2, '2018-04-30 12:50:11', '2019-02-28 01:25:01'),
(5, 0, 1, 8, 3, '2018-04-30 12:50:19', '2018-04-30 12:50:25'),
(6, 0, 1, 9, 2, '2018-09-17 10:11:34', '2018-09-17 10:11:42'),
(7, 1, 1, 15, 2, '2018-10-15 12:31:56', '2018-10-15 12:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'System Administrator', '2018-04-26 13:17:25', '2018-04-26 13:17:25'),
(2, 'user', 'End User', '2018-04-26 13:17:25', '2018-04-26 13:17:25');

-- --------------------------------------------------------

--
-- Table structure for table `terms_and_privacy`
--

CREATE TABLE `terms_and_privacy` (
  `id` bigint(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `type` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms_and_privacy`
--

INSERT INTO `terms_and_privacy` (`id`, `title`, `content`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Terms And Conditions Title', 'This is terms and conditions body', 'terms', '2018-10-29 15:33:32', '2018-10-29 15:33:32'),
(2, 'Privacy Title', 'This is privacy content updated', 'privacy', '2018-10-29 15:37:54', '2018-10-29 15:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_online` int(11) DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `dob`, `gender`, `password`, `role_id`, `token`, `device_type`, `device_token`, `is_online`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Goseeum', 'Admin', 'admin@goseeum.com', NULL, 'male', '$2y$10$oRkGko3a.XQ89pGSCIEpe.a8nJtzokoPzd9dwGZxMOotux3QrIYNC', 1, NULL, NULL, NULL, 0, 'PPhonT8R27nXO6YFlq07SPTi8o5bc6k6A2gPq5NnteufInptAKqMrHpb8ZEX', '2018-04-26 13:18:18', '2018-04-26 13:18:18'),
(2, 'Ameer', 'Hamza', 'enduser@goseeum.com', '21/03/2018', 'male', '$2y$10$SpdtK5rIQrc4I199GsK4Fumd5tMV19zRIImgzPc8LPktiOBzVljNG', 2, 'auth0|5b1392f177bfa', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 1, 'tz9lE7meGESIATQS4HQEXP6GmMZjN0Utaa5h54AbCwmhbZMSpmrrFcz2s4NU', '2018-04-26 13:18:19', '2018-12-18 03:01:02'),
(3, 'Noor', 'Muhammad', 'enduser1@gmail.com', '04/04/1994', 'MA', '$2y$10$11Javvdr.HRdbipp5ywh7u9sEknz9tJ9J52xPE3PocMaAH6zW75ey', 2, NULL, NULL, NULL, 0, '2hbCtLzCqouR8PbygSKIKQY0uc42qnM6wfYHwC1E7aSyXXbPdDfoEh4dxHYX', '2018-04-30 12:40:11', '2018-04-30 12:40:11'),
(7, 'ali', 'hamza', 'ali.hmza@gmail.com', '21/03/2018', 'male', '$2y$10$11Javvdr.HRdbipp5ywh7u9sEknz9tJ9J52xPE3PocMaAH6zW75ey', 2, 'auth0|5b13b8ad7b839', NULL, NULL, 0, NULL, '2018-06-03 04:45:17', '2018-06-03 04:45:17'),
(8, 'ali', 'hamza 1', 'ali.hmza1@gmail.com', '21/03/2018', 'male', '$2y$10$SpdtK5rIQrc4I199GsK4Fumd5tMV19zRIImgzPc8LPktiOBzVljNG', 2, 'auth0|5b13c76f59950', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 0, 'stVfDZJtesxXchzYke8tNpHpb4jp8ibY25vNIakkiuH2KoWsguOcNnc6lgp4', '2018-06-03 05:48:15', '2018-06-03 05:48:15'),
(10, 'Muhammad', 'Umer', 'm.umer1076@gmail.com', '08/08/2018', 'MA', '$2y$10$NANY00K.26eAWwu59ZDys.cLcITaTEJwWcJmb8aRWqhFRj2dlQnu2', 2, 'auth0|5b8ebd3755ba5', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 0, NULL, '2018-09-04 12:13:27', '2018-09-04 12:13:27'),
(12, 'ali', 'hamza2', 'ali.hmza2@gmail.com', '21/03/2018', 'male', '$2y$10$SpdtK5rIQrc4I199GsK4Fumd5tMV19zRIImgzPc8LPktiOBzVljNG', 2, 'auth0|5bc61dc96960c', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 0, 'DFxokWQcHoVwmwYGIsLjZpF7qzHcfnpfShEb3dW143y3eAX6ZEqNfeC7tLZs', '2018-10-16 12:20:09', '2018-10-16 12:20:09'),
(13, 'ali', 'hamza3', 'ali.hmza3@gmail.com', '21/03/2018', 'male', '$2y$10$Jv4f8NXDRPIUsd7Jytb6NO/V5tWg0qTmfqcv78QQpz7I8C.Iv/5Ve', 2, 'auth0|5bc620e9ea7b7', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 0, NULL, '2018-10-16 12:33:29', '2018-10-16 12:33:29'),
(14, 'ali', 'hamza4', 'ali.hmza4@gmail.com', '21/03/2018', 'male', '$2y$10$ngHk.VKwk5P3N6SEV1F5Ke6D1N6G2.NtBPmiq2ohuRc8l8jS8LMPS', 2, 'auth0|5bc621062ddfe', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 0, NULL, '2018-10-16 12:33:58', '2018-10-16 12:33:58'),
(15, 'ali', 'hamza5', 'ali.hmza5@gmail.com', '21/03/2018', 'male', '$2y$10$LIjZxc7XEcnIM3GG3XUwxOyOJcRu.dF.byCttmyBRq15dca7fEo.m', 2, 'auth0|5bc6211555563', 'android', 'ijasdjkasjnasjkndajsdnasjkd', 0, NULL, '2018-10-16 12:34:13', '2018-10-16 12:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `photo_id` bigint(20) NOT NULL,
  `photo_type` varchar(20) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `photo_status` int(11) NOT NULL DEFAULT '1',
  `photo_user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` (`photo_id`, `photo_type`, `photo_url`, `photo_status`, `photo_user_id`, `created_at`, `updated_at`) VALUES
(1, 'cover', '5c41850190b77.jpg', 1, 2, '2018-06-13 10:41:06', '2018-06-13 10:41:06'),
(2, 'cover', '5c41850190b77.jpg', 1, 2, '2018-06-13 10:56:03', '2018-06-13 10:56:03'),
(3, 'profile', '5c41850190b77.jpg', 1, 2, '2018-06-13 10:56:53', '2018-06-13 10:56:53'),
(4, 'profile', '5c41850190b77.jpg', 1, 2, '2018-06-13 11:13:42', '2018-06-13 11:13:42'),
(5, 'profile', '5c41850190b77.jpg', 1, 2, '2018-06-13 11:16:01', '2018-06-13 11:16:01'),
(6, 'cover', '5c41850190b77.jpg', 1, 2, '2018-06-13 11:16:14', '2018-06-13 11:16:14'),
(7, 'profile', '5c41850190b77.jpg', 1, 2, '2018-06-13 11:28:08', '2018-06-13 11:28:08'),
(8, 'cover', '5c41850190b77.jpg', 1, 2, '2018-06-15 01:53:16', '2018-06-15 01:53:16'),
(9, 'profile', '5c41850190b77.jpg', 1, 2, '2018-06-15 01:59:20', '2018-06-15 01:59:20'),
(10, 'profile', '5c41850190b77.jpg', 1, 2, '2018-09-04 12:10:14', '2018-09-04 12:10:14'),
(11, 'profile', '5c41850190b77.jpg', 1, 2, '2018-09-06 12:33:50', '2018-09-06 12:33:50'),
(12, 'profile', '5c41850190b77.jpg', 1, 2, '2018-10-16 11:53:34', '2018-10-16 11:53:34'),
(13, 'cover', '5c41850190b77.jpg', 1, 2, '2018-10-16 11:54:52', '2018-10-16 11:54:52'),
(14, 'profile', '5c41850190b77.jpg', 1, 12, '2018-10-16 12:20:09', '2018-10-16 12:20:09'),
(15, 'profile', '5c41850190b77.jpg', 1, 14, '2018-10-16 12:33:58', '2018-10-16 12:33:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conv_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`frd_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `locations` ADD FULLTEXT KEY `location_name` (`location_name`);

--
-- Indexes for table `locations_checkins`
--
ALTER TABLE `locations_checkins`
  ADD PRIMARY KEY (`lc_id`);

--
-- Indexes for table `locations_rating`
--
ALTER TABLE `locations_rating`
  ADD PRIMARY KEY (`lr_id`);

--
-- Indexes for table `locations_saved`
--
ALTER TABLE `locations_saved`
  ADD PRIMARY KEY (`ls_id`);

--
-- Indexes for table `locations_subscribed`
--
ALTER TABLE `locations_subscribed`
  ADD PRIMARY KEY (`lsb_id`);

--
-- Indexes for table `location_comments`
--
ALTER TABLE `location_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `location_hours`
--
ALTER TABLE `location_hours`
  ADD PRIMARY KEY (`lh_id`);

--
-- Indexes for table `location_images`
--
ALTER TABLE `location_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`noti_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `posts_meta`
--
ALTER TABLE `posts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_and_privacy`
--
ALTER TABLE `terms_and_privacy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`photo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conv_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `frd_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `locations_checkins`
--
ALTER TABLE `locations_checkins`
  MODIFY `lc_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations_rating`
--
ALTER TABLE `locations_rating`
  MODIFY `lr_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `locations_saved`
--
ALTER TABLE `locations_saved`
  MODIFY `ls_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `locations_subscribed`
--
ALTER TABLE `locations_subscribed`
  MODIFY `lsb_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `location_comments`
--
ALTER TABLE `location_comments`
  MODIFY `comment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `location_hours`
--
ALTER TABLE `location_hours`
  MODIFY `lh_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `location_images`
--
ALTER TABLE `location_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `noti_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `posts_meta`
--
ALTER TABLE `posts_meta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `comment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `like_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `terms_and_privacy`
--
ALTER TABLE `terms_and_privacy`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `photo_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
