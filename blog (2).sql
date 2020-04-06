-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 30, 2020 at 01:31 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` tinyint(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` tinyint(4) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `header`, `content`, `author_id`, `category_id`, `created_at`, `modified_at`, `image`) VALUES
(9, 'dd', 'ddd', 'dddd', 71, 1, '2020-03-29 16:38:56', '2020-03-29 16:38:56', 'https://cdn.pixabay.com/photo/2014/07/31/23/28/woman-407168_1280.jpg'),
(10, 'dd', 'ddd', 'dddd', 71, 1, '2020-03-29 16:39:53', '2020-03-29 16:39:53', 'https://cdn.pixabay.com/photo/2015/07/29/00/08/photographer-865295_1280.jpg'),
(11, 'dd2', 'ddd2', 'dddd2hhh', 71, 1, '2020-03-29 16:40:49', '2020-03-29 16:40:49', 'https://dvyvvujm9h0uq.cloudfront.net/com/articles/1564659754-foodphotography-2.jpg'),
(16, 'Travel photography tips', '', '', 71, 2, '2020-03-29 18:11:54', '2020-03-29 18:11:54', 'https://erinoutdoors.com/wp-content/uploads/2018/05/DSC1396.jpg'),
(17, 'Photography is the art of our time', 'Photography is the art of our time The old masters painted the drama of life and death. Today photography captures the human condition – better than any other artistic medium of our age', '', 71, 5, '2020-03-29 19:11:46', '2020-03-29 19:11:46', 'https://d2jv9003bew7ag.cloudfront.net/uploads/Joanna-Mr%C3%B3wka-Untitled-Image-via-lensculturecom-865x570.jpg'),
(18, 'Article test', 'test', 'Cest un peu dur', 71, 1, '2020-03-29 19:30:11', '2020-03-29 19:30:11', 'https://cdn.pixabay.com/photo/2017/04/08/09/01/photography-2212761_1280.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `articleTag`
--

CREATE TABLE `articleTag` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articleTag`
--

INSERT INTO `articleTag` (`id`, `article_id`, `tag_id`) VALUES
(1, 15, 71),
(2, 15, 72),
(3, 15, 73),
(4, 15, 74),
(5, 15, 75),
(35, 19, 114),
(36, 20, 114),
(37, 20, 114),
(38, 21, 114),
(39, 21, 114),
(40, 22, 114),
(41, 23, 114),
(63, 26, 150),
(64, 26, 151),
(65, 26, 154),
(66, 17, 155),
(67, 17, 156),
(68, 17, 157),
(90, 16, 158),
(91, 16, 114),
(92, 16, 160),
(93, 18, 182),
(94, 18, 183),
(95, 18, 184);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` tinyint(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `created_at`) VALUES
(1, 'Food', '2020-03-28 13:08:50'),
(2, 'Travel', '2020-03-28 13:08:50'),
(3, 'Nature', '2020-03-28 13:10:39'),
(4, 'Lyfestyle', '2020-03-28 13:10:39'),
(5, 'Street', '2020-03-28 13:12:52');

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `id` tinyint(4) NOT NULL,
  `author_id` tinyint(4) NOT NULL,
  `article_id` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Comments`
--

INSERT INTO `Comments` (`id`, `author_id`, `article_id`, `content`, `created_at`) VALUES
(1, 3, 3, 'beau !!!', '2020-03-27 11:54:31'),
(8, 71, 18, 'hhhh', '2020-03-30 11:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `title`) VALUES
(75, '#2019'),
(71, '#animal'),
(156, '#art'),
(132, '#beau'),
(138, '#belle'),
(143, '#belleee'),
(157, '#camera'),
(129, '#connard'),
(127, '#cookie'),
(183, '#cute'),
(134, '#deca'),
(130, '#degoute'),
(112, '#ewqads'),
(158, '#family'),
(122, '#golang'),
(73, '#hudiii'),
(74, '#hyfi'),
(131, '#joli'),
(114, '#nature'),
(151, '#new'),
(150, '#nouveau'),
(182, '#pets'),
(184, '#photo'),
(116, '#php'),
(111, '#qweq'),
(155, '#street'),
(115, '#sunday'),
(128, '#test'),
(160, '#travel'),
(154, '#vho'),
(113, '#weq'),
(72, '#zigoto');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` tinyint(4) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `usergroup` varchar(255) NOT NULL,
  `is_banned` tinyint(1) NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `usergroup`, `is_banned`, `is_activated`, `created_at`, `modified_at`) VALUES
(65, 'lauraaaa', '$2y$10$QVGFx.nm7PejIuDHxy0lpeSrJt6V4I9Gu2LHb2b2AzOk3kl8oDNaS', 'laura.baudean@orange.fr', 'user', 0, 1, '2020-03-26 00:06:17', '2020-03-26 00:06:17'),
(69, 'LLLLLLL', '$2y$10$aL6HQ.KeJW7dNI7EUhiVE.pmD0CtHkInFbrZX/vKMPnhtBQH3Wskq', 'email@wanadoo.fr', 'admin', 1, 0, '2020-03-26 15:39:34', '2020-03-26 15:39:34'),
(71, 'ghjghj', '$2y$10$IYZG0YgFA9666kxT2ZoaNO7nw.XNexmRuKl70LWwOtU9YIF73rjVm', 'baudean.laura@gmail.com', 'admin', 0, 1, '2020-03-26 18:21:49', '2020-03-26 18:21:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articleTag`
--
ALTER TABLE `articleTag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `articleTag`
--
ALTER TABLE `articleTag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
