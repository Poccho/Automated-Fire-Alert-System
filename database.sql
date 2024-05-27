-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2024 at 06:46 PM
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
-- Database: `4402151_alert`
--

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE `alert` (
  `alert_id` int(11) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `alert_time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `barangay_code` int(255) NOT NULL,
  `alert_status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alert`
--

INSERT INTO `alert` (`alert_id`, `latitude`, `longitude`, `label`, `alert_time`, `barangay_code`, `alert_status`) VALUES
(61, '6.12437155', '125.19642775282315', 'NDDU', '2024-05-27 16:17:28.978502', 10, 0),
(62, '6.1169509', '125.17154325059084', 'MSU HIGHSCHOOL', '2024-05-04 03:29:16.791928', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `barangay_code` int(255) NOT NULL,
  `barangay_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`barangay_code`, `barangay_name`) VALUES
(1, 'Apopong'),
(2, 'Baluan'),
(3, 'Batomelong'),
(4, 'Buayan'),
(5, 'Bula'),
(6, 'Calumpang'),
(7, 'City Heights'),
(8, 'Conel'),
(9, 'Dadiangas East'),
(10, 'Dadiangas North'),
(11, 'Dadiangas South'),
(12, 'Dadiangas West'),
(13, 'Fatima'),
(14, 'Katangawan'),
(15, 'Labangal'),
(16, 'Lagao'),
(17, 'Ligaya'),
(18, 'Mabuhay'),
(19, 'Olympog'),
(20, 'San Isidro'),
(21, 'San Jose'),
(22, 'Siguel'),
(23, 'Sinawal'),
(24, 'Tambler'),
(25, 'Tinagacan'),
(26, 'Upper Labay');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `incident_no` int(255) NOT NULL,
  `barangay_no` int(255) NOT NULL,
  `date` date NOT NULL,
  `alarm_time` datetime(6) NOT NULL,
  `building_address` varchar(255) NOT NULL,
  `type_of_incident` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`incident_no`, `barangay_no`, `date`, `alarm_time`, `building_address`, `type_of_incident`) VALUES
(21, 1, '2024-05-22', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'qweqweq'),
(22, 1, '2003-11-23', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'qweqq'),
(23, 1, '0000-00-00', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'qweqweq'),
(24, 1, '2024-05-26', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'daob'),
(25, 1, '2001-11-11', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'qweqwe'),
(26, 1, '2024-05-06', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'qwe'),
(27, 1, '2024-05-26', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'sdfs'),
(28, 9, '2024-05-26', '2024-05-04 11:31:29.000000', 'SM GENSAN', 'qweqwe'),
(29, 1, '2024-05-22', '2024-05-04 00:00:00.000000', 'Bairoy Household', 'qweqweq');

-- --------------------------------------------------------

--
-- Table structure for table `section1`
--

