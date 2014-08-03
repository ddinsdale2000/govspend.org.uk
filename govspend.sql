-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2014 at 10:50 AM
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
-- Table structure for table `construction`
--

CREATE TABLE IF NOT EXISTS `construction` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sector` varchar(50) DEFAULT NULL,
  `sub_sector` varchar(50) DEFAULT NULL,
  `sub_group` varchar(50) DEFAULT NULL,
  `project` varchar(50) DEFAULT NULL,
  `description` text,
  `town_city` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `asset_owner` varchar(50) DEFAULT NULL,
  `econ_reg_asset` varchar(50) DEFAULT NULL,
  `funding_source` varchar(50) DEFAULT NULL,
  `earliest_start_date` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `lookup1` varchar(50) DEFAULT NULL,
  `scheme_status_indicator1` varchar(50) DEFAULT NULL,
  `date_in_service` varchar(50) DEFAULT NULL,
  `on_schedule` varchar(50) DEFAULT NULL,
  `lookup2` varchar(50) DEFAULT NULL,
  `scheme_status_indicator2` varchar(50) DEFAULT NULL,
  `total_capex_all` decimal(20,2) DEFAULT NULL,
  `total_capex_public` decimal(20,2) DEFAULT NULL,
  `spend_financial2013_14` decimal(20,2) DEFAULT NULL,
  `spend_financial2014_15` decimal(20,2) DEFAULT NULL,
  `spend_financial2015_16` decimal(20,2) DEFAULT NULL,
  `total2013_16` decimal(20,2) DEFAULT NULL,
  `total2016_20` decimal(20,2) DEFAULT NULL,
  `total2020_beyond` decimal(20,2) DEFAULT NULL,
  `estimate_status` varchar(50) DEFAULT NULL,
  `basis_of_cost` varchar(50) DEFAULT NULL,
  `base_year` varchar(50) DEFAULT NULL,
  `significant_land_cost` varchar(50) DEFAULT NULL,
  `finance_cost` varchar(50) DEFAULT NULL,
  `fm_other_cost` varchar(50) DEFAULT NULL,
  `planned_procurement_route` varchar(50) DEFAULT NULL,
  `expiry_date_existing_frameworks` varchar(50) DEFAULT NULL,
  `procuring_authority` varchar(50) DEFAULT NULL,
  `public_source` text,
  `data_source` varchar(50) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1048 ;

-- --------------------------------------------------------

--
-- Table structure for table `cpv-codes`
--

CREATE TABLE IF NOT EXISTS `cpv-codes` (
  `cpv_code` varchar(8) NOT NULL,
  `cpv_description` varchar(200) NOT NULL,
  PRIMARY KEY (`cpv_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `g-cloud`
--

CREATE TABLE IF NOT EXISTS `g-cloud` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Framework` text,
  `Lot` text,
  `Supplier` text,
  `Customer` text,
  `For_Month` date DEFAULT NULL,
  `Product_Service_Description` text,
  `Total_Charge` decimal(20,2) DEFAULT NULL,
  `Sector` varchar(30) DEFAULT NULL,
  `SME` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13493 ;

-- --------------------------------------------------------

--
-- Table structure for table `pipeline`
--

CREATE TABLE IF NOT EXISTS `pipeline` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `NoticeOrganisationName` text,
  `NoticeTitle` text,
  `PipelineType` text,
  `ReferenceNumber` text,
  `Confidence` text,
  `SpendFinancial2012_13` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2013_14` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2014_15` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2015_16` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2016_17` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2017_18` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2018_19` bigint(20) unsigned DEFAULT NULL,
  `SpendFinancial2019_20` bigint(20) DEFAULT '0',
  `TotalCapitalCost` text,
  `DeliveryLocation` text,
  `DeliveryLocationNUTSCode` text,
  `DeliveryLocationLAUCode` text,
  `DeliveryLocationComments` text,
  `StartDate` text,
  `ApproachToMarket` text,
  `ApproachDate` text,
  `LastChangeDate` text,
  `PublishedDate` text,
  `ContactEmail` text,
  `BuyerGroupID` text,
  `BuyerGroupName` text,
  `NumberOfDocuments` text,
  `CPVCodes` text,
  `NoticeID` text,
  `ParentNoticeID` text,
  `RootNoticeID` text,
  `TopBuyerGroup` text,
  `TopBuyerGroupName` text,
  `TopLevelDepartment` text,
  `TopLevelDepartmentName` text,
  `URL` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=743 ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_messages`
--

CREATE TABLE IF NOT EXISTS `sys_messages` (
  `key_id` varchar(30) NOT NULL,
  `message` varchar(120) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
