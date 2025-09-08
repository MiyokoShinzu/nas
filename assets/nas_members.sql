-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 07:54 PM
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
-- Database: `nasdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `nas_members`
--

CREATE TABLE `nas_members` (
  `id` int(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `year` varchar(5) NOT NULL,
  `section` varchar(2) NOT NULL,
  `position` varchar(50) NOT NULL,
  `is_member` int(2) NOT NULL,
  `datetime_registered` datetime NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `suffix` varchar(50) NOT NULL,
  `status` int(2) NOT NULL,
  `access` int(2) NOT NULL,
  `suggestion` text NOT NULL,
  `email` varchar(250) NOT NULL,
  `contact` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nas_members`
--

INSERT INTO `nas_members` (`id`, `firstname`, `lastname`, `year`, `section`, `position`, `is_member`, `datetime_registered`, `middlename`, `suffix`, `status`, `access`, `suggestion`, `email`, `contact`) VALUES
(3, 'Karl Stephen', 'Evallo', '4', 'A', '', 0, '2025-09-08 23:46:24', 'Obispado', '', 0, 0, 'Suggest building the website full.', 'evallokarlstephen@gmail.com', '09485383498'),
(4, 'asd', 'sadsadsa', '1', 'A', '', 0, '2025-09-08 23:47:03', 'asdas', 'dsadsad', 0, 0, 'asdsadsad', 'asdsadsad', 'sadsadsad'),
(5, 'asd', 'asdsa', '3', 'A', '', 0, '2025-09-08 23:48:33', 'adasd', 'asdsad', 0, 0, 'asdasdsa', 'asdasdasd', 'sadsadsad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nas_members`
--
ALTER TABLE `nas_members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nas_members`
--
ALTER TABLE `nas_members`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
