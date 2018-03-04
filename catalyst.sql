-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 04, 2018 at 04:14 AM
-- Server version: 5.7.20-log
-- PHP Version: 7.2.0

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `catalyst`
--
CREATE DATABASE IF NOT EXISTS `catalyst` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `catalyst`;

-- --------------------------------------------------------

--
-- Table structure for table `api_authorizations`
--

CREATE TABLE `api_authorizations` (
  `ID` int(10) UNSIGNED NOT NULL,
  `API_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `ACCESS_TOKEN` varchar(40) CHARACTER SET ascii NOT NULL,
  `ACCESS_SECRET` varchar(60) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `api_authorizations`:
--   `USER_ID`
--       `users` -> `ID`
--   `API_ID`
--       `api_keys` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `ID` int(11) UNSIGNED NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `USER_ID` int(11) UNSIGNED NOT NULL,
  `DESCRIPTION` mediumtext NOT NULL,
  `CLIENT_ID` varchar(16) CHARACTER SET ascii NOT NULL,
  `CLIENT_SECRET` varchar(60) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `api_keys`:
--   `USER_ID`
--       `users` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `artist_pages`
--

CREATE TABLE `artist_pages` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique ID for each artist page',
  `USER_ID` int(11) UNSIGNED NOT NULL COMMENT 'Corresponds to `users`.`ID`',
  `TOKEN` char(9) CHARACTER SET ascii NOT NULL COMMENT 'Unique token for the artist page, used for profile picture',
  `NAME` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Artist''s name - displayed in title bar and page top',
  `URL` varchar(64) CHARACTER SET ascii NOT NULL COMMENT 'Artist URL, used as https://catalystapp.co/Artist/<URL HERE>/',
  `DESCRIPTION` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Artist''s description, markdown',
  `TOS` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Artist''s TOS - shown only when you go to commission',
  `IMG` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Artist''s profile picture, null if default, file path (suffixed after token)',
  `COLOR` binary(3) NOT NULL COMMENT 'Artist''s color hex',
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'If the artist is deleted.  Artist''s have a special ability to "undelete" their pages - rather than forcing them to contact us to create another one if they take it down, this is easier.  When they delete it, all their commission types and all are also marked deleted.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the information which pertains to an artist page datatype.\nThis is an "addition" to a user account - each artist is a user (USER_ID)';

--
-- RELATIONSHIPS FOR TABLE `artist_pages`:
--   `USER_ID`
--       `users` -> `ID`
--   `COLOR`
--       `colors` -> `HEX`
--

-- --------------------------------------------------------

--
-- Table structure for table `artist_social_media`
--

CREATE TABLE `artist_social_media` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique identifier for each entry',
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Sort order for the row, used to allow the artist to customize the ordering (as not to remove all and reinsert)',
  `ARTIST_ID` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID of the artist the row is for',
  `NETWORK` varchar(12) CHARACTER SET ascii NOT NULL COMMENT 'The network which the artist is making an entry for.  Relates to `social_media_meta`.`INTEGRAION_NAME`',
  `SERVICE_URL` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'URL of the service.  MEDIUMTEXT as it could be longer than 255 characters (some places _really_ need to get their crap together)',
  `DISP_NAME` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User-customizable label for the chip'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Social media entries for artists.  Displayed as chips on their profile.';

--
-- RELATIONSHIPS FOR TABLE `artist_social_media`:
--   `NETWORK`
--       `social_media_meta` -> `INTEGRATION_NAME`
--   `ARTIST_ID`
--       `artist_pages` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `artist_streaming_integrations`
--

CREATE TABLE `artist_streaming_integrations` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique ID of the service integration',
  `ARTIST_ID` int(11) UNSIGNED NOT NULL COMMENT 'Artist who added the integration',
  `SERVICE` varchar(12) CHARACTER SET ascii NOT NULL COMMENT 'Service name, must be in `social_media_meta`.`INTEGRATION_NAME`',
  `SERVICE_ID` varchar(255) CHARACTER SET ascii DEFAULT NULL COMMENT 'Not yet used, may eventually refer to a user ID or similar, potentially can be combined with `SERVICE_UNAME`',
  `SERVICE_UNAME` varchar(255) CHARACTER SET ascii DEFAULT NULL COMMENT 'Username to the artist''s profile/account on the service',
  `URL` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'URL to access the stream',
  `IS_STREAMING` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Whether the artist is currently streaming',
  `CACHE_TS` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Last time we checked if the artist was streaming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Special streaming services for artist''s profiles.';

--
-- RELATIONSHIPS FOR TABLE `artist_streaming_integrations`:
--   `SERVICE`
--       `social_media_meta` -> `INTEGRATION_NAME`
--

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique identifier of the character',
  `USER_ID` int(11) UNSIGNED NOT NULL COMMENT 'Corresponds to the character''s owner',
  `CHARACTER_TOKEN` char(7) CHARACTER SET ascii NOT NULL COMMENT 'Unique tokens used for files and URL',
  `NAME` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Character''s name',
  `DESCRIPTION` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Markdown description of character',
  `COLOR` binary(3) NOT NULL COMMENT 'Color of character',
  `PUBLIC` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Whether the character is publicly accessible',
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'If the character is deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table which contains all of a user''s characters';

--
-- RELATIONSHIPS FOR TABLE `characters`:
--   `USER_ID`
--       `users` -> `ID`
--   `COLOR`
--       `colors` -> `HEX`
--

-- --------------------------------------------------------

--
-- Table structure for table `character_images`
--

CREATE TABLE `character_images` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique ID for the image',
  `CHARACTER_ID` int(11) UNSIGNED NOT NULL COMMENT 'Corresponds to the character the image is for',
  `CAPTION` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Image caption',
  `CREDIT` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `PATH` varchar(15) CHARACTER SET ascii NOT NULL COMMENT 'Image path (appended to character token)',
  `NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'If the image is nsfw',
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Image sort'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Images of a character';

--
-- RELATIONSHIPS FOR TABLE `character_images`:
--   `CHARACTER_ID`
--       `characters` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `HEX` binary(3) NOT NULL COMMENT '6 character hex'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores all valid colors (needed for FKs)';

--
-- RELATIONSHIPS FOR TABLE `colors`:
--

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`HEX`) VALUES(0x000000);
INSERT INTO `colors` (`HEX`) VALUES(0x004d40);
INSERT INTO `colors` (`HEX`) VALUES(0x006064);
INSERT INTO `colors` (`HEX`) VALUES(0x00695c);
INSERT INTO `colors` (`HEX`) VALUES(0x00796b);
INSERT INTO `colors` (`HEX`) VALUES(0x00838f);
INSERT INTO `colors` (`HEX`) VALUES(0x00897b);
INSERT INTO `colors` (`HEX`) VALUES(0x0091ea);
INSERT INTO `colors` (`HEX`) VALUES(0x009688);
INSERT INTO `colors` (`HEX`) VALUES(0x0097a7);
INSERT INTO `colors` (`HEX`) VALUES(0x00acc1);
INSERT INTO `colors` (`HEX`) VALUES(0x00b0ff);
INSERT INTO `colors` (`HEX`) VALUES(0x00b8d4);
INSERT INTO `colors` (`HEX`) VALUES(0x00bcd4);
INSERT INTO `colors` (`HEX`) VALUES(0x00bfa5);
INSERT INTO `colors` (`HEX`) VALUES(0x00c853);
INSERT INTO `colors` (`HEX`) VALUES(0x00e5ff);
INSERT INTO `colors` (`HEX`) VALUES(0x00e676);
INSERT INTO `colors` (`HEX`) VALUES(0x01579b);
INSERT INTO `colors` (`HEX`) VALUES(0x0277bd);
INSERT INTO `colors` (`HEX`) VALUES(0x0288d1);
INSERT INTO `colors` (`HEX`) VALUES(0x039be5);
INSERT INTO `colors` (`HEX`) VALUES(0x03a9f4);
INSERT INTO `colors` (`HEX`) VALUES(0x0d47a1);
INSERT INTO `colors` (`HEX`) VALUES(0x1565c0);
INSERT INTO `colors` (`HEX`) VALUES(0x18ffff);
INSERT INTO `colors` (`HEX`) VALUES(0x1976d2);
INSERT INTO `colors` (`HEX`) VALUES(0x1a237e);
INSERT INTO `colors` (`HEX`) VALUES(0x1b5e20);
INSERT INTO `colors` (`HEX`) VALUES(0x1de9b6);
INSERT INTO `colors` (`HEX`) VALUES(0x1e88e5);
INSERT INTO `colors` (`HEX`) VALUES(0x212121);
INSERT INTO `colors` (`HEX`) VALUES(0x2196f3);
INSERT INTO `colors` (`HEX`) VALUES(0x263238);
INSERT INTO `colors` (`HEX`) VALUES(0x26a69a);
INSERT INTO `colors` (`HEX`) VALUES(0x26c6da);
INSERT INTO `colors` (`HEX`) VALUES(0x283593);
INSERT INTO `colors` (`HEX`) VALUES(0x2962ff);
INSERT INTO `colors` (`HEX`) VALUES(0x2979ff);
INSERT INTO `colors` (`HEX`) VALUES(0x29b6f6);
INSERT INTO `colors` (`HEX`) VALUES(0x2e7d32);
INSERT INTO `colors` (`HEX`) VALUES(0x303f9f);
INSERT INTO `colors` (`HEX`) VALUES(0x304ffe);
INSERT INTO `colors` (`HEX`) VALUES(0x311b92);
INSERT INTO `colors` (`HEX`) VALUES(0x33691e);
INSERT INTO `colors` (`HEX`) VALUES(0x37474f);
INSERT INTO `colors` (`HEX`) VALUES(0x388e3c);
INSERT INTO `colors` (`HEX`) VALUES(0x3949ab);
INSERT INTO `colors` (`HEX`) VALUES(0x3d5afe);
INSERT INTO `colors` (`HEX`) VALUES(0x3e2723);
INSERT INTO `colors` (`HEX`) VALUES(0x3f51b5);
INSERT INTO `colors` (`HEX`) VALUES(0x40c4ff);
INSERT INTO `colors` (`HEX`) VALUES(0x424242);
INSERT INTO `colors` (`HEX`) VALUES(0x42a5f5);
INSERT INTO `colors` (`HEX`) VALUES(0x43a047);
INSERT INTO `colors` (`HEX`) VALUES(0x448aff);
INSERT INTO `colors` (`HEX`) VALUES(0x4527a0);
INSERT INTO `colors` (`HEX`) VALUES(0x455a64);
INSERT INTO `colors` (`HEX`) VALUES(0x4a148c);
INSERT INTO `colors` (`HEX`) VALUES(0x4caf50);
INSERT INTO `colors` (`HEX`) VALUES(0x4db6ac);
INSERT INTO `colors` (`HEX`) VALUES(0x4dd0e1);
INSERT INTO `colors` (`HEX`) VALUES(0x4e342e);
INSERT INTO `colors` (`HEX`) VALUES(0x4fc3f7);
INSERT INTO `colors` (`HEX`) VALUES(0x512da8);
INSERT INTO `colors` (`HEX`) VALUES(0x536dfe);
INSERT INTO `colors` (`HEX`) VALUES(0x546e7a);
INSERT INTO `colors` (`HEX`) VALUES(0x558b2f);
INSERT INTO `colors` (`HEX`) VALUES(0x5c6bc0);
INSERT INTO `colors` (`HEX`) VALUES(0x5d4037);
INSERT INTO `colors` (`HEX`) VALUES(0x5e35b1);
INSERT INTO `colors` (`HEX`) VALUES(0x607d8b);
INSERT INTO `colors` (`HEX`) VALUES(0x616161);
INSERT INTO `colors` (`HEX`) VALUES(0x6200ea);
INSERT INTO `colors` (`HEX`) VALUES(0x64b5f6);
INSERT INTO `colors` (`HEX`) VALUES(0x64dd17);
INSERT INTO `colors` (`HEX`) VALUES(0x64ffda);
INSERT INTO `colors` (`HEX`) VALUES(0x651fff);
INSERT INTO `colors` (`HEX`) VALUES(0x66bb6a);
INSERT INTO `colors` (`HEX`) VALUES(0x673ab7);
INSERT INTO `colors` (`HEX`) VALUES(0x689f38);
INSERT INTO `colors` (`HEX`) VALUES(0x69f0ae);
INSERT INTO `colors` (`HEX`) VALUES(0x6a1b9a);
INSERT INTO `colors` (`HEX`) VALUES(0x6d4c41);
INSERT INTO `colors` (`HEX`) VALUES(0x757575);
INSERT INTO `colors` (`HEX`) VALUES(0x76ff03);
INSERT INTO `colors` (`HEX`) VALUES(0x78909c);
INSERT INTO `colors` (`HEX`) VALUES(0x795548);
INSERT INTO `colors` (`HEX`) VALUES(0x7986cb);
INSERT INTO `colors` (`HEX`) VALUES(0x7b1fa2);
INSERT INTO `colors` (`HEX`) VALUES(0x7c4dff);
INSERT INTO `colors` (`HEX`) VALUES(0x7cb342);
INSERT INTO `colors` (`HEX`) VALUES(0x7e57c2);
INSERT INTO `colors` (`HEX`) VALUES(0x81c784);
INSERT INTO `colors` (`HEX`) VALUES(0x827717);
INSERT INTO `colors` (`HEX`) VALUES(0x82b1ff);
INSERT INTO `colors` (`HEX`) VALUES(0x880e4f);
INSERT INTO `colors` (`HEX`) VALUES(0x8bc34a);
INSERT INTO `colors` (`HEX`) VALUES(0x8c9eff);
INSERT INTO `colors` (`HEX`) VALUES(0x8d6e63);
INSERT INTO `colors` (`HEX`) VALUES(0x8e24aa);
INSERT INTO `colors` (`HEX`) VALUES(0x90a4ae);
INSERT INTO `colors` (`HEX`) VALUES(0x9575cd);
INSERT INTO `colors` (`HEX`) VALUES(0x9c27b0);
INSERT INTO `colors` (`HEX`) VALUES(0x9ccc65);
INSERT INTO `colors` (`HEX`) VALUES(0x9e9d24);
INSERT INTO `colors` (`HEX`) VALUES(0x9e9e9e);
INSERT INTO `colors` (`HEX`) VALUES(0xa1887f);
INSERT INTO `colors` (`HEX`) VALUES(0xaa00ff);
INSERT INTO `colors` (`HEX`) VALUES(0xab47bc);
INSERT INTO `colors` (`HEX`) VALUES(0xad1457);
INSERT INTO `colors` (`HEX`) VALUES(0xaed581);
INSERT INTO `colors` (`HEX`) VALUES(0xaeea00);
INSERT INTO `colors` (`HEX`) VALUES(0xafb42b);
INSERT INTO `colors` (`HEX`) VALUES(0xb2ff59);
INSERT INTO `colors` (`HEX`) VALUES(0xb388ff);
INSERT INTO `colors` (`HEX`) VALUES(0xb71c1c);
INSERT INTO `colors` (`HEX`) VALUES(0xba68c8);
INSERT INTO `colors` (`HEX`) VALUES(0xbdbdbd);
INSERT INTO `colors` (`HEX`) VALUES(0xbf360c);
INSERT INTO `colors` (`HEX`) VALUES(0xc0ca33);
INSERT INTO `colors` (`HEX`) VALUES(0xc2185b);
INSERT INTO `colors` (`HEX`) VALUES(0xc51162);
INSERT INTO `colors` (`HEX`) VALUES(0xc62828);
INSERT INTO `colors` (`HEX`) VALUES(0xc6ff00);
INSERT INTO `colors` (`HEX`) VALUES(0xcddc39);
INSERT INTO `colors` (`HEX`) VALUES(0xd32f2f);
INSERT INTO `colors` (`HEX`) VALUES(0xd4e157);
INSERT INTO `colors` (`HEX`) VALUES(0xd50000);
INSERT INTO `colors` (`HEX`) VALUES(0xd500f9);
INSERT INTO `colors` (`HEX`) VALUES(0xd81b60);
INSERT INTO `colors` (`HEX`) VALUES(0xd84315);
INSERT INTO `colors` (`HEX`) VALUES(0xdce775);
INSERT INTO `colors` (`HEX`) VALUES(0xdd2c00);
INSERT INTO `colors` (`HEX`) VALUES(0xe040fb);
INSERT INTO `colors` (`HEX`) VALUES(0xe53935);
INSERT INTO `colors` (`HEX`) VALUES(0xe57373);
INSERT INTO `colors` (`HEX`) VALUES(0xe64a19);
INSERT INTO `colors` (`HEX`) VALUES(0xe65100);
INSERT INTO `colors` (`HEX`) VALUES(0xe91e63);
INSERT INTO `colors` (`HEX`) VALUES(0xea80fc);
INSERT INTO `colors` (`HEX`) VALUES(0xec407a);
INSERT INTO `colors` (`HEX`) VALUES(0xeeff41);
INSERT INTO `colors` (`HEX`) VALUES(0xef5350);
INSERT INTO `colors` (`HEX`) VALUES(0xef6c00);
INSERT INTO `colors` (`HEX`) VALUES(0xf06292);
INSERT INTO `colors` (`HEX`) VALUES(0xf44336);
INSERT INTO `colors` (`HEX`) VALUES(0xf4511e);
INSERT INTO `colors` (`HEX`) VALUES(0xf50057);
INSERT INTO `colors` (`HEX`) VALUES(0xf57c00);
INSERT INTO `colors` (`HEX`) VALUES(0xf57f17);
INSERT INTO `colors` (`HEX`) VALUES(0xf9a825);
INSERT INTO `colors` (`HEX`) VALUES(0xfb8c00);
INSERT INTO `colors` (`HEX`) VALUES(0xfbc02d);
INSERT INTO `colors` (`HEX`) VALUES(0xfdd835);
INSERT INTO `colors` (`HEX`) VALUES(0xff1744);
INSERT INTO `colors` (`HEX`) VALUES(0xff3d00);
INSERT INTO `colors` (`HEX`) VALUES(0xff4081);
INSERT INTO `colors` (`HEX`) VALUES(0xff5252);
INSERT INTO `colors` (`HEX`) VALUES(0xff5722);
INSERT INTO `colors` (`HEX`) VALUES(0xff6d00);
INSERT INTO `colors` (`HEX`) VALUES(0xff6e40);
INSERT INTO `colors` (`HEX`) VALUES(0xff6f00);
INSERT INTO `colors` (`HEX`) VALUES(0xff7043);
INSERT INTO `colors` (`HEX`) VALUES(0xff80ab);
INSERT INTO `colors` (`HEX`) VALUES(0xff8a65);
INSERT INTO `colors` (`HEX`) VALUES(0xff8a80);
INSERT INTO `colors` (`HEX`) VALUES(0xff8f00);
INSERT INTO `colors` (`HEX`) VALUES(0xff9100);
INSERT INTO `colors` (`HEX`) VALUES(0xff9800);
INSERT INTO `colors` (`HEX`) VALUES(0xff9e80);
INSERT INTO `colors` (`HEX`) VALUES(0xffa000);
INSERT INTO `colors` (`HEX`) VALUES(0xffa726);
INSERT INTO `colors` (`HEX`) VALUES(0xffab00);
INSERT INTO `colors` (`HEX`) VALUES(0xffab40);
INSERT INTO `colors` (`HEX`) VALUES(0xffb300);
INSERT INTO `colors` (`HEX`) VALUES(0xffb74d);
INSERT INTO `colors` (`HEX`) VALUES(0xffc107);
INSERT INTO `colors` (`HEX`) VALUES(0xffc400);
INSERT INTO `colors` (`HEX`) VALUES(0xffca28);
INSERT INTO `colors` (`HEX`) VALUES(0xffd180);
INSERT INTO `colors` (`HEX`) VALUES(0xffd54f);
INSERT INTO `colors` (`HEX`) VALUES(0xffd600);
INSERT INTO `colors` (`HEX`) VALUES(0xffd740);
INSERT INTO `colors` (`HEX`) VALUES(0xffe57f);
INSERT INTO `colors` (`HEX`) VALUES(0xffea00);
INSERT INTO `colors` (`HEX`) VALUES(0xffeb3b);
INSERT INTO `colors` (`HEX`) VALUES(0xffee58);
INSERT INTO `colors` (`HEX`) VALUES(0xfff176);
INSERT INTO `colors` (`HEX`) VALUES(0xffff00);
INSERT INTO `colors` (`HEX`) VALUES(0xffff8d);

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `ID` int(11) UNSIGNED NOT NULL,
  `USER_ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `TOKEN` char(12) CHARACTER SET ascii NOT NULL,
  `INTERNAL_STATE` enum('WAITING_QUOTE','DENIED','WAITING_PAYMENT','WAITING_ART','WAITING_BUYER_CONFIRM','WAITING_REVIEW','COMPLETE','CANCELED') CHARACTER SET ascii NOT NULL DEFAULT 'WAITING_QUOTE',
  `CHARACTER_ID_ARRAY` mediumtext CHARACTER SET ascii NOT NULL,
  `OPTION_ARRAY` mediumtext CHARACTER SET ascii NOT NULL,
  `FULL_REQUEST` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `SHIPPING_ADDR` mediumtext COLLATE utf8mb4_unicode_ci,
  `SHIPPING_INSTRUCTIONS` mediumtext COLLATE utf8mb4_unicode_ci,
  `NSFW` tinyint(1) UNSIGNED NOT NULL,
  `QUOTE_PRICE` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `QUOTE_USDEQ` decimal(6,2) UNSIGNED DEFAULT NULL,
  `QUOTE_MADE_AT` datetime DEFAULT NULL,
  `ARCHIVED_ARTIST` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ARCHIVED_BUYER` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commissions`:
--   `COMMISSION_TYPE_ID`
--       `commission_types` -> `ID`
--   `USER_ID`
--       `users` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_payments`
--

