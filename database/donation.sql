-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2025 at 04:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `donation`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_tbl`
--

CREATE TABLE `activity_tbl` (
  `adminID` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `date` varchar(250) NOT NULL,
  `action` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_tbl`
--

INSERT INTO `activity_tbl` (`adminID`, `name`, `date`, `action`) VALUES
(1, 'Super Admin', '6/10/2024', 'Edit Information');

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `adminID` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `date` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`adminID`, `name`, `email`, `date`, `password`, `role`) VALUES
(1, 'Super Admin', 'admin@gmail.com', '2024-06-10', '$2y$10$7OvgecJu5u09dsFBQhRpfudd4UKqOt1qxNoZ6VCdAi1j/hlQBbTHy', 'super_admin'),
(2, 'Mochi', 'mochikun@gmail.com', '2025-03-10', '$2y$10$RwsFoqHogNEUGGv1hPC7E.AkBAnbqoUux8CFIcqEMVmAITnra.0LG', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `donationinfo_tbl`
--

CREATE TABLE `donationinfo_tbl` (
  `donateID` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `age` varchar(250) NOT NULL,
  `gender` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donationinfo_tbl`
--

INSERT INTO `donationinfo_tbl` (`donateID`, `name`, `age`, `gender`, `phone`, `email`, `address`) VALUES
(83, 'Jerico', '12', 'Male', '9664709009', '123', '1232');

-- --------------------------------------------------------

--
-- Table structure for table `donation_tbl`
--

CREATE TABLE `donation_tbl` (
  `donationID` int(11) NOT NULL,
  `donorName` varchar(250) NOT NULL,
  `corporate` varchar(250) NOT NULL,
  `date` varchar(250) NOT NULL,
  `typeDonation` varchar(250) NOT NULL,
  `typePayment` varchar(250) NOT NULL,
  `photo` varchar(250) NOT NULL,
  `photos` varchar(250) NOT NULL,
  `cash` varchar(250) NOT NULL,
  `typeDelivery` varchar(250) NOT NULL,
  `typeInkind` varchar(250) NOT NULL,
  `kindFood` varchar(250) NOT NULL,
  `howFood` varchar(250) NOT NULL,
  `yearExpire` varchar(250) NOT NULL,
  `typeClothes` varchar(250) NOT NULL,
  `howClothes` varchar(250) NOT NULL,
  `sizeClothes` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_tbl`
--

INSERT INTO `donation_tbl` (`donationID`, `donorName`, `corporate`, `date`, `typeDonation`, `typePayment`, `photo`, `photos`, `cash`, `typeDelivery`, `typeInkind`, `kindFood`, `howFood`, `yearExpire`, `typeClothes`, `howClothes`, `sizeClothes`) VALUES
(93, 'Maloi', 'Bini', '2024-07-31', 'Cash Donation', 'Gcash', 'uploads_admin/6df3e921d34bd1e32013d0e0d3282c73.png', '', '2', '', '', '', '', '', '', '', ''),
(97, 'Jerico', 'N/A', '2025-03-10', 'Cash Donation', 'Cash', '', '', '12', '', '', '', '', '', '', '', ''),
(100, 'Maloi', 'Bini', '2025-03-10', 'In-Kind Donation', '', '', '', '', '', 'Food', 'Canned Foods', '100', '2026', '', '', ''),
(101, 'Maloi', 'Bini', '2025-03-10', 'In-Kind Donation', '', '', '', '', '', 'Clothes', '', '', '', 'Sweaters', '100', 'Small to Large');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_tbl`
--

CREATE TABLE `expenses_tbl` (
  `expID` int(11) NOT NULL,
  `description` varchar(250) NOT NULL,
  `cash` varchar(250) NOT NULL,
  `date` varchar(250) NOT NULL,
  `photo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses_tbl`
--

INSERT INTO `expenses_tbl` (`expID`, `description`, `cash`, `date`, `photo`) VALUES
(39, 'asd', '50', '2024-06-11', 'e9891e76-d65a-4d7f-811f-4abf05add3d2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD UNIQUE KEY `adminID` (`adminID`);

--
-- Indexes for table `donationinfo_tbl`
--
ALTER TABLE `donationinfo_tbl`
  ADD UNIQUE KEY `donateID` (`donateID`);

--
-- Indexes for table `donation_tbl`
--
ALTER TABLE `donation_tbl`
  ADD UNIQUE KEY `donationID` (`donationID`);

--
-- Indexes for table `expenses_tbl`
--
ALTER TABLE `expenses_tbl`
  ADD UNIQUE KEY `expID` (`expID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donationinfo_tbl`
--
ALTER TABLE `donationinfo_tbl`
  MODIFY `donateID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `donation_tbl`
--
ALTER TABLE `donation_tbl`
  MODIFY `donationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `expenses_tbl`
--
ALTER TABLE `expenses_tbl`
  MODIFY `expID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
