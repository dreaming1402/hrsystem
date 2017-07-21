-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2017 at 06:02 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_action`
--

CREATE TABLE `prefix_db_action` (
  `id` int(11) NOT NULL,
  `db_action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `db_action_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `db_action_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `db_action_success` tinyint(1) NOT NULL DEFAULT '0',
  `db_action_content` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_print_card`
--

CREATE TABLE `prefix_db_print_card` (
  `id` int(11) NOT NULL,
  `db_print_card_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `db_print_card_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `db_print_card_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `db_print_card_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `db_print_card_create_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `db_print_card_mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `db_print_card_mod_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `db_print_card_trash_flag` tinyint(1) NOT NULL DEFAULT '0',
  `db_print_card_trash_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `db_print_card_trash_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `db_print_card_restore_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `db_print_card_restore_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `employee_department` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(8) DEFAULT NULL,
  `employee_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contract_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maternity_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maternity_begin` date DEFAULT NULL,
  `maternity_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prefix_db_action`
--
ALTER TABLE `prefix_db_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prefix_db_print_card`
--
ALTER TABLE `prefix_db_print_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `db_print_card_id` (`db_print_card_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prefix_db_action`
--
ALTER TABLE `prefix_db_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=691;
--
-- AUTO_INCREMENT for table `prefix_db_print_card`
--
ALTER TABLE `prefix_db_print_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
