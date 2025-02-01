-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2024 at 04:46 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `placeID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `placeID`, `userID`, `comment`) VALUES
(2, 2, 2, 'A must see!'),
(3, 1, 1, 'Great for hiking!'),
(4, 5, 2, 'Beautiful scenery!'),
(5, 3, 1, 'Incredible experience!');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `country`) VALUES
(1, 'USA'),
(2, 'France'),
(3, 'Ireland'),
(4, 'Japan'),
(5, 'London');

-- --------------------------------------------------------

--
-- Table structure for table `likee`
--

CREATE TABLE `likee` (
  `id` int(11) NOT NULL,
  `placeID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likee`
--

INSERT INTO `likee` (`id`, `placeID`, `userID`) VALUES
(1, 5, 2),
(2, 3, 1),
(3, 2, 2),
(4, 1, 1),
(5, 3, 3),
(7, 2, 1),
(8, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE `place` (
  `id` int(11) NOT NULL,
  `travelID` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
  `location` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `description` text NOT NULL,
  `photoFileName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `place`
--

INSERT INTO `place` (`id`, `travelID`, `name`, `location`, `description`, `photoFileName`) VALUES
(1, 1, 'Grand Canyon', 'Arizona', 'Amazing view of the canyon.', 'GrandCanyon.jpg'),
(2, 2, 'Eiffel Tower', 'Paris', 'Iconic landmark in Paris.', 'EiffelTower.jpg'),
(3, 1, 'Statue of Liberty', 'New York', ' The Statue of Liberty is a 305-foot (93-metre) statue located on Liberty Island in Upper New York Bay, off the coast of New York City.', 'StatueofLiberty1.jpg'),
(5, 4, 'London eye', 'Central London', 'The London Eye, or the Millennium Wheel, is a cantilevered observation wheel on the South Bank of the River Thames in London.', 'Londoneye.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `travel`
--

CREATE TABLE `travel` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `countryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `travel`
--

INSERT INTO `travel` (`id`, `userID`, `month`, `year`, `countryID`) VALUES
(1, 1, 6, 2022, 1),
(2, 2, 7, 2023, 2),
(4, 2, 5, 2024, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `emailAddress` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `photoFileName` varchar(50) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `emailAddress`, `password`, `photoFileName`) VALUES
(1, 'Ghada', 'Abdullah', 'ghaadaaab9@gmail.com', '$2a$12$qO7.kblQerJyblnx6k6AYOC9N.YOQwV3iDPi3LMsAVrczkixQ/VNS', 'female.jpg'),
(2, 'Abdullah', 'Saud', 'Abdullah@gmail.com', '$2a$12$Yw0tzr78RIrIT5UYju6gZOv8em5APsRlHJXe0iTaFVMl0gbh1CrWW', 'boy.jpg'),
(3, 'Khalid', 'Saud', 'Khalid@gmail.com', '$2a$12$e9NBm9U/U7He.1VLgm4e..u6gtnTnZso.erKY6fRsR2Kfr5RmGoMW', 'profilePic.jpg'),
(6, 'Mashael', 'Aljaad', 'mashael@gmail.com', '$2y$10$3wsBFWmePjM/bL8NxZNO3OU543CYixJwJ91Vd/y4ESTHCmdbC7AD6', 'female.png'),
(7, 'Raghad', 'Alzkeri', 'raghad@gmail.com', '$2y$10$z5rtPGvVf7nPoOt6ZR1i8.rZaZhw4AteOXQb/bTEbv7EwdbxJNtXG', 'user_7_profile.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `placeID` (`placeID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likee`
--
ALTER TABLE `likee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `placeID` (`placeID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travelID` (`travelID`);

--
-- Indexes for table `travel`
--
ALTER TABLE `travel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `countryID` (`countryID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `likee`
--
ALTER TABLE `likee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `travel`
--
ALTER TABLE `travel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`placeID`) REFERENCES `place` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);

--
-- Constraints for table `likee`
--
ALTER TABLE `likee`
  ADD CONSTRAINT `likee_ibfk_1` FOREIGN KEY (`placeID`) REFERENCES `place` (`id`),
  ADD CONSTRAINT `likee_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);

--
-- Constraints for table `place`
--
ALTER TABLE `place`
  ADD CONSTRAINT `place_ibfk_1` FOREIGN KEY (`travelID`) REFERENCES `travel` (`id`);

--
-- Constraints for table `travel`
--
ALTER TABLE `travel`
  ADD CONSTRAINT `travel_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `travel_ibfk_2` FOREIGN KEY (`countryID`) REFERENCES `country` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
