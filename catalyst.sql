-- GENERATED AS OF 6c9c013da5e33f01c3b4703b87e95d9d5ff63038
-- SEE databas_structure_modifications.yaml

-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 07, 2019 at 06:54 PM
-- Server version: 10.3.13-MariaDB-2
-- PHP Version: 7.3.4-1+ubuntu18.04.1+deb.sury.org+3

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
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'If the artist is deleted.  Artist''s have a special ability to "undelete" their pages - rather than forcing them to contact us to create another one if they take it down, this is easier.  When they delete it, all their commission types and all are also marked deleted.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the information which pertains to an artist page datatype.\nThis is an "addition" to a user account - each artist is a user (USER_ID)';

-- --------------------------------------------------------

--
-- Table structure for table `artist_social_media`
--

CREATE TABLE `artist_social_media` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique identifier for each entry',
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Sort order for the row, used to allow the artist to customize the ordering (as not to remove all and reinsert)',
  `ARTIST_ID` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID of the artist the row is for',
  `NETWORK` varchar(12) CHARACTER SET ascii NOT NULL COMMENT 'The network which the artist is making an entry for.  Relates to `social_media_meta`.`INTEGRAION_NAME`',
  `SERVICE_URL` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL of the service.  MEDIUMTEXT as it could be longer than 255 characters (some places _really_ need to get their crap together)',
  `DISP_NAME` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User-customizable label for the chip'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Social media entries for artists.  Displayed as chips on their profile.';

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
  `URL` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL to access the stream',
  `IS_STREAMING` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Whether the artist is currently streaming',
  `CACHE_TS` datetime DEFAULT current_timestamp() COMMENT 'Last time we checked if the artist was streaming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Special streaming services for artist''s profiles.';

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
  `PUBLIC` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Whether the character is publicly accessible',
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'If the character is deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table which contains all of a user''s characters';

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
  `NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'If the image is nsfw',
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Image sort'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Images of a character';

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `HEX` binary(3) NOT NULL COMMENT '6 character hex'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores all valid colors (needed for FKs)';

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
INSERT INTO `colors` (`HEX`) VALUES(0x40e0d0);
INSERT INTO `colors` (`HEX`) VALUES(0x424242);
INSERT INTO `colors` (`HEX`) VALUES(0x42a5f5);
INSERT INTO `colors` (`HEX`) VALUES(0x43a047);
INSERT INTO `colors` (`HEX`) VALUES(0x4527a0);
INSERT INTO `colors` (`HEX`) VALUES(0x455a64);
INSERT INTO `colors` (`HEX`) VALUES(0x4a148c);
INSERT INTO `colors` (`HEX`) VALUES(0x4caf50);
INSERT INTO `colors` (`HEX`) VALUES(0x4db6ac);
INSERT INTO `colors` (`HEX`) VALUES(0x4dd0e1);
INSERT INTO `colors` (`HEX`) VALUES(0x4e342e);
INSERT INTO `colors` (`HEX`) VALUES(0x4fc3f7);
INSERT INTO `colors` (`HEX`) VALUES(0x512da8);
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
INSERT INTO `colors` (`HEX`) VALUES(0x651fff);
INSERT INTO `colors` (`HEX`) VALUES(0x66bb6a);
INSERT INTO `colors` (`HEX`) VALUES(0x673ab7);
INSERT INTO `colors` (`HEX`) VALUES(0x689f38);
INSERT INTO `colors` (`HEX`) VALUES(0x6a1b9a);
INSERT INTO `colors` (`HEX`) VALUES(0x6d4c41);
INSERT INTO `colors` (`HEX`) VALUES(0x757575);
INSERT INTO `colors` (`HEX`) VALUES(0x76ff03);
INSERT INTO `colors` (`HEX`) VALUES(0x78909c);
INSERT INTO `colors` (`HEX`) VALUES(0x795548);
INSERT INTO `colors` (`HEX`) VALUES(0x7986cb);
INSERT INTO `colors` (`HEX`) VALUES(0x7b1fa2);
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
INSERT INTO `colors` (`HEX`) VALUES(0xe53935);
INSERT INTO `colors` (`HEX`) VALUES(0xe57373);
INSERT INTO `colors` (`HEX`) VALUES(0xe64a19);
INSERT INTO `colors` (`HEX`) VALUES(0xe65100);
INSERT INTO `colors` (`HEX`) VALUES(0xe91e63);
INSERT INTO `colors` (`HEX`) VALUES(0xea80fc);
INSERT INTO `colors` (`HEX`) VALUES(0xec407a);
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
INSERT INTO `colors` (`HEX`) VALUES(0xff5722);
INSERT INTO `colors` (`HEX`) VALUES(0xff6d00);
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
INSERT INTO `colors` (`HEX`) VALUES(0xffb300);
INSERT INTO `colors` (`HEX`) VALUES(0xffb74d);
INSERT INTO `colors` (`HEX`) VALUES(0xffc107);
INSERT INTO `colors` (`HEX`) VALUES(0xffc400);
INSERT INTO `colors` (`HEX`) VALUES(0xffca28);
INSERT INTO `colors` (`HEX`) VALUES(0xffd180);
INSERT INTO `colors` (`HEX`) VALUES(0xffd600);
INSERT INTO `colors` (`HEX`) VALUES(0xffe57f);
INSERT INTO `colors` (`HEX`) VALUES(0xffea00);
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
  `SHIPPING_ADDR` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SHIPPING_INSTRUCTIONS` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NSFW` tinyint(1) UNSIGNED NOT NULL,
  `QUOTE_PRICE` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `QUOTE_USDEQ` decimal(8,2) UNSIGNED DEFAULT NULL,
  `QUOTE_MADE_AT` datetime DEFAULT NULL,
  `ARCHIVED_ARTIST` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `ARCHIVED_BUYER` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `BASE_USD_COST` decimal(9,4) UNSIGNED NOT NULL,
  `ATTRS` mediumtext CHARACTER SET ascii NOT NULL,
  `ACCEPTING_QUOTES` tinyint(1) NOT NULL DEFAULT 1,
  `ACCEPTING_REQUESTS` tinyint(1) NOT NULL DEFAULT 1,
  `ACCEPTING_TRADES` tinyint(1) NOT NULL DEFAULT 1,
  `ACCEPTING_COMMISSIONS` tinyint(1) NOT NULL DEFAULT 1,
  `VISIBLE` tinyint(1) NOT NULL DEFAULT 0,
  `DELETED` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_attributes`
--