CREATE TABLE `section1` (
  `incident_no` int(255) NOT NULL,
  `coordinates` varchar(255) NOT NULL,
  `occupants_were` varchar(255) NOT NULL,
  `brief_history` varchar(255) NOT NULL,
  `num_injuries` int(255) NOT NULL,
  `num_deaths` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section1`
--

INSERT INTO `section1` (`incident_no`, `coordinates`, `occupants_were`, `brief_history`, `num_injuries`, `num_deaths`) VALUES
(21, '6.130086,125.132344', 'evacuated', 'qweqwe', 0, 0),
(22, '6.130086,125.132344', 'evacuated', 'qweqw', 0, 0),
(23, '6.130086,125.132344', 'not_evacuated', 'qweqwe', 0, 0),
(24, '6.130086,125.132344', 'relocated', 'nag daob sila', 0, 0),
(25, '6.130086,125.132344', 'not_evacuated', 'qwe', 0, 0),
(26, '6.130086,125.132344', 'evacuated', 'qwe', 0, 0),
(27, '6.130086,125.132344', 'relocated', 'sdf', 0, 0),
(28, '6.116097,125.181139', 'not_evacuated', 'qweqwe', 0, 0),
(29, '6.130086,125.132344', 'evacuated', 'qweqwe', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `section2`
--

CREATE TABLE `section2` (
  `incident_no` int(255) NOT NULL,
  `fire_origin_area` varchar(255) NOT NULL,
  `ignition_equipment` varchar(255) NOT NULL,
  `ignition_heat_form` varchar(255) NOT NULL,
  `ignited_material_type` varchar(255) NOT NULL,
  `ignited_material_form` varchar(255) NOT NULL,
  `extinguishment_method` varchar(255) NOT NULL,
  `fire_origin_level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section2`
--

INSERT INTO `section2` (`incident_no`, `fire_origin_area`, `ignition_equipment`, `ignition_heat_form`, `ignited_material_type`, `ignited_material_form`, `extinguishment_method`, `fire_origin_level`) VALUES
(21, 'qweq', 'we', 'qweq', 'qw', 'qweq', 'qweqw', 'qweq'),
(22, 'qweqw', 'weqwe', 'qweqw', 'eqweq', 'eqweq', 'qweq', 'qweqwe'),
(23, 'qweqw', 'qweqwe', 'eqweq', 'qwe', 'weqw', 'qweqwe', 'eqweq'),
(24, 'asd', 'asd', 'asd', 'asd', 'asd', 'asd', 'asdasd'),
(25, 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe'),
(26, 'qwe', 'qwe', 'qwe', 'qw', 'qwe', 'qwe', 'qwe'),
(27, 'sdf', 'sdf', 'sdf', 'sdf', 'sdf', 'sdf', 'sdf'),
(28, 'qweqwe', 'we', 'qweqwe', 'qwe', 'qwe', 'qweq', 'qwe'),
(29, 'qweq', 'we', 'qweq', 'qw', 'qweq', 'qweqw', 'qweq');

-- --------------------------------------------------------

--
-- Table structure for table `section3`
--

CREATE TABLE `section3` (
  `incident_no` int(255) NOT NULL,
  `flame_damage_extent` varchar(255) NOT NULL,
  `smoke_damage_extent` varchar(255) NOT NULL,
  `detector_performance` varchar(255) NOT NULL,
  `sprinkler_performance` varchar(255) NOT NULL,
  `most_smoke_material_type` varchar(255) NOT NULL,
  `smoke_travel_avenue` varchar(255) NOT NULL,
  `origin_room` varchar(255) NOT NULL,
  `most_smoke_material_form` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section3`
--

INSERT INTO `section3` (`incident_no`, `flame_damage_extent`, `smoke_damage_extent`, `detector_performance`, `sprinkler_performance`, `most_smoke_material_type`, `smoke_travel_avenue`, `origin_room`, `most_smoke_material_form`) VALUES
(21, 'qweqe', 'qweqwe', 'qweq', 'qweqwe', 'qweqwe', 'qweqwe', 'qweq', 'qweqw'),
(22, 'qweqw', 'we', 'eqweq', 'qweq', 'weqwe', 'qwe', 'qweqwe', 'qweq'),
(23, 'qweqwe', 'eqwe', 'qwe', 'weqwe', 'qwqweqw', 'eqweq', 'eqweqwe', 'qweqw'),
(24, 'asd', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd'),
(25, 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe'),
(26, 'qwe', 'qw', 'qwe', 'eqwe', 'qwe', 'qwe', 'qwe', 'qwe'),
(27, 'sdf', 'sdf', 'sdf', 'sdf', 'sdf', 'sdf', 'sdf', 'sdf'),
(28, 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe'),
(29, 'qweqe', 'qweqwe', 'qweq', 'qweqwe', 'qweqwe', 'qweqwe', 'qweq', 'qweqw');

-- --------------------------------------------------------

--
-- Table structure for table `section4`
--

CREATE TABLE `section4` (
  `incident_no` int(255) NOT NULL,
  `reporters_name` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section4`
--

INSERT INTO `section4` (`incident_no`, `reporters_name`, `date`) VALUES
(21, 'pocho', '2024-05-22'),
(22, 'suisei', '2003-11-23'),
(23, 'okayu', '0000-00-00'),
(24, 'watambe', '2024-05-26'),
(25, 'matsuri', '2024-05-26'),
(26, 'korone', '2024-05-26'),
(27, 'chloe', '2024-05-26'),
(28, 'yay', '2024-05-26'),
(29, 'pocho', '2024-05-26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `station_location` varchar(255) NOT NULL,
  `barangay_code` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type`, `username`, `email`, `password`, `station_location`, `barangay_code`) VALUES
(5, 'user', 'apopong', 'apopong@gmail.com', '$2y$10$/9ZEWC2vF2c1sza/apAkeeykUt4hlyDrA5FTpfRSN6qe2YmVcScsi', '6.1313112,125.130319', 1),
(7, 'admin', 'pocho', 'rogeranthony1127@gmail.com', '$2y$10$ughiKSZT6vgDCx5peHanl.RWyP87pEYFQbb/kypNnv7W2iWjwi6wC', 'null', 1),
(12, 'user', 'fatima', 'sample@gmail.com', '$2y$10$xelruRIELy9nPCZxriBdju6gNfMgoNBtySRQMzY94e3xHEv8pU0H.', '6.073838, 125.115167', 13),
(17, 'user', '1234', 'rogerantony@gmail.om', '$2y$10$6HmoOKKnXkeWhFiWZ1UZ0.IhgvaNog3bqdScSLzpOI.dDFpOnL1xC', '6.113376, 125.170986', 12),
(18, 'user', '1233', 'user@gmail.com', '$2y$10$zL1eCw49swVR97fJE5EAT.R0u9uYZ4Z4wEQfDYSWM/YRAgLHs6MBK', '6.112570, 125.170466', 9),
(19, 'user', 'gsccentralfirestation', 'bfp@gmail.com', '$2y$10$zZ82NdAr3hcieCBzGRASR.IINbRM5emm6dB8OMvn74W.ncZMPi77m', '6.114540, 125.170637', 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`alert_id`);

--
-- Indexes for table `barangay`
--
ALTER TABLE `barangay`
  ADD PRIMARY KEY (`barangay_code`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`incident_no`);

--
-- Indexes for table `section1`
--
ALTER TABLE `section1`
  ADD PRIMARY KEY (`incident_no`);

--
-- Indexes for table `section2`
--
ALTER TABLE `section2`
  ADD PRIMARY KEY (`incident_no`);

--
-- Indexes for table `section3`
--
ALTER TABLE `section3`
  ADD PRIMARY KEY (`incident_no`);

--
-- Indexes for table `section4`
--
ALTER TABLE `section4`
  ADD PRIMARY KEY (`incident_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alert`
--
ALTER TABLE `alert`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `barangay_code` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `incident_no` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `section1`
--
ALTER TABLE `section1`
  ADD CONSTRAINT `section1_ibfk_1` FOREIGN KEY (`incident_no`) REFERENCES `report` (`incident_no`) ON UPDATE CASCADE;

--
-- Constraints for table `section2`
--
ALTER TABLE `section2`
  ADD CONSTRAINT `section2_ibfk_1` FOREIGN KEY (`incident_no`) REFERENCES `report` (`incident_no`) ON UPDATE CASCADE;

--
-- Constraints for table `section3`
--
ALTER TABLE `section3`
  ADD CONSTRAINT `section3_ibfk_1` FOREIGN KEY (`incident_no`) REFERENCES `report` (`incident_no`) ON UPDATE CASCADE;

--
-- Constraints for table `section4`
--
ALTER TABLE `section4`
  ADD CONSTRAINT `section4_ibfk_1` FOREIGN KEY (`incident_no`) REFERENCES `report` (`incident_no`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