CREATE TABLE `commission_payments` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Identifier of the payment',
  `COMMISSION_ID` int(11) UNSIGNED NOT NULL COMMENT 'Corresponds to the commission which is being paid for',
  `COMMISSION_TYPE_PAYMENT_ID` int(11) UNSIGNED NOT NULL COMMENT 'Corresponds to the type of payemnt used',
  `TRANSACTION_ID` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Transaction ID (medium text because they may be long blobs of information, confirmation numbers, etc)',
  `AMOUNT` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The amount paid, with units. (May add one in USD for analytic reasons, but artist may have to do that (don''t want to burden/confuse commissioner, and exchange rates should be the same))',
  `PROOF_PATH` varchar(15) CHARACTER SET ascii DEFAULT NULL COMMENT 'Optional image for proving the transaction. Screenshot or similar.',
  `MADE_AT` datetime NOT NULL COMMENT 'When the payment was made (customizable by the commissioner)',
  `ARTIST_VERDICT` enum('DENIED','CONFIRMED') CHARACTER SET ascii DEFAULT NULL COMMENT 'Whether the artist has confirmed receipt or denied that it came'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payments for commissions';

--
-- RELATIONSHIPS FOR TABLE `commission_payments`:
--   `COMMISSION_ID`
--       `commissions` -> `ID`
--   `COMMISSION_TYPE_PAYMENT_ID`
--       `commission_type_payment_options` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_stages`
--

CREATE TABLE `commission_stages` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_STAGE_ID` int(11) UNSIGNED NOT NULL,
  `ESTIMATE` datetime DEFAULT NULL,
  `COMPLETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_stages`:
