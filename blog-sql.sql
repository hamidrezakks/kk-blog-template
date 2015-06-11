-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2014 at 10:28 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hw4db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogsbykk`
--

CREATE TABLE IF NOT EXISTS `blogsbykk` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `blog_url` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `blog_address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `header_pic` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `blogsbykk`
--

INSERT INTO `blogsbykk` (`id`, `author_id`, `blog_url`, `name`, `description`, `blog_address`, `header_pic`, `category`, `date`, `time`, `state`) VALUES
(1000, 100000, 'qrefwerf', 'haji', 'awsfras', 'asdfca', '', 'hey', 'aservs', 'aervf', 1),
(1001, 100000, '', 'Hey', 'aergf', '', 'blogs/1001thumb9063964.jpg', 'hey', 'June 17, 2014', '19:20', 1),
(1002, 100000, 'kk', '', 'WFRER', 'kk', '', 'master', 'June 17, 2014', '19:27', 1),
(1003, 100001, 'srfvtg', 'Second Test', 'wrvgtw', 'srfvtg', '', 'kk', 'June 17, 2014', '22:12', 1),
(1004, 100001, 'wrong', 'wrong', 'wrgtgrwetg', 'wrong', 'blogs/1004thumb3360687.jpg', 'master', 'June 18, 2014', '1:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `commentsbykk`
--

CREATE TABLE IF NOT EXISTS `commentsbykk` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `author` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `body` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `resto` int(11) NOT NULL DEFAULT '0',
  `tores` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `showcm` int(11) NOT NULL DEFAULT '0',
  `date` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `commentsbykk`
--

INSERT INTO `commentsbykk` (`id`, `post_id`, `author`, `body`, `resto`, `tores`, `showcm`, `date`, `state`) VALUES
(1000, 1005, 'Hey There', 'er', 0, '1001|', 0, '2014-06-18 9:14', 1),
(1001, 1005, 'Hey There', 'ererwegrwgwrgt', 0, '0', 0, '2014-06-18 9:19', 1),
(1002, 1005, 'KK', 'Thanks', 1001, '0', 0, '2014-06-18 9:47', 1),
(1003, 1005, 'KK', 'nice post', 0, '0|1006|1009', 0, '2014-06-18 9:49', 1),
(1004, 1005, 'admin', 'thank you', 1003, '0', 0, '2014-06-18 9:50', 1),
(1005, 1004, 'Hamidreza', 'Salam', 0, '0', 0, '2014-06-18 9:52', 1),
(1006, 1005, 'Admin', 'Thankl You', 1003, '0|1007|1011', 0, '2014-06-18 10:11', 1),
(1007, 1005, 'KK', 'your welcomed', 1006, '0|1008', 0, '2014-06-18 10:15', 1),
(1008, 1005, 'Admin', ';)', 1007, '0', 0, '2014-06-18 10:18', 1),
(1009, 1005, 'Fucker', 'Is it good?', 1003, '0|1010', 0, '2014-06-18 10:18', 1),
(1010, 1005, 'Admin', 'What do you mean?', 1009, '0', 0, '2014-06-18 10:19', 1),
(1011, 1005, 'Kazemi', 'chetori?', 1006, '0', 0, '2014-06-18 11:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `postsbykk`
--

CREATE TABLE IF NOT EXISTS `postsbykk` (
  `id` int(11) NOT NULL,
  `blogid` int(11) NOT NULL,
  `post_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `post_body` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `post_comments` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `post_number` int(11) NOT NULL DEFAULT '0',
  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1',
  `ppass` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `postsbykk`
--

INSERT INTO `postsbykk` (`id`, `blogid`, `post_title`, `post_body`, `post_comments`, `post_number`, `date`, `time`, `state`, `ppass`) VALUES
(1000, 1001, 'Hello There', 'posts/1000index.dtx', '', 0, '2014 06 18', '1:05', 1, ''),
(1001, 1003, 'Hey Again', 'posts/1001index.dtx', '', 0, '2014-06-18', '1:24', 1, ''),
(1002, 1004, '123', 'posts/1002index.dtx', '', 0, '2014-06-18', '1:31', 1, ''),
(1003, 1004, 'Hello There', 'posts/1003index.dtx', '', 0, '2014-06-18', '1:46', 1, ''),
(1004, 1004, 'Dear all 2', 'posts/1004index.dtx', '', 0, '2014-06-18', '1:50', 1, ''),
(1005, 1004, 'yup', 'posts/1005index.dtx', '', 0, '2014-06-18', '9:04', 1, '123');

-- --------------------------------------------------------

--
-- Table structure for table `registerbykk`
--

CREATE TABLE IF NOT EXISTS `registerbykk` (
  `id` int(11) NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `avatarpic` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `md5_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `user_level` int(2) NOT NULL DEFAULT '0',
  `uadmin` int(1) NOT NULL DEFAULT '0',
  `ulogout` int(1) NOT NULL DEFAULT '1',
  `ulaerror` int(2) NOT NULL DEFAULT '0',
  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `registerbykk`
--

INSERT INTO `registerbykk` (`id`, `email`, `password`, `name`, `birthdate`, `country`, `avatarpic`, `md5_code`, `user_level`, `uadmin`, `ulogout`, `ulaerror`, `date`, `time`, `state`) VALUES
(100000, 'k_h1372@yahoo.com', '0015865355', 'Hamidreza', '1993-05-27', 'United States', 'avatar/3255493.jpg', '15ee54356a5edfea410311839aa32c63', 2, 0, 0, 0, 'June 17 2014', '14:10', 1),
(100001, 'kh.1372@yahoo.com', '123456', 'Mr.KK', '2014-06-17', 'United States', 'avatar/1000014549407.jpg', 'e10adc3949ba59abbe56e057f20f883e', 2, 0, 1, 0, 'June 17 2014', '22:11', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
