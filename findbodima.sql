-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2024 at 01:49 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `findbodima`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_advertisement`
--

CREATE TABLE `tbl_advertisement` (
  `AdvertisementID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `PropertyID` int(11) DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `OrderID` int(11) NOT NULL,
  `DateTime` datetime DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `PropertyID` int(11) DEFAULT NULL,
  `spaceReq` int(11) NOT NULL,
  `IsDeleted` tinyint(1) DEFAULT 0,
  `Stts` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`OrderID`, `DateTime`, `UserID`, `PropertyID`, `spaceReq`, `IsDeleted`, `Stts`) VALUES
(274, '2024-05-15 19:31:10', 91682589, 1, 0, 0, 'pending'),
(291, '2024-05-15 19:34:55', 91682589, 1, 0, 0, 'pending'),
(447, '2024-05-15 19:31:10', 91682589, 1, 0, 0, 'pending'),
(486, '2024-05-15 19:29:59', 91682589, 1, 0, 0, 'pending'),
(630, '2024-05-15 19:29:59', 91682589, 1, 0, 0, 'pending'),
(667, '2024-05-15 19:34:15', 91682589, 1, 0, 0, 'pending'),
(744, '2024-05-15 19:34:55', 91682589, 1, 0, 0, 'pending'),
(802, '2024-05-15 19:34:15', 91682589, 1, 0, 0, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `PaymentID` int(11) NOT NULL,
  `DateTime` datetime DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`PaymentID`, `DateTime`, `OrderID`, `UserID`, `Amount`) VALUES
