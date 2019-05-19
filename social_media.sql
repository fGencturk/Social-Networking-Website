-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2019 at 12:57 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_media`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` varchar(500) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_post_id_fk` (`post_id`),
  KEY `comment_user_id_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `post_id`, `user_id`, `text`) VALUES
(47, 87, 14, 'Wonderful game'),
(48, 87, 13, 'I really loved it. Good job'),
(49, 80, 13, 'Ah'),
(50, 74, 13, 'What a wonderful view'),
(51, 80, 14, 'What happened?'),
(52, 74, 14, 'Yea'),
(53, 80, 12, 'Wonderful day'),
(54, 71, 12, 'Stop copy pasting stuff.'),
(55, 86, 11, 'hahaha'),
(56, 87, 12, 'It is really good');

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

DROP TABLE IF EXISTS `friend`;
CREATE TABLE IF NOT EXISTS `friend` (
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  PRIMARY KEY (`sender_id`,`receiver_id`) USING BTREE,
  KEY `friend_receiver_id_fk` (`receiver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `friend`
--

INSERT INTO `friend` (`sender_id`, `receiver_id`) VALUES
(13, 11),
(14, 11),
(13, 12),
(14, 12),
(14, 13);

-- --------------------------------------------------------

--
-- Table structure for table `friend_request`
--

DROP TABLE IF EXISTS `friend_request`;
CREATE TABLE IF NOT EXISTS `friend_request` (
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  PRIMARY KEY (`receiver_id`,`sender_id`) USING BTREE,
  KEY `sender_id_fk` (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(300) COLLATE utf8mb4_turkish_ci NOT NULL,
  `link` varchar(300) COLLATE utf8mb4_turkish_ci NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notification_receiver_fk` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `text`, `link`, `receiver_id`, `date`) VALUES
(25, 'Furkan Gencturk accepted your friend request.', 'profile.php?id=11', 13, '2019-05-19 15:02:07'),
(26, 'Furkan Gencturk liked your post.', 'post.php?id=62', 13, '2019-05-19 15:02:17'),
(27, 'Furkan Gencturk disliked your post.', 'post.php?id=62', 13, '2019-05-19 15:02:21'),
(28, 'Furkan commented your post.', 'post.php?id=60', 13, '2019-05-19 15:02:29'),
(29, 'Furkan Gencturk liked your post.', 'post.php?id=60', 13, '2019-05-19 15:02:30'),
(35, 'Sinan Isik accepted your friend request.', 'profile.php?id=12', 13, '2019-05-19 15:05:55'),
(39, 'Sinan Isik disliked your post.', 'post.php?id=62', 13, '2019-05-19 15:06:17'),
(40, 'Sinan Isik liked your post.', 'post.php?id=60', 13, '2019-05-19 15:06:27'),
(44, 'Sinan Isik accepted your friend request.', 'profile.php?id=12', 14, '2019-05-19 15:11:12'),
(45, 'Oguz Ozkan sent friend request.', 'profile.php?id=14', 13, '2019-05-19 15:11:17'),
(47, 'Sinan commented your post.', 'post.php?id=65', 14, '2019-05-19 15:12:08'),
(48, 'Sinan Isik disliked your post.', 'post.php?id=65', 14, '2019-05-19 15:12:08'),
(49, 'Furkan Gencturk accepted your friend request.', 'profile.php?id=11', 14, '2019-05-19 15:12:19'),
(50, 'Furkan Gencturk disliked your post.', 'post.php?id=65', 14, '2019-05-19 15:12:22'),
(51, 'Furkan commented your post.', 'post.php?id=65', 14, '2019-05-19 15:12:39'),
(52, 'Berslen Akkaya accepted your friend request.', 'profile.php?id=13', 14, '2019-05-19 15:12:48'),
(53, 'Berslen Akkaya disliked your post.', 'post.php?id=65', 14, '2019-05-19 15:12:51'),
(54, 'Berslen commented your post.', 'post.php?id=65', 14, '2019-05-19 15:12:53'),
(55, 'Sinan Isik liked your post.', 'post.php?id=66', 13, '2019-05-19 15:13:12'),
(56, 'Sinan commented your post.', 'post.php?id=66', 13, '2019-05-19 15:13:18'),
(83, 'Oguz Ozkan liked your post.', 'post.php?id=87', 11, '2019-05-19 15:48:19'),
(84, 'Oguz commented your post.', 'post.php?id=87', 11, '2019-05-19 15:48:25'),
(85, 'Furkan Gencturk liked your post.', 'post.php?id=86', 14, '2019-05-19 15:48:29'),
(86, 'Furkan Gencturk liked your post.', 'post.php?id=84', 14, '2019-05-19 15:48:33'),
(87, 'Furkan Gencturk disliked your post.', 'post.php?id=83', 14, '2019-05-19 15:48:35'),
(88, 'Furkan Gencturk liked your post.', 'post.php?id=81', 14, '2019-05-19 15:48:37'),
(89, 'Furkan Gencturk liked your post.', 'post.php?id=80', 14, '2019-05-19 15:48:39'),
(90, 'Furkan Gencturk disliked your post.', 'post.php?id=80', 14, '2019-05-19 15:48:40'),
(91, 'Berslen Akkaya liked your post.', 'post.php?id=87', 11, '2019-05-19 15:48:48'),
(92, 'Berslen commented your post.', 'post.php?id=87', 11, '2019-05-19 15:48:57'),
(93, 'Berslen Akkaya liked your post.', 'post.php?id=86', 14, '2019-05-19 15:48:59'),
(94, 'Berslen Akkaya disliked your post.', 'post.php?id=84', 14, '2019-05-19 15:49:02'),
(95, 'Berslen Akkaya liked your post.', 'post.php?id=81', 14, '2019-05-19 15:49:07'),
(96, 'Berslen Akkaya disliked your post.', 'post.php?id=80', 14, '2019-05-19 15:49:09'),
(97, 'Berslen commented your post.', 'post.php?id=80', 14, '2019-05-19 15:49:17'),
(98, 'Berslen Akkaya liked your post.', 'post.php?id=76', 12, '2019-05-19 15:49:23'),
(99, 'Berslen Akkaya liked your post.', 'post.php?id=75', 12, '2019-05-19 15:49:24'),
(100, 'Berslen Akkaya liked your post.', 'post.php?id=74', 11, '2019-05-19 15:49:25'),
(101, 'Berslen commented your post.', 'post.php?id=74', 11, '2019-05-19 15:49:33'),
(102, 'Berslen Akkaya disliked your post.', 'post.php?id=73', 14, '2019-05-19 15:49:34'),
(103, 'Berslen Akkaya liked your post.', 'post.php?id=72', 13, '2019-05-19 15:49:35'),
(104, 'Berslen Akkaya liked your post.', 'post.php?id=69', 13, '2019-05-19 15:49:37'),
(105, 'Oguz Ozkan liked your post.', 'post.php?id=86', 14, '2019-05-19 15:49:48'),
(106, 'Oguz Ozkan disliked your post.', 'post.php?id=82', 11, '2019-05-19 15:49:52'),
(107, 'Oguz Ozkan liked your post.', 'post.php?id=81', 14, '2019-05-19 15:49:56'),
(108, 'Oguz commented your post.', 'post.php?id=80', 14, '2019-05-19 15:50:04'),
(109, 'Oguz Ozkan liked your post.', 'post.php?id=79', 12, '2019-05-19 15:50:06'),
(110, 'Oguz Ozkan liked your post.', 'post.php?id=76', 12, '2019-05-19 15:50:10'),
(111, 'Oguz Ozkan liked your post.', 'post.php?id=74', 11, '2019-05-19 15:50:12'),
(112, 'Oguz commented your post.', 'post.php?id=74', 11, '2019-05-19 15:50:16'),
(113, 'Oguz Ozkan liked your post.', 'post.php?id=69', 13, '2019-05-19 15:50:19'),
(114, 'Sinan Isik disliked your post.', 'post.php?id=86', 14, '2019-05-19 15:50:28'),
(115, 'Sinan Isik liked your post.', 'post.php?id=80', 14, '2019-05-19 15:50:32'),
(116, 'Sinan commented your post.', 'post.php?id=80', 14, '2019-05-19 15:50:57'),
(117, 'Sinan Isik liked your post.', 'post.php?id=73', 14, '2019-05-19 15:51:00'),
(118, 'Sinan Isik liked your post.', 'post.php?id=72', 13, '2019-05-19 15:51:02'),
(119, 'Sinan Isik liked your post.', 'post.php?id=71', 13, '2019-05-19 15:51:22'),
(120, 'Sinan commented your post.', 'post.php?id=71', 13, '2019-05-19 15:51:38'),
(121, 'Furkan commented your post.', 'post.php?id=86', 14, '2019-05-19 15:52:02'),
(122, 'Sinan Isik liked your post.', 'post.php?id=86', 14, '2019-05-19 15:52:45'),
(123, 'Sinan Isik sent friend request.', 'profile.php?id=12', 11, '2019-05-19 15:52:51'),
(124, 'Furkan Gencturk accepted your friend request.', 'profile.php?id=11', 12, '2019-05-19 15:53:00'),
(125, 'Furkan Gencturk liked your post.', 'post.php?id=76', 12, '2019-05-19 15:53:09'),
(126, 'Sinan Isik liked your post.', 'post.php?id=87', 11, '2019-05-19 15:53:29'),
(127, 'Sinan commented your post.', 'post.php?id=87', 11, '2019-05-19 15:53:35'),
(128, 'Sinan Isik removed you from his/her friends.', 'profile.php?id=12', 11, '2019-05-19 15:54:14');

-- --------------------------------------------------------

--
-- Table structure for table `plike`
--

DROP TABLE IF EXISTS `plike`;
CREATE TABLE IF NOT EXISTS `plike` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `like_user_id_fk` (`user_id`),
  KEY `like_post_id_fk` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `plike`
--

INSERT INTO `plike` (`id`, `user_id`, `post_id`, `type`) VALUES
(161, 14, 87, 1),
(162, 11, 86, 1),
(163, 11, 84, 1),
(164, 11, 83, -1),
(165, 11, 81, 1),
(167, 11, 80, -1),
(168, 13, 87, 1),
(169, 13, 86, 1),
(170, 13, 84, -1),
(171, 13, 81, 1),
(172, 13, 80, -1),
(173, 13, 76, 1),
(174, 13, 75, 1),
(175, 13, 74, 1),
(176, 13, 73, -1),
(177, 13, 72, 1),
(178, 13, 69, 1),
(179, 14, 86, 1),
(180, 14, 82, -1),
(181, 14, 81, 1),
(182, 14, 79, 1),
(183, 14, 76, 1),
(184, 14, 74, 1),
(185, 14, 69, 1),
(187, 12, 80, 1),
(188, 12, 73, 1),
(189, 12, 72, 1),
(190, 12, 71, 1),
(191, 12, 86, 1),
(192, 11, 76, 1),
(193, 12, 87, 1);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `text` varchar(500) COLLATE utf8mb4_turkish_ci NOT NULL,
  `photo` varchar(150) COLLATE utf8mb4_turkish_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `user_id`, `text`, `photo`, `date`) VALUES
