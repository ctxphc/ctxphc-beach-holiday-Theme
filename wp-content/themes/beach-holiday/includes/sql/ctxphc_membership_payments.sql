-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2012 at 09:02 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ctxphc_wp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ctxphc_membership_payments`
--

CREATE TABLE IF NOT EXISTS `ctxphc_membership_payments` (
  `pmnt_id` int(11) NOT NULL AUTO_INCREMENT,
  `pmnt_user` int(9) NOT NULL,
  `txn_id` varchar(19) NOT NULL,
  `payer_email` varchar(75) NOT NULL,
  `pmnt_amount` float(9,2) NOT NULL,
  `pmnt_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IPN_data` text NOT NULL,
  `IPN_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pmnt_completed` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`pmnt_id`),
  UNIQUE KEY `txn_id` (`txn_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `ctxphc_membership_payments`
--

INSERT INTO `ctxphc_membership_payments` (`pmnt_id`, `pmnt_user`, `txn_id`, `payer_email`, `pmnt_amount`, `pmnt_date`, `IPN_data`, `IPN_date`, `pmnt_completed`) VALUES
(1, 1573, '', 'kaptkaos@gmail.com', 40.00, '2012-12-26 17:42:09', '', '0000-00-00 00:00:00', 'N');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