(23, '2024-05-15 19:31:10', 274, 91682589, 10000),
(24, '2024-05-15 19:34:15', 667, 91682589, 10000),
(25, '2024-05-15 19:34:15', 802, 91682589, 10000),
(26, '2024-05-15 19:34:55', 291, 91682589, 10000),
(27, '2024-05-15 19:34:55', 744, 91682589, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property`
--

CREATE TABLE `tbl_property` (
  `PropertyID` int(11) NOT NULL,
  `UserID` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`UserID`)),
  `IsDeleted` tinyint(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) NOT NULL,
  `propertyLocation` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `totalSpace` int(11) NOT NULL,
  `availableSpace` int(11) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `AdvertiserID` int(11) DEFAULT NULL,
  `DateTime` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_property`
--

INSERT INTO `tbl_property` (`PropertyID`, `UserID`, `IsDeleted`, `image`, `image2`, `image3`, `image4`, `propertyLocation`, `price`, `type`, `totalSpace`, `availableSpace`, `Description`, `AdvertiserID`, `DateTime`) VALUES
(1, '[1, 3]', 0, 'i1.jpeg', 'image2.jpg', 'image3.jpg', '', 'New York City', 10000, 'Apartment', 4, 2, 'Spacious apartment with great amenities', 3, '2024-05-05 15:08:12.025606'),
(2, '[2, 4]', 0, 'i2.jpeg', 'image5.jpg', 'image6.jpg', '', 'Los Angeles', 1500, 'House', 5, 3, 'Cozy house with backyard', 3, '2024-04-02 08:52:56.078675'),
(3, '[3, 6, 7]', 0, 'i3.jpeg', 'image8.jpg', 'image9.jpg', '', 'Chicago', 800, 'Condo', 4, 1, 'Modern condo in downtown area', 6, '2024-04-02 08:52:31.940260'),
(4, '[4, 8]', 0, 'image10.jpg', 'image11.jpg', 'image12.jpg', '', 'Houston', 1200, 'Apartment', 4, 2, 'Luxurious apartment with city view', 6, '2024-04-02 08:52:40.589925'),
(5, '[5, 9]', 0, 'image13.jpg', 'image14.jpg', 'image15.jpg', '', 'Phoenix', 900, 'House', 4, 2, 'Family-friendly house in a quiet neighborhood', 10, '2024-04-02 08:52:51.517587'),
(6, '[6, 10, 11]', 0, 'image16.jpg', 'image17.jpg', 'image18.jpg', '', 'San Francisco', 1800, 'Apartment', 6, 3, 'High-rise apartment with stunning views', 10, '2024-04-02 08:52:46.326958'),
(7, '[7, 12]', 0, 'image19.jpg', 'image20.jpg', 'image21.jpg', '', 'Dallas', 1000, 'Condo', 3, 1, 'Cozy condo near downtown', 10, '2024-04-02 08:53:55.088892'),
(8, '[]', 0, 'image22.jpg', 'image23.jpg', 'image24.jpg', '', 'Miami', 1600, 'House', 3, 3, 'Spacious house with swimming pool', 10, '2024-04-02 08:52:10.771725'),
(9, '[]', 0, 'image25.jpg', 'image26.jpg', 'image27.jpg', '', 'Seattle', 1100, 'Apartment', 2, 2, 'Modern apartment in a vibrant neighborhood', 10, '2024-04-02 08:52:36.466536'),
(10, '[10, 15]', 0, 'image28.jpg', 'image29.jpg', 'image30.jpg', '', 'Atlanta', 1300, 'House', 4, 2, 'Charming house with garden', 16, '2024-04-01 09:06:49.709950'),
(12, '[12, 17]', 0, 'image34.jpg', 'image35.jpg', 'image36.jpg', '', 'Philadelphia', 1400, 'Apartment', 4, 2, 'Newly renovated apartment in historic area', 20, '2024-04-02 08:52:25.399406'),
(22, '[5,3,9]', 0, 'i1.jpeg', 'i2.jpeg', 'i3.jpeg', 'i3.jpeg', 'gampha', 250000, 'apartment', 6, 4, 'house with an excellent architecture, friendly neighbour hood, gym, mall, schools near by', 91682589, '2024-04-03 07:37:43.356973'),
(128928, NULL, 0, 'OIP (2).jpeg', '', '', '', 'jaffna', 12333, 'fgbrths', 3, 2, 'feetgueguitghitgpfnvfdghvhhgefvgtgtg', 91682589, '2024-05-09 05:29:18.000000'),
(813904, '[1,2]', 0, 'OIP (3).jpeg', '', '', '', 'colombo', 368922111, 'stryhg', 11, 1, 'rfgthtryjytrgsdhrthoigpffdbferhvrufuefy', 91682589, '2024-05-09 05:28:44.511834');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_propertyrating`
--

CREATE TABLE `tbl_propertyrating` (
  `id` int(11) NOT NULL,
  `PropertyID` int(11) NOT NULL,
  `Rating` int(11) NOT NULL,
  `IsDeleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_propertyrating`
--

INSERT INTO `tbl_propertyrating` (`id`, `PropertyID`, `Rating`, `IsDeleted`) VALUES
(5, 1, 4, 0),
(6, 1, 5, 0),
(7, 1, 4, 0),
(8, 1, 4, 0),
(9, 2, 5, 0),
(10, 1, 5, 0),
(11, 2, 4, 0),
(12, 2, 2, 0),
(13, 2, 5, 0),
(14, 22, 5, 0),
(15, 2, 3, 0),
(17, 128928, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_third_party_advertisement`
--

CREATE TABLE `tbl_third_party_advertisement` (
  `ThirdPartyAdvertisementID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT 0,
  `Description` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `showTime` int(11) NOT NULL,
  `Link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_third_party_advertisement`
--

INSERT INTO `tbl_third_party_advertisement` (`ThirdPartyAdvertisementID`, `UserID`, `IsDeleted`, `Description`, `Image`, `showTime`, `Link`) VALUES
(8, 91682589, 0, 'codeinfinity', 'logo.png', 5, 'codeinfinity.lk');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `UserID` int(11) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT 0,
  `IsActive` tinyint(1) DEFAULT 1,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Gender` varchar(50) DEFAULT NULL,
  `HomeCity` varchar(50) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(5000) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `UserType` int(1) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`UserID`, `Age`, `IsDeleted`, `IsActive`, `FirstName`, `LastName`, `Gender`, `HomeCity`, `Username`, `Password`, `Email`, `UserType`) VALUES
(1, 25, 0, 1, 'John', 'Doe', 'Male', 'New York', 'johndoe123', 'password123', 'johndoe@example.com', 3),
(2, 30, 0, 1, 'Jane', 'Smith', 'Female', 'Los Angeles', 'janesmith456', 'pass456', 'janesmith@example.com', 3),
(3, 28, 0, 1, 'Michael', 'Johnsonnne', 'Male', 'Chicago', 'mikej88', 'securepass', 'mike.johnson@example.com', 2),
(4, 35, 0, 1, 'Emily', 'Williams', 'Female', 'Houston', 'emilyw123', 'p@ssword789', 'emily.williams@example.com', 3),
(5, 22, 0, 1, 'David', 'Brown', 'Male', 'Phoenix', 'dbrown22', 'abc123', 'david.brown@example.com', 3),
(6, 31, 0, 1, 'Sarah', 'Miller', 'Female', 'San Francisco', 'smiller31', 'password123', 'sarah.miller@example.com', 2),
(7, 29, 0, 1, 'James', 'Jones', 'Male', 'Dallas', 'jjones29', 'jamespass', 'james.jones@example.com', 3),
(8, 27, 0, 1, 'Jessica', 'Davis', 'Female', 'Miami', 'jdavis27', 'secure456', 'jessica.davis@example.com', 3),
(10, 26, 0, 1, 'Megan', 'Garcia', 'Female', 'Atlanta', 'mgarcia26', 'pass789', 'megan.garcia@example.com', 2),
(11, 40, 0, 1, 'Christopher', 'Rodriguez', 'Male', 'Denver', 'crodriguez40', 'pass1234', 'chris.rodriguez@example.com', 3),
(12, 24, 0, 1, 'Amanda', 'Hernandez', 'Female', 'Philadelphia', 'ahernandez24', 'pass4567', 'amanda.hernandez@example.com', 3),
(13, 32, 0, 1, 'Matthew', 'Lopez', 'Male', 'Boston', 'mlopez32', 'password1', 'matthew.lopez@example.com', 2),
(14, 28, 0, 1, 'Ashley', 'Gonzalez', 'Female', 'Las Vegas', 'agonzalez28', 'pass890', 'ashley.gonzalez@example.com', 3),
(15, 29, 0, 1, 'Kevin', 'Wilson', 'Male', 'Orlando', 'kwilson29', 'securepass123', 'kevin.wilson@example.com', 3),
(16, 35, 0, 1, 'Jennifer', 'Perez', 'Female', 'Portland', 'jperez35', 'mypassword', 'jennifer.perez@example.com', 2),
(17, 26, 0, 1, 'Brandon', 'Sanchez', 'Male', 'San Diego', 'bsanchez26', '12345pass', 'brandon.sanchez@example.com', 3),
(18, 27, 0, 1, 'Nicole', 'Ramirez', 'Female', 'Austin', 'nramirez27', 'pass2022', 'nicole.ramirez@example.com', 3),
(19, 34, 0, 1, 'Justin', 'Torres', 'Male', 'Nashville', 'jtorres34', 'password!', 'justin.torres@example.com', 3),
(20, 30, 0, 1, 'Lauren', 'Flores', 'Female', 'Minneapolis', 'lflores30', 'securepass2022', 'lauren.flores@example.com', 2),
(85040138, 23, 0, 1, 'Navaratnaraja', 'Hariharan', 'male', 'Colombo', 'asdfgh12345', 'sdgh12345', 'haritheguy21@gmail.com', 2),
(91682589, 30, 0, 1, 'test', 'test', 'male', 'test', 'test', '$2y$10$jew2XOhrgMWZIpyW8r2Hke3H9JMIoEX6xRWOYulHmjGD5hT5ogY6a', 'test@gmail.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_advertisement`
--
ALTER TABLE `tbl_advertisement`
  ADD PRIMARY KEY (`AdvertisementID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PropertyID` (`PropertyID`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PropertyID` (`PropertyID`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tbl_property`
--
ALTER TABLE `tbl_property`
  ADD PRIMARY KEY (`PropertyID`),
  ADD KEY `UserID` (`UserID`(768)),
  ADD KEY `AdvertiserID` (`AdvertiserID`);

--
-- Indexes for table `tbl_propertyrating`
--
ALTER TABLE `tbl_propertyrating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_third_party_advertisement`
--
ALTER TABLE `tbl_third_party_advertisement`
  ADD PRIMARY KEY (`ThirdPartyAdvertisementID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_propertyrating`
--
ALTER TABLE `tbl_propertyrating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_third_party_advertisement`
--
ALTER TABLE `tbl_third_party_advertisement`
  MODIFY `ThirdPartyAdvertisementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_advertisement`
--
ALTER TABLE `tbl_advertisement`
  ADD CONSTRAINT `tbl_advertisement_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbl_user` (`UserID`),
  ADD CONSTRAINT `tbl_advertisement_ibfk_2` FOREIGN KEY (`PropertyID`) REFERENCES `tbl_property` (`PropertyID`);

--
-- Constraints for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD CONSTRAINT `tbl_payment_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `tbl_order` (`OrderID`),
  ADD CONSTRAINT `tbl_payment_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `tbl_user` (`UserID`);

--
-- Constraints for table `tbl_property`
--
ALTER TABLE `tbl_property`
  ADD CONSTRAINT `tbl_property_ibfk_2` FOREIGN KEY (`AdvertiserID`) REFERENCES `tbl_user` (`UserID`);

--
-- Constraints for table `tbl_third_party_advertisement`
--
ALTER TABLE `tbl_third_party_advertisement`
  ADD CONSTRAINT `tbl_third_party_advertisement_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbl_user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
