-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 10:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ever_trade`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_achievements`
--

CREATE TABLE `tbl_achievements` (
  `id` int(11) NOT NULL,
  `count` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_achievements`
--

INSERT INTO `tbl_achievements` (`id`, `count`, `description`, `color`) VALUES
(1, 10000, 'Happy Customers', 'text-primary'),
(2, 500, 'Products Available', 'text-danger'),
(3, 50, 'Industry Awards', 'text-success'),
(4, 20, 'Years of Experience', 'text-warning');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_carousel`
--

CREATE TABLE `tbl_carousel` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_carousel`
--

INSERT INTO `tbl_carousel` (`id`, `image_url`, `alt_text`, `display_order`) VALUES
(1, 'assets/img/t1.png', 'assets/img/t1.png', 1),
(2, 'assets/img/t2.png', 'Hardware 2', 2),
(3, 'assets/img/t3.png', 'Hardware 3', 3),
(4, 'assets/img/t4.png', 'Hardware 4', 4),
(5, 'assets/img/t5.png', 'Hardware 5', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `name`) VALUES
(3, 'Electrical'),
(2, 'Hand Tools'),
(1, 'Power Tools'),
(4, 'svvab');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hardware`
--

CREATE TABLE `tbl_hardware` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `page_slug` varchar(255) NOT NULL,
  `use_cases` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_hardware`
--

INSERT INTO `tbl_hardware` (`id`, `name`, `images`, `description`, `price`, `stock`, `category_id`, `page_slug`, `use_cases`) VALUES
(1, 'Drill Machine', '[\"p1.png\", \"p1_2.png\"]', 'High precision and power for all drilling needs. \nPerfect for professional and DIY projects.\nErgonomic design for comfortable handling. ', 120.00, 10, 1, 'drill-machine', 'adghfjhkgjkjlkjvcfgvmhb,jn.,hbmhvgn\nHigh precision and power for all drilling needs. \nPerfect for professional and DIY projects.\nErgonomic design for comfortable handling'),
(2, 'Circular Saw', '[\"p2.png\", \"p2_2.png\"]', 'High-speed circular saw.', 150.00, 0, 1, 'circular-saw', NULL),
(3, 'Wrench Set', '[\"p3.png\", \"p3_2.png\"]', 'Set of adjustable wrenches.', 50.00, 15, 2, 'wrench-set', NULL),
(4, 'Hammer', '[\"p4.png\", \"p4_2.png\"]', 'Heavy-duty steel hammer.', 20.00, 30, 2, 'hammer', NULL),
(5, 'Electric Plug', '[\"p5.png\", \"p5_2.png\"]', 'Universal electric plug.', 5.00, 100, 3, 'electric-plug', NULL),
(6, 'Light Bulb', '[\"p6.png\", \"p6_2.png\"]', 'LED energy-saving bulb.', 8.00, 50, 3, 'light-bulb', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hero`
--

CREATE TABLE `tbl_hero` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `button_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_hero`
--

INSERT INTO `tbl_hero` (`id`, `title`, `subtitle`, `description`, `button_text`) VALUES
(1, 'Quality Hardware Imports', 'Your Trusted Hardware Supplier', 'Explore our extensive range of top-notch hardware items, from essential tools to high-quality machinery. Importing made seamless with our reliable services.', 'Discover Products');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_icons`
--

CREATE TABLE `tbl_icons` (
  `id` int(11) NOT NULL,
  `icon_name` varchar(100) DEFAULT NULL,
  `icon_color` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_icons`
--

INSERT INTO `tbl_icons` (`id`, `icon_name`, `icon_color`, `title`, `description`) VALUES
(1, 'fa-hammer', 'text-danger', 'General Hardware', 'We offer a wide range of general hardware products that meet industry standards.'),
(2, 'fa-tools', 'text-primary', 'Hardware Merchants', 'We are a trusted supplier of top-quality hardware products for various industries.'),
(3, 'fa-award', 'text-success', 'Quality Products', 'Committed to delivering the best quality products with innovative solutions.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leadership`
--

CREATE TABLE `tbl_leadership` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `quote` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_leadership`
--

INSERT INTO `tbl_leadership` (`id`, `name`, `position`, `image_url`, `quote`) VALUES
(1, 'John Doe', 'CEO', 'assets/img/placeholder.jpg', 'Leadership is not about titles, it\'s about impact.'),
(2, 'Jane Smith', 'CTO', 'assets/img/placeholder.jpg', 'Innovation is the heart of technology.'),
(3, 'Mark Johnson', 'Vice CEO', 'assets/img/placeholder.jpg', 'Success comes from teamwork and dedication.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mission_vision`
--

CREATE TABLE `tbl_mission_vision` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon_name` varchar(15) NOT NULL,
  `icon_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_mission_vision`
--

INSERT INTO `tbl_mission_vision` (`id`, `heading`, `description`, `icon_name`, `icon_color`) VALUES
(1, 'Our Mission', 'To provide top-notch hardware solutions that cater to both individual and industrial needs, ensuring quality, reliability, and affordability in every product.', 'fa-bullhorn', 'text-primary'),
(2, 'Our Vision', 'To become the most trusted and innovative hardware provider globally, continuously improving and expanding our product line to meet the evolving market demands.', 'fa-eye', 'text-success');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navbar`
--

CREATE TABLE `tbl_navbar` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `page_slug` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_navbar`
--

INSERT INTO `tbl_navbar` (`id`, `title`, `page_slug`, `display_order`) VALUES
(1, 'Home', 'home.php', 1),
(2, 'Products', 'products.php', 2),
(3, 'About Us', 'about.php', 3),
(4, 'Contact Us', 'contact.php', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_owner`
--

CREATE TABLE `tbl_owner` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `location` text NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `office_time` varchar(255) DEFAULT NULL,
  `google_map_link` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_owner`
--

INSERT INTO `tbl_owner` (`id`, `name`, `logo`, `contact`, `email`, `location`, `facebook`, `twitter`, `instagram`, `linkedin`, `whatsapp`, `office_time`, `google_map_link`) VALUES
(1, 'Ever Trade International', 'assets/img/evlogo.png', '+94 771234567', 'info@evertrade.com', 'Colombo, Sri Lanka', 'https://facebook.com/evertrade', 'https://twitter.com/evertrade', 'https://instagram.com/evertrade', 'https://linkedin.com/company/evertrade', '+94771234567', 'Mon - Fri: 9:00 AM - 5:00 PM', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.81597678079!2d79.77826335115893!3d6.921832307550433!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1740459686840!5m2!1sen!2slk');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pages`
--

CREATE TABLE `tbl_pages` (
  `id` int(11) NOT NULL,
  `pages` varchar(500) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `img_url` varchar(255) NOT NULL,
  `alt_text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `pages`, `heading`, `description`, `img_url`, `alt_text`) VALUES
(1, 'about_us', 'ABOUT US', 'We are a leading company in the hardware industry, committed to providing top-quality products and services. With a focus on durability, innovation, and cost-effectiveness, we offer a wide range of tools, equipment, and building materials for professionals and DIY enthusiasts.', 'assets/img/rods-city.png', 'rod-city'),
(2, 'contact_us', 'CONTACT US', 'We are a leading company in the hardware industry, committed to providing top-quality products and services. With a focus on durability, innovation, and cost-effectiveness, we offer a wide range of tools, equipment, and building materials for professionals and DIY enthusiasts.', 'assets/img/contact.png', 'rod-city');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_review`
--

CREATE TABLE `tbl_review` (
  `id` int(11) NOT NULL,
  `hardware_id` int(11) NOT NULL,
  `reviewer_name` varchar(255) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_review`
--

INSERT INTO `tbl_review` (`id`, `hardware_id`, `reviewer_name`, `rating`, `description`, `created_at`) VALUES
(1, 1, 'John Doe', 5, 'Excellent hardware! Very reliable and durable.', '2025-02-23 08:15:21'),
(2, 1, 'Alice Smith', 4, 'Great performance, but slightly overpriced.', '2025-02-23 08:15:21'),
(3, 2, 'Michael Brown', 3, 'Decent product but had some issues with durability.', '2025-02-23 08:15:21'),
(4, 2, 'Emily Davis', 5, 'Works flawlessly! Highly recommended.', '2025-02-23 08:15:21'),
(5, 3, 'David Wilson', 4, 'Good value for money. Performs well under load.', '2025-02-23 08:15:21'),
(6, 3, 'Sarah Johnson', 2, 'Not satisfied. Had some issues with overheating.', '2025-02-23 08:15:21'),
(7, 4, 'James Anderson', 5, 'Amazing hardware, exceeded my expectations!', '2025-02-23 08:15:21'),
(8, 4, 'Olivia Martinez', 3, 'Average product, nothing special.', '2025-02-23 08:15:21'),
(9, 5, 'Robert Lee', 4, 'Performs well, but the installation was tricky.', '2025-02-23 08:15:21'),
(10, 5, 'Sophia Clark', 5, 'Superb quality! Would definitely buy again.', '2025-02-23 08:15:21'),
(11, 6, 'William White', 2, 'Not worth the price. Expected better performance.', '2025-02-23 08:15:21'),
(12, 6, 'Emma Lewis', 4, 'Good product overall, but could be improved.', '2025-02-23 08:15:21'),
(13, 1, 'df d', 2, 'sfbsbsfb sfb', '2025-02-24 05:50:55'),
(14, 1, 'sgsrbsfb', 5, 'bsfbsbsb', '2025-02-24 05:54:11'),
(15, 3, 'mgjmcjm,chj,mhj,m,j', 1, 'gjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm', '2025-02-24 08:07:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_achievements`
--
ALTER TABLE `tbl_achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_carousel`
--
ALTER TABLE `tbl_carousel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tbl_hardware`
--
ALTER TABLE `tbl_hardware`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tbl_hero`
--
ALTER TABLE `tbl_hero`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_icons`
--
ALTER TABLE `tbl_icons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_leadership`
--
ALTER TABLE `tbl_leadership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_mission_vision`
--
ALTER TABLE `tbl_mission_vision`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_navbar`
--
ALTER TABLE `tbl_navbar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_owner`
--
ALTER TABLE `tbl_owner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pages`
--
ALTER TABLE `tbl_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_review`
--
ALTER TABLE `tbl_review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hardware_id` (`hardware_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_achievements`
--
ALTER TABLE `tbl_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_carousel`
--
ALTER TABLE `tbl_carousel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_hardware`
--
ALTER TABLE `tbl_hardware`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_hero`
--
ALTER TABLE `tbl_hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_icons`
--
ALTER TABLE `tbl_icons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_leadership`
--
ALTER TABLE `tbl_leadership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_mission_vision`
--
ALTER TABLE `tbl_mission_vision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_navbar`
--
ALTER TABLE `tbl_navbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_owner`
--
ALTER TABLE `tbl_owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_pages`
--
ALTER TABLE `tbl_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_review`
--
ALTER TABLE `tbl_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_hardware`
--
ALTER TABLE `tbl_hardware`
  ADD CONSTRAINT `tbl_hardware_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_review`
--
ALTER TABLE `tbl_review`
  ADD CONSTRAINT `tbl_review_ibfk_1` FOREIGN KEY (`hardware_id`) REFERENCES `tbl_hardware` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