--   `COMMISSION_TYPE_STAGE_ID`
--       `commission_type_stages` -> `ID`
--   `COMMISSION_ID`
--       `commissions` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_types`
--

CREATE TABLE `commission_types` (
  `ID` int(11) UNSIGNED NOT NULL,
  `ARTIST_PAGE_ID` int(11) UNSIGNED NOT NULL,
  `TOKEN` char(8) CHARACTER SET ascii NOT NULL,
  `NAME` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BLURB` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DESCRIPTION` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `SORT` int(11) UNSIGNED NOT NULL,
  `BASE_COST` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BASE_USDEQ` decimal(6,2) NOT NULL,
  `ATTRS` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `PHYSICAL_ADDR_NEEDED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `OPEN` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_types`:
--   `ARTIST_PAGE_ID`
--       `artist_pages` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_attributes`
--

CREATE TABLE `commission_type_attributes` (
  `SET_KEY` varchar(13) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `GROUP` varchar(7) NOT NULL,
  `SORT` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- RELATIONSHIPS FOR TABLE `commission_type_attributes`:
--   `GROUP`
--       `commission_type_attribute_groups` -> `GROUP`
--

--
-- Dumping data for table `commission_type_attributes`
--

INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ABSTRACT', 'Abstract', 'Expresses the character in a non-standard way', 'STYLES', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ACCESSORY', 'Accessories', 'Harnesses, collars, etc.', 'FURSUIT', 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ADOPT', 'Adoptables', 'Pre-made characters', 'ART', 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ANIMATION', 'Animation', 'Animated artwork', 'TYPES', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ANTHRO', 'Anthropomorphic', 'Anthropomorphic or human-like characters', 'MISC', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('APPAREL', 'Apparel', 'Examples include hoodies, kigurumi, etc.', 'TYPES', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('AQUATIC', 'Aquatic', 'Aquatic', 'SPECIES', 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('AUDIO', 'Audio', 'Any type of audio, whether it be a tape, song, etc.', 'TYPES', 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('AVIAN', 'Avians', 'Birds and other flying animals', 'SPECIES', 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BABYFUR', 'Babyfur', 'Adult-baby characters (The chararacter must be over 18 for NSFW art)', 'NICHES', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BADGE', 'Badge', 'Typically a bust of a character and their name, can be worn at conventions', 'ART', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BEAR', 'Bear', 'Bears', 'SPECIES', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BODYSUIT', 'Bodysuit', 'The portion of the suit which covers the torso and legs', 'FURSUIT', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BONDAGE', 'Bondage', 'Anything including restraining a character', 'NICHES', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BUGS', 'Bugs', 'Bugs, such as insects', 'SPECIES', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('BUST', 'Bust', 'Shows the head, neck, and some chest/shoulders', 'ART', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CANDY_GORE', 'Candy Gore', 'This includes any type of gore except the blood-and-guts kind, such as fruit gore or crystal gore', 'NICHES', 10);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CANON', 'Canon', 'Canon characters, such as those from cartoons, Pokemon, etc.', 'SPECIES', 17);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CATS', 'Cats', 'Domesticated cats', 'SPECIES', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CELL_SHADING', 'Cell shading', 'Basic shading', 'ACTIONS', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CENTAURS', 'Centaurs', 'A character which has a body of a horce', 'SPECIES', 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CHIBI', 'Chibi', 'Small, cartoon-like characters with oversized heads', 'ART', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('COMIC', 'Comic', 'A set of pages containing one or more panels that tell a story', 'ART', 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('COUPLE', 'Couple', 'The art involves 2 characters, likely as a couple', 'MISC', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CRAFTING_ACT', 'Crafting', 'Crafting a physical object, like a plush', 'ACTIONS', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CRAFTING_TYPE', 'Crafting', 'Crafted objects, like a plushie or similar', 'TYPES', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CUB', 'Cub', 'Characters who are underage (Catalyst does not allow NSFW art of minors)', 'NICHES', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('CUSTOM', 'Custom', 'Suits custom-made to match a character', 'FURSUIT', 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('DEER', 'Deer', 'Deer', 'SPECIES', 10);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('DIGITAL', 'Digital Artwork', 'Artwork made on a computer', 'TYPES', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('DIGITIGRADE', 'Digitigrade', 'Legs with padding to look more like an animal', 'FURSUIT', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('DOGS', 'Dogs', 'Any domesticated dog, including examples like German Shepards, Beagles, etc.', 'SPECIES', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('DRAGON', 'Dragons', 'Dragons', 'SPECIES', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('EQUESTRIAN', 'Equestrians', 'Horses and similar', 'SPECIES', 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('EXPLICIT', 'Explicit', 'Anything containing sex toys or genetalia, including backsack', 'NSFW', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FAT', 'Fat/Obese', 'Fat and obese characters', 'NICHES', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FEET', 'Feetpaws', 'The shows/feet of the costume', 'FURSUIT', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FEMALE', 'Female', 'A female character', 'GENDER', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FERAL', 'Feral', 'Characters which are natural, do not stand on 2 legs, etc', 'MISC', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FLAT', 'Flat Color', '\"Bucket-fill\" art, contains no shading', 'ACTIONS', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FOXES', 'Foxes', 'Foxes, including Fennecs', 'SPECIES', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FULLBODY', 'Full-body', 'Shows the character\'s entire body', 'ART', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FULLSUIT', 'Fullsuit', 'Contains all the parts of the suit, including the body', 'FURSUIT', 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('FURSUITS', 'Fursuit', 'A costume similar to one of a mascot for a character', 'TYPES', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('GAME', 'Game', 'A custom-created game', 'TYPES', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('GORE', 'Gore', 'Anything which involves mutilation/gashes of a character, or anything similar to that, including death', 'NICHES', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HALFBODY', 'Half-body', 'Shows the character from the waist up', 'ART', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HEAD', 'Head', 'The head of the costume', 'FURSUIT', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HEADSHOT', 'Headshot', 'A piece of art detailing a character\'s head', 'ART', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HERM', 'Hermaphrodite', 'Contains parts of both female and males', 'GENDER', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HUMAN', 'Human', 'Human characters', 'MISC', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HYENA', 'Hyenas', 'Hyenas', 'SPECIES', 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HYPER', 'Hyper', 'Any character with certain body parts much larger than their normal size', 'NICHES', 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('HYPNOSIS', 'Hypnosis', 'Any form of mind control, including corruption, falls under this category', 'NICHES', 17);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ICONS', 'Icons', 'Art which is designed to be used as a profile picture', 'ART', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('INFLATION', 'Inflation', 'Forced feeding or otherwise inflating a character with a substance', 'NICHES', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('KEMONO', 'Kemono', '\"Anime-style\" art, originated in Japan', 'STYLES', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('LATEX_RUBBER', 'Latex/Rubber', 'Art in which latex or rubber may be drawn', 'NICHES', 18);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('LEGS', 'Legs', 'Just the legs of the costume', 'FURSUIT', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('LINEART', 'Lineart', 'Cleaned-up sketch, thick, definitive lines', 'ACTIONS', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MACHINE', 'Machines', 'Aeromorphs and other vehicle-related characters', 'SPECIES', 16);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MAICRO', 'Macro/Micro', 'Characters who are either giants (macro) or tiny (micro)', 'NICHES', 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MALE', 'Male', 'A male character', 'GENDER', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MANUFACTURING', 'Manufacturing', 'Includes anything like 3D-Printing', 'ACTIONS', 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MATURE', 'Mature', 'Anything which is suggestive or contains extreme fetishes', 'NSFW', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MULTIPLE_CHAR', 'Multiple Characters', 'The art may involve 3 or more characters', 'MISC', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MUSCLES', 'Muscles', 'Characters with defined or exaggerated muscles', 'NICHES', 16);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('MUSTELIDS', 'Mustelids', 'Includes weasels, otters, etc.', 'SPECIES', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ODOR', 'Odor/Musk', 'Any strong odors, including in the context of feet, pits, or musk in general', 'NICHES', 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('ORIG_SPECIES', 'Original Species', 'Other original species such as Dutch Angel Dragons, Protogens, etc.', 'SPECIES', 19);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER', 'Other', 'Something not listed in the other options', 'SPECIES', 22);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_ACTION', 'Other', 'Something not listed in the other options', 'ACTIONS', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_ART', 'Other', 'Something not listed in the other options', 'ART', 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_CANINE', 'Other Canine', 'Other animals from the canine family', 'SPECIES', 20);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_FELINE', 'Other Felines', 'Other felines like tigers, lions, etc.', 'SPECIES', 21);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_GENDER', 'Other', 'Something not listed in the other options', 'GENDER', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_STYLE', 'Other', 'Something not listed in the other options', 'STYLES', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_SUIT', 'Other', 'Something not listed in the other options', 'FURSUIT', 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('OTHER_TYPES', 'Other', 'Something not listed in the other options', 'TYPES', 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PAINTING', 'Painting', 'Either physically painting, or creating a similar effect digitally', 'ACTIONS', 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PARTIAL', 'Partial', 'Typically consists of the head, paws, and tail', 'FURSUIT', 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PARTS', 'Parts', 'Individual parts of the suit', 'FURSUIT', 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PAWS', 'Paws', 'The gloves of the costume', 'FURSUIT', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PAWS_NICHE', 'Paws', 'Anything in which a main aspect are feet', 'NICHES', 19);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PIXEL', 'Pixel Art', 'Art constructed from a small number of pixels (squares)', 'ART', 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PLANTIGRADE', 'Plantigrade', 'Flat, human-like legs', 'FURSUIT', 10);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PLUSH', 'Plushes', 'The drawing of a character to look like a plush', 'NICHES', 15);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('PREMADE', 'Pre-mades', 'Pre-made suits for sale', 'FURSUIT', 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('REALISTIC', 'Realistic', 'Shows the character as though it was real', 'STYLES', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('REF', 'Reference Sheets', 'Art which depicts multiple views of a character, showing all their aspects and markings', 'ART', 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('REPTILES', 'Reptiles', 'Reptiles like lizards, dinosuars, etc.', 'SPECIES', 15);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('RODENTS', 'Rodents', 'Rats, mice, etc.', 'SPECIES', 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SCAT', 'Scat', 'Anything with poop', 'NICHES', 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SCULPTING', 'Sculpting', 'Physically sculpting an object', 'ACTIONS', 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SEMI_REAL', 'Semi-realistic', 'Realistic but with a few cartoony elements', 'STYLES', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SERGALS', 'Sergals', 'The cheese-shaped fantasy animal', 'SPECIES', 18);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SFW', 'SFW', 'A peice with no sexual content or lightly suggestive', 'NSFW', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SHADED', 'Shaded', 'Fully shaded with gradients and smoothing', 'ACTIONS', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SKETCH', 'Sketch', 'A rough sketch', 'ACTIONS', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SM', 'Sadism/Masochism', 'Giving or recieving pain or otherwise asserting dominance over another character', 'NICHES', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SOLO', 'Solo', 'The art only involves may only involve one character', 'MISC', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('STICKERS', 'Stickers', 'A set of busts and half-bodies which show expressions', 'ART', 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('SURREAL', 'Surreal', 'Shows a surreal representation of the character', 'STYLES', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('TAIL', 'Tail', 'The tail', 'FURSUIT', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('TF', 'Transformation', 'A character being transformed into another species or similar, may be through magic or other means', 'NICHES', 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('TOONY', 'Cartoony', 'Typically contains non-realistic qualities like large, flat eyes, flat colors, etc.', 'STYLES', 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('TRADITIONAL', 'Traditional Artwork', 'Hand drawn or painted', 'TYPES', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('TRANS', 'Transgender', 'Someone who either feels they have no gender (genderless) or do not fit the one they were born with', 'GENDER', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('URINE', 'Urine', 'Typical relating to watersports, this involves anything with urine', 'NICHES', 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('VORE', 'Vore', 'Consuming of another character through any means', 'NICHES', 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('WOLVES', 'Wolves', 'Wolves, including derivatives like Dingoes', 'SPECIES', 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('WRITING', 'Writing', 'Stories and other literature', 'TYPES', 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP`, `SORT`) VALUES('YCH', 'YCH', 'Your Character Here - pre-made lineart which will be colored to fit a custom character', 'ART', 10);

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_attribute_groups`
--

CREATE TABLE `commission_type_attribute_groups` (
  `GROUP` varchar(7) NOT NULL,
  `LABEL` varchar(255) NOT NULL,
  `SORT` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- RELATIONSHIPS FOR TABLE `commission_type_attribute_groups`:
--

--
-- Dumping data for table `commission_type_attribute_groups`
--

INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('ACTIONS', 'Further Classification of Commissions', 4);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('ART', 'Specific Classifications of Art', 1);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('FURSUIT', 'Fursuit Parts and Accessories', 2);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('GENDER', 'Genders', 6);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('MISC', 'Miscellaneous', 9);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('NICHES', 'Certain Niche Types of Art', 8);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('NSFW', 'Whether the Commission is Explicit, Safe, or Suggestive', 7);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('SPECIES', 'Types of Species That Fit This Commission', 5);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('STYLES', 'Art and Craft Styles', 3);
INSERT INTO `commission_type_attribute_groups` (`GROUP`, `LABEL`, `SORT`) VALUES('TYPES', 'General Type of Commission', 0);

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_images`
--

