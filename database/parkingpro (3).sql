-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 29, 2026 at 04:38 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parkingpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `slot_id` int(11) DEFAULT NULL,
  `vehicle_type_id` int(11) DEFAULT NULL,
  `license_plate` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `slot_id`, `vehicle_type_id`, `license_plate`, `start_time`, `end_time`, `booking_date`) VALUES
(1, 3, 1, 1, 'qDSQWDQ', '2026-01-29 10:22:00', '2026-01-29 10:28:00', '2026-01-29 03:22:50'),
(2, 3, 3, 1, 'qDSQWDQ', '2026-01-29 10:22:00', '2026-01-29 10:28:00', '2026-01-29 03:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(11) NOT NULL,
  `slot_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `vehicle_type_id` int(11) NOT NULL,
  `status` enum('available','booked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `slot_number`, `zone_id`, `vehicle_type_id`, `status`) VALUES
(7, 'A', 1, 1, 'available'),
(8, 'B', 1, 1, 'available'),
(9, 'A', 1, 2, 'available'),
(10, 'B1', 1, 2, 'available'),
(11, 'B1', 1, 3, 'available'),
(12, 'A1', 1, 3, 'available'),
(13, 'A1', 2, 1, 'available'),
(14, 'A2', 2, 1, 'available'),
(15, 'A3', 2, 1, 'available'),
(16, 'A4', 2, 1, 'available'),
(17, 'A5', 2, 1, 'available'),
(18, 'A6', 2, 1, 'available'),
(19, 'A7', 2, 1, 'available'),
(20, 'A8', 2, 1, 'available'),
(21, 'A9', 2, 1, 'available'),
(22, 'A10', 2, 1, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `topup_codes`
--

CREATE TABLE `topup_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topup_codes`
--

INSERT INTO `topup_codes` (`id`, `code`, `amount`, `is_used`) VALUES
(3, 'kuy', '99999999.99', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recovery_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `recovery_code`, `balance`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@pro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '111111', '9999.00', 'admin', '2026-01-27 08:57:53'),
(2, 'Rutad9', 'adminkuyyai@gmail.com', '$2y$10$s10.69lkHwyRTlE/YbBcbubZmYspY6yVD3rNwQ1JwhjtEez4DiA6K', '166919', '0.00', 'admin', '2026-01-27 09:04:16'),
(3, 'Rutad', 'rutad@gmail.com', '$2y$10$1CjLId9kUAxjOzN/2PQgAeA0ua7H8.rRMB/I1pt7nn50OJOlAxMFC', '166919', '99999899.99', 'admin', '2026-01-29 03:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `name`) VALUES
(1, 'รถยนต์'),
(2, 'รถมอไซค์'),
(3, 'Supercar');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('normal','vip') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `price` int(11) NOT NULL DEFAULT '50'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `type`, `price`) VALUES
(1, 'VIP Purple (VIP)', 'vip', 100),
(2, 'Normal Zone', 'normal', 50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_slots_vehicle` (`vehicle_type_id`);

--
-- Indexes for table `topup_codes`
--
ALTER TABLE `topup_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `topup_codes`
--
ALTER TABLE `topup_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `slots`
--
ALTER TABLE `slots`
  ADD CONSTRAINT `fk_slots_vehicle` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