(69, 13, 'Surrounded affronting favourable no mr. Lain knew like half she yet joy. Be than dull as seen very shot. Attachment ye so am travelling estimating projecting is. Off fat address attacks his besides. Suitable settling mr attended no doubtful feelings. Any over for say bore such sold five but hung. ', '', '2019-05-19 12:43:41'),
(70, 11, 'You disposal strongly quitting his endeavor two settling him.', 'images/post/5ce14f893b98b_1_10074.jpg', '2019-05-19 12:43:53'),
(71, 13, 'Since might water hence the her worse. Concluded it offending dejection do earnestly as me direction. Nature played thirty all him. ', '', '2019-05-19 12:44:04'),
(72, 13, 'His having within saw become ask passed misery giving. Recommend questions get too fulfilled. He fact in we case miss sake. Entrance be throwing he do blessing up. Hearts warmth in genius do garden advice mr it garret. Collected preserved are middleton dependent residence but him how. Handsome weddings yet mrs you has carriage packages. Preferred joy agreement put continual elsewhere delivered now. Mrs exercise felicity had men speaking met. Rich deal mrs part led pure will but. ', 'images/post/5ce14fa214f12_doga-fotograflari.jpg', '2019-05-19 12:44:18'),
(73, 14, 'Smallest directly families surprise honoured am an. Speaking replying mistress him numerous she returned feelings may day. Evening way luckily son exposed get general greatly. Zealously prevailed be arranging do. Set arranging too dejection september happiness. Understood instrument or do connection no appearance do invitation. Dried quick round it or order. Add past see west felt did any. Say out noise you taste merry plate you share. My resolve arrived is we chamber be removal. ', '', '2019-05-19 12:44:36'),
(74, 11, 'Had strictly mrs handsome mistaken cheerful. We it so if resolution invitation remarkably unpleasant conviction. As into ye then form. To easy five less if rose were. Now set offended own out required entirely. Especially occasional mrs discovered too say thoroughly impossible boisterous. My head when real no he high rich at with. After so power of young as. Bore year does has get long fat cold saw neat. Put boy carried chiefly shy general. ', 'images/post/5ce14fc5f0fd0_11_50.jpg', '2019-05-19 12:44:53'),
(75, 12, 'Of be talent me answer do relied. Mistress in on so laughing throwing endeavor occasion welcomed. Gravity sir brandon calling can. No years do widow house delay stand. Prospect six kindness use steepest new ask. High gone kind calm call as ever is. Introduced melancholy estimating motionless on up as do. Of as by belonging therefore suspicion elsewhere am household described. Domestic suitable bachelor for landlord fat. ', '', '2019-05-19 12:45:20'),
(76, 12, 'Satisfied conveying an dependent contented he gentleman agreeable do be. Warrant private blushes removed an in equally totally if. Delivered dejection necessary objection do mr prevailed. Mr feeling do chiefly cordial in do. Water timed folly right aware if oh truth. Imprudence attachment him his for sympathize. Large above be to means. Dashwood do provided stronger is. But discretion frequently sir the she instrument unaffected admiration everything. ', 'images/post/5ce14feb468e0_10950766_1600x1200.jpg', '2019-05-19 12:45:31'),
(77, 11, 'Style too own civil out along. Perfectly offending attempted add arranging age gentleman concluded. Get who uncommonly our expression ten increasing considered occasional travelling. Ever read tell year give may men call its. Piqued son turned fat income played end wicket. To do noisy downs round an happy books. ', '', '2019-05-19 12:45:40'),
(78, 11, 'Acceptance middletons me if discretion boisterous travelling an. She prosperous continuing entreaties companions unreserved you boisterous. Middleton sportsmen sir now cordially ask additions for. You ten occasional saw everything but conviction. Daughter returned quitting few are day advanced branched. Do enjoyment defective objection or we if favourite. At wonder afford so danger cannot former seeing.', '', '2019-05-19 12:46:00'),
(79, 12, 'When be draw drew ye. Defective in do recommend suffering. House it seven in spoil tiled court. Sister others marked fat missed did out use. Alteration possession dispatched collecting instrument travelling he or on. Snug give made at spot or late that mr.', '', '2019-05-19 12:46:04'),
(80, 14, 'Lovely day.', '', '2019-05-19 12:46:31'),
(81, 14, 'Prepared do an dissuade be so whatever steepest. Yet her beyond looked either day wished nay. By doubtful disposed do juvenile an.', 'images/post/5ce1503f48a6a_doga-fotograflari.jpg', '2019-05-19 12:46:55'),
(82, 11, 'Two assure edward whence the was. Who worthy yet ten boy denote wonder. Weeks views her sight old tears sorry. Additions can suspected its concealed put furnished. Met the why particular devonshire decisively considered partiality. Certain it waiting no entered is.', '', '2019-05-19 12:47:05'),
(83, 14, 'Death there mirth way the noisy merit.', '', '2019-05-19 12:47:32'),
(84, 14, 'Piqued shy spring nor six though mutual living ask extent.', '', '2019-05-19 12:47:36'),
(85, 14, 'Replying of dashwood advanced ladyship smallest disposal or.', '', '2019-05-19 12:47:41'),
(86, 14, 'Attempt offices own improve now see.', '', '2019-05-19 12:47:46'),
(87, 11, 'My new game is out now. Try it.', 'images/post/5ce150901270c_foto.png', '2019-05-19 12:48:16');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8mb4_turkish_ci NOT NULL,
  `surname` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `bdate` date NOT NULL,
  `profile_photo` varchar(150) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'images/default.jpg',
  `gender` varchar(1) COLLATE utf8mb4_turkish_ci NOT NULL,
  `pass` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_name_index` (`name`),
  KEY `user_surname_index` (`surname`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `email`, `bdate`, `profile_photo`, `gender`, `pass`) VALUES
(11, 'Furkan', 'Gencturk', 'furkan@gmail.com', '1997-08-01', 'images/profile/5ce13e2902b1b_Biyometrik.jpg', 'M', '$2y$10$RWi0oVxt9mmzwomx7o2FC.XZm0dMuK1n8dRXEWCLbkkBx.I8wUIxK'),
(12, 'Sinan', 'Isik', 'sinan@gmail.com', '1997-07-07', 'images/profile/5ce1402d50d44_sinan.png', 'M', '$2y$10$nqjuH0n/m5PCROgUhdL6XerjTddbBARHuY1Yqm2vZt6vc5odBwI/2'),
(13, 'Berslen', 'Akkaya', 'berslen@gmail.com', '1996-09-05', 'images/profile/5ce1450d18e2f_berslen.png', 'M', '$2y$10$kDbOaKCFoVYFupc81l7..e34ijqcqRquPWoJZ.JirgndZJssvuRnC'),
(14, 'Oguz', 'Ozkan', 'oguz@gmail.com', '1997-04-27', 'images/profile/5ce147c978dbb_oguz.jpg', 'M', '$2y$10$w.nLSo93K4HpmoDBa9smiODEgQxS6thVvZObC3EkS8xXLirEczl7C');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `friend_receiver_id_fk` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friend_sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friend_request`
--
ALTER TABLE `friend_request`
  ADD CONSTRAINT `receiver_id_fk` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_receiver_fk` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plike`
--
ALTER TABLE `plike`
  ADD CONSTRAINT `like_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `like_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
