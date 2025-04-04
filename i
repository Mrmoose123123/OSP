-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: ccsw-mysql-exams1.mysql.database.azure.com
-- Generation Time: Apr 04, 2025 at 02:14 PM
-- Server version: 8.0.40-azure
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `0020037074_24_890_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultant`
--

CREATE TABLE `consultant` (
  `Consultant_id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` int DEFAULT NULL COMMENT 'FK referencing the Region table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores details about consultants';

--
-- Dumping data for table `consultant`
--

INSERT INTO `consultant` (`Consultant_id`, `first_name`, `Surname`, `region_id`) VALUES
(1, 'Alice', 'Smith', 1),
(2, 'Bob', 'Jones', 1),
(3, 'Charlie', 'Davis', 2),
(4, 'Diana', 'Evans', 3),
(5, 'Edward', 'Harris', 2),
(6, 'Fiona', 'Clarke', 4);

-- --------------------------------------------------------

--
-- Table structure for table `consultation_bookings`
--

CREATE TABLE `consultation_bookings` (
  `Consult_id` int NOT NULL COMMENT 'Unique ID for each consultation booking',
  `user_id` int NOT NULL COMMENT 'FK referencing the User (from `users` table) who made the booking',
  `consult_slot_id` int NOT NULL COMMENT 'FK referencing the specific consult_slot definition booked',
  `Consultation_date_recorded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the booking was made',
  `consultant_id` int NOT NULL COMMENT 'FK referencing the Consultant assigned to the booking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Records bookings made for consultations';

--
-- Dumping data for table `consultation_bookings`
--

INSERT INTO `consultation_bookings` (`Consult_id`, `user_id`, `consult_slot_id`, `Consultation_date_recorded`, `consultant_id`) VALUES
(11, 2, 5, '2025-04-04 09:14:19', 5);

-- --------------------------------------------------------

--
-- Table structure for table `consult_slot`
--

CREATE TABLE `consult_slot` (
  `Consult_slot_id` int NOT NULL COMMENT 'Unique ID for a specific consultation slot definition',
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Day of the week for the slot',
  `start_time` time NOT NULL COMMENT 'Start time of the slot',
  `end_time` time NOT NULL COMMENT 'End time of the slot',
  `available_slot` tinyint(1) DEFAULT '1' COMMENT 'Indicates if this type of slot is generally available for booking (TRUE=yes, FALSE=no)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines available time slots for consultations';

--
-- Dumping data for table `consult_slot`
--

INSERT INTO `consult_slot` (`Consult_slot_id`, `day`, `start_time`, `end_time`, `available_slot`) VALUES
(1, 'monday', '09:00:00', '10:00:00', 1),
(2, 'monday', '10:00:00', '11:00:00', 1),
(3, 'monday', '11:00:00', '12:00:00', 1),
(4, 'tuesday', '14:00:00', '15:00:00', 1),
(5, 'tuesday', '15:00:00', '16:00:00', 1),
(6, 'wednesday', '09:30:00', '10:30:00', 1),
(7, 'wednesday', '10:30:00', '11:30:00', 1),
(8, 'thursday', '14:00:00', '15:00:00', 1),
(9, 'friday', '09:00:00', '10:00:00', 1),
(10, 'friday', '10:00:00', '11:00:00', 1),
(11, 'friday', '13:00:00', '14:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `engineer`
--

CREATE TABLE `engineer` (
  `Engineer_id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` int DEFAULT NULL COMMENT 'FK referencing the Region table',
  `Engineer_mobile` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Engineer contact mobile number'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores details about engineers';

--
-- Dumping data for table `engineer`
--

INSERT INTO `engineer` (`Engineer_id`, `first_name`, `Surname`, `region_id`, `Engineer_mobile`) VALUES
(1, 'George', 'Miller', 1, '07111222333'),
(2, 'Hannah', 'Wilson', 2, NULL),
(3, 'Ian', 'Taylor', 3, '07333444555'),
(4, 'Jane', 'Anderson', 1, '07555666777'),
(5, 'Kevin', 'Thomas', 5, '07888999000'),
(6, 'Laura', 'Roberts', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `installation_bookings`
--

CREATE TABLE `installation_bookings` (
  `installation_id` int NOT NULL COMMENT 'Unique ID for each installation booking',
  `user_id` int NOT NULL COMMENT 'FK referencing the User (from `users` table) who made the booking',
  `installation_slot_id` int NOT NULL COMMENT 'FK referencing the specific installation_slot definition booked',
  `installation_date_recorded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the booking was made',
  `Engineer_id` int NOT NULL COMMENT 'FK referencing the Engineer assigned to the booking',
  `Consult_id` int DEFAULT NULL COMMENT 'FK referencing a prior Consultation booking (optional?)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Records bookings made for installations';

--
-- Dumping data for table `installation_bookings`
--

INSERT INTO `installation_bookings` (`installation_id`, `user_id`, `installation_slot_id`, `installation_date_recorded`, `Engineer_id`, `Consult_id`) VALUES
(11, 3, 3, '2025-04-04 09:23:22', 6, 11);

-- --------------------------------------------------------

--
-- Table structure for table `installation_slot`
--

CREATE TABLE `installation_slot` (
  `Installation_slot_id` int NOT NULL COMMENT 'Unique ID for a specific installation slot definition',
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Day of the week for the slot',
  `start_time` time NOT NULL COMMENT 'Start time of the slot',
  `end_time` time NOT NULL COMMENT 'End time of the slot',
  `available_slot` tinyint(1) DEFAULT '1' COMMENT 'Indicates if this type of slot is generally available for booking (TRUE=yes, FALSE=no)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines available time slots for installations';

--
-- Dumping data for table `installation_slot`
--

INSERT INTO `installation_slot` (`Installation_slot_id`, `day`, `start_time`, `end_time`, `available_slot`) VALUES
(1, 'monday', '13:00:00', '15:00:00', 1),
(2, 'tuesday', '09:00:00', '11:00:00', 1),
(3, 'tuesday', '13:00:00', '16:00:00', 1),
(4, 'wednesday', '09:00:00', '12:00:00', 1),
(5, 'thursday', '09:00:00', '11:00:00', 1),
(6, 'thursday', '13:00:00', '15:00:00', 1),
(7, 'friday', '09:00:00', '12:00:00', 1),
(8, 'friday', '13:00:00', '16:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE `region` (
  `Region_id` int NOT NULL,
  `Region` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of the region'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores geographical regions';

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`Region_id`, `Region`) VALUES
(1, 'North West'),
(2, 'Midlands'),
(3, 'South East'),
(4, 'Scotland'),
(5, 'Wales');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `password` varchar(150) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `firstname` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `surname` varchar(100) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(100) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `mobile` int NOT NULL,
  `date_recorded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `surname`, `date_of_birth`, `email`, `mobile`, `date_recorded`) VALUES
(2, 'u', '$2y$10$OJJ446KKMXSnKNFe9TDe9utLhzgGUsTXeYW3.PDvRuUDN6EHeeraq', 'f', 's', '2007-02-08', 's@gmail.com', 324234, '2025-03-28 00:00:00'),
(3, 'mike1', '$2y$10$MgcFZkdpSG3VdgMH1ziFdO6jLLK6O9.Ir0cYiBtPu2e8VMY/EaOt2', 'mike', 'johns', '2006-02-08', 'mikejohns@gmail.com', 748297334, '2025-04-01 00:00:00'),
(4, 'test1', '$2y$10$TefG.ep5CZ4JUdnoVQuA8.3k2UpvXi60XsP0TCarnjxSlyRhyWQp.', '1', '2', '2007-02-08', 'hello@gmail.com', 748297334, '2025-04-04 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultant`
--
ALTER TABLE `consultant`
  ADD PRIMARY KEY (`Consultant_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `consultation_bookings`
--
ALTER TABLE `consultation_bookings`
  ADD PRIMARY KEY (`Consult_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `consult_slot_id` (`consult_slot_id`),
  ADD KEY `consultant_id` (`consultant_id`);

--
-- Indexes for table `consult_slot`
--
ALTER TABLE `consult_slot`
  ADD PRIMARY KEY (`Consult_slot_id`),
  ADD UNIQUE KEY `unique_consult_slot_time` (`day`,`start_time`);

--
-- Indexes for table `engineer`
--
ALTER TABLE `engineer`
  ADD PRIMARY KEY (`Engineer_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `installation_bookings`
--
ALTER TABLE `installation_bookings`
  ADD PRIMARY KEY (`installation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `installation_slot_id` (`installation_slot_id`),
  ADD KEY `Engineer_id` (`Engineer_id`),
  ADD KEY `Consult_id` (`Consult_id`);

--
-- Indexes for table `installation_slot`
--
ALTER TABLE `installation_slot`
  ADD PRIMARY KEY (`Installation_slot_id`),
  ADD UNIQUE KEY `unique_install_slot_time` (`day`,`start_time`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`Region_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consultant`
--
ALTER TABLE `consultant`
  MODIFY `Consultant_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `consultation_bookings`
--
ALTER TABLE `consultation_bookings`
  MODIFY `Consult_id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each consultation booking', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `consult_slot`
--
ALTER TABLE `consult_slot`
  MODIFY `Consult_slot_id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for a specific consultation slot definition', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `engineer`
--
ALTER TABLE `engineer`
  MODIFY `Engineer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `installation_bookings`
--
ALTER TABLE `installation_bookings`
  MODIFY `installation_id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each installation booking', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `installation_slot`
--
ALTER TABLE `installation_slot`
  MODIFY `Installation_slot_id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for a specific installation slot definition', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `Region_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultant`
--
ALTER TABLE `consultant`
  ADD CONSTRAINT `consultant_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`Region_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `consultation_bookings`
--
ALTER TABLE `consultation_bookings`
  ADD CONSTRAINT `consultation_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_bookings_ibfk_2` FOREIGN KEY (`consult_slot_id`) REFERENCES `consult_slot` (`Consult_slot_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_bookings_ibfk_3` FOREIGN KEY (`consultant_id`) REFERENCES `consultant` (`Consultant_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `engineer`
--
ALTER TABLE `engineer`
  ADD CONSTRAINT `engineer_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`Region_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `installation_bookings`
--
ALTER TABLE `installation_bookings`
  ADD CONSTRAINT `installation_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `installation_bookings_ibfk_2` FOREIGN KEY (`installation_slot_id`) REFERENCES `installation_slot` (`Installation_slot_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `installation_bookings_ibfk_3` FOREIGN KEY (`Engineer_id`) REFERENCES `engineer` (`Engineer_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `installation_bookings_ibfk_4` FOREIGN KEY (`Consult_id`) REFERENCES `consultation_bookings` (`Consult_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
