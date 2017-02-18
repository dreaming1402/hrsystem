-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2017 at 09:52 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_contract`
--

CREATE TABLE `prefix_db_contract` (
  `id` int(11) NOT NULL,
  `contract_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contract_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Probation',
  `contract_basic_salary` decimal(15,0) NOT NULL DEFAULT '0',
  `contract_responsibitity_salary` decimal(15,0) NOT NULL DEFAULT '0',
  `contract_insurance_salary` decimal(15,0) NOT NULL DEFAULT '0',
  `contract_begin` date DEFAULT NULL,
  `contract_end` date DEFAULT NULL,
  `contract_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_trash_flag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_employee`
--

CREATE TABLE `prefix_db_employee` (
  `id` int(11) NOT NULL,
  `department` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(8) NOT NULL,
  `employee_old_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `job` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `present_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'female',
  `person_id` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `person_issued_date` date DEFAULT NULL,
  `person_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ethenic` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `education` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `working_status` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'active',
  `left_date` date DEFAULT NULL,
  `account` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pit_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `health_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_trash_flag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_health`
--

CREATE TABLE `prefix_db_health` (
  `id` int(11) NOT NULL,
  `health_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `health_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `health_date` date DEFAULT NULL,
  `health_flag` int(11) DEFAULT '0',
  `health_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `health_trash_flag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_maternity`
--

CREATE TABLE `prefix_db_maternity` (
  `id` int(11) NOT NULL,
  `maternity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `maternity_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `maternity_begin` date DEFAULT NULL,
  `maternity_end` date DEFAULT NULL,
  `maternity_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maternity_trash_flag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_print_card`
--

CREATE TABLE `prefix_db_print_card` (
  `id` int(11) NOT NULL,
  `print_card_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `print_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `print_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_department` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(8) DEFAULT NULL,
  `employee_full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_contract_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maternity_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maternity_begin` date DEFAULT NULL,
  `maternity_end` date DEFAULT NULL,
  `print_card_trash_flag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_db_social`
--

CREATE TABLE `prefix_db_social` (
  `id` int(11) NOT NULL,
  `social_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `social_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_date` date DEFAULT NULL,
  `social_flag` int(11) DEFAULT '0',
  `social_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_trash_flag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prefix_db_contract`
--
ALTER TABLE `prefix_db_contract`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contract_id` (`contract_id`),
  ADD KEY `contract_type` (`contract_type`);

--
-- Indexes for table `prefix_db_employee`
--
ALTER TABLE `prefix_db_employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `prefix_db_health`
--
ALTER TABLE `prefix_db_health`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `health_id` (`health_id`);

--
-- Indexes for table `prefix_db_maternity`
--
ALTER TABLE `prefix_db_maternity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maternity_id` (`maternity_id`);

--
-- Indexes for table `prefix_db_print_card`
--
ALTER TABLE `prefix_db_print_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `print_card_id` (`print_card_id`) USING BTREE;

--
-- Indexes for table `prefix_db_social`
--
ALTER TABLE `prefix_db_social`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_id` (`social_id`),
  ADD KEY `social_place` (`social_place`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prefix_db_contract`
--
ALTER TABLE `prefix_db_contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prefix_db_employee`
--
ALTER TABLE `prefix_db_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prefix_db_health`
--
ALTER TABLE `prefix_db_health`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prefix_db_maternity`
--
ALTER TABLE `prefix_db_maternity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prefix_db_print_card`
--
ALTER TABLE `prefix_db_print_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prefix_db_social`
--
ALTER TABLE `prefix_db_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
