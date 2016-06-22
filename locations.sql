-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2016 at 07:10 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `onstatetest`
--

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_name` varchar(255) NOT NULL,
  `loc_lat` varchar(255) NOT NULL,
  `loc_long` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `loc_name`, `loc_lat`, `loc_long`) VALUES
(1, 'London, Uk', '51.5073509', '-0.1277583'),
(2, 'Edinburgh, UK', '55.953252', '-3.188267'),
(3, 'Cardiff, UK', '51.481581', '-3.17909'),
(4, 'Belfast, UK', '54.597285', '-5.93012'),
(5, 'Normanton, UK', '53.700876', '-1.417148'),
(6, 'Boston, UK', '52.97894', '-0.026577'),
(7, 'Leeds, UK', '53.8007554', '-1.5490774'),
(8, 'Manchester, UK', '53.4807593', '-2.2426305');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
