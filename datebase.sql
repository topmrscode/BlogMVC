-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 06, 2020 at 09:01 AM
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
(9, 'How to create a photography business ', 'follow this marketing tips to create your own business', 'dddd', 69, 5, '2020-03-29 16:38:56', '2020-03-29 16:38:56', 'https://cdn.pixabay.com/photo/2014/07/31/23/28/woman-407168_1280.jpg'),
(10, 'The best food photographies', 'The best food photographies', 'dddd', 69, 7, '2020-03-29 16:39:53', '2020-03-29 16:39:53', 'https://cdn.pixabay.com/photo/2015/07/29/00/08/photographer-865295_1280.jpg'),
(11, 'The best street photographies', 'The best street photographies', 'The best street photographies content', 74, 5, '2020-03-29 16:40:49', '2020-03-29 16:40:49', 'https://dvyvvujm9h0uq.cloudfront.net/com/articles/1564659754-foodphotography-2.jpg'),
(16, 'Travel photography tips', 'the best tips for better travel photography', '', 69, 10, '2020-03-29 18:11:54', '2020-03-29 18:11:54', 'https://erinoutdoors.com/wp-content/uploads/2018/05/DSC1396.jpg'),
(17, 'Photography is the art of our time', 'Photography is the art of our time The old masters painted the drama of life and death. Today photography captures the human condition – better than any other artistic medium of our age', '', 74, 5, '2020-03-29 19:11:46', '2020-03-29 19:11:46', 'https://d2jv9003bew7ag.cloudfront.net/uploads/Joanna-Mr%C3%B3wka-Untitled-Image-via-lensculturecom-865x570.jpg'),
(18, '8 tips for better pets photography', 'Pets have always been a big part of my life, and are important members of many of the families I photograph. I encourage my clients to include pets in their photo sessions wherever possible.', 'Dogs often smile during, or after, vigorous exercise. If you don’t want to photograph the dog in motion, you can throw a ball, or run around with him for a few minutes, before coaxing him into position. The image below shows our dog smiling as he cools off in the shade, following a manic ball-throwing session', 74, 9, '2020-03-29 19:30:11', '2020-03-29 19:30:11', 'https://cdn.pixabay.com/photo/2017/04/08/09/01/photography-2212761_1280.jpg'),
(22, '10 ways to promote your portrait photography', 'Do you have a portrait photography business in need of some more clients? Here are 10 tips that will help in promoting and marketing your portrait photography.', 'While you may have already built an excellent website to show off your portrait photography, if your website doesn’t include a blog, you’re missing out on a great opportunity to promote your portrait photography business. Adding unique content to your site will improve its search results ranking. Also, your blog offers an opportunity to show your expertise in portrait photography and establish you as an industry leader.', 69, 9, '2020-04-06 10:43:32', '2020-04-06 10:43:32', 'https://www.b612studio.fr/wp-content/uploads/068-1.jpg');

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
(41, 23, 114),
(63, 26, 150),
(64, 26, 151),
(65, 26, 154),
(66, 17, 155),
(67, 17, 156),
(68, 17, 157),
(100, 19, 114),
(101, 19, 190),
(102, 21, 114),
(106, 22, 192),
(107, 22, 193),
(108, 22, 194),
(109, 18, 182),
(110, 18, 183),
(111, 18, 184),
(112, 18, 114),
(113, 16, 158),
(114, 16, 114),
(115, 16, 160),
(116, 9, 194),
(117, 9, 193);

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
(3, 'Nature', '2020-03-28 13:10:39'),
(5, 'Street', '2020-03-28 13:12:52'),
(7, 'Food', '2020-04-04 21:00:42'),
(8, 'architecture', '2020-04-05 12:51:07'),
(9, 'Portrait', '2020-04-06 10:45:29'),
(10, 'Travel', '2020-04-06 10:45:50');

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
(8, 71, 18, 'hhhh', '2020-03-30 11:26:50'),
(9, 71, 18, 'hello2', '2020-04-03 18:43:39'),
(10, 71, 9, 'hhhjjjj', '2020-04-03 18:47:52'),
(12, 71, 11, 'kigiii', '2020-04-03 18:56:32'),
(13, 77, 18, 'jjjjjjj', '2020-04-04 20:24:34'),
(14, 77, 18, 'hgjgjh', '2020-04-04 20:47:59');

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
(194, '#business'),
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
(190, '#natur'),
(114, '#nature'),
(151, '#new'),
(150, '#nouveau'),
(182, '#pets'),
(184, '#photo'),
(193, '#photography'),
(116, '#php'),
(192, '#portrait'),
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
(69, 'Paul', '$2y$10$aL6HQ.KeJW7dNI7EUhiVE.pmD0CtHkInFbrZX/vKMPnhtBQH3Wskq', 'email@wanadoo.fr', 'admin', 1, 0, '2020-03-26 15:39:34', '2020-03-26 15:39:34'),
(74, 'Nathalie', '$2y$10$ybuk2uvdA.POBlxoVKr90uwNZr41tZAFu/pzkgKTh1nsbvE7nf7zy', 'vincent.hy@orange.com', 'writer', 0, 1, '2020-04-03 19:16:52', '2020-04-03 19:16:52'),
(80, 'laura', '$2y$10$dU4YzY.81I4zcsX/ESYIUeebslaaqsOXd2rGUhlFDOph7I/KSHcoK', 'baudean.laura@gmail.com', 'admin', 0, 1, '2020-04-05 12:42:52', '2020-04-05 12:42:52'),
(82, 'Corentin', '$2y$10$ptZGzcuHRpCEekSD/Wm5BeQsNSPmN07xg3UwUl/pZ474P4O91FmR6', 'admin@admin.com', 'user', 0, 0, '2020-04-05 12:50:23', '2020-04-05 12:50:23');

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
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `articleTag`
--
ALTER TABLE `articleTag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