CREATE TABLE `commission_type_attributes` (
  `SET_KEY` varchar(40) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `GROUP_ID` int(2) UNSIGNED NOT NULL,
  `SORT` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `commission_type_attributes`
--

INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('3D_MODELING', '3D Modeling', 'Includes anything like 3D-Printing', 1, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ABSTRACT', 'Abstract', 'Expresses the character in a non-standard way', 10, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ADOPTABLES', 'Adoptables', 'Pre-made characters', 1, 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ANIMATION', 'Animation', 'Animated artwork', 0, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ANTHROPOMORPHIC', 'Anthropomorphic', 'Anthropomorphic or human-like characters', 6, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('APPAREL', 'Apparel', 'Hoodies, clothing, kigurumi, etc.', 0, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('AQUATIC', 'Aquatic', 'Aquatic', 9, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ARMOR', 'Armor', 'Suits of armor and other really cool stuff', 2, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('AUDIO', 'Audio', 'Any type of audio, whether it be a tape, song, theme, instrumental, etc.', 0, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('AVIANS', 'Avians', 'Birds and other flying animals', 9, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BABYFUR', 'Babyfur', 'Adult-baby characters (The character must be over 18 for NSFW art)', 7, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BADGE', 'Badge', 'Typically a bust of a character and their name, can be worn at conventions', 1, 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BEAR', 'Bear', 'Bears', 9, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BODYSUIT', 'Bodysuit', 'The portion of the suit which covers the torso and legs', 2, 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BONDAGE', 'Bondage', 'Anything including restraining a character', 7, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BUGS', 'Bugs', 'Bugs, such as insects', 9, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('BUST', 'Bust', 'Shows the head, neck, and some chest/shoulders', 1, 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CANDY_GORE', 'Candy Gore', 'This includes any type of gore except the blood-and-guts kind, such as fruit gore or crystal gore', 7, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CANON', 'Canon', 'Canon characters, such as those from cartoons, Pokemon, etc.', 9, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CARTOONY', 'Cartoony', 'Typically contains non-realistic qualities like large, flat eyes, flat colors, etc.', 10, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CELL_SHADING', 'Cell shading', 'Basic shading', 1, 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CENTAURS', 'Centaurs', 'A character which has a body of a horse', 9, 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CHARACTER_SUMMARY', 'Character Summary', 'A quick biography for a character, typically for a ref-sheet text', 3, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CHIBI', 'Chibi', 'Small, cartoon-like characters with over sized heads', 1, 17);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('COMIC', 'Comic', 'A set of pages containing one or more panels that tell a story', 1, 18);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('COUPLE', 'Couple', 'The art involves 2 characters, likely as a couple', 6, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CRAFTING', 'Crafting', 'Crafted objects, like a plushie or similar', 0, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CUB', 'Cub', 'Characters who are underage (Catalyst does not allow NSFW art of minors)', 7, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CUSTOM', 'Custom', 'Suits custom-made to match a character', 2, 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('CUSTOM_T_SHIRTS', 'Custom T-Shirts', 'Custom-printed T-Shirts', 2, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('DEER', 'Deer', 'antler bois', 9, 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('DIGITAL_ARTWORK', 'Digital Artwork', 'Artwork made on a computer', 0, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('DIGITIGRADE', 'Digitigrade', 'Legs with padding to look more like an animal', 2, 10);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('DOGS', 'Dogs', 'Any domesticated dog, including examples like German Shepards, Beagles, etc.', 9, 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('DRAGONS', 'Dragons', 'Dragons', 9, 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('EQUESTRIANS', 'Equestrians', 'Horses and similar', 9, 10);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('EXPLICIT', 'Explicit', 'Genitals, explicititly sexual situations, fringe fetishes, including violence and gore', 8, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FAN_FICTION', 'FanFiction', 'Writing of characters in a TV show/movie/etc.', 3, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FAT_OBESE', 'Fat/Obese', 'Fat and obese characters', 7, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FEETPAWS', 'Feetpaws', 'The shows/feet of the costume', 2, 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FELINES', 'Felines', 'Domesticated cats', 9, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FEMALE', 'Female', 'A female character', 5, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FERAL', 'Feral', 'Characters which are natural, do not stand on 2 legs, etc', 6, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FLAT_COLOR', 'Flat Color', '\"Bucket-fill\" art, contains no shading', 1, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FOXES', 'Foxes', 'Foxes, including Fennecs', 9, 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FULLSUIT', 'Fullsuit', 'Contains all the parts of the suit, including the body', 2, 15);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FULL_BODY', 'Full-body', 'Shows the character\'s entire body', 1, 16);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FURSUIT', 'Fursuit', 'A costume similar to one of a mascot for a character', 0, 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('FURSUIT_ACCESSORIES', 'Fursuit Accessories', 'Harnesses, collars, etc.', 2, 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('GAME', 'Game', 'A custom-created game', 0, 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('GORE', 'Gore', 'Anything which involves mutilation/gashes of a character, or anything similar to that, including death', 7, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HALF_BODY', 'Half-body', 'Shows the character from the waist up', 1, 15);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HANDPAWS', 'Handpaws', 'The gloves of the costume', 2, 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HEAD', 'Head', 'The head of the costume', 2, 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HEADSHOT', 'Headshot', 'A piece of art detailing a character\'s head', 1, 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HUMAN', 'Human', 'Human characters', 6, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HYENAS', 'Hyenas', 'Hyenas', 9, 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HYPER', 'Hyper', 'Any character with certain body parts much larger than their normal size', 7, 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('HYPNOSIS', 'Hypnosis', 'Any form of mind control, including corruption, falls under this category', 7, 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ICONS', 'Icons', 'Art which is designed to be used as a profile picture', 1, 19);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('INFLATION', 'Inflation', 'Forced feeding or otherwise inflating a character with a substance', 7, 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('INTERSEX', 'Intersex', 'Shares both male and female parts', 5, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('JEWELRY', 'Jewelry', 'Fancy overpriced shiny stuff', 2, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('KEMONO', 'Kemono', '\"Anime-style\" art, originated in Japan', 10, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('KIGURUMI', 'Kigurumi', 'A suit designed to look like a cartoon animal, typically used as pajamas', 2, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('LATEX_RUBBER', 'Latex/Rubber', 'Art in which latex or rubber may be drawn', 7, 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('LEGS', 'Legs', 'Just the legs of the costume', 2, 16);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('LINEART', 'Lineart', 'Cleaned-up sketch: thick, definitive lines', 1, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('LONG', 'Long', '2m+ animation', 4, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MACHINES', 'Machines', 'Aeromorphs and other vehicle-related characters', 9, 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MACRO_MICRO', 'Macro/Micro', 'Characters who are either giants (macro) or tiny (micro)', 7, 10);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MALE', 'Male', 'A male character', 5, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MANUFACTURING', 'Manufacturing', 'Includes anything like 3D-Printing, etching', 1, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MATURE', 'Mature', 'No genitals, however moderately suggestive is allowed', 8, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MEDIUM', 'Medium', '30s-2m animation', 4, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MULTIPLE_CHARACTERS', 'Multiple Characters', 'The art may involve 3 or more characters', 6, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MULTI_CHAPTER', 'Multi-Chapter', 'A multiple-chapter fiction', 3, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MUSCLES', 'Muscles', 'Characters with defined or exaggerated muscles', 7, 11);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('MUSTELIDS', 'Mustelids', 'Includes weasels, otters, etc.', 9, 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ODOR_MUSK', 'Odor/Musk', 'Any strong odors, including in the context of feet, pits, or musk in general', 7, 12);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('ORIGINAL_SPECIES', 'Original Species', 'Other original species such as Dutch Angel Dragons, Protogens, etc.', 9, 19);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_ANIMATION', 'Other', 'Something not covered by the other options', 4, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_APPAREL', 'Other', 'Something not listed in the other options', 2, 18);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_ART', 'Other', 'Something not listed in the other options', 1, 23);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_ARTS', 'Other', 'Something not listed in the other options', 10, 6);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_CANINE', 'Other Canine', 'Other animals from the canine family', 9, 21);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_CLOTHING', 'Other Clothing', 'Sewed clothing, costumes, etc.', 2, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_GENDER', 'Other', 'Something not listed in the other options', 5, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_SPECIES', 'Other', 'Something not listed in the other options', 9, 20);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_TYPE', 'Other', 'Something not listed in the other options', 0, 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('OTHER_WRITING', 'Other', 'Something not covered by the other options', 3, 5);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('PAINTING', 'Painting', 'Either physically painting, or creating a similar effect digitally', 1, 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('PARTIAL', 'Partial', 'Typically consists of the head, paws, and tail', 2, 17);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('PAWS', 'Paws', 'Anything in which a main aspect are feet', 7, 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('PIXEL_ART', 'Pixel Art', 'Art constructed from a small number of pixels (squares)', 1, 20);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('PLANTIGRADE', 'Plantigrade', 'Flat, human-like legs', 2, 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('PLUSHES', 'Plushes', 'The drawing of a character to look like a plush', 7, 14);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('POETRY', 'Poetry', 'A poem such as free-verse, haiku, limerick, etc', 3, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('REALISTIC', 'Realistic', 'Shows the character as though it was real', 10, 2);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('REFERENCE_SHEETS', 'Reference Sheets', 'Art which depicts multiple views of a character, showing all their aspects and markings', 1, 22);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('REPTILES', 'Reptiles', 'Reptiles like lizards, dinosaurs, etc.', 9, 15);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('RODENTS', 'Rodents', 'Rats, mice, etc.', 9, 16);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SADISM_MASOCHISM', 'Sadism/Masochism', 'Giving or receiving pain or otherwise asserting dominance over another character', 7, 16);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SAFE', 'Safe', 'A piece with no sexual content or lightly suggestive at worst', 8, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SCAT', 'Scat', 'Anything with poop', 7, 15);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SCENERY', 'Scenery', 'Landscapes and the like', 1, 9);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SCULPTING', 'Sculpting', 'Physically sculpting an object', 1, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SEMI_REALISTIC', 'Semi-realistic', 'Realistic but with a few cartoony elements', 10, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SERGALS', 'Sergals', 'The cheese-shaped fantasy animal', 9, 17);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SHADED', 'Shaded', 'Fully shaded with gradients and smoothing', 1, 7);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SHORT', 'Short', '<30s animation', 4, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SHORT_STORY', 'Short Story', 'A short story, typically only a chapter', 3, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SKETCH', 'Sketch', 'A rough sketch', 1, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SOLO', 'Solo', 'The art only involves one character', 6, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('STICKERS', 'Stickers', 'A set of busts and half-bodies which show expressions', 1, 21);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('SURREAL', 'Surreal', 'Shows a surreal representation of the character', 10, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('TAIL', 'Tail', 'The tail of the suit', 2, 13);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('TRADITIONAL_ARTWORK', 'Traditional Artwork', 'Hand drawn or painted', 0, 1);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('TRANSFORMATION', 'Transformation', 'A character being transformed into another species or similar, may be through magic or other means', 7, 17);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('TRANSGENDER', 'Transgender', 'Someone who either feels they have no gender (genderless) or do not fit the one they were born with', 5, 3);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('URINE', 'Urine', 'Typical relating to watersports, this involves anything with urine', 7, 18);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('VIDEO_INTRO', 'Video Intro', 'An introduction for a video, typically YouTube', 4, 0);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('VORE', 'Vore', 'Consuming of another character through any means', 7, 19);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('WIGS', 'Wigs', 'Custom-made hair pieces', 2, 4);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('WOLVES', 'Wolves', 'Wolves, including derivatives like dingoes', 9, 18);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('WRITING', 'Writing', 'Stories and other literature', 0, 8);
INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('YCH', 'YCH', 'Your Character Here - pre-made lineart which will be colored to fit a custom character', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_attribute_groups`
--

CREATE TABLE `commission_type_attribute_groups` (
  `ID` int(2) UNSIGNED NOT NULL,
  `LABEL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `commission_type_attribute_groups`
--

INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(4, 'Animation Specifics');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(2, 'Apparel, Cosplay, and Fursuit Specifics');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(10, 'Art and Craft Styles');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(1, 'Art Specifics');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(5, 'Genders');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(0, 'General Type of Commission');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(8, 'Maturity Rating');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(6, 'Miscellaneous');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(7, 'Niche Art');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(9, 'Types of Species That Fit This Commission');
INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES(3, 'Writing and Story Specifics');

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_images`
--

CREATE TABLE `commission_type_images` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `CAPTION` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `COMMISSIONER` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `PATH` varchar(15) CHARACTER SET ascii NOT NULL,
  `NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_modifiers`
--

CREATE TABLE `commission_type_modifiers` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `NAME` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PRICE` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `USDEQ` decimal(8,2) UNSIGNED NOT NULL,
  `GROUP` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `MULTIPLE` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `SORT` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_payment_options`
--

CREATE TABLE `commission_type_payment_options` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `TYPE` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `INSTRUCTIONS` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `SORT` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_type_stages`
--

CREATE TABLE `commission_type_stages` (
  `ID` int(11) UNSIGNED NOT NULL,
  `COMMISSION_TYPE_ID` int(11) UNSIGNED NOT NULL,
  `STAGE` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SORT` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `DELETED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `email_list`
--

CREATE TABLE `email_list` (
  `EMAIL` varchar(254) CHARACTER SET ascii NOT NULL,
  `CONTEXT` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `READ` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `BODY` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patrons`
--

CREATE TABLE `patrons` (
  `ID` int(10) UNSIGNED NOT NULL,
  `PATREON_ID` varchar(36) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `CURRENT` tinyint(1) NOT NULL DEFAULT 1,
  `PLEDGED_CENTS` int(10) UNSIGNED NOT NULL,
  `TOTAL_CENTS` int(10) UNSIGNED NOT NULL,
  `SINCE` date DEFAULT NULL,
  `DESCRIPTION` mediumtext NOT NULL,
  `SOCIAL_CHIPS` mediumtext NOT NULL,
  `IMAGE_LOC` varchar(255) NOT NULL,
  `CUSTOM_IMAGE` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patrons`
--

INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(1, '1d942069-5805-4e1a-9566-5ee336416756', 'RD', 0, 0, 300, NULL, '*No description provided*', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@RD#8935\"}]\r\n', '1d942069-5805-4e1a-9566-5ee336416756-gray.png', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(2, '2b8c637c-133b-4b9c-b7fd-6da0173fbf65', 'AnalogHorse', 0, 0, 3000, NULL, '_Pending description from patron_', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@AnalogHorse#0621\"}]\r\n', '2b8c637c-133b-4b9c-b7fd-6da0173fbf65-gray.png', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(3, '45d2a7ab-1c70-4a78-8e76-f83aaed3c2f0', 'King Amdusias', 0, 100, 1400, NULL, '_Pending description from patron_', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"King Amdusias#0276\"},{\"NETWORK\":\"PATREON\",\"SERVICE_URL\":\"https:\\/\\/www.patreon.com\\/KingAmdusias\",\"DISP_NAME\":\"KingAmdusias\"},{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/instagram.com\\/amdusias.png\",\"DISP_NAME\":\"@amdusias.png\"},{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/kingamdusias\",\"DISP_NAME\":\"@KingAmdusias\"}]\r\n', '45d2a7ab-1c70-4a78-8e76-f83aaed3c2f0-gray.png', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(4, '678b1129-b08a-4abc-8a60-c0f9ba492d52', 'Coyote-Lovely', 0, 0, 0, NULL, '*No description provided*', '[{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/CoyoteLovelyDA\\/\",\"DISP_NAME\":\"@CoyoteLovelyDA\"},{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/www.instagram.com\\/CoyoteLovelyDA\\/\",\"DISP_NAME\":\"@CoyoteLovelyDA\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"Coyote-Lovely#2810\"},{\"NETWORK\":\"AMINO\",\"SERVICE_URL\":\"https:\\/\\/aminoapps.com\\/c\\/furry-amino\\/page\\/user\\/coyote-lovely\\/2vKW_21gi6fxgP1Z23WPn4ba2WbRYXJPeNW\",\"DISP_NAME\":\"Coyote-Lovely\"}]\r\n', '678b1129-b08a-4abc-8a60-c0f9ba492d52-gray.jpg', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(5, '84062a06-180a-4d33-a157-48d9ecbc9aa8', 'Styx Renegade', 1, 100, 4200, '2018-03-06', 'An eastern noodle', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Styx Y. Renegade#5836\"},{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"https:\\/\\/www.furaffinity.net\\/user\\/eonlover380\\/\",\"DISP_NAME\":\"~EonLover380\"},{\"NETWORK\":\"YOUTUBE\",\"SERVICE_URL\":\"https:\\/\\/www.youtube.com\\/channel\\/UCXhmlLoK6clgNGrZ-02tsYg\",\"DISP_NAME\":\"Styx Y. Renegade\"},{\"NETWORK\":\"REDDIT\",\"SERVICE_URL\":\"https:\\/\\/www.reddit.com\\/user\\/Styx_Renegade\\/\",\"DISP_NAME\":\"\\/u\\/Styx_Renegade\"}]\r\n', '84062a06-180a-4d33-a157-48d9ecbc9aa8.gif', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(6, '8c187db3-ca83-4d44-b4d3-a4d586853617', 'SINNERSCOUT', 0, 0, 2100, NULL, '_Pending description from patron_', '[{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"https:\\/\\/www.furaffinity.net\\/user\\/sinnerscout\\/\",\"DISP_NAME\":\"~sinnerscout\"},{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/www.instagram.com\\/sinnerscout\\/\",\"DISP_NAME\":\"@sinnerscout\"},{\"NETWORK\":\"TELEGRAM\",\"SERVICE_URL\":\"https:\\/\\/telegram.dog\\/sinnerscout\",\"DISP_NAME\":\"@sinnerscout\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"SINNERSCOUT#1276\"}]\r\n', '8c187db3-ca83-4d44-b4d3-a4d586853617-gray.jpg', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(7, 'e1f671dc-9ac5-457b-8977-783fa2ca7681', 'keeri', 1, 100, 1200, '2018-05-28', 'A strawberry fox', '[{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"https:\\/\\/www.furaffinity.net\\/user\\/keeri\\/\",\"DISP_NAME\":\"~keeri\"},{\"NETWORK\":\"TUMBLR\",\"SERVICE_URL\":\"https:\\/\\/keerifox.tumblr.com\\/\",\"DISP_NAME\":\"keerifox\"},{\"NETWORK\":\"STEAM\",\"SERVICE_URL\":\"https:\\/\\/steamcommunity.com\\/id\\/keeri\",\"DISP_NAME\":\"keeri\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@keeri#4538\"}]\r\n', 'e1f671dc-9ac5-457b-8977-783fa2ca7681.gif', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(8, 'e876a5a5-ee10-4181-831c-36a31e5a7bd6', 'Mango', 1, 100, 900, '2018-09-16', 'A stupid gay fruit', '[{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/instagram.com\\/water.paws\",\"DISP_NAME\":\"water.paws\"},{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/mangopaw\",\"DISP_NAME\":\"mangopaw\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Manghoul#6321\"},{\"NETWORK\":\"SELF\",\"SERVICE_URL\":\"https:\\/\\/manguwu.patreon.catl.st\\/\",\"DISP_NAME\":\"Commission Info\"}]\r\n', 'e876a5a5-ee10-4181-831c-36a31e5a7bd6.png', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(9, 'ee82df59-a2f4-4091-8f41-037903b8c911', 'nyawenyye', 1, 200, 2700, '2018-03-31', 'I\'m a dumb bat', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@nyawenyye \\ud83d\\udc99#9869\"}]\r\n', 'ee82df59-a2f4-4091-8f41-037903b8c911.png', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(10, 'f8b3f6ea-ab8c-4ecd-b8fd-9ff15aaceb06', 'Lykai', 0, 0, 200, NULL, '*No description provided*', '[{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"http:\\/\\/www.furaffinity.net\\/user\\/lykai\\/\",\"DISP_NAME\":\"~Lykai\"},{\"NETWORK\":\"TELEGRAM\",\"SERVICE_URL\":\"https:\\/\\/t.me\\/Lykai\",\"DISP_NAME\":\"@Lykai\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Lykai#2495\"},{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/GoldDingus\",\"DISP_NAME\":\"@GoldDingus\"},{\"NETWORK\":\"SELF\",\"SERVICE_URL\":\"https:\\/\\/beta.catalystapp.co\\/Character\\/View\\/Lykai\",\"DISP_NAME\":\"Reference images\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:lykai@catalystapp.co\",\"DISP_NAME\":\"lykai@catalystapp.co\"}]\r\n', 'f8b3f6ea-ab8c-4ecd-b8fd-9ff15aaceb06-gray.png', 0);
INSERT INTO `patrons` (`ID`, `PATREON_ID`, `NAME`, `CURRENT`, `PLEDGED_CENTS`, `TOTAL_CENTS`, `SINCE`, `DESCRIPTION`, `SOCIAL_CHIPS`, `IMAGE_LOC`, `CUSTOM_IMAGE`) VALUES(11, '967bfe23-5c79-4e64-876a-c545a504bad6', 'Toish', 1, 2000, 6000, '2019-03-29', 'Woof!', '[{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https://twitter.com/toi_wah\",\"DISP_NAME\":\"@toi_wah\"},{\"NETWORK\":\"SELF\",\"SERVICE_URL\":\"https://toish.patreon.catl.st/\",\"DISP_NAME\":\"Character Reference\"}]', '967bfe23-5c79-4e64-876a-c545a504bad6.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pending_thumbnail_queue`
--

CREATE TABLE `pending_thumbnail_queue` (
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'Unique DB Identifier of the row',
  `FOLDER` varchar(128) NOT NULL COMMENT 'Folder the image resides in',
  `TOKEN` varchar(128) NOT NULL COMMENT 'Token for the image',
  `PATH` varchar(128) NOT NULL COMMENT 'Image path'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ENVIRONMENT` enum('ALWAYS','DEVEL','PRODUCTION') NOT NULL,
  `TYPE` enum('SCRIPT','STYLE') NOT NULL,
  `PRIORITY` int(11) NOT NULL,
  `LOCAL` tinyint(1) NOT NULL DEFAULT 1,
  `NAME` varchar(255) NOT NULL,
  `MINIFIED_NAME` varchar(255) NOT NULL,
  `VERSIONED` tinyint(1) NOT NULL DEFAULT 0,
  `LATEST_SOURCE` enum('COMMIT','TAG') CHARACTER SET ascii DEFAULT NULL,
  `DATE_OF_LATEST_UPGRADE` date DEFAULT NULL,
  `GITHUB_REPO_NAME` varchar(128) DEFAULT NULL,
  `CHANGELOG_URL` mediumtext NOT NULL,
  `CURRENT_VERSION` varchar(40) CHARACTER SET ascii DEFAULT NULL,
  `LATEST_VERSION` varchar(40) CHARACTER SET ascii DEFAULT NULL,
  `ATTRIBUTES` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(1, 'ALWAYS', 'SCRIPT', 0, 0, 'jquery.js', 'jquery.min.js', 1, 'TAG', '2019-05-10', 'jquery/jquery', 'https://blog.jquery.com/category/jquery/', '75f7e963708b60f37a42b777f35825d33c4f8e7a', '75f7e963708b60f37a42b777f35825d33c4f8e7a', '[]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(2, 'ALWAYS', 'SCRIPT', 1000, 0, 'materialize.js', 'materialize.min.js', 1, 'TAG', '2019-05-10', 'Dogfalo/materialize', 'https://github.com/Dogfalo/materialize/blob/v1-dev/CHANGELOG.md', '80e8ed370487aaf1e2185b028f7deda40da94eb9', '80e8ed370487aaf1e2185b028f7deda40da94eb9', '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(3, 'ALWAYS', 'SCRIPT', 1500, 0, 'draggable.bundle.legacy.js', 'draggable.bundle.legacy.min.js', 1, 'TAG', '2019-05-10', 'Shopify/draggable', 'https://github.com/Shopify/draggable/blob/master/CHANGELOG.md', 'c518fb184c7606feeda6d5d56aacc61ced0ec8fd', 'c518fb184c7606feeda6d5d56aacc61ced0ec8fd', '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(4, 'ALWAYS', 'SCRIPT', 5000, 0, 'jsencrypt.js', 'jsencrypt.min.js', 1, 'TAG', '2019-05-10', 'travist/jsencrypt', 'http://travistidwell.com/jsencrypt', 'cc8a8c70f63e109d812c5052443610f678cc946d', 'cc8a8c70f63e109d812c5052443610f678cc946d', '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(5, 'ALWAYS', 'SCRIPT', 5000, 0, 'aesjs.js', 'aesjs.min.js', 1, 'TAG', '2019-05-10', 'ricmoo/aes-js', 'https://github.com/ricmoo/aes-js/releases', '7c9fad4add4b349dcb89a4e2125f37defaef3bc8', '7c9fad4add4b349dcb89a4e2125f37defaef3bc8', '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(6, 'ALWAYS', 'SCRIPT', 5000, 0, 'sjcl.js', 'sjcl.min.js', 1, 'TAG', '2019-05-10', 'bitwiseshiftleft/sjcl', 'https://github.com/bitwiseshiftleft/sjcl/releases', '2a6ed4a22449334de2dd9b4e960af679e0e87303', '2a6ed4a22449334de2dd9b4e960af679e0e87303', '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(7, 'ALWAYS', 'SCRIPT', 100000, 0, 'cheet.js', 'cheet.min.js', 1, 'COMMIT', '2019-05-10', 'namuol/cheet.js', 'https://github.com/namuol/cheet.js/commits/master', '5c1a8e8f75539540f59330c0af34cfdf9b231b53', '5c1a8e8f75539540f59330c0af34cfdf9b231b53', '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(8, 'ALWAYS', 'SCRIPT', 2000, 0, 'https://google.com/recaptcha/api.js', 'https://google.com/recaptcha/api.js', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(9, 'DEVEL', 'SCRIPT', 2, 1, 'modules/error_handler.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(10, 'DEVEL', 'SCRIPT', 5, 1, 'modules/polyfills.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(11, 'DEVEL', 'SCRIPT', 10, 1, 'modules/onload.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(12, 'DEVEL', 'SCRIPT', 15, 1, 'modules/input_functions.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(13, 'DEVEL', 'SCRIPT', 20, 1, 'modules/color_functions.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(14, 'DEVEL', 'SCRIPT', 25, 1, 'modules/totp_preview.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(15, 'DEVEL', 'SCRIPT', 30, 1, 'modules/ajax_progress.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(16, 'DEVEL', 'SCRIPT', 35, 1, 'modules/console_message.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(17, 'DEVEL', 'SCRIPT', 40, 1, 'modules/markdown_parser.js', 'N/A', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(18, 'PRODUCTION', 'SCRIPT', 10, 1, 'N/A', 'dist/packed.min.js', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(19, 'ALWAYS', 'SCRIPT', 10000, 0, 'https://googletagmanager.com/gtag/js?id=UA-112460506-1', 'https://googletagmanager.com/gtag/js?id=UA-112460506-1', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"defer\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(20, 'ALWAYS', 'STYLE', 100, 0, 'materialize.css', 'materialize.min.css', 1, 'TAG', '2019-05-10', 'Dogfalo/materialize', 'https://github.com/Dogfalo/materialize/blob/v1-dev/CHANGELOG.md', '80e8ed370487aaf1e2185b028f7deda40da94eb9', '80e8ed370487aaf1e2185b028f7deda40da94eb9', '[]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(21, 'ALWAYS', 'STYLE', 200, 1, 'color-{color}.css', 'color-{color}.css', 0, NULL, NULL, NULL, 'M/A', NULL, NULL, '[]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(25, 'ALWAYS', 'STYLE', 300, 1, 'overall.css', 'overall.min.css', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(26, 'ALWAYS', 'STYLE', 1000, 0, 'https://fonts.googleapis.com/css?family=Material+Icons&', 'https://fonts.googleapis.com/css?family=Material+Icons&', 0, NULL, NULL, NULL, 'N/A', NULL, NULL, '[\"crossorigin=\\\"anonymous\\\"\"]');
INSERT INTO `resources` (`ID`, `ENVIRONMENT`, `TYPE`, `PRIORITY`, `LOCAL`, `NAME`, `MINIFIED_NAME`, `VERSIONED`, `LATEST_SOURCE`, `DATE_OF_LATEST_UPGRADE`, `GITHUB_REPO_NAME`, `CHANGELOG_URL`, `CURRENT_VERSION`, `LATEST_VERSION`, `ATTRIBUTES`) VALUES(27, 'ALWAYS', 'STYLE', 1100, 1, 'https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i&', 'https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i&', 0, NULL, NULL, NULL, 'M/A', NULL, NULL, '[\"crossorigin=\\\"anonymous\\\"\"]');

-- --------------------------------------------------------

--
-- Table structure for table `social_media_meta`
--

CREATE TABLE `social_media_meta` (
  `SORT_ORDER` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `VISIBLE` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `INTEGRATION_NAME` varchar(12) NOT NULL,
  `IMAGE_PATH` varchar(19) NOT NULL,
  `DEFAULT_HUMAN_NAME` varchar(18) NOT NULL,
  `CHIP_CLASSES` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `social_media_meta`
--

INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(10, 1, '500PX', '500px.png', '500px', 'black white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(130, 1, 'AMINO', 'amino.png', 'Furry Amino', 'cyan white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(20, 1, 'AO3', 'archiveofourown.png', 'Archive of Our Own', 'red darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(30, 1, 'BANDCAMP', 'bandcamp.png', 'Bandcamp', 'teal lighten-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(40, 0, 'CUSTOM', 'custom.png', 'Custom Link', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(50, 1, 'DEVIANTART', 'deviantart.png', 'DeviantArt', 'green white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(60, 1, 'DISCORD', 'discord.png', 'Discord', 'discord-blurple white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(70, 0, 'DOMAIN', 'domain.png', 'Custom Website', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(80, 1, 'EMAIL', 'email.png', 'Email', 'user-color white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(90, 1, 'ETSY', 'etsy.png', 'Etsy', 'orange darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(100, 1, 'FACEBOOK', 'facebook.png', 'Facebook', 'blue darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(110, 1, 'FANFICTION', 'fanfiction.png', 'FanFiction', 'indigo darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(120, 1, 'FURAFFINITY', 'furaffinity.png', 'Fur Affinity', 'orange white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(140, 1, 'FURRYNETWORK', 'furrynetwork.png', 'Furry Network', 'blue darken-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(150, 1, 'GITHUB', 'github.png', 'GitHub', 'black white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(160, 1, 'GPLUS', 'googleplus.png', 'Google+', 'red darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(170, 1, 'INKBUNNY', 'inkbunny.png', 'Inkbunny', 'light-green darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(180, 1, 'INSTAGRAM', 'instagram.png', 'Instagram', 'pink accent-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(190, 1, 'KOFI', 'kofi.png', 'Ko-fi', 'cyan darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(210, 1, 'PATREON', 'patreon.png', 'Patreon', 'deep-orange accent-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(220, 1, 'PAYPAL', 'paypal.png', 'PayPal', 'blue darken-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(230, 1, 'PERISCOPE', 'periscope.png', 'Periscope', 'cyan darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(240, 1, 'PICARTO', 'picartotv.png', 'Picarto.TV', 'blue-grey darken-2 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(250, 1, 'PINTEREST', 'pinterest.png', 'Pinterest', 'red darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(260, 1, 'REDDIT', 'reddit.png', 'Reddit', 'reddit-orangered white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(0, 1, 'SELF', 'self.png', 'Catalyst', 'user-color white-text no-img-margin');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(270, 1, 'SNAPCHAT', 'snapchat.png', 'Snapchat', 'yellow darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(280, 1, 'SOFURRY', 'sofurry.png', 'SoFurry', 'brown darken-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(290, 1, 'SOUNDCLOUD', 'soundcloud.png', 'SoundCloud', 'deep-orange darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(300, 1, 'SPOTIFY', 'spotify.png', 'Spotify', 'green accent-4 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(310, 1, 'STEAM', 'steam.png', 'Steam', 'black white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(320, 1, 'TELEGRAM', 'telegram.png', 'Telegram', 'light-blue white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(330, 1, 'TRELLO', 'trello.png', 'Trello', 'light-blue darken-3 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(340, 1, 'TUMBLR', 'tumblr.png', 'Tumblr', 'tumblr-blue white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(350, 1, 'TWITCH', 'twitch.png', 'Twitch', 'deep-purple darken-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(360, 1, 'TWITTER', 'twitter.png', 'Twitter', 'blue lighten-1 white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(370, 1, 'VIMEO', 'vimeo.png', 'Vimeo', 'light-blue white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(380, 1, 'WEASYL', 'weasyl.png', 'Weasyl', 'weasyl-murroon white-text');
INSERT INTO `social_media_meta` (`SORT_ORDER`, `VISIBLE`, `INTEGRATION_NAME`, `IMAGE_PATH`, `DEFAULT_HUMAN_NAME`, `CHIP_CLASSES`) VALUES(390, 1, 'YOUTUBE', 'youtube.png', 'YouTube', 'shitty-youtube-red white-text');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `ID` int(10) UNSIGNED NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `DESCRIPTION` mediumtext NOT NULL,
  `SOCIAL_MEDIA_CHIPS` mediumtext NOT NULL,
  `IMAGE_PATH` varchar(15) NOT NULL,
  `SORT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(1, 'Fauxil Fox', 'Fauxil is the founder of Catalyst! He has developed most every aspect of the platform. He oversees all operations and decisions as well. He is studying Computer Science, and loves to code! He especially loves backend web-developement or general programming, his primary language being PHP.\r\n\r\nProfile Picture by aspenshadow ([FurAffinity](https://www.furaffinity.net/view/27621841/))', '[{\"NETWORK\":\"SELF\",\"SERVICE_URL\":\"https:\\/\\/c.catl.st\\/fauxil\",\"DISP_NAME\":\"Character Reference\"},{\"NETWORK\":\"DOMAIN\",\"SERVICE_URL\":\"http:\\/\\/xn--fp8h58f.ws\",\"DISP_NAME\":\"Fauxkai Site!\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"fauxil@catalystapp.co\",\"DISP_NAME\":\"@catalystapp.co\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"Fauxil_Fox#5881\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"fauxil_fox@furmail.net\",\"DISP_NAME\":\"@furmail.net\"},{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"https:\\/\\/furaffinity.net\\/user\\/fauxilfox\",\"DISP_NAME\":\"~Fauxil_Fox\"},{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/instagram.com\\/fauxil_fox\",\"DISP_NAME\":\"@fauxil_fox\"},{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/instagram.com\\/furry_irl_v2\",\"DISP_NAME\":\"@furry_irl_v2\"},{\"NETWORK\":\"REDDIT\",\"SERVICE_URL\":\"https:\\/\\/reddit.com\\/u\\/fauxil_fox\",\"DISP_NAME\":\"/u/Fauxil_Fox\"},{\"NETWORK\":\"TELEGRAM\",\"SERVICE_URL\":\"https:\\/\\/telegram.dog\\/Fauxil_Fox\",\"DISP_NAME\":\"@Fauxil_Fox\"},{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/fauxilfox\",\"DISP_NAME\":\"@fauxilfox\"}]', 'fauxil.jpg', 10);
INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(2, 'Lykai', 'A wonderful and sweet wolf that keeps everything and everyone going.  Studying computer science and machine learning!\r\n\r\nProfile Picture by blackmustang13 ([FurAffinity](https://www.furaffinity.net/view/25588593/))\r\n', '[{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"http:\\/\\/www.furaffinity.net\\/user\\/lykai\\/\",\"DISP_NAME\":\"~Lykai\"},{\"NETWORK\":\"TELEGRAM\",\"SERVICE_URL\":\"https:\\/\\/t.me\\/Lykai\",\"DISP_NAME\":\"@Lykai\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Lykai#2495\"},{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/GoldDingus\",\"DISP_NAME\":\"@GoldDingus\"},{\"NETWORK\":\"SELF\",\"SERVICE_URL\":\"https:\\/\\/beta.catalystapp.co\\/Character\\/View\\/Lykai\",\"DISP_NAME\":\"Reference images\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:lykai@catalystapp.co\",\"DISP_NAME\":\"lykai@catalystapp.co\"}]', 'lykai.png', 20);
INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(3, 'Disco Bob', 'Disco Bob (Discombobulation) loves to help out others with whatever problems they encounter. He studies Chemistry and English and loves all sorts of animals (primarily his dogs). Disco Bob is always happy to get a message from someone who needs help or simply wants to talk.\r\n\r\nProfile Picture by Orlando Fox ([Twitter](https://twitter.com/Orlando_Fox/), [Website](http://afoxdraws.com/index.html))', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Discombobulation#5558\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:blackoak@catalystapp.co\",\"DISP_NAME\":\"blackoak@catalystapp.co\"}]', 'disco.jpg', 30);
INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(4, 'Foxxo', 'From the land of maple syrup, moose, and beavers.  AKA Canada.  Also is a blue fox thing.  Speaking of blue things, Cherry MX Blue™ key switches are great. ^(We^ ^are^ ^not^ ^endorsed^ ^nor^ ^paid^ ^by^ ^Cherry)^\r\n\r\n\r\nProfile Picture by wo7f ([FurAffinity](https://www.furaffinity.net/view/24726073/))', '[{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/fluffracing\",\"DISP_NAME\":\"@fluffracing\"},{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"http:\\/\\/furaffinity.net\\/user\\/foxxoracing\",\"DISP_NAME\":\"~foxxoracing\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Foxxo#7183\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:foxxo@catalystapp.co\",\"DISP_NAME\":\"foxxo@catalystapp.co\"}]', 'foxxo.jpg', 40);
INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(5, 'Acey', 'Acey is an artist who is looking to become a professional illustrator in the future!\r\n\r\nProfile Picture by quietwheezing on Amino', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@oneggiri#4094\"},{\"NETWORK\":\"INSTAGRAM\",\"SERVICE_URL\":\"https:\\/\\/instagram.com\\/acemoonkid\\/\",\"DISP_NAME\":\"@acemoonkid\"},{\"NETWORK\":\"DEVIANTART\",\"SERVICE_URL\":\"https:\\/\\/oneggiri.deviantart.com\\/\",\"DISP_NAME\":\"oneggiri\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:acey@catalystapp.co\",\"DISP_NAME\":\"acey@catalystapp.co\"}]\r\n', 'acey.png', 50);
INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(6, 'Jiki Scott', 'Jiki is a social media tycoonist who\'s studying marketing and the principles needed to make social platforms.\r\n\r\nImage by ShadowNinja976 ([FurAffinity](https://www.furaffinity.net/view/19702583/))', '[{\"NETWORK\":\"TELEGRAM\",\"SERVICE_URL\":\"https:\\/\\/t.me\\/JikiScott\",\"DISP_NAME\":\"@JikiScott\"},{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"Jiki Scott#7840\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:jiki@catalystapp.co\",\"DISP_NAME\":\"jiki@catalystapp.co\"}]\r\n', 'jiki.jpg', 60);
INSERT INTO `staff` (`ID`, `NAME`, `DESCRIPTION`, `SOCIAL_MEDIA_CHIPS`, `IMAGE_PATH`, `SORT`) VALUES(7, 'Soul Wesson', 'Soul has been an artist for almost a decade now, and loves growing her style with the help of the community. Always one to help others, she loves taking the time to help others via Twitter, Twitch, and several other communities and art sites. Great at diffusing tense situations, and always ready to lend a hand, Soul often offers her friendship first, and her services second. Her messages are always open for anyone who needs advice, or just someone to talk to.\r\n\r\nImage by Jasmae ([FurAffinity](https://www.furaffinity.net/view/22443950/))', '[{\"NETWORK\":\"DISCORD\",\"SERVICE_URL\":null,\"DISP_NAME\":\"@Soul Wesson#7693\"},{\"NETWORK\":\"TWITTER\",\"SERVICE_URL\":\"https:\\/\\/twitter.com\\/SoulWesson\\/\",\"DISP_NAME\":\"@SoulWesson\"},{\"NETWORK\":\"FURAFFINITY\",\"SERVICE_URL\":\"http:\\/\\/www.furaffinity.net\\/user\\/soulcommissions\\/\",\"DISP_NAME\":\"~SoulCommissions\"},{\"NETWORK\":\"TWITCH\",\"SERVICE_URL\":\"https:\\/\\/twitch.tv\\/soulwesson\\/\",\"DISP_NAME\":\"SoulWesson\"},{\"NETWORK\":\"EMAIL\",\"SERVICE_URL\":\"mailto:soul.wesson@catalystapp.co\",\"DISP_NAME\":\"soul.wesson@catalystapp.co\"}]\r\n', 'soul.jpg', 70);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) UNSIGNED NOT NULL,
  `FILE_TOKEN` char(10) CHARACTER SET ascii NOT NULL,
  `APPROVED_BETA_TESTER` tinyint(1) NOT NULL DEFAULT 0,
  `USERNAME` varchar(64) CHARACTER SET ascii NOT NULL,
  `HASHED_PASSWORD` varchar(255) CHARACTER SET ascii NOT NULL,
  `PASSWORD_RESET_TOKEN` char(32) CHARACTER SET ascii NOT NULL,
  `TOTP_KEY` binary(10) DEFAULT NULL,
  `TOTP_RESET_TOKEN` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EMAIL` varchar(254) CHARACTER SET ascii DEFAULT NULL,
  `EMAIL_VERIFIED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `EMAIL_VERIFICATION_SENDABLE` tinyint(1) NOT NULL DEFAULT 0,
  `EMAIL_TOKEN` varchar(12) CHARACTER SET ascii NOT NULL,
  `ARTIST_PAGE_ID` int(11) UNSIGNED DEFAULT NULL,
  `PICTURE_LOC` varchar(15) CHARACTER SET ascii DEFAULT NULL,
  `PICTURE_NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `NSFW` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `COLOR` binary(3) NOT NULL DEFAULT '^ ',
  `NICK` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mistake',
  `REFERRER` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SUSPENDED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `DEACTIVATED` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_social_media`
--

CREATE TABLE `user_social_media` (
  `ID` int(11) UNSIGNED NOT NULL,
  `SORT` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `USER_ID` int(11) UNSIGNED DEFAULT NULL,
  `NETWORK` varchar(12) CHARACTER SET ascii NOT NULL,
  `SERVICE_URL` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DISP_NAME` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD UNIQUE KEY `USER` (`USER_ID`,`DELETED`),
  ADD UNIQUE KEY `URL_DELETED` (`URL`,`DELETED`),
  ADD KEY `DELETED` (`DELETED`),
  ADD KEY `artist_pages_ibfk_2` (`COLOR`);

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
-- Indexes for table `commission_types`
--
ALTER TABLE `commission_types`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ARTIST_PAGE_ID` (`ARTIST_PAGE_ID`),
  ADD KEY `SORT` (`SORT`),
  ADD KEY `BASE_USD_COST` (`BASE_USD_COST`),
  ADD KEY `DELETED` (`DELETED`);
ALTER TABLE `commission_types` ADD FULLTEXT KEY `ATTRS` (`ATTRS`);

--
-- Indexes for table `commission_type_attributes`
--
ALTER TABLE `commission_type_attributes`
  ADD PRIMARY KEY (`SET_KEY`),
  ADD KEY `GROUP_ID` (`GROUP_ID`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `commission_type_attribute_groups`
--
ALTER TABLE `commission_type_attribute_groups`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `LABEL_2` (`LABEL`);

--
-- Indexes for table `commission_type_images`
--
ALTER TABLE `commission_type_images`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`);

--
-- Indexes for table `commission_type_modifiers`
--
ALTER TABLE `commission_type_modifiers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `USDEQ` (`USDEQ`),
  ADD KEY `DELETED` (`DELETED`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `commission_type_payment_options`
--
ALTER TABLE `commission_type_payment_options`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TYPE` (`TYPE`),
  ADD KEY `DELETED` (`DELETED`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `commission_type_stages`
--
ALTER TABLE `commission_type_stages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `COMMISSION_TYPE_ID` (`COMMISSION_TYPE_ID`),
  ADD KEY `DELETED` (`DELETED`),
  ADD KEY `SORT` (`SORT`);

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
-- Indexes for table `patrons`
--
ALTER TABLE `patrons`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `PATREON_ID` (`PATREON_ID`),
  ADD KEY `CURRENT` (`CURRENT`),
  ADD KEY `PLEDGED_CENTS` (`PLEDGED_CENTS`),
  ADD KEY `TOTAL_CENTS` (`TOTAL_CENTS`);

--
-- Indexes for table `pending_thumbnail_queue`
--
ALTER TABLE `pending_thumbnail_queue`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `social_media_meta`
--
ALTER TABLE `social_media_meta`
  ADD PRIMARY KEY (`INTEGRATION_NAME`),
  ADD UNIQUE KEY `INTEGRATION_NAME` (`INTEGRATION_NAME`),
  ADD KEY `IMAGE_PATH` (`IMAGE_PATH`),
  ADD KEY `SORT_ORDER` (`SORT_ORDER`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SORT` (`SORT`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `FILE_TOKEN` (`FILE_TOKEN`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`),
  ADD UNIQUE KEY `CREATOR_PAGE_ID` (`ARTIST_PAGE_ID`) USING BTREE,
  ADD KEY `COLOR` (`COLOR`),
  ADD KEY `REFERRER` (`REFERRER`);

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patrons`
--
ALTER TABLE `patrons`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pending_thumbnail_queue`
--
ALTER TABLE `pending_thumbnail_queue`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique DB Identifier of the row';

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `commission_types`
--
ALTER TABLE `commission_types`
  ADD CONSTRAINT `commission_types_ibfk_1` FOREIGN KEY (`ARTIST_PAGE_ID`) REFERENCES `artist_pages` (`ID`);

--
-- Constraints for table `commission_type_attributes`
--
ALTER TABLE `commission_type_attributes`
  ADD CONSTRAINT `commission_type_attributes_ibfk_1` FOREIGN KEY (`GROUP_ID`) REFERENCES `commission_type_attribute_groups` (`ID`);

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
