-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 05:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `praja_shakthi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` int(11) NOT NULL,
  `house_no` varchar(20) DEFAULT NULL,
  `head_of_household` varchar(100) DEFAULT NULL,
  `poverty_category` enum('Low Income','Middle Income','Vulnerable') DEFAULT NULL,
  `support_received` varchar(255) DEFAULT NULL,
  `status` enum('Active','Pending','Completed') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`id`, `house_no`, `head_of_household`, `poverty_category`, `support_received`, `status`) VALUES
(4, '01', 'Thilsan', 'Middle Income', 'Food items', 'Active'),
(5, '03', 'Ahnaf', 'Vulnerable', 'Dresses', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `gn_profile`
--

CREATE TABLE `gn_profile` (
  `id` int(11) NOT NULL,
  `division_name` varchar(100) DEFAULT NULL,
  `division_number` varchar(20) DEFAULT NULL,
  `population` int(11) DEFAULT NULL,
  `households` int(11) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gn_profile`
--

INSERT INTO `gn_profile` (`id`, `division_name`, `division_number`, `population`, `households`, `contact_no`) VALUES
(1, 'Sammanthurai', 'S/24', 65000, 2500, '0760617846');

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `meeting_title` varchar(200) NOT NULL,
  `meeting_date` date NOT NULL,
  `meeting_time` time NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `agenda` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `meeting_title`, `meeting_date`, `meeting_time`, `location`, `agenda`, `created_at`) VALUES
(1, 'Drug free Socity', '2026-01-18', '13:14:00', 'DS office', 'All of the people attend the meeting on coming monday', '2026-01-14 07:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `status` enum('Planned','Ongoing','Completed') DEFAULT 'Planned',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `progress` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `description`, `budget`, `status`, `created_at`, `progress`) VALUES
(1, 'Road project', 'To devolope the brocken roadf', 10000000.00, 'Ongoing', '2026-01-14 06:10:40', 0),
(2, 'House project', 're assamble the hpose', 102982.00, 'Completed', '2026-01-14 06:11:20', 0),
(3, 'Class project ', 'renavate the class', 450000.00, 'Planned', '2026-01-14 06:11:53', 0),
(4, 'House project', 'hi iam', 345678.00, 'Completed', '2026-01-14 06:12:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('GN_Officer','Council_Member','Divisional_Secretariat','Community_Member') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Thilsan', '$2y$10$WkaN.wFC9aUYfF16VoDVZu3i23PG5dGLUtxviQ0ZR65Glys.hCOce', 'GN_Officer', '2026-01-14 05:38:29'),
(4, 'Ahnaf', '$2y$10$fXU7r7hRNMvU8Mq6FWtEP.jVKDh.VA2OgOyMye0GEduv/H0HpnSMG', 'Divisional_Secretariat', '2026-01-14 05:57:12'),
(5, 'Farhath', '$2y$10$17tyZ4Yc6HIMCNmewymJ.Ott9KTRHgUqAbUd21ikXSSZB/obXYPzG', 'GN_Officer', '2026-01-14 08:10:18'),
(6, 'Hassan', '$2y$10$G7l4OzsyOKA9PstT.ZwIoezgx73uXysWOZBG5t/FMYPK2lhq.tTIO', 'Council_Member', '2026-01-17 03:21:37'),
(7, 'Hansaf', '$2y$10$Nlp1i4.Kj87/1MWKrw/VXeVoYVFwegRRfPQSYwz8NepDCWYytcZp2', 'Community_Member', '2026-01-17 03:21:53'),
(8, 'Besrol', '$2y$10$5XArTvv5TGEWp6EmVGc0iuKuZrjEkMZLZefEUlkpFqnmJoY6MGNeW', 'GN_Officer', '2026-01-18 04:36:25'),
(9, 'Mahthi', '$2y$10$s1kviiYF6Y4jNfpClHG61OvNAeRyQsQNwCEyTuuPPSwqI1M1O7.ze', 'Council_Member', '2026-01-18 04:43:19'),
(10, 'Mihran', '$2y$10$HRb.l.PCHyOaikS9ZDi0TefiGX7NmKAXWktp/5.6Q7LVnyOGvz77.', 'Community_Member', '2026-01-18 04:44:12'),
(11, 'MIhjab', '$2y$10$DYTWoZokFdScsJ6pc5mRCOQWJ.lguNqX12gLO8ezGSF0Rbc4p6w/G', 'Divisional_Secretariat', '2026-01-18 04:44:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gn_profile`
--
ALTER TABLE `gn_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gn_profile`
--
ALTER TABLE `gn_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
