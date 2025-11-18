-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 18, 2025 at 06:06 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brickmmo_parts`
--

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `row` int NOT NULL,
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `rgb` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `is_trans` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `num_parts` int DEFAULT NULL,
  `num_sets` int DEFAULT NULL,
  `y1` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `y2` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `elements`
--

CREATE TABLE `elements` (
  `row` int NOT NULL,
  `element_id` int NOT NULL,
  `part_num` varchar(255) DEFAULT NULL,
  `color_id` int DEFAULT NULL,
  `design_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `row` int NOT NULL,
  `id` int NOT NULL,
  `version` int DEFAULT NULL,
  `set_num` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_minifigs`
--

CREATE TABLE `inventory_minifigs` (
  `row` int NOT NULL,
  `inventory_id` int DEFAULT NULL,
  `fig_num` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_parts`
--

CREATE TABLE `inventory_parts` (
  `row` int NOT NULL,
  `inventory_id` int DEFAULT NULL,
  `part_num` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `color_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `is_spare` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `img_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_sets`
--

CREATE TABLE `inventory_sets` (
  `row` int NOT NULL,
  `inventory_id` int DEFAULT NULL,
  `set_num` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `minifigs`
--

CREATE TABLE `minifigs` (
  `row` int NOT NULL,
  `fig_num` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `num_parts` int DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `row` int NOT NULL,
  `part_num` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `part_cat_id` int DEFAULT NULL,
  `part_material` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `part_categories`
--

CREATE TABLE `part_categories` (
  `row` int NOT NULL,
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `part_relationships`
--

CREATE TABLE `part_relationships` (
  `row` int NOT NULL,
  `rel_type` varchar(255) DEFAULT NULL,
  `child_part_num` varchar(255) DEFAULT NULL,
  `parent_part_num` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `row` int NOT NULL,
  `set_num` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `theme_id` int DEFAULT NULL,
  `num_parts` int DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `row` int NOT NULL,
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `elements`
--
ALTER TABLE `elements`
  ADD PRIMARY KEY (`element_id`),
  ADD KEY `part_num` (`part_num`),
  ADD KEY `color_id` (`color_id`),
  ADD KEY `design_id` (`design_id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_num` (`set_num`);

--
-- Indexes for table `inventory_minifigs`
--
ALTER TABLE `inventory_minifigs`
  ADD KEY `fig_num` (`fig_num`),
  ADD KEY `inventory_id` (`inventory_id`,`fig_num`) USING BTREE;

--
-- Indexes for table `inventory_parts`
--
ALTER TABLE `inventory_parts`
  ADD KEY `color_id` (`color_id`),
  ADD KEY `part_num` (`part_num`),
  ADD KEY `inventory_id` (`inventory_id`),
  ADD KEY `inventory_id_2` (`inventory_id`),
  ADD KEY `part_num_2` (`part_num`),
  ADD KEY `color_id_2` (`color_id`);

--
-- Indexes for table `inventory_sets`
--
ALTER TABLE `inventory_sets`
  ADD UNIQUE KEY `inventory_id` (`inventory_id`,`set_num`),
  ADD KEY `set_num` (`set_num`);

--
-- Indexes for table `minifigs`
--
ALTER TABLE `minifigs`
  ADD PRIMARY KEY (`fig_num`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`part_num`),
  ADD KEY `part_cat_id` (`part_cat_id`),
  ADD KEY `part_num` (`part_num`),
  ADD KEY `part_cat_id_2` (`part_cat_id`);

--
-- Indexes for table `part_categories`
--
ALTER TABLE `part_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `part_relationships`
--
ALTER TABLE `part_relationships`
  ADD UNIQUE KEY `rel_type` (`rel_type`,`child_part_num`,`parent_part_num`) USING BTREE,
  ADD KEY `child_part_num` (`child_part_num`),
  ADD KEY `parent_part_num` (`parent_part_num`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`set_num`),
  ADD KEY `theme_id` (`theme_id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
