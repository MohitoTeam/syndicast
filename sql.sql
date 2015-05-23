-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 23 Maj 2015, 20:27
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `syndication`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Artysta'),
(2, 'Radio'),
(3, 'Admin'),
(4, 'Asystent');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mp3`
--

CREATE TABLE IF NOT EXISTS `mp3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(11) NOT NULL,
  `date` timestamp NOT NULL,
  `img` varchar(32) NOT NULL,
  `title` varchar(256) NOT NULL,
  `approve` int(1) DEFAULT '0',
  `length` int(3) DEFAULT NULL,
  `bitrate` int(3) DEFAULT NULL,
  `promoted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  UNIQUE KEY `date` (`date`),
  UNIQUE KEY `img` (`img`),
  UNIQUE KEY `img_2` (`img`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `mp3`
--

INSERT INTO `mp3` (`id`, `artist_id`, `date`, `img`, `title`, `approve`, `length`, `bitrate`, `promoted`) VALUES
(1, 20, '2015-05-02 17:13:01', '991dce97282d17e641e124153625a085', 'dsvsv', 1, 248, 320, 0),
(4, 20, '2015-05-02 17:15:14', '3c7ea0255cae9b96b448e8f3b0ed95e2', 'qqq', 1, 248, 320, 0),
(5, 20, '2015-05-02 18:11:01', '515ac302acc589dff2d61f850071a2ef', 'gegregreg', 1, 248, 320, 0),
(7, 20, '2015-05-02 20:33:24', 'bba9acd0be6777dc8d1856c481f18863', 'mm', 1, 248, 320, 1),
(9, 20, '2015-05-02 20:39:41', 'c6378fb5d6f8dbd4b8bbef1c6e088f12', 'rregreg', 1, 248, 320, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `podcasts`
--

CREATE TABLE IF NOT EXISTS `podcasts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(16) NOT NULL,
  `date` timestamp NOT NULL,
  `img` varchar(64) NOT NULL,
  `title` varchar(256) NOT NULL,
  `approve` int(1) NOT NULL DEFAULT '0',
  `length` int(4) DEFAULT NULL,
  `bitrate` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  UNIQUE KEY `img` (`img`),
  KEY `id` (`id`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Zrzut danych tabeli `podcasts`
--

INSERT INTO `podcasts` (`id`, `artist_id`, `date`, `img`, `title`, `approve`, `length`, `bitrate`) VALUES
(35, 20, '2015-04-22 11:38:44', '2fa1740314b88c36d53572edf3e99412', 'gegfeygfu', 1, NULL, NULL),
(37, 20, '2015-04-22 11:48:58', 'fc26e84ef978ecb26f938767ee4e6ba4', 'brrr', 1, NULL, NULL),
(39, 20, '2015-04-22 12:10:02', '21ae161605fd9d4a968a4b2ee4731a2a', 'ppp', 1, NULL, NULL),
(45, 20, '2015-04-22 17:10:59', '16d92ffd59b8cd6bc880bc3317c17717', 'bre', 1, NULL, NULL),
(47, 20, '2015-04-22 17:46:22', '8bf3a67e0e52d8a933f23fba0283065d', 'tmp', 1, 225, NULL),
(49, 20, '2015-04-22 18:11:17', '88a97336c24f96fc7d2f156076e72739', 'xcx', 1, 321, NULL),
(51, 20, '2015-04-22 18:16:48', '47426b7f1b942fb2b533bcb2a483ea45', 'zzz', 1, 225, NULL),
(53, 20, '2015-04-22 18:23:09', '87a99596508b33791374d7bf6105902b', 'z', 1, 192, 0),
(55, 20, '2015-04-22 18:25:01', '25da5062f466572e59a8c4b443770e52', 'zxcv', 1, 307, 320),
(56, 9, '2015-04-29 16:41:54', 'ad9d7742359abd40fcfcb8b156cd8c0f', '', 0, 0, 0),
(57, 9, '2015-04-29 16:42:12', '582c031b7bad911b05ca6989dfddc448', '', 0, 0, 0),
(58, 9, '2015-04-29 16:42:27', '731e9fc7076867d77205ee5a63c02377', '', 0, 0, 0),
(59, 20, '2015-04-29 18:00:40', 'f859c5a1f5ce305779dfa50b6eca8204', '', 0, 0, 0),
(61, 20, '2015-04-29 18:06:01', 'f88d12d433656a689dcf8179eb036d6d', 'cvv', 1, 248, 320),
(62, 20, '2015-05-02 14:25:27', '991dce97282d17e641e124153625a085', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subscribed_podcasts`
--

CREATE TABLE IF NOT EXISTS `subscribed_podcasts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `podcast_id` int(11) NOT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `active` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `subscribed_podcasts`
--

INSERT INTO `subscribed_podcasts` (`id`, `user_id`, `podcast_id`, `start_date`, `end_date`, `active`) VALUES
(1, 17, 55, '2015-05-05 09:09:37', '2015-05-05 09:09:37', 1),
(2, 20, 61, '2015-05-05 11:56:07', '2015-05-05 11:56:07', 1),
(3, 17, 45, '2015-05-05 11:57:25', '2015-05-05 11:57:25', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_hash` varchar(32) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `ip`, `user_agent`, `date`, `active`, `activation_hash`, `role`) VALUES
(1, 'test', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', 'test@test.com', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-04 16:07:05', 0, '', 1),
(2, 'wgfw4g4', 'c8afeec4e9d29fa6307bc246965fe136a95bc47a9cfdedba0df256358eaa45ec0bf8d7a4333a4b13dc9a5508137d0f4d212272b27e64e41d4745a66b5f480759', 'bweiufgbwi@op.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-04 16:55:39', 0, '', 1),
(3, 'wgfw4g4455', 'c8afeec4e9d29fa6307bc246965fe136a95bc47a9cfdedba0df256358eaa45ec0bf8d7a4333a4b13dc9a5508137d0f4d212272b27e64e41d4745a66b5f480759', 'bweiufgbrrwi@op.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-04 16:57:57', 0, '', 1),
(4, 'njncjds', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'bceiufbe@op.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-04 17:33:38', 0, '61e99db42921b5126618f81358c366f5', 1),
(5, 'njncjds3r3', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'bceiufbe@op.plf', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-04 17:34:59', 0, '118d15075eafcc279537450e5f688568', 1),
(6, 'wffew', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'ewggwrh@op.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-07 12:18:59', 0, 'b3e23df945c0e6bfcb28d82dc9c574a2', 1),
(8, 'asdfsgsg', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'wegwg@wo.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0', '2015-04-07 13:03:56', 0, '47da54bc335a1ae7bbea4b97612c8913', 1),
(9, 'egregerg', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'wefewg@op.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-20 18:33:40', 1, 'f40846eaedc5124a8aced70e8aaa83c7', 1),
(17, 'feddd', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'egerg@op.pl', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36 OPR/28.0.1750.48', '2015-04-08 15:39:38', 1, '5523ff810b22f5523ff810b239', 2),
(18, 'ppp', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'ppp@op.pl', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36 OPR/28.0.1750.51', '2015-04-21 15:35:33', 0, '88a003d346d5df68dee219dd91e2c661', 1),
(19, 'ooo', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'ooo@op.pl', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36 OPR/28.0.1750.51', '2015-04-21 16:40:34', 0, '98aba024f20460bbe30f87a6078e3774', 1),
(20, 'uiop', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'uiop@op.pl', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36 OPR/28.0.1750.51', '2015-04-21 16:49:15', 1, '501d238e4967195a66b4926caba0aa28', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