CREATE TABLE `commission_type_images` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `CAPTION` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PATH` varchar(15) CHARACTER SET ascii NOT NULL,
  `NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `PRIMARY` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_type_images`:
--   `COMMISSION_TYPE_ID`
--       `commission_types` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_modifiers`
--

CREATE TABLE `commission_type_modifiers` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `NAME` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PRICE` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `USDEQ` decimal(6,2) UNSIGNED NOT NULL,
  `GROUP` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `MULTIPLE` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_type_modifiers`:
--   `COMMISSION_TYPE_ID`
--       `commission_types` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_payment_options`
--

CREATE TABLE `commission_type_payment_options` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `TYPE` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ADDRESS` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `INSTRUCTIONS` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_type_payment_options`:
--   `COMMISSION_TYPE_ID`
--       `commission_types` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_stages`
--

CREATE TABLE `commission_type_stages` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `STAGE` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_type_stages`:
--   `COMMISSION_TYPE_ID`
--       `commission_types` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_wips`
--

CREATE TABLE `commission_wips` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_ID` int(11) UNSIGNED NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `BODY` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `PATH` varchar(15) CHARACTER SET ascii DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `commission_wips`:
--   `COMMISSION_ID`
--       `commissions` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_list`
--

CREATE TABLE `email_list` (
  `EMAIL` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CONTEXT` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `email_list`:
--

-- --------------------------------------------------------

--
-- Table structure for table `feature_board_comments`
--

CREATE TABLE `feature_board_comments` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) UNSIGNED NOT NULL,
  `FEATURE_ID` int(11) UNSIGNED NOT NULL,
  `BODY` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `feature_board_comments`:
--   `USER_ID`
--       `users` -> `ID`
--   `FEATURE_ID`
--       `feature_board_items` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `feature_board_groups`
--

CREATE TABLE `feature_board_groups` (
  `INTERNAL_NAME` varchar(255) CHARACTER SET ascii NOT NULL,
  `NAME` varchar(255) CHARACTER SET ascii NOT NULL,
  `SORT` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `feature_board_groups`:
--

--
-- Dumping data for table `feature_board_groups`
--

INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('ACCOUNTS', 'Authentication and accounts', 1);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('ARTIST', 'Artist\'s Pages', 3);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('CHARACTERS', 'Characters', 2);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('COMMISSIONS', 'Commissions', 5);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('COMMISSION_TYPES', 'Commission Types', 4);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('HOME', 'Home/Browsing', 7);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('MISC', 'Miscellaneous', 99);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('MISC_BUGS', 'Bugs', 98);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('MISC_INTERFACE', 'Interface issues', 97);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('SEARCHING', 'Searching', 6);
INSERT INTO `feature_board_groups` (`INTERNAL_NAME`, `NAME`, `SORT`) VALUES('WRITING', 'Writing-related items', 0);

-- --------------------------------------------------------

--
-- Table structure for table `feature_board_items`
--

CREATE TABLE `feature_board_items` (
  `ID` int(11) UNSIGNED NOT NULL,
  `NAME` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `AUTOGEN_URL` varchar(64) CHARACTER SET ascii NOT NULL,
  `CREATED_TS` datetime NOT NULL,
  `AUTHOR_ID` int(11) UNSIGNED DEFAULT NULL,
  `STATUS` varchar(255) CHARACTER SET ascii NOT NULL DEFAULT 'AWAITING_REVIEW',
  `GROUP` varchar(255) CHARACTER SET ascii NOT NULL,
  `INTRODUCTION` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `PROPOSAL` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ACKNOWLEDGEMENT` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `FUTURE_SCOPE` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `DEVELOPER_NOTE` mediumtext COLLATE utf8mb4_unicode_ci,
  `ESTIMATED_MANHOURS` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `feature_board_items`:
--   `AUTHOR_ID`
--       `users` -> `ID`
--   `STATUS`
--       `feature_board_statuses` -> `INTERNAL_NAME`
--   `GROUP`
--       `feature_board_groups` -> `INTERNAL_NAME`
--

-- --------------------------------------------------------

--
-- Table structure for table `feature_board_statuses`
--

CREATE TABLE `feature_board_statuses` (
  `INTERNAL_NAME` varchar(255) CHARACTER SET ascii NOT NULL,
  `NAME` varchar(255) CHARACTER SET ascii NOT NULL,
  `DESCRIPTION` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `SORT` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `feature_board_statuses`:
--

--
-- Dumping data for table `feature_board_statuses`
--

INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('AWAITING_REVIEW', 'Awaiting review', 'During this stage, you may be contacted and various edits to your proposal may be made.  Afterwards, if your proposal is not a duplicate and meets various other criteria, it will become listed on the feature board.', 0);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('COMPLETED', 'Completed', 'The feature has been implemented into Catalyst.  Thanks for your contribution!', 7);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('DENIED', 'Denied', 'The proposal was denied.  This could be due to a lack of support, simple impossibility, or for going against Catalyst\'s mission.', 2);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('DEVEL', 'Development', 'This feature is in active development!  Expect to see progress very soon!', 6);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('PLANNING', 'Planning', 'The feature\'s in-depth details are being worked out and investigated.  This is the stage right before active development!', 5);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('QUEUE', 'Queued', 'Your proposal has made it! Congratulations!  Now, wait for it to be developed.  It\'s currently in the queue, however has a low priority compared to other features.', 3);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('QUEUE_HP', 'Important Queue', 'Your proposal has made it! Congratulations!  Now, wait for it to be developed.  It\'s of a high priority so will be completed before other less-important features.', 4);
INSERT INTO `feature_board_statuses` (`INTERNAL_NAME`, `NAME`, `DESCRIPTION`, `SORT`) VALUES('VOTE', 'Voting', 'Members of the community may vote and comment on this feature and share feedback.  During such time the proposal may be edited for clarification\'s sake.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feature_board_votes`
--

CREATE TABLE `feature_board_votes` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) UNSIGNED NOT NULL,
  `FEATURE_ID` int(11) UNSIGNED NOT NULL,
  `VOTE_TYPE` enum('YES','NO','MAYBE','IRRELEVANT') CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `feature_board_votes`:
--   `USER_ID`
--       `users` -> `ID`
--   `FEATURE_ID`
--       `feature_board_items` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `ID` int(11) UNSIGNED NOT NULL,
  `FROM_ID` int(11) UNSIGNED DEFAULT NULL,
  `TO_ID` int(11) UNSIGNED NOT NULL,
  `FROM_TYPE` enum('SYSTEM','USER','ARTIST') CHARACTER SET ascii NOT NULL,
  `TO_TYPE` enum('USER','ARTIST') CHARACTER SET ascii NOT NULL,
  `REPLY_TO` int(11) UNSIGNED DEFAULT NULL,
  `CONCERNING_COMMISSION` int(11) UNSIGNED DEFAULT NULL,
  `SENT_AT` datetime NOT NULL,
  `READ` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `BODY` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `messages`:
--   `FROM_ID`
--       `users` -> `ID`
--   `TO_ID`
--       `users` -> `ID`
--   `CONCERNING_COMMISSION`
--       `commissions` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `social_media_meta`
--

CREATE TABLE `social_media_meta` (
  `SORT_ORDER` int(2) UNSIGNED NOT NULL DEFAULT '0',
  `VISIBLE` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `INTEGRATION_NAME` varchar(12) NOT NULL,
  `IMAGE_PATH` varchar(16) NOT NULL,
  `DEFAULT_HUMAN_NAME` varchar(18) NOT NULL,
  `CHIP_CLASSES` varchar(31) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- RELATIONSHIPS FOR TABLE `social_media_meta`:
--

--
-- Dumping data for table `social_media_meta`
--

INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(26, 1, '500PX', '500px.png', '500px', 'black white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(2, 1, 'AMINO', 'amino.png', 'Furry Amino', 'cyan white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(31, 1, 'AO3', 'ao3.png', 'Archive of Our Own', 'red darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(24, 1, 'BANDCAMP', 'bandcamp.png', 'Bandcamp', 'teal lighten-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(33, 0, 'CUSTOM', 'custom.png', 'Custom Link', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(6, 1, 'DEVIANTART', 'deviantart.png', 'DeviantArt', 'green white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(30, 1, 'DISCORD', 'discord.png', 'Discord', 'discord-blurple white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(34, 0, 'DOMAIN', 'domain.png', 'Custom Website', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(32, 1, 'EMAIL', 'email.png', 'Email', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(28, 1, 'ETSY', 'etsy.png', 'Etsy', 'orange darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(11, 1, 'FACEBOOK', 'fb.png', 'Facebook', 'blue darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(31, 1, 'FANFICTION', 'ff.png', 'FanFiction', 'indigo darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(1, 1, 'FURAFFINITY', 'fa.png', 'Fur Affinity', 'orange white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(5, 1, 'FURRYNETWORK', 'furrynetwork.png', 'Furry Network', 'blue darken-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(14, 1, 'GPLUS', 'gplus.png', 'Google+', 'red darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(3, 1, 'INKBUNNY', 'inkbunny.png', 'Inkbunny', 'light-green darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(10, 1, 'INSTAGRAM', 'ig.png', 'Instagram', 'pink accent-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(21, 1, 'KOFI', 'ko-fi.png', 'Ko-fi', 'cyan darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(0, 0, 'ONEDRIVE', 'onedrive.png', 'OneDrive', 'blue darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(20, 1, 'PATREON', 'patreon.png', 'Patreon', 'deep-orange accent-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(22, 1, 'PAYPAL', 'paypal.png', 'PayPal', 'blue darken-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(18, 1, 'PERISCOPE', 'periscope.png', 'Periscope', 'cyan darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(16, 1, 'PICARTO', 'picarto.png', 'Picarto.TV', 'blue-grey darken-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(27, 1, 'PINTEREST', 'pintrest.png', 'Pinterest', 'red darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(9, 1, 'REDDIT', 'reddit.png', 'Reddit', 'reddit-orangered white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(0, 1, 'SELF', 'self.png', 'Catalyst', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(12, 1, 'SNAPCHAT', 'snap.png', 'Snapchat', 'yellow darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(4, 1, 'SOFURRY', 'sofurry.png', 'SoFurry', 'brown darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(23, 1, 'SOUNDCLOUD', 'soundcloud.png', 'SoundCloud', 'deep-orange darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(25, 1, 'SPOTIFY', 'spotify.png', 'Spotify', 'green accent-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(31, 1, 'STEAM', 'steam.png', 'Steam', 'black white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(29, 1, 'TELEGRAM', 'telegram.png', 'Telegram', 'light-blue white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(0, 0, 'TRELLO', 'trello.png', 'Trello', 'light-blue darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(13, 1, 'TUMBLR', 'tumblr.png', 'Tumblr', 'tumblr-blue white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(17, 1, 'TWITCH', 'twitch.png', 'Twitch', 'deep-purple darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(8, 1, 'TWITTER', 'twitter.png', 'Twitter', 'blue lighten-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(19, 1, 'VIMEO', 'vimeo.png', 'Vimeo', 'light-blue white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(7, 1, 'WEASYL', 'weasyl.png', 'Weasyl', 'weasyl-murroon white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(15, 1, 'YOUTUBE', 'youtube.png', 'YouTube', 'shitty-youtube-red white-text');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) UNSIGNED NOT NULL,
  `FILE_TOKEN` char(10) CHARACTER SET ascii DEFAULT NULL,
  `USERNAME` varchar(64) CHARACTER SET ascii NOT NULL,
  `HASHED_PASSWORD` varchar(60) CHARACTER SET ascii NOT NULL,
  `PASSWORD_RESET_TOKEN` varchar(16) CHARACTER SET ascii NOT NULL,
  `TOTP_KEY` binary(10) DEFAULT NULL,
  `TOTP_RESET_TOKEN` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EMAIL` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EMAIL_VERIFIED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `EMAIL_TOKEN` varchar(12) CHARACTER SET ascii NOT NULL,
  `ARTIST_PAGE_ID` int(11) UNSIGNED DEFAULT NULL,
  `PICTURE_LOC` varchar(15) CHARACTER SET ascii DEFAULT NULL,
  `PICTURE_NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `COLOR` binary(3) NOT NULL DEFAULT '^ ',
  `NICK` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mistake',
  `SUSPENDED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `DEACTIVATED` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--   `COLOR`
--       `colors` -> `HEX`
--   `ARTIST_PAGE_ID`
--       `artist_pages` -> `ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_social_media`
--

CREATE TABLE `user_social_media` (
  `ID` int(11) UNSIGNED NOT NULL,
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `USER_ID` int(11) UNSIGNED DEFAULT NULL,
  `NETWORK` varchar(12) CHARACTER SET ascii NOT NULL,
  `SERVICE_URL` mediumtext COLLATE utf8mb4_unicode_ci,
  `DISP_NAME` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `user_social_media`:
--   `USER_ID`
--       `users` -> `ID`
--   `NETWORK`
--       `social_media_meta` -> `INTEGRATION_NAME`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_wishlists`
--

CREATE TABLE `user_wishlists` (
  `ID` int(11) UNSIGNED NOT NULL,
  `USER_ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `user_wishlists`:
--   `USER_ID`
--       `users` -> `ID`
--   `COMMISSION_TYPE_ID`
--       `commission_types` -> `ID`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_authorizations`
--
ALTER TABLE `api_authorizations`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ACCESS_TOKEN` (`ACCESS_TOKEN`),
  ADD KEY `API_ID` (`API_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `NAME` (`NAME`),
  ADD UNIQUE KEY `CLIENT_ID` (`CLIENT_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `artist_pages`
--
ALTER TABLE `artist_pages`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USER` (`USER_ID`, `DELETED`),
  ADD UNIQUE KEY `URL_DELETED` (`URL`, `DELETED`),
  ADD KEY `DELETED` (`DELETED`),

--
-- Indexes for table `artist_social_media`
--
ALTER TABLE `artist_social_media`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PAGE_ID` (`ARTIST_ID`),
  ADD KEY `NETWORK` (`NETWORK`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `artist_streaming_integrations`
--
ALTER TABLE `artist_streaming_integrations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`ARTIST_ID`),
  ADD KEY `IS_STREAMING` (`IS_STREAMING`),
  ADD KEY `SERVICE` (`SERVICE`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `NAME` (`NAME`),
  ADD KEY `COLOR` (`COLOR`);

--
-- Indexes for table `character_images`
--
ALTER TABLE `character_images`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CHARACTER_ID` (`CHARACTER_ID`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD UNIQUE KEY `HEX` (`HEX`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `INTERNAL_STATE` (`INTERNAL_STATE`),
  ADD KEY `NSFW` (`NSFW`),
  ADD KEY `ARCHIVED_ARTIST` (`ARCHIVED_ARTIST`),
  ADD KEY `ARCHIVED_BUYER` (`ARCHIVED_BUYER`);

--
-- Indexes for table `commission_payments`
--
ALTER TABLE `commission_payments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_ID` (`COMMISSION_ID`),
  ADD KEY `COMMISSION_TYPE_PAYMENT_ID` (`COMMISSION_TYPE_PAYMENT_ID`),
  ADD KEY `ARTIST_VERDICT` (`ARTIST_VERDICT`);

--
-- Indexes for table `commission_stages`
--
ALTER TABLE `commission_stages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_ID` (`COMMISSION_ID`),
  ADD KEY `COMMISSION_TYPE_STAGE_ID` (`COMMISSION_TYPE_STAGE_ID`),
  ADD KEY `ESTIMATE` (`ESTIMATE`),
  ADD KEY `COMPLETED` (`COMPLETED`),
  ADD KEY `ESTIMATE_2` (`ESTIMATE`,`COMPLETED`);

--
-- Indexes for table `commission_types`
--
ALTER TABLE `commission_types`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ARTIST_PAGE_ID` (`ARTIST_PAGE_ID`),
  ADD KEY `SORT` (`SORT`),
  ADD KEY `BASE_USDEQ` (`BASE_USDEQ`),
  ADD KEY `DELETED` (`DELETED`);

--
-- Indexes for table `commission_type_attributes`
--
ALTER TABLE `commission_type_attributes`
  ADD PRIMARY KEY (`SET_KEY`),
  ADD KEY `NAME` (`NAME`),
  ADD KEY `GROUP` (`GROUP`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `commission_type_attribute_groups`
--
ALTER TABLE `commission_type_attribute_groups`
  ADD PRIMARY KEY (`GROUP`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `commission_type_images`
--
ALTER TABLE `commission_type_images`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `PRIMARY_2` (`PRIMARY`);

--
-- Indexes for table `commission_type_modifiers`
--
ALTER TABLE `commission_type_modifiers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `USDEQ` (`USDEQ`),
  ADD KEY `DELETED` (`DELETED`);

--
-- Indexes for table `commission_type_payment_options`
--
ALTER TABLE `commission_type_payment_options`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TYPE` (`TYPE`),
  ADD KEY `DELETED` (`DELETED`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`);

--
-- Indexes for table `commission_type_stages`
--
ALTER TABLE `commission_type_stages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `DELETED` (`DELETED`);

--
-- Indexes for table `commission_wips`
--
ALTER TABLE `commission_wips`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_ID` (`COMMISSION_ID`),
  ADD KEY `CREATED_AT` (`CREATED_AT`);

--
-- Indexes for table `email_list`
--
ALTER TABLE `email_list`
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `feature_board_comments`
--
ALTER TABLE `feature_board_comments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FEATURE_ID` (`FEATURE_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `feature_board_groups`
--
ALTER TABLE `feature_board_groups`
  ADD PRIMARY KEY (`INTERNAL_NAME`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `feature_board_items`
--
ALTER TABLE `feature_board_items`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `AUTOGEN_URL` (`AUTOGEN_URL`),
  ADD KEY `STATUS` (`STATUS`),
  ADD KEY `AUTHOR_ID` (`AUTHOR_ID`),
  ADD KEY `GROUP` (`GROUP`);

--
-- Indexes for table `feature_board_statuses`
--
ALTER TABLE `feature_board_statuses`
  ADD PRIMARY KEY (`INTERNAL_NAME`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `feature_board_votes`
--
ALTER TABLE `feature_board_votes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FEATURE_ID` (`FEATURE_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FROM_ID` (`FROM_ID`),
  ADD KEY `TO_ID` (`TO_ID`),
  ADD KEY `FROM_TYPE` (`FROM_TYPE`),
  ADD KEY `TO_TYPE` (`TO_TYPE`),
  ADD KEY `REPLY_TO` (`REPLY_TO`),
  ADD KEY `CONCERNING_COMMISSION` (`CONCERNING_COMMISSION`),
  ADD KEY `READ` (`READ`);

--
-- Indexes for table `social_media_meta`
--
ALTER TABLE `social_media_meta`
  ADD PRIMARY KEY (`INTEGRATION_NAME`),
  ADD UNIQUE KEY `INTEGRATION_NAME` (`INTEGRATION_NAME`),
  ADD KEY `IMAGE_PATH` (`IMAGE_PATH`),
  ADD KEY `SORT_ORDER` (`SORT_ORDER`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`),
  ADD UNIQUE KEY `COMM_PAGE_ID` (`ARTIST_PAGE_ID`) USING BTREE,
  ADD UNIQUE KEY `FILE_TOKEN` (`FILE_TOKEN`),
  ADD KEY `COLOR` (`COLOR`);

--
-- Indexes for table `user_social_media`
--
ALTER TABLE `user_social_media`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PAGE_ID` (`USER_ID`),
  ADD KEY `NETWORK` (`NETWORK`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_authorizations`
--
ALTER TABLE `api_authorizations`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artist_pages`
--
ALTER TABLE `artist_pages`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each artist page';

--
-- AUTO_INCREMENT for table `artist_social_media`
--
ALTER TABLE `artist_social_media`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each entry';

--
-- AUTO_INCREMENT for table `artist_streaming_integrations`
--
ALTER TABLE `artist_streaming_integrations`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID of the service integration';

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier of the character';

--
-- AUTO_INCREMENT for table `character_images`
--
ALTER TABLE `character_images`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the image';

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_payments`
--
ALTER TABLE `commission_payments`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identifier of the payment';

--
-- AUTO_INCREMENT for table `commission_stages`
--
ALTER TABLE `commission_stages`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_types`
--
ALTER TABLE `commission_types`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_type_images`
--
ALTER TABLE `commission_type_images`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_type_modifiers`
--
ALTER TABLE `commission_type_modifiers`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_type_payment_options`
--
ALTER TABLE `commission_type_payment_options`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_type_stages`
--
ALTER TABLE `commission_type_stages`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_wips`
--
ALTER TABLE `commission_wips`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feature_board_comments`
--
ALTER TABLE `feature_board_comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feature_board_items`
--
ALTER TABLE `feature_board_items`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feature_board_votes`
--
ALTER TABLE `feature_board_votes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_social_media`
--
ALTER TABLE `user_social_media`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_authorizations`
--
ALTER TABLE `api_authorizations`
  ADD CONSTRAINT `api_authorizations_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `api_authorizations_ibfk_2` FOREIGN KEY (`API_ID`) REFERENCES `api_keys` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `artist_pages`
--
ALTER TABLE `artist_pages`
  ADD CONSTRAINT `artist_pages_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `artist_pages_ibfk_2` FOREIGN KEY (`COLOR`) REFERENCES `colors` (`HEX`);

--
-- Constraints for table `artist_social_media`
--
ALTER TABLE `artist_social_media`
  ADD CONSTRAINT `artist_social_media_ibfk_1` FOREIGN KEY (`NETWORK`) REFERENCES `social_media_meta` (`INTEGRATION_NAME`),
  ADD CONSTRAINT `artist_social_media_ibfk_2` FOREIGN KEY (`ARTIST_ID`) REFERENCES `artist_pages` (`ID`);

--
-- Constraints for table `artist_streaming_integrations`
--
ALTER TABLE `artist_streaming_integrations`
  ADD CONSTRAINT `artist_streaming_integrations_ibfk_2` FOREIGN KEY (`SERVICE`) REFERENCES `social_media_meta` (`INTEGRATION_NAME`);

--
-- Constraints for table `characters`
--
ALTER TABLE `characters`
  ADD CONSTRAINT `characters_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `characters_ibfk_2` FOREIGN KEY (`COLOR`) REFERENCES `colors` (`HEX`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `character_images`
--
ALTER TABLE `character_images`
  ADD CONSTRAINT `character_images_ibfk_1` FOREIGN KEY (`CHARACTER_ID`) REFERENCES `characters` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_ibfk_1` FOREIGN KEY (`COMMISSION_TYPE_ID`) REFERENCES `commission_types` (`ID`),
  ADD CONSTRAINT `commissions_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `commission_payments`
--
ALTER TABLE `commission_payments`
  ADD CONSTRAINT `commission_payments_ibfk_1` FOREIGN KEY (`COMMISSION_ID`) REFERENCES `commissions` (`ID`),
  ADD CONSTRAINT `commission_payments_ibfk_2` FOREIGN KEY (`COMMISSION_TYPE_PAYMENT_ID`) REFERENCES `commission_type_payment_options` (`ID`);

--
-- Constraints for table `commission_stages`
--
ALTER TABLE `commission_stages`
  ADD CONSTRAINT `commission_stages_ibfk_1` FOREIGN KEY (`COMMISSION_TYPE_STAGE_ID`) REFERENCES `commission_type_stages` (`ID`),
  ADD CONSTRAINT `commission_stages_ibfk_2` FOREIGN KEY (`COMMISSION_ID`) REFERENCES `commissions` (`ID`);

--
-- Constraints for table `commission_types`
--
ALTER TABLE `commission_types`
  ADD CONSTRAINT `commission_types_ibfk_1` FOREIGN KEY (`ARTIST_PAGE_ID`) REFERENCES `artist_pages` (`ID`);

--
-- Constraints for table `commission_type_attributes`
--
ALTER TABLE `commission_type_attributes`
  ADD CONSTRAINT `commission_type_attributes_ibfk_1` FOREIGN KEY (`GROUP`) REFERENCES `commission_type_attribute_groups` (`GROUP`);

--
-- Constraints for table `commission_type_images`
--
ALTER TABLE `commission_type_images`
  ADD CONSTRAINT `commission_type_images_ibfk_1` FOREIGN KEY (`COMMISSION_TYPE_ID`) REFERENCES `commission_types` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `commission_type_modifiers`
--
ALTER TABLE `commission_type_modifiers`
  ADD CONSTRAINT `commission_type_modifiers_ibfk_1` FOREIGN KEY (`COMMISSION_TYPE_ID`) REFERENCES `commission_types` (`ID`);

--
-- Constraints for table `commission_type_payment_options`
--
ALTER TABLE `commission_type_payment_options`
  ADD CONSTRAINT `commission_type_payment_options_ibfk_1` FOREIGN KEY (`COMMISSION_TYPE_ID`) REFERENCES `commission_types` (`ID`);

--
-- Constraints for table `commission_type_stages`
--
ALTER TABLE `commission_type_stages`
  ADD CONSTRAINT `commission_type_stages_ibfk_1` FOREIGN KEY (`COMMISSION_TYPE_ID`) REFERENCES `commission_types` (`ID`);

--
-- Constraints for table `commission_wips`
--
ALTER TABLE `commission_wips`
  ADD CONSTRAINT `commission_wips_ibfk_1` FOREIGN KEY (`COMMISSION_ID`) REFERENCES `commissions` (`ID`);

--
-- Constraints for table `feature_board_comments`
--
ALTER TABLE `feature_board_comments`
  ADD CONSTRAINT `feature_board_comments_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `feature_board_comments_ibfk_2` FOREIGN KEY (`FEATURE_ID`) REFERENCES `feature_board_items` (`ID`);

--
-- Constraints for table `feature_board_items`
--
ALTER TABLE `feature_board_items`
  ADD CONSTRAINT `feature_board_items_ibfk_1` FOREIGN KEY (`AUTHOR_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `feature_board_items_ibfk_2` FOREIGN KEY (`STATUS`) REFERENCES `feature_board_statuses` (`INTERNAL_NAME`),
  ADD CONSTRAINT `feature_board_items_ibfk_3` FOREIGN KEY (`GROUP`) REFERENCES `feature_board_groups` (`INTERNAL_NAME`);

--
-- Constraints for table `feature_board_votes`
--
ALTER TABLE `feature_board_votes`
  ADD CONSTRAINT `feature_board_votes_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `feature_board_votes_ibfk_2` FOREIGN KEY (`FEATURE_ID`) REFERENCES `feature_board_items` (`ID`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`FROM_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`TO_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`CONCERNING_COMMISSION`) REFERENCES `commissions` (`ID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`COLOR`) REFERENCES `colors` (`HEX`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`ARTIST_PAGE_ID`) REFERENCES `artist_pages` (`ID`);

--
-- Constraints for table `user_social_media`
--
ALTER TABLE `user_social_media`
  ADD CONSTRAINT `user_social_media_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `user_social_media_ibfk_2` FOREIGN KEY (`NETWORK`) REFERENCES `social_media_meta` (`INTEGRATION_NAME`);

--
-- Constraints for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  ADD CONSTRAINT `user_wishlists_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `user_wishlists_ibfk_2` FOREIGN KEY (`COMMISSION_TYPE_ID`) REFERENCES `commission_types` (`ID`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
