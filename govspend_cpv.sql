-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2014 at 10:52 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `govspend`
--

-- --------------------------------------------------------

--
-- Table structure for table `cpv-codes`
--

CREATE TABLE IF NOT EXISTS `cpv-codes` (
  `cpv_code` varchar(8) NOT NULL,
  `cpv_description` varchar(200) NOT NULL,
  PRIMARY KEY (`cpv_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
