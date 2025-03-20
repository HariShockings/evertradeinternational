-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 07:13 AM
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
(1, 't1.png', 'bgyhkibkb', 1),
(2, 't2.png', 'Hardware 2', 2),
(3, 't3.png', 'Hardware 3', 3),
(4, 't4.png', 'Hardware 4', 4),
(5, 't5.png', 'Hardware 5', 5),
(9, '67dada9f94956_362615731_252149267587326_6201485608611506429_n.jpg', 'harii', 6);

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
(1, 'Hand Tools'),
(2, 'Power Tools');

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
(1, 'Drill Machine', '[\"../uploads/1742121638_362615731_252149267587326_6201485608611506429_n.jpg\"]', 'High precision and power for all drilling needs. Perfect for professional and DIY projects.Ergonomic design for comfortable handling. ', 30.00, 100, 1, 'drill-machine', 'abc'),
(2, 'Circular Saw', '[\"p2.png\", \"p2_2.png\"]', 'High-speed circular saw.', 150.00, 0, 1, 'circular-saw', NULL),
(3, 'Wrench Set', '[\"p3.png\",\" p3_2.png\"]', 'Set of adjustable wrenches.', 50.00, 150, 2, 'wrench-set', ''),
(4, 'Hammer', '[\"p4.png\", \"p4_2.png\"]', 'Heavy-duty steel hammer.', 20.00, 30, 1, 'hammer', NULL),
(5, 'Electric Plug', '[\"p5.png\", \"p5_2.png\"]', 'Universal electric plug.', 5.00, 100, 3, 'electric-plug', NULL),
(6, 'Light Bulb', '[\"p6.png\",\"p6_2.png\"]', 'LED energy-saving bulb.', 8.00, 50, 2, 'light-bulb', 'vavavvavb');

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
(1, 'John Doe', 'CEO', '', 'Leadership is not about titles, it\'s about impact.'),
(2, 'Jane Smith', 'CTO', 'placeholder.jpg', 'Innovation is the heart of technology.'),
(5, 'Jane Smith', 'CTOo', '67dbab16dd9f2_362615731_252149267587326_6201485608611506429_n.jpg', 'Innovation is the heart of technology.'),
(11, 'Jane Smith', 'ggg', '67dbad84e4d1f_362615731_252149267587326_6201485608611506429_n.jpg', 'Innovation is the heart of technology.'),
(13, 'wfehbsrh', 'CTOo', '67dbb190d32b2_photo-1535713875002-d1d0cf377fde.jpeg', 'dndmnd');

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
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','done') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`id`, `product_id`, `quantity`, `order_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 1440.00, 'done', '2025-03-09 07:04:18', '2025-03-09 07:25:57');

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
(1, 'Ever Trade International', '67c58a98daad9_evlogo.png', '+94 771234567', 'info@evertrade.com', 'Colombo, Sri Lanka', 'https://facebook.com/evertrade', 'https://twitter.com/evertrade', 'https://instagram.com/evertrade', 'https://linkedin.com/company/evertrade', '+94771234567', 'Mon - Fri: 9:00 AM - 5:00 PM', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.81597678079!2d79.77826335115893!3d6.921832307550433!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1740459686840!5m2!1sen!2slk');

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
(1, 'about_us', 'ABOUT US', 'We are a leading company in the hardware industry, committed to providing top-quality products and services. With a focus on durability, innovation, and cost-effectiveness, we offer a wide range of tools, equipment, and building materials for professionals and DIY enthusiasts.', 'rods-city.png', 'rod-city'),
(2, 'contact_us', 'CONTACT US', 'We are a leading company in the hardware industry, committed to providing top-quality products and services. With a focus on durability, innovation, and cost-effectiveness, we offer a wide range of tools, equipment, and building materials for professionals and DIY enthusiasts.', 'contact.png', 'rod-city');

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
(15, 3, 'mgjmcjm,chj,mhj,m,j', 1, 'gjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm\r\ngjmg,mxgjgmgmgjmmhm', '2025-02-24 08:07:45'),
(16, 3, 'ghgj', 4, 'fhchcj', '2025-03-16 10:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@123.com', '$2y$10$DJQZ11QiAii46qVJcKjL5OxQuEnD4.5gT6aK/EU015X4C9HsEiiTq');

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
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_hardware`
--
ALTER TABLE `tbl_hardware`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_mission_vision`
--
ALTER TABLE `tbl_mission_vision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_navbar`
--
ALTER TABLE `tbl_navbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_hardware`
--
ALTER TABLE `tbl_hardware`
  ADD CONSTRAINT `tbl_hardware_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `tbl_orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tbl_hardware` (`id`);

--
-- Constraints for table `tbl_review`
--
ALTER TABLE `tbl_review`
  ADD CONSTRAINT `tbl_review_ibfk_1` FOREIGN KEY (`hardware_id`) REFERENCES `tbl_hardware` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
