-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2023 at 02:27 PM
-- Server version: 5.7.42
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notebrains_laundryking`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `addon_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addon_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `addon_id` text COLLATE utf8mb4_unicode_ci,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`, `phone_code`, `created_at`, `updated_at`) VALUES
(1, 'AF', 'Afghanistan', '93', NULL, NULL),
(2, 'AL', 'Albania', '355', NULL, NULL),
(3, 'DZ', 'Algeria', '213', NULL, NULL),
(4, 'AS', 'American Samoa', '1684', NULL, NULL),
(5, 'AD', 'Andorra', '376', NULL, NULL),
(6, 'AO', 'Angola', '244', NULL, NULL),
(7, 'AI', 'Anguilla', '1264', NULL, NULL),
(8, 'AQ', 'Antarctica', '0', NULL, NULL),
(9, 'AG', 'Antigua And Barbuda', '1268', NULL, NULL),
(10, 'AR', 'Argentina', '54', NULL, NULL),
(11, 'AM', 'Armenia', '374', NULL, NULL),
(12, 'AW', 'Aruba', '297', NULL, NULL),
(13, 'AU', 'Australia', '61', NULL, NULL),
(14, 'AT', 'Austria', '43', NULL, NULL),
(15, 'AZ', 'Azerbaijan', '994', NULL, NULL),
(16, 'BS', 'Bahamas The', '1242', NULL, NULL),
(17, 'BH', 'Bahrain', '973', NULL, NULL),
(18, 'BD', 'Bangladesh', '880', NULL, NULL),
(19, 'BB', 'Barbados', '1246', NULL, NULL),
(20, 'BY', 'Belarus', '375', NULL, NULL),
(21, 'BE', 'Belgium', '32', NULL, NULL),
(22, 'BZ', 'Belize', '501', NULL, NULL),
(23, 'BJ', 'Benin', '229', NULL, NULL),
(24, 'BM', 'Bermuda', '1441', NULL, NULL),
(25, 'BT', 'Bhutan', '975', NULL, NULL),
(26, 'BO', 'Bolivia', '591', NULL, NULL),
(27, 'BA', 'Bosnia and Herzegovina', '387', NULL, NULL),
(28, 'BW', 'Botswana', '267', NULL, NULL),
(29, 'BV', 'Bouvet Island', '0', NULL, NULL),
(30, 'BR', 'Brazil', '55', NULL, NULL),
(31, 'IO', 'British Indian Ocean Territory', '246', NULL, NULL),
(32, 'BN', 'Brunei', '673', NULL, NULL),
(33, 'BG', 'Bulgaria', '359', NULL, NULL),
(34, 'BF', 'Burkina Faso', '226', NULL, NULL),
(35, 'BI', 'Burundi', '257', NULL, NULL),
(36, 'KH', 'Cambodia', '855', NULL, NULL),
(37, 'CM', 'Cameroon', '237', NULL, NULL),
(38, 'CA', 'Canada', '1', NULL, NULL),
(39, 'CV', 'Cape Verde', '238', NULL, NULL),
(40, 'KY', 'Cayman Islands', '1345', NULL, NULL),
(41, 'CF', 'Central African Republic', '236', NULL, NULL),
(42, 'TD', 'Chad', '235', NULL, NULL),
(43, 'CL', 'Chile', '56', NULL, NULL),
(44, 'CN', 'China', '86', NULL, NULL),
(45, 'CX', 'Christmas Island', '61', NULL, NULL),
(46, 'CC', 'Cocos (Keeling) Islands', '672', NULL, NULL),
(47, 'CO', 'Colombia', '57', NULL, NULL),
(48, 'KM', 'Comoros', '269', NULL, NULL),
(49, 'CG', 'Republic Of The Congo', '242', NULL, NULL),
(50, 'CD', 'Democratic Republic Of The Congo', '242', NULL, NULL),
(51, 'CK', 'Cook Islands', '682', NULL, NULL),
(52, 'CR', 'Costa Rica', '506', NULL, NULL),
(53, 'CI', 'Cote D Ivoire (Ivory Coast)', '225', NULL, NULL),
(54, 'HR', 'Croatia (Hrvatska)', '385', NULL, NULL),
(55, 'CU', 'Cuba', '53', NULL, NULL),
(56, 'CY', 'Cyprus', '357', NULL, NULL),
(57, 'CZ', 'Czech Republic', '420', NULL, NULL),
(58, 'DK', 'Denmark', '45', NULL, NULL),
(59, 'DJ', 'Djibouti', '253', NULL, NULL),
(60, 'DM', 'Dominica', '1767', NULL, NULL),
(61, 'DO', 'Dominican Republic', '1809', NULL, NULL),
(62, 'TP', 'East Timor', '670', NULL, NULL),
(63, 'EC', 'Ecuador', '593', NULL, NULL),
(64, 'EG', 'Egypt', '20', NULL, NULL),
(65, 'SV', 'El Salvador', '503', NULL, NULL),
(66, 'GQ', 'Equatorial Guinea', '240', NULL, NULL),
(67, 'ER', 'Eritrea', '291', NULL, NULL),
(68, 'EE', 'Estonia', '372', NULL, NULL),
(69, 'ET', 'Ethiopia', '251', NULL, NULL),
(70, 'XA', 'External Territories of Australia', '61', NULL, NULL),
(71, 'FK', 'Falkland Islands', '500', NULL, NULL),
(72, 'FO', 'Faroe Islands', '298', NULL, NULL),
(73, 'FJ', 'Fiji Islands', '679', NULL, NULL),
(74, 'FI', 'Finland', '358', NULL, NULL),
(75, 'FR', 'France', '33', NULL, NULL),
(76, 'GF', 'French Guiana', '594', NULL, NULL),
(77, 'PF', 'French Polynesia', '689', NULL, NULL),
(78, 'TF', 'French Southern Territories', '0', NULL, NULL),
(79, 'GA', 'Gabon', '241', NULL, NULL),
(80, 'GM', 'Gambia The', '220', NULL, NULL),
(81, 'GE', 'Georgia', '995', NULL, NULL),
(82, 'DE', 'Germany', '49', NULL, NULL),
(83, 'GH', 'Ghana', '233', NULL, NULL),
(84, 'GI', 'Gibraltar', '350', NULL, NULL),
(85, 'GR', 'Greece', '30', NULL, NULL),
(86, 'GL', 'Greenland', '299', NULL, NULL),
(87, 'GD', 'Grenada', '1473', NULL, NULL),
(88, 'GP', 'Guadeloupe', '590', NULL, NULL),
(89, 'GU', 'Guam', '1671', NULL, NULL),
(90, 'GT', 'Guatemala', '502', NULL, NULL),
(91, 'XU', 'Guernsey and Alderney', '44', NULL, NULL),
(92, 'GN', 'Guinea', '224', NULL, NULL),
(93, 'GW', 'Guinea-Bissau', '245', NULL, NULL),
(94, 'GY', 'Guyana', '592', NULL, NULL),
(95, 'HT', 'Haiti', '509', NULL, NULL),
(96, 'HM', 'Heard and McDonald Islands', '0', NULL, NULL),
(97, 'HN', 'Honduras', '504', NULL, NULL),
(98, 'HK', 'Hong Kong S.A.R.', '852', NULL, NULL),
(99, 'HU', 'Hungary', '36', NULL, NULL),
(100, 'IS', 'Iceland', '354', NULL, NULL),
(101, 'IN', 'India', '91', NULL, NULL),
(102, 'ID', 'Indonesia', '62', NULL, NULL),
(103, 'IR', 'Iran', '98', NULL, NULL),
(104, 'IQ', 'Iraq', '964', NULL, NULL),
(105, 'IE', 'Ireland', '353', NULL, NULL),
(106, 'IL', 'Israel', '972', NULL, NULL),
(107, 'IT', 'Italy', '39', NULL, NULL),
(108, 'JM', 'Jamaica', '1876', NULL, NULL),
(109, 'JP', 'Japan', '81', NULL, NULL),
(110, 'XJ', 'Jersey', '44', NULL, NULL),
(111, 'JO', 'Jordan', '962', NULL, NULL),
(112, 'KZ', 'Kazakhstan', '7', NULL, NULL),
(113, 'KE', 'Kenya', '254', NULL, NULL),
(114, 'KI', 'Kiribati', '686', NULL, NULL),
(115, 'KP', 'Korea North', '850', NULL, NULL),
(116, 'KR', 'Korea South', '82', NULL, NULL),
(117, 'KW', 'Kuwait', '965', NULL, NULL),
(118, 'KG', 'Kyrgyzstan', '996', NULL, NULL),
(119, 'LA', 'Laos', '856', NULL, NULL),
(120, 'LV', 'Latvia', '371', NULL, NULL),
(121, 'LB', 'Lebanon', '961', NULL, NULL),
(122, 'LS', 'Lesotho', '266', NULL, NULL),
(123, 'LR', 'Liberia', '231', NULL, NULL),
(124, 'LY', 'Libya', '218', NULL, NULL),
(125, 'LI', 'Liechtenstein', '423', NULL, NULL),
(126, 'LT', 'Lithuania', '370', NULL, NULL),
(127, 'LU', 'Luxembourg', '352', NULL, NULL),
(128, 'MO', 'Macau S.A.R.', '853', NULL, NULL),
(129, 'MK', 'Macedonia', '389', NULL, NULL),
(130, 'MG', 'Madagascar', '261', NULL, NULL),
(131, 'MW', 'Malawi', '265', NULL, NULL),
(132, 'MY', 'Malaysia', '60', NULL, NULL),
(133, 'MV', 'Maldives', '960', NULL, NULL),
(134, 'ML', 'Mali', '223', NULL, NULL),
(135, 'MT', 'Malta', '356', NULL, NULL),
(136, 'XM', 'Man (Isle of)', '44', NULL, NULL),
(137, 'MH', 'Marshall Islands', '692', NULL, NULL),
(138, 'MQ', 'Martinique', '596', NULL, NULL),
(139, 'MR', 'Mauritania', '222', NULL, NULL),
(140, 'MU', 'Mauritius', '230', NULL, NULL),
(141, 'YT', 'Mayotte', '269', NULL, NULL),
(142, 'MX', 'Mexico', '52', NULL, NULL),
(143, 'FM', 'Micronesia', '691', NULL, NULL),
(144, 'MD', 'Moldova', '373', NULL, NULL),
(145, 'MC', 'Monaco', '377', NULL, NULL),
(146, 'MN', 'Mongolia', '976', NULL, NULL),
(147, 'MS', 'Montserrat', '1664', NULL, NULL),
(148, 'MA', 'Morocco', '212', NULL, NULL),
(149, 'MZ', 'Mozambique', '258', NULL, NULL),
(150, 'MM', 'Myanmar', '95', NULL, NULL),
(151, 'NA', 'Namibia', '264', NULL, NULL),
(152, 'NR', 'Nauru', '674', NULL, NULL),
(153, 'NP', 'Nepal', '977', NULL, NULL),
(154, 'AN', 'Netherlands Antilles', '599', NULL, NULL),
(155, 'NL', 'Netherlands The', '31', NULL, NULL),
(156, 'NC', 'New Caledonia', '687', NULL, NULL),
(157, 'NZ', 'New Zealand', '64', NULL, NULL),
(158, 'NI', 'Nicaragua', '505', NULL, NULL),
(159, 'NE', 'Niger', '227', NULL, NULL),
(160, 'NG', 'Nigeria', '234', NULL, NULL),
(161, 'NU', 'Niue', '683', NULL, NULL),
(162, 'NF', 'Norfolk Island', '672', NULL, NULL),
(163, 'MP', 'Northern Mariana Islands', '1670', NULL, NULL),
(164, 'NO', 'Norway', '47', NULL, NULL),
(165, 'OM', 'Oman', '968', NULL, NULL),
(166, 'PK', 'Pakistan', '92', NULL, NULL),
(167, 'PW', 'Palau', '680', NULL, NULL),
(168, 'PS', 'Palestinian Territory Occupied', '970', NULL, NULL),
(169, 'PA', 'Panama', '507', NULL, NULL),
(170, 'PG', 'Papua new Guinea', '675', NULL, NULL),
(171, 'PY', 'Paraguay', '595', NULL, NULL),
(172, 'PE', 'Peru', '51', NULL, NULL),
(173, 'PH', 'Philippines', '63', NULL, NULL),
(174, 'PN', 'Pitcairn Island', '0', NULL, NULL),
(175, 'PL', 'Poland', '48', NULL, NULL),
(176, 'PT', 'Portugal', '351', NULL, NULL),
(177, 'PR', 'Puerto Rico', '1787', NULL, NULL),
(178, 'QA', 'Qatar', '974', NULL, NULL),
(179, 'RE', 'Reunion', '262', NULL, NULL),
(180, 'RO', 'Romania', '40', NULL, NULL),
(181, 'RU', 'Russia', '70', NULL, NULL),
(182, 'RW', 'Rwanda', '250', NULL, NULL),
(183, 'SH', 'Saint Helena', '290', NULL, NULL),
(184, 'KN', 'Saint Kitts And Nevis', '1869', NULL, NULL),
(185, 'LC', 'Saint Lucia', '1758', NULL, NULL),
(186, 'PM', 'Saint Pierre and Miquelon', '508', NULL, NULL),
(187, 'VC', 'Saint Vincent And The Grenadines', '1784', NULL, NULL),
(188, 'WS', 'Samoa', '684', NULL, NULL),
(189, 'SM', 'San Marino', '378', NULL, NULL),
(190, 'ST', 'Sao Tome and Principe', '239', NULL, NULL),
(191, 'SA', 'Saudi Arabia', '966', NULL, NULL),
(192, 'SN', 'Senegal', '221', NULL, NULL),
(193, 'RS', 'Serbia', '381', NULL, NULL),
(194, 'SC', 'Seychelles', '248', NULL, NULL),
(195, 'SL', 'Sierra Leone', '232', NULL, NULL),
(196, 'SG', 'Singapore', '65', NULL, NULL),
(197, 'SK', 'Slovakia', '421', NULL, NULL),
(198, 'SI', 'Slovenia', '386', NULL, NULL),
(199, 'XG', 'Smaller Territories of the UK', '44', NULL, NULL),
(200, 'SB', 'Solomon Islands', '677', NULL, NULL),
(201, 'SO', 'Somalia', '252', NULL, NULL),
(202, 'ZA', 'South Africa', '27', NULL, NULL),
(203, 'GS', 'South Georgia', '0', NULL, NULL),
(204, 'SS', 'South Sudan', '211', NULL, NULL),
(205, 'ES', 'Spain', '34', NULL, NULL),
(206, 'LK', 'Sri Lanka', '94', NULL, NULL),
(207, 'SD', 'Sudan', '249', NULL, NULL),
(208, 'SR', 'Suricountry_name', '597', NULL, NULL),
(209, 'SJ', 'Svalbard And Jan Mayen Islands', '47', NULL, NULL),
(210, 'SZ', 'Swaziland', '268', NULL, NULL),
(211, 'SE', 'Sweden', '46', NULL, NULL),
(212, 'CH', 'Switzerland', '41', NULL, NULL),
(213, 'SY', 'Syria', '963', NULL, NULL),
(214, 'TW', 'Taiwan', '886', NULL, NULL),
(215, 'TJ', 'Tajikistan', '992', NULL, NULL),
(216, 'TZ', 'Tanzania', '255', NULL, NULL),
(217, 'TH', 'Thailand', '66', NULL, NULL),
(218, 'TG', 'Togo', '228', NULL, NULL),
(219, 'TK', 'Tokelau', '690', NULL, NULL),
(220, 'TO', 'Tonga', '676', NULL, NULL),
(221, 'TT', 'Trincountry_idad And Tobago', '1868', NULL, NULL),
(222, 'TN', 'Tunisia', '216', NULL, NULL),
(223, 'TR', 'Turkey', '90', NULL, NULL),
(224, 'TM', 'Turkmenistan', '7370', NULL, NULL),
(225, 'TC', 'Turks And Caicos Islands', '1649', NULL, NULL),
(226, 'TV', 'Tuvalu', '688', NULL, NULL),
(227, 'UG', 'Uganda', '256', NULL, NULL),
(228, 'UA', 'Ukraine', '380', NULL, NULL),
(229, 'AE', 'United Arab Emirates', '971', NULL, NULL),
(230, 'GB', 'United Kingdom', '44', NULL, NULL),
(231, 'US', 'United States', '1', NULL, NULL),
(232, 'UM', 'United States Minor Outlying Islands', '1', NULL, NULL),
(233, 'UY', 'Uruguay', '598', NULL, NULL),
(234, 'UZ', 'Uzbekistan', '998', NULL, NULL),
(235, 'VU', 'Vanuatu', '678', NULL, NULL),
(236, 'VA', 'Vatican City State (Holy See)', '39', NULL, NULL),
(237, 'VE', 'Venezuela', '58', NULL, NULL),
(238, 'VN', 'Vietnam', '84', NULL, NULL),
(239, 'VG', 'Virgin Islands (British)', '1284', NULL, NULL),
(240, 'VI', 'Virgin Islands (US)', '1340', NULL, NULL),
(241, 'WF', 'Wallis And Futuna Islands', '681', NULL, NULL),
(242, 'EH', 'Western Sahara', '212', NULL, NULL),
(243, 'YE', 'Yemen', '967', NULL, NULL),
(244, 'YU', 'Yugoslavia', '38', NULL, NULL),
(245, 'ZM', 'Zambia', '260', NULL, NULL),
(246, 'ZW', 'Zimbabwe', '26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salutation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `membership` int(11) DEFAULT NULL,
  `membership_start_date` timestamp NULL DEFAULT NULL,
  `membership_end_date` timestamp NULL DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin` int(11) DEFAULT NULL,
  `discount` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `refer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrel_customer_id` int(11) NOT NULL DEFAULT '0',
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `flat_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `route_suggestion` text COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '1=''selected address'', ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_membership_logs`
--

CREATE TABLE `customer_membership_logs` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `membership_start_date` timestamp NULL DEFAULT NULL,
  `membership_end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_queries`
--

CREATE TABLE `customer_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_verification_codes`
--

CREATE TABLE `customer_verification_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_types`
--

CREATE TABLE `delivery_types` (
  `id` int(11) NOT NULL,
  `delivery_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `cut_off_amount` int(11) NOT NULL DEFAULT '0',
  `cut_off_charge` int(11) NOT NULL DEFAULT '0',
  `delivery_in_days` int(11) NOT NULL DEFAULT '0',
  `pickup_time_from` varchar(255) DEFAULT NULL,
  `pickup_time_to` varchar(255) DEFAULT NULL,
  `delivery_time_from` varchar(255) DEFAULT NULL,
  `delivery_time_to` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery_types`
--

INSERT INTO `delivery_types` (`id`, `delivery_name`, `type`, `amount`, `cut_off_amount`, `cut_off_charge`, `delivery_in_days`, `pickup_time_from`, `pickup_time_to`, `delivery_time_from`, `delivery_time_to`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Regular Delivery', 'Flat', 0, 200, 50, 5, '10:00', '19:30', '10:15', '19:30', 1, '2022-12-23 04:55:59', '2023-05-30 22:23:23'),
(2, 'Fast Delivery-50% Extra', 'Percentage', 50, 200, 50, 2, '10:00', '20:00', '08:00', '10:00', 1, '2022-12-23 06:36:04', '2023-03-15 19:37:04'),
(3, 'Express Delivery-100% Extra', 'Percentage', 100, 200, 50, 1, '10:00', '12:00', '19:00', '21:00', 1, '2022-12-23 18:01:32', '2023-03-15 19:34:34');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_date` date NOT NULL,
  `expense_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_amount` double(15,2) NOT NULL,
  `payment_mode` int(11) DEFAULT NULL,
  `tax_included` int(11) NOT NULL,
  `tax_percentage` double(6,2) DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `financial_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_category_type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_years`
--

CREATE TABLE `financial_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `starting_date` date DEFAULT NULL,
  `ending_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `financial_years`
--

INSERT INTO `financial_years` (`id`, `year`, `starting_date`, `ending_date`, `created_at`, `updated_at`) VALUES
(1, '2022', '2022-04-01', '2023-03-31', '2022-11-25 05:55:45', '2022-11-25 05:55:45'),
(2, '2023', '2023-04-01', '2024-03-31', '2023-05-04 15:18:03', '2023-05-04 15:18:23'),
(3, '2023', '2023-04-01', '2024-03-31', '2023-05-11 22:41:07', '2023-05-11 22:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `master_settings`
--

CREATE TABLE `master_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `master_value` longtext COLLATE utf8mb4_unicode_ci,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_settings`
--

INSERT INTO `master_settings` (`id`, `master_title`, `master_value`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'default_currency', '₹', 1, NULL, NULL),
(2, 'default_application_name', 'LAUNDRY KING  ®', 1, NULL, NULL),
(3, 'default_phone_number', '123456', 1, NULL, NULL),
(4, 'default_tax_percentage', '18', 1, NULL, NULL),
(5, 'default_state', 'West Bengal', 1, NULL, NULL),
(6, 'default_city', 'Kolkata', 1, NULL, NULL),
(7, 'default_country', 'IN', 1, NULL, NULL),
(8, 'default_zip_code', '691001', 1, NULL, NULL),
(9, 'default_address', 'address', 1, NULL, NULL),
(10, 'store_email', 'store@store.com', 1, NULL, NULL),
(11, 'store_tax_number', 'tax@tax', 1, NULL, NULL),
(12, 'default_printer', '1', 1, NULL, NULL),
(13, 'forget_password_enable', '1', 1, NULL, NULL),
(14, 'sms_createorder', 'Hi <name> An Order #<order_number> was created and will be delivered on <delivery_date> Your Order Total is <total>.', 1, NULL, NULL),
(15, 'sms_statuschange', 'Hi <name> Your Order #<order_number> status has been changed to <status> on <current_time>', 1, NULL, NULL),
(16, 'default_financial_year', '1', 1, NULL, NULL),
(17, 'default_district', '', 1, NULL, NULL),
(18, 'default_logo', '/logo/1675667830.png', 1, NULL, NULL),
(19, 'refer_amount', '50', 1, NULL, NULL),
(20, 'joining_bonus', '20', 1, NULL, NULL),
(21, 'sms_account_sid', '', 1, NULL, NULL),
(22, 'sms_auth_token', '', 1, NULL, NULL),
(23, 'sms_twilio_number', '', 1, NULL, NULL),
(24, 'sms_enabled', '', 1, NULL, NULL),
(25, 'order_prefix', 'LK', 1, NULL, NULL),
(26, 'rewash_time', '2', 1, NULL, NULL),
(27, 'garmnt_scrn', '2', 1, NULL, NULL),
(28, 'store_start_time', '09:45', 1, NULL, NULL),
(29, 'store_close_time', '20:22', 1, NULL, NULL),
(30, 'see_customer', '1', 1, NULL, NULL),
(31, 'reedem_amount', '100', 1, NULL, NULL),
(32, 'delivery_date', '1', 1, NULL, NULL),
(33, 'tag_id', '1', 1, NULL, NULL),
(34, 'service', '1', 1, NULL, NULL),
(35, 'address', '1', 1, NULL, NULL),
(36, 'customer_name', '1', 1, NULL, NULL),
(37, 'garment_name', '1', 1, NULL, NULL),
(38, 'order_status', '1', 1, NULL, NULL),
(39, 'order_number', '1', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL,
  `membership_name` varchar(255) NOT NULL,
  `min_price` int(11) NOT NULL,
  `max_price` int(11) NOT NULL,
  `discount_type` int(11) NOT NULL DEFAULT '0' COMMENT '1=Order discount\r\n2=Cashback',
  `discount` int(11) NOT NULL DEFAULT '0',
  `express_fee` int(11) NOT NULL DEFAULT '0',
  `delivery_fee` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `membership_name`, `min_price`, `max_price`, `discount_type`, `discount`, `express_fee`, `delivery_fee`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(11, 'Silver Tier', 10000, 29999, 2, 2, 30, 1, 'silver.png', 1, '2023-04-19 06:33:21', '2023-04-19 06:33:21'),
(18, 'Gold Tier', 30000, 59999, 1, 4, 30, 1, 'gold.png', 1, '2023-05-11 14:50:17', '2023-05-11 14:50:17'),
(20, 'Platinum Tier', 60000, 900000, 2, 6, 30, 0, 'platinum.png', 1, '2023-04-04 03:12:49', '2023-04-04 03:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_02_21_092427_create_countries_table', 1),
(6, '2022_02_21_092427_create_financial_years_table', 1),
(7, '2022_02_21_092430_create_master_settings_table', 1),
(8, '2022_02_21_092644_create_expense_categories_table', 1),
(9, '2022_02_21_092944_create_expenses_table', 1),
(10, '2022_02_21_093542_create_customers_table', 1),
(11, '2022_02_21_093811_create_service_types_table', 1),
(12, '2022_02_21_093939_create_services_table', 1),
(13, '2022_02_21_094046_create_service_details_table', 1),
(14, '2022_02_21_094504_create_addons_table', 1),
(15, '2022_02_21_094505_create_orders_table', 1),
(16, '2022_02_21_095107_create_order_details_table', 1),
(17, '2022_02_21_095437_create_order_addon_details_table', 1),
(18, '2022_02_21_095622_create_payments_table', 1),
(19, '2022_02_23_170213_create_translations_table', 1),
(20, '2022_02_25_050554_add_addon_id', 1),
(21, '2022_03_21_035629_add_is_rtl_to_translations_table', 1),
(22, '2022_06_07_070019_add_color_code_in_order_details', 1),
(23, '2022_06_08_052536_add_created_by_in_expenses', 1),
(24, '2022_06_08_052748_add_created_by_in_expense_categories', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_option` int(11) NOT NULL DEFAULT '1',
  `delivery_option` int(11) NOT NULL DEFAULT '1',
  `outlet_id` int(11) NOT NULL DEFAULT '0',
  `delivery_outlet_id` int(11) NOT NULL DEFAULT '0',
  `workstation_id` int(11) NOT NULL DEFAULT '0',
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `delivery_type_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ready_date` datetime DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `delivered_date` datetime DEFAULT NULL,
  `pickup_date` datetime DEFAULT NULL,
  `pickup_address_id` int(11) NOT NULL DEFAULT '0',
  `pickup_flat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_address` text COLLATE utf8mb4_unicode_ci,
  `pickup_route_suggestion` text COLLATE utf8mb4_unicode_ci,
  `pickup_address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_other` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_pincode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_address_id` int(11) DEFAULT '0',
  `delivery_flat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `delivery_route_suggestion` text COLLATE utf8mb4_unicode_ci,
  `delivery_address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_other` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_pincode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_driver_id` int(11) NOT NULL DEFAULT '0',
  `delivery_driver_id` int(11) NOT NULL DEFAULT '0',
  `pickup_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` double(15,2) NOT NULL DEFAULT '0.00',
  `addon_total` double(15,2) NOT NULL DEFAULT '0.00',
  `delivery_charge` double(15,2) NOT NULL DEFAULT '0.00',
  `express_charge` double(15,2) NOT NULL DEFAULT '0.00',
  `discount` double(15,2) NOT NULL DEFAULT '0.00',
  `tax_percentage` double(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` double(15,2) NOT NULL DEFAULT '0.00',
  `total` double(15,2) NOT NULL DEFAULT '0.00',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `instruction` text COLLATE utf8mb4_unicode_ci,
  `voucher_id` int(11) NOT NULL DEFAULT '0',
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_discount` double(15,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '0',
  `flag` int(11) NOT NULL DEFAULT '0' COMMENT '1 = active for rewash,\r\n0= inactive',
  `rating` int(11) DEFAULT NULL,
  `feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_type` int(11) DEFAULT NULL,
  `cancel_request` int(11) NOT NULL DEFAULT '0' COMMENT '1=request sent\r\n2=approved\r\n3=decline',
  `cancel_by` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `financial_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_addon_details`
--

CREATE TABLE `order_addon_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `order_detail_id` bigint(20) UNSIGNED NOT NULL,
  `addon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `addon_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addon_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_type_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_price` double(15,2) DEFAULT NULL,
  `service_quantity` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defected_quantity` int(11) DEFAULT '0',
  `service_detail_total` double(15,2) DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `color_code` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details_details`
--

CREATE TABLE `order_details_details` (
  `id` int(11) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `order_detail_id` bigint(20) UNSIGNED NOT NULL,
  `garment_tag_id` varchar(255) DEFAULT NULL,
  `image` varchar(155) DEFAULT NULL,
  `remarks` text,
  `rewash_image` varchar(255) DEFAULT NULL,
  `rewash_note` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `accepted` int(11) NOT NULL,
  `ready_at` timestamp NULL DEFAULT NULL,
  `rewash_confirm` int(11) DEFAULT '0',
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `outlets`
--

CREATE TABLE `outlets` (
  `id` bigint(20) NOT NULL,
  `outlet_code` varchar(255) DEFAULT NULL,
  `outlet_name` varchar(255) DEFAULT NULL,
  `workstation_id` int(11) NOT NULL DEFAULT '0',
  `outlet_address` text NOT NULL,
  `outlet_phone` varchar(255) DEFAULT NULL,
  `outlet_latitude` varchar(100) DEFAULT NULL,
  `outlet_longitude` varchar(100) DEFAULT NULL,
  `google_map` text,
  `google_reviews` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `outlet_drivers`
--

CREATE TABLE `outlet_drivers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `outlet_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `received_amount` double(15,2) NOT NULL DEFAULT '0.00',
  `payment_type` int(11) NOT NULL,
  `payment_note` longtext COLLATE utf8mb4_unicode_ci,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `financial_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 9, 'auth_token', '0fcdaa5db3b8fc0f67aebe29c791783490d69282510118e21ebad91450e78a8b', '[\"*\"]', NULL, '2022-12-23 17:17:25', '2022-12-23 17:17:25'),
(2, 'App\\Models\\User', 9, 'auth_token', 'fc732ba97d60316d0cd8a52581e3c48430546ead3fcfbcec90b17f9145e2fe37', '[\"*\"]', '2022-12-26 12:19:59', '2022-12-26 11:43:17', '2022-12-26 12:19:59'),
(3, 'App\\Models\\User', 9, 'auth_token', '06c36ae4dcfab1a6caede464082311556e5b854ccdac4d28bd94fb5cd7d6ef60', '[\"*\"]', NULL, '2022-12-26 11:53:21', '2022-12-26 11:53:21'),
(10, 'App\\Models\\User', 9, 'auth_token', '5c03dbce7146bd263d75acefaa14466551cf3a5fcd2761cbeadf015d6b7a37de', '[\"*\"]', NULL, '2022-12-26 12:03:42', '2022-12-26 12:03:42'),
(13, 'App\\Models\\User', 9, 'auth_token', 'edb755e797ff6184f02d82a4c47579e759de852f806532250d12a10b97888f55', '[\"*\"]', NULL, '2022-12-26 12:20:54', '2022-12-26 12:20:54'),
(14, 'App\\Models\\User', 9, 'auth_token', 'e7a1f8b1e6234c205f8e8b4b83e3fcd840280efbbbd89b5514e97735454831bf', '[\"*\"]', '2022-12-26 15:44:09', '2022-12-26 12:24:10', '2022-12-26 15:44:09'),
(20, 'App\\Models\\Customer', 25, 'auth_token', '7e81a8e968cc15d6fa18b6472e688d8c22ac40350f902b2f4e2032166abe8366', '[\"*\"]', NULL, '2022-12-26 15:32:27', '2022-12-26 15:32:27'),
(22, 'App\\Models\\User', 9, 'auth_token', 'a4c931e178a33482c72de63b72c56c84e388319353420181f5186e1af911a9aa', '[\"*\"]', '2022-12-26 15:44:29', '2022-12-26 15:44:18', '2022-12-26 15:44:29'),
(28, 'App\\Models\\Customer', 27, 'auth_token', '6e3870f349e71db026c454569df6d67e013b66d82e5c235404714131affb82aa', '[\"*\"]', '2022-12-26 17:46:01', '2022-12-26 17:45:41', '2022-12-26 17:46:01'),
(29, 'App\\Models\\Customer', 28, 'auth_token', 'ea0abf2072675a3b1b39640950f1a96fcc279ac1d32f3c8737f26af7ea222678', '[\"*\"]', '2022-12-26 17:47:08', '2022-12-26 17:46:49', '2022-12-26 17:47:08'),
(32, 'App\\Models\\Customer', 29, 'auth_token', '1f84c1f6ad60689eac09a625764dfdc17265e0736ac4e2ca6fb26c037faac27a', '[\"*\"]', '2022-12-26 18:05:13', '2022-12-26 18:04:59', '2022-12-26 18:05:13'),
(36, 'App\\Models\\Customer', 26, 'auth_token', 'bd9c223bd1c8d6075f69819d084289889af8aaacd1bf5003646fb3e5eaae88e6', '[\"*\"]', '2022-12-26 18:16:11', '2022-12-26 18:14:51', '2022-12-26 18:16:11'),
(37, 'App\\Models\\Customer', 26, 'auth_token', 'f811342a5638878c39094a072833cf85da21d9e2c27a28385baa60d9d91febfe', '[\"*\"]', NULL, '2022-12-26 18:16:50', '2022-12-26 18:16:50'),
(38, 'App\\Models\\Customer', 26, 'auth_token', '83b11abfef8ee4a8f967030accdc40f04e6b4f6c9a19cfc39ee71097ce343b0e', '[\"*\"]', '2022-12-26 18:17:31', '2022-12-26 18:17:27', '2022-12-26 18:17:31'),
(39, 'App\\Models\\Customer', 26, 'auth_token', 'd2194f632f8f042e7a1b25bb25886cf5855e4ab012acde295b80e1884ae7b8a2', '[\"*\"]', '2022-12-26 18:20:58', '2022-12-26 18:18:36', '2022-12-26 18:20:58'),
(42, 'App\\Models\\Customer', 31, 'auth_token', '5e8734d1c54f07b707152ab36b4d40701470344d7b93335ab21858a3db927f17', '[\"*\"]', '2022-12-26 18:39:34', '2022-12-26 18:28:01', '2022-12-26 18:39:34'),
(43, 'App\\Models\\Customer', 31, 'auth_token', '62e109e41d5aea8611157621ccf40933b8f412ee7fd467511e405853ee956961', '[\"*\"]', '2022-12-26 18:44:56', '2022-12-26 18:42:39', '2022-12-26 18:44:56'),
(44, 'App\\Models\\Customer', 31, 'auth_token', 'e77e41a073633376acef67c940e6a5d47adb91f3099c58d87db55a2bf3e59cbe', '[\"*\"]', '2022-12-26 18:45:24', '2022-12-26 18:45:07', '2022-12-26 18:45:24'),
(45, 'App\\Models\\Customer', 31, 'auth_token', '2d1b13745957f935d1c5aaedf3d001046fe295a69d9711f606b51f53e553cbe1', '[\"*\"]', NULL, '2022-12-26 18:47:31', '2022-12-26 18:47:31'),
(46, 'App\\Models\\Customer', 31, 'auth_token', 'd65bea0afc337073db76e2c685ab6708da20e3f051648fc47bda00d757af614e', '[\"*\"]', '2022-12-26 18:50:19', '2022-12-26 18:50:04', '2022-12-26 18:50:19'),
(49, 'App\\Models\\Customer', 31, 'auth_token', '653106cedc331d5198b978d710830b3363fd153097bedb657612f37407971e60', '[\"*\"]', '2022-12-28 12:07:48', '2022-12-26 23:15:41', '2022-12-28 12:07:48'),
(53, 'App\\Models\\Customer', 31, 'auth_token', 'fa424dec0a954fb66c3b0fdb0c731a3200d4efe0147baf17d7f7e2389933f605', '[\"*\"]', '2022-12-27 17:17:01', '2022-12-27 12:37:48', '2022-12-27 17:17:01'),
(56, 'App\\Models\\Customer', 32, 'auth_token', '09f51d8f3a39c68ca37b054655e5b7224ec0ac8ddcb475ca33927a580eeb51e8', '[\"*\"]', '2022-12-28 11:46:38', '2022-12-28 11:45:55', '2022-12-28 11:46:38'),
(57, 'App\\Models\\Customer', 31, 'auth_token', '34891a596f549daf996875c4ec92a9f2086621e2039b0d683b1ddc38e2f0a5c6', '[\"*\"]', '2022-12-28 12:08:38', '2022-12-28 12:02:41', '2022-12-28 12:08:38'),
(58, 'App\\Models\\Customer', 33, 'auth_token', 'e046c1f75240d3db0118b2c9477d50dd670289a6c34d2576bbaa6eb7bde78ef8', '[\"*\"]', '2022-12-31 10:53:55', '2022-12-30 06:03:32', '2022-12-31 10:53:55'),
(59, 'App\\Models\\Customer', 33, 'auth_token', 'd32b65a28366add6237e2189daebb431c4408d0d5da0b8371eaf19fd19ddde01', '[\"*\"]', NULL, '2022-12-30 10:07:24', '2022-12-30 10:07:24'),
(60, 'App\\Models\\Customer', 33, 'auth_token', '7a51bc747c3df953c7770cef18c8b3d6a41f3644bd79d718d0e1f50028a6272d', '[\"*\"]', NULL, '2022-12-30 10:13:52', '2022-12-30 10:13:52'),
(61, 'App\\Models\\Customer', 33, 'auth_token', '6e8ab0a66c339e327ca7ebb183580d10219f0ddf6676641a213b5097b6cf6029', '[\"*\"]', NULL, '2022-12-30 10:47:45', '2022-12-30 10:47:45'),
(62, 'App\\Models\\Customer', 33, 'auth_token', '8bd83f07c6ba4fe8d997f022f9f6cca49c9b98f029616998c275b1cba571498c', '[\"*\"]', NULL, '2022-12-30 13:50:52', '2022-12-30 13:50:52'),
(64, 'App\\Models\\Customer', 33, 'auth_token', '4a1491de6eab462c20c8fb8b079fb6faadb794fe3bd065abefdc3ca008398ffd', '[\"*\"]', '2022-12-30 22:14:48', '2022-12-30 22:12:26', '2022-12-30 22:14:48'),
(65, 'App\\Models\\Customer', 33, 'auth_token', 'fcf513c23bfce1c6c7e52704cd8f251be99e522e19f3ff9bae742434d9209c28', '[\"*\"]', '2023-01-03 17:08:08', '2022-12-31 10:41:52', '2023-01-03 17:08:08'),
(66, 'App\\Models\\Customer', 33, 'auth_token', 'b5a6696059568d2a9d43c41a975fcd1837765ca280987ccb15128406d7b6023c', '[\"*\"]', '2022-12-31 11:47:01', '2022-12-31 11:46:18', '2022-12-31 11:47:01'),
(72, 'App\\Models\\Customer', 30, 'auth_token', '5b0dfb5688450879562073379f257511093db9a3d6a0e45e1cc149a440d8171c', '[\"*\"]', '2023-01-02 21:19:02', '2023-01-02 19:24:42', '2023-01-02 21:19:02'),
(73, 'App\\Models\\Customer', 33, 'auth_token', 'c81192f973de4b1794f5505ca5863b240e04a666f4a8559bffcca4dade75f3d3', '[\"*\"]', '2023-01-02 20:16:38', '2023-01-02 20:15:36', '2023-01-02 20:16:38'),
(74, 'App\\Models\\Customer', 33, 'auth_token', 'ec46a79382af0b49a0f4d92c15cf2bb5acee3ce4fcbdf7fd69d059480faeaae6', '[\"*\"]', '2023-01-03 15:40:10', '2023-01-03 15:20:05', '2023-01-03 15:40:10'),
(75, 'App\\Models\\Customer', 33, 'auth_token', '1b6458e713672643425306f1c176ef3119b54d7ae9c46f9ea593f4f3af4da226', '[\"*\"]', '2023-01-03 15:49:47', '2023-01-03 15:40:34', '2023-01-03 15:49:47'),
(76, 'App\\Models\\Customer', 33, 'auth_token', '75a7b78e134c3dc52aba1b3f9976ddd0500cdd97be1075608e5e4c6635db7ad1', '[\"*\"]', '2023-01-03 17:09:02', '2023-01-03 17:07:26', '2023-01-03 17:09:02'),
(77, 'App\\Models\\Customer', 33, 'auth_token', '2f98cf668b9091f0edf2a96306facd1b4df30f00ad55ca1c4866982444e7c624', '[\"*\"]', '2023-01-03 17:19:59', '2023-01-03 17:09:14', '2023-01-03 17:19:59'),
(78, 'App\\Models\\Customer', 33, 'auth_token', '7cc40c7ef8aee4454155c39900e88d0d60f5b78d476a13f5064cd56c1695a0e1', '[\"*\"]', NULL, '2023-01-03 17:15:23', '2023-01-03 17:15:23'),
(79, 'App\\Models\\User', 11, 'auth_token', '3f3c2f8de9a14ef251a258d5077edd407b106925d9edd835577f63d75e440980', '[\"*\"]', NULL, '2023-01-03 17:15:42', '2023-01-03 17:15:42'),
(80, 'App\\Models\\User', 11, 'auth_token', 'e661000948649f5a7a47d1cb897d3e94d0efc6196789537f56b6b0e814413214', '[\"*\"]', NULL, '2023-01-03 17:34:36', '2023-01-03 17:34:36'),
(81, 'App\\Models\\User', 11, 'auth_token', '0559f84cb1f0c8fd47aaad1215ac18a3d03b80a3be4faf22513d130696c6d142', '[\"*\"]', '2023-01-03 17:42:25', '2023-01-03 17:42:03', '2023-01-03 17:42:25'),
(82, 'App\\Models\\User', 11, 'auth_token', '84be9b44e8f7980c75a12cd03a5c084c4d4143d4f1106e21dd2a40d28bae5ec0', '[\"*\"]', '2023-02-23 15:55:10', '2023-01-03 17:43:12', '2023-02-23 15:55:10'),
(83, 'App\\Models\\User', 11, 'auth_token', '8b27d96445c2aca95caa5638d557c2285ba590393eb32af688d735e9f4bbacbb', '[\"*\"]', NULL, '2023-01-03 18:08:06', '2023-01-03 18:08:06'),
(93, 'App\\Models\\Customer', 33, 'auth_token', '54f513c17ac219ef269d4e2ded4ff6a3c17081f59dd041f99dd812f74a32bd39', '[\"*\"]', '2023-01-04 18:34:56', '2023-01-04 11:10:28', '2023-01-04 18:34:56'),
(94, 'App\\Models\\Customer', 36, 'auth_token', 'fb5ab402581676c03c6e7c6bc9ef8dc4a9fd4f33965253f0e1773fecd07c0b55', '[\"*\"]', '2023-01-04 18:47:17', '2023-01-04 18:35:11', '2023-01-04 18:47:17'),
(95, 'App\\Models\\Customer', 36, 'auth_token', 'f2d484de4845106fc657c004ef94654ad9fb28b80b4922afc161401eefb931eb', '[\"*\"]', '2023-01-07 12:07:12', '2023-01-05 11:05:58', '2023-01-07 12:07:12'),
(102, 'App\\Models\\Customer', 38, 'auth_token', '03db1eef1e34c048357b3a014b284b9a7390173427d4dd0533751f696a48310c', '[\"*\"]', NULL, '2023-01-06 15:18:42', '2023-01-06 15:18:42'),
(103, 'App\\Models\\Customer', 38, 'auth_token', '3136c8e13e02b5aa0a9bb8083785e90df38eda36d233e164b397915d7c6fe626', '[\"*\"]', NULL, '2023-01-06 15:21:08', '2023-01-06 15:21:08'),
(104, 'App\\Models\\Customer', 39, 'auth_token', '9f49abea24e41f76814435c86910a058671d8dbc13e5f31091fc699109d91bbd', '[\"*\"]', '2023-01-06 19:26:22', '2023-01-06 15:29:11', '2023-01-06 19:26:22'),
(105, 'App\\Models\\Customer', 34, 'auth_token', '781dcc7e284cf9a97616d16b3dd108fa641b22cd28faef79898c6e35f75fc322', '[\"*\"]', '2023-01-07 11:55:27', '2023-01-06 17:16:08', '2023-01-07 11:55:27'),
(106, 'App\\Models\\Customer', 39, 'auth_token', '7fbf7c6a88985248a60f155a97117961e0de193fc9f8c31991f9d9c85a176f4c', '[\"*\"]', NULL, '2023-01-06 18:59:41', '2023-01-06 18:59:41'),
(107, 'App\\Models\\Customer', 34, 'auth_token', 'a7365fca69673e74a3421d79819013aca0e9bcb86e37e3fe820c4ef6937ef8db', '[\"*\"]', '2023-01-06 20:32:32', '2023-01-06 19:13:56', '2023-01-06 20:32:32'),
(108, 'App\\Models\\Customer', 39, 'auth_token', '669bbe1d0b248fb34d01a09616aeb08bfa6b6529e2fdb99861fa3042492918f3', '[\"*\"]', '2023-01-23 12:05:32', '2023-01-07 11:41:44', '2023-01-23 12:05:32'),
(110, 'App\\Models\\Customer', 40, 'auth_token', '63b2daf7361918c6c669558f393a53e7572855a30cd82c7836d8d76800d272e5', '[\"*\"]', '2023-01-07 12:21:59', '2023-01-07 12:06:19', '2023-01-07 12:21:59'),
(111, 'App\\Models\\Customer', 40, 'auth_token', 'a9a5cf5e38306a0be113e3745395569817f91883ca4ee03a2af947373d2a7fc0', '[\"*\"]', '2023-01-09 13:15:12', '2023-01-08 07:17:52', '2023-01-09 13:15:12'),
(112, 'App\\Models\\Customer', 40, 'auth_token', '454cc2a1222eed0d8b14b5fba73f9d2c83c6f33a761cf862190f936ea2693685', '[\"*\"]', '2023-01-08 18:41:43', '2023-01-08 10:12:25', '2023-01-08 18:41:43'),
(113, 'App\\Models\\Customer', 39, 'auth_token', '546679cae89b2a28d5f664687ecadc50578d99eae92cc53d14c508675ac33784', '[\"*\"]', '2023-01-09 13:39:50', '2023-01-08 10:16:55', '2023-01-09 13:39:50'),
(114, 'App\\Models\\Customer', 40, 'auth_token', '17fe0d955eb5abbb2323fb942b02e2c8141af4a39c1b6b4ade67de822e3f6906', '[\"*\"]', '2023-01-10 15:22:45', '2023-01-08 22:54:17', '2023-01-10 15:22:45'),
(115, 'App\\Models\\Customer', 36, 'auth_token', '49b963986dda0dd3d5befdc753b1ee826cf59bfda01011689ce991db2b9a0b43', '[\"*\"]', '2023-01-10 10:20:07', '2023-01-10 10:17:37', '2023-01-10 10:20:07'),
(116, 'App\\Models\\Customer', 40, 'auth_token', '96882be13bd1fac686f2edf2a272706e3c8fe2681b046b360de124a0fcd3571a', '[\"*\"]', '2023-01-18 17:56:30', '2023-01-10 10:38:39', '2023-01-18 17:56:30'),
(119, 'App\\Models\\Customer', 36, 'auth_token', 'ab66573c4ee04760c9dde43c92214b6af8d52811bfaaacc08df9d4bdce2ea213', '[\"*\"]', NULL, '2023-01-10 16:50:38', '2023-01-10 16:50:38'),
(120, 'App\\Models\\Customer', 36, 'auth_token', 'be6cbe524f875b4806892f6f11ea48fa7d339d5acd0c5d57bdbe227d860eede6', '[\"*\"]', NULL, '2023-01-10 16:50:49', '2023-01-10 16:50:49'),
(121, 'App\\Models\\Customer', 36, 'auth_token', '0ab52b939bb3f58aea40250e4cef2bbcdd19a09e573e3660ac849d103ace76f3', '[\"*\"]', '2023-01-10 17:13:27', '2023-01-10 16:50:52', '2023-01-10 17:13:27'),
(122, 'App\\Models\\Customer', 36, 'auth_token', 'ca3f73b235fdcb59b1598e8282cb4d00e33c763f799bbe000a259bb523b08726', '[\"*\"]', '2023-01-10 17:26:41', '2023-01-10 17:26:03', '2023-01-10 17:26:41'),
(123, 'App\\Models\\Customer', 36, 'auth_token', '9256659c4b2abb398825830605f5d64845db9725f2cca3e802ff032be0592c30', '[\"*\"]', NULL, '2023-01-12 19:13:57', '2023-01-12 19:13:57'),
(124, 'App\\Models\\Customer', 36, 'auth_token', '5e56abb195b6d7241e1ec27b635176a247a1e750b9caab63fb8910302fcd333b', '[\"*\"]', '2023-01-15 22:57:23', '2023-01-15 22:56:39', '2023-01-15 22:57:23'),
(125, 'App\\Models\\Customer', 47, 'auth_token', '16af478e7bcea27653cb2c071c1edb1d19e10d479f865cbf924d990ea9ce2031', '[\"*\"]', '2023-01-17 16:30:54', '2023-01-17 16:30:17', '2023-01-17 16:30:54'),
(126, 'App\\Models\\Customer', 47, 'auth_token', '6f8caab8187c2ab9f00f6f9acc6ae4a9d58715864e6e695b341257f1bf1391df', '[\"*\"]', '2023-01-30 11:27:52', '2023-01-18 18:59:30', '2023-01-30 11:27:52'),
(143, 'App\\Models\\Customer', 50, 'auth_token', 'adf5310d1b7abc2bd25194df62ecdceb98e05c59fdc75469926781b4f410e9ed', '[\"*\"]', '2023-01-25 15:33:24', '2023-01-25 15:33:02', '2023-01-25 15:33:24'),
(144, 'App\\Models\\Customer', 50, 'auth_token', '634ec0f43d8d720c80a1d0dc6c5bbde8aa65d0c558391c2f40b95a303f55f537', '[\"*\"]', '2023-02-03 13:35:00', '2023-01-25 16:15:51', '2023-02-03 13:35:00'),
(151, 'App\\Models\\Customer', 50, 'auth_token', 'cad665e72aa46045ac6d0a9c357820304f24a6e2ae46adcb022eec1c758fa418', '[\"*\"]', '2023-01-31 12:10:33', '2023-01-30 11:46:20', '2023-01-31 12:10:33'),
(152, 'App\\Models\\Customer', 50, 'auth_token', '2c6f1e93b521276e06a0b8c810e015415daeb006bdef71486e9d49dd226c8db3', '[\"*\"]', '2023-02-08 17:17:54', '2023-01-30 13:30:04', '2023-02-08 17:17:54'),
(153, 'App\\Models\\Customer', 50, 'auth_token', '5e2109d2bfcb1feef926890c75222de6d81994c00bc04dd6005010031cf7b74b', '[\"*\"]', '2023-01-31 14:59:20', '2023-01-31 14:26:36', '2023-01-31 14:59:20'),
(154, 'App\\Models\\Customer', 50, 'auth_token', '0fca7416f466292acb616b87c0f70481c21a5ddd63d79255c24ac6a887feef30', '[\"*\"]', '2023-01-31 15:21:45', '2023-01-31 15:21:33', '2023-01-31 15:21:45'),
(157, 'App\\Models\\Customer', 50, 'auth_token', 'd2968dcae87824afbd555defd4ebf5543e3905d36dd727e027ebc46df8b5688e', '[\"*\"]', '2023-01-31 19:01:29', '2023-01-31 18:54:47', '2023-01-31 19:01:29'),
(159, 'App\\Models\\Customer', 50, 'auth_token', '159e783d760162a9b57bf48fdc8163235075acbcfa5e563d3bf19b1886a3d8f2', '[\"*\"]', '2023-02-01 15:52:15', '2023-02-01 10:15:18', '2023-02-01 15:52:15'),
(163, 'App\\Models\\Customer', 50, 'auth_token', 'd25ba1648fc64e4b3ec2192322a035ee8d11f36a4a82c8bc6950c6fac5413651', '[\"*\"]', '2023-02-01 15:48:41', '2023-02-01 15:10:14', '2023-02-01 15:48:41'),
(167, 'App\\Models\\Customer', 50, 'auth_token', 'f10e688f8d737e58ccdc11f93516069448ede71feb0f2cab5c6bd476c48fd757', '[\"*\"]', NULL, '2023-02-02 16:34:19', '2023-02-02 16:34:19'),
(168, 'App\\Models\\Customer', 50, 'auth_token', '7be0faf77e52703bda95bf3ace01a30c99fc33572a5616e0520c03e22f215ab2', '[\"*\"]', '2023-02-03 10:33:13', '2023-02-02 17:49:32', '2023-02-03 10:33:13'),
(171, 'App\\Models\\Customer', 50, 'auth_token', '28380708b4b7d39c8bdc732b7e9cc6ab382302c4039276bc73d167b982f8155e', '[\"*\"]', '2023-02-06 13:24:18', '2023-02-03 11:11:42', '2023-02-06 13:24:18'),
(211, 'App\\Models\\Customer', 50, 'auth_token', 'c8b5d84fcbbfb5011c8b8210b33a6a20e872c4ff3c60e27c70ff2c1c9b4cb8ed', '[\"*\"]', NULL, '2023-02-06 18:26:46', '2023-02-06 18:26:46'),
(212, 'App\\Models\\Customer', 55, 'auth_token', '751005567b08cda51398dd5e7d846ef915898598b7261013fcd430a810e931a1', '[\"*\"]', NULL, '2023-02-06 18:27:01', '2023-02-06 18:27:01'),
(213, 'App\\Models\\Customer', 50, 'auth_token', 'af9019676ffa0f646bbdc917c231be6bd1f4c3957032d17ad8a3cd5d75025fba', '[\"*\"]', NULL, '2023-02-06 18:27:50', '2023-02-06 18:27:50'),
(218, 'App\\Models\\Customer', 56, 'auth_token', '28bb480f4da88fa7809cb3782e07bbea830549a49f28da7f97daf24ed3ebef04', '[\"*\"]', NULL, '2023-02-07 17:51:59', '2023-02-07 17:51:59'),
(219, 'App\\Models\\Customer', 56, 'auth_token', '11907964ef41ca6f63b36f880b2d5459a2427b96443f2c0fd8a42d4538269be3', '[\"*\"]', '2023-02-07 18:20:44', '2023-02-07 17:53:05', '2023-02-07 18:20:44'),
(226, 'App\\Models\\Customer', 50, 'auth_token', 'a1ef550791e18c889301be58fe6dc7d129041db11a3dea1b03db1f139838bfbe', '[\"*\"]', NULL, '2023-02-08 11:55:39', '2023-02-08 11:55:39'),
(229, 'App\\Models\\Customer', 50, 'auth_token', 'f04796c1c9a8a760bc7d4c254645a75d762e507f93baee2290639641a2069d5d', '[\"*\"]', '2023-02-11 13:27:39', '2023-02-08 13:54:58', '2023-02-11 13:27:39'),
(230, 'App\\Models\\Customer', 59, 'auth_token', 'd1238f1e3b09d21766b37deabce5a4d9ccf05205b928cd46e767063dd51f3f3f', '[\"*\"]', '2023-02-14 10:38:56', '2023-02-08 14:47:56', '2023-02-14 10:38:56'),
(231, 'App\\Models\\Customer', 50, 'auth_token', '98cf30cbcc56cfcb0f2e7a9a49f45077a0194ef23db20de507bb6ed7367386c1', '[\"*\"]', '2023-02-08 16:46:52', '2023-02-08 16:44:44', '2023-02-08 16:46:52'),
(232, 'App\\Models\\Customer', 50, 'auth_token', '72351cfa67b340fffdd5cca78f56c48331e8fea30432ca8554e7015d1a3ba706', '[\"*\"]', '2023-02-08 17:19:26', '2023-02-08 16:57:25', '2023-02-08 17:19:26'),
(245, 'App\\Models\\Customer', 62, 'auth_token', 'c6806dc9636becb9dd4464669796caa5b21fa8525a067a382550c85d3abdf6b5', '[\"*\"]', NULL, '2023-02-10 12:14:03', '2023-02-10 12:14:03'),
(251, 'App\\Models\\Customer', 50, 'auth_token', '74217a1913f1203143bdc519c80d364068a5551ed0a2e0b079134af7d01b1cf3', '[\"*\"]', '2023-02-10 15:06:35', '2023-02-10 14:39:22', '2023-02-10 15:06:35'),
(252, 'App\\Models\\Customer', 50, 'auth_token', '092e3921ae649a0e2aa40b85f65edfab33339d49506fbac2252db3020a397ff0', '[\"*\"]', '2023-02-10 19:07:49', '2023-02-10 17:46:42', '2023-02-10 19:07:49'),
(256, 'App\\Models\\Customer', 50, 'auth_token', '074917548253bcae35934147b1ca952a23c3dc6a855d80f16bcb0c20f30fb8d3', '[\"*\"]', NULL, '2023-02-12 10:52:43', '2023-02-12 10:52:43'),
(263, 'App\\Models\\Customer', 64, 'auth_token', '66184c5e59256019f01b9d48271b22ceeea06b5100a1bf6f881d96e9be9e39c4', '[\"*\"]', '2023-02-13 11:30:50', '2023-02-13 09:42:49', '2023-02-13 11:30:50'),
(264, 'App\\Models\\Customer', 64, 'auth_token', 'fabaef1804f6aeb104fbcb0947fb2d70422a26746e1a142fa0be82a094ead32b', '[\"*\"]', '2023-02-13 10:43:27', '2023-02-13 09:47:39', '2023-02-13 10:43:27'),
(265, 'App\\Models\\Customer', 69, 'auth_token', 'b87fc3e790739adc27783cce7f0207c019f217753dc6591fa4241b7dd88883e7', '[\"*\"]', '2023-02-13 14:14:33', '2023-02-13 10:00:37', '2023-02-13 14:14:33'),
(266, 'App\\Models\\Customer', 50, 'auth_token', '736d17f1c5fe51231180a9ae725ca2595f2a97f6f537e800260a7d73300089fb', '[\"*\"]', '2023-02-13 10:49:07', '2023-02-13 10:44:24', '2023-02-13 10:49:07'),
(267, 'App\\Models\\Customer', 50, 'auth_token', '963e3f3c9e4a76ef5769417a38d0a3a63407eac428b94938d224719a7d82f4d5', '[\"*\"]', '2023-02-13 10:56:40', '2023-02-13 10:49:49', '2023-02-13 10:56:40'),
(268, 'App\\Models\\Customer', 50, 'auth_token', 'd13232e9eccc6908d78cd5c73e56e626c8b98c3b2a428df715927242b4487374', '[\"*\"]', '2023-02-13 11:35:08', '2023-02-13 11:24:44', '2023-02-13 11:35:08'),
(269, 'App\\Models\\Customer', 64, 'auth_token', '60ffc5dc8e050f78c7923249dab0977a1a58391e021ae3dce102e866dcef90cd', '[\"*\"]', '2023-02-13 11:35:14', '2023-02-13 11:25:33', '2023-02-13 11:35:14'),
(270, 'App\\Models\\Customer', 51, 'auth_token', '5cd36f27d4462f68268f58781b6d9d8a9433b63bc0e3cabf891c2cff64e513e1', '[\"*\"]', '2023-02-13 11:36:12', '2023-02-13 11:35:54', '2023-02-13 11:36:12'),
(271, 'App\\Models\\Customer', 47, 'auth_token', '498680a0e3a803b22f197359a3eaa328ac419f0efd9fb7bf50ea3af6ce84f923', '[\"*\"]', '2023-02-13 11:36:42', '2023-02-13 11:36:33', '2023-02-13 11:36:42'),
(272, 'App\\Models\\Customer', 70, 'auth_token', 'a77610ed82a7235ce2d789c4674adf4d07547fbc934d8da2168fcbb9baab0f3d', '[\"*\"]', '2023-02-13 11:39:09', '2023-02-13 11:39:00', '2023-02-13 11:39:09'),
(273, 'App\\Models\\Customer', 71, 'auth_token', '6aa0e56a1dba12a6179c98dd0e5d48ae94a2e53a375052af3eee5022a081dd22', '[\"*\"]', '2023-02-13 11:40:25', '2023-02-13 11:40:13', '2023-02-13 11:40:25'),
(274, 'App\\Models\\Customer', 72, 'auth_token', 'a4e1eb5057c3b3c26af547f44196627a93f277c5e2957ea66b3bde735bd51170', '[\"*\"]', '2023-02-13 11:42:07', '2023-02-13 11:41:58', '2023-02-13 11:42:07'),
(275, 'App\\Models\\Customer', 73, 'auth_token', 'd5c3a70a6ce414ef4a894b34576b04d5b813ac666e58c1c6005c3e4f33d1644c', '[\"*\"]', '2023-02-13 11:42:51', '2023-02-13 11:42:41', '2023-02-13 11:42:51'),
(276, 'App\\Models\\Customer', 74, 'auth_token', 'd6bd5dc0a4f237cf750197a72bceea31810500aba67f3d70f81a019688945abf', '[\"*\"]', '2023-02-13 11:59:19', '2023-02-13 11:43:54', '2023-02-13 11:59:19'),
(280, 'App\\Models\\Customer', 76, 'auth_token', 'd263100db4f177b4fb7c303fc04749bdaf962fca9c5c027f7dbccea3d22dd76c', '[\"*\"]', '2023-02-13 12:21:53', '2023-02-13 12:05:56', '2023-02-13 12:21:53'),
(281, 'App\\Models\\Customer', 76, 'auth_token', '0dc2522aa8e546987f9c12403dd704d33be73f5daee47ac2962a35fb4834a650', '[\"*\"]', '2023-02-13 12:26:48', '2023-02-13 12:21:53', '2023-02-13 12:26:48'),
(284, 'App\\Models\\Customer', 76, 'auth_token', 'd527c1cd73c72f8d6b7c06f9e98273fd8fb01cd3a5fede5cd876193740fd74ef', '[\"*\"]', '2023-02-13 18:30:53', '2023-02-13 12:38:07', '2023-02-13 18:30:53'),
(286, 'App\\Models\\Customer', 76, 'auth_token', '8b4c87d65968e9a518d356d9280b25e551181b9cf9574f0fcef2bd94a1a78baa', '[\"*\"]', NULL, '2023-02-13 18:13:58', '2023-02-13 18:13:58'),
(288, 'App\\Models\\Customer', 75, 'auth_token', 'e9a739b34dc662f6b9202dfd81929d111b2cb15516f67b6f7b46e6c6ea517ba7', '[\"*\"]', '2023-02-13 23:20:52', '2023-02-13 23:09:37', '2023-02-13 23:20:52'),
(292, 'App\\Models\\Customer', 75, 'auth_token', '740d57b3e6b8a8f080c4affa7d5ceef4a1d271235323d10c85b134adb5871e94', '[\"*\"]', '2023-02-14 10:14:13', '2023-02-14 09:53:28', '2023-02-14 10:14:13'),
(293, 'App\\Models\\Customer', 75, 'auth_token', '58d59f1963ac1a659b90d369e208ea9bacee42412dc2c1a25b023ec22b234353', '[\"*\"]', '2023-02-14 10:11:45', '2023-02-14 09:54:15', '2023-02-14 10:11:45'),
(294, 'App\\Models\\Customer', 76, 'auth_token', '040fbebb715e8a23cfe9d0dcd30523d4fe4ce9482807ce6384eb6ceea6a3d145', '[\"*\"]', '2023-02-14 17:10:18', '2023-02-14 09:58:46', '2023-02-14 17:10:18'),
(296, 'App\\Models\\Customer', 78, 'auth_token', '9d743a858e4215dd2a171e2161a88e4e3ea5b43b34ef8c4fd7f8337f6c57255e', '[\"*\"]', '2023-02-14 12:31:01', '2023-02-14 12:07:07', '2023-02-14 12:31:01'),
(297, 'App\\Models\\Customer', 79, 'auth_token', '877bbf6deed3997c5f0442dcf5a840029d70c324363dbab4ce12d236e8a0d2cb', '[\"*\"]', '2023-02-14 12:35:41', '2023-02-14 12:31:01', '2023-02-14 12:35:41'),
(298, 'App\\Models\\Customer', 78, 'auth_token', '5962aaf8adb009d4bb90804d0cb6e40a5e1acfe46c30662110955ac1a4af5a26', '[\"*\"]', '2023-02-14 12:33:13', '2023-02-14 12:32:59', '2023-02-14 12:33:13'),
(302, 'App\\Models\\Customer', 76, 'auth_token', '0accbfc457074a3b2c9d17b268dda97affe1c38e5dda84956c4f6e6e226d39be', '[\"*\"]', '2023-02-14 17:11:42', '2023-02-14 16:49:19', '2023-02-14 17:11:42'),
(324, 'App\\Models\\Customer', 80, 'auth_token', 'a341c2e154b9bc43f79c99fcdfe1ac171a270575ca379dce2c52170b120753b9', '[\"*\"]', '2023-02-16 19:14:01', '2023-02-16 15:19:17', '2023-02-16 19:14:01'),
(325, 'App\\Models\\Customer', 80, 'auth_token', 'dfa1a8ed52443a94074b5171058b417fc4f072d2111ae6cfa28744f5436d862d', '[\"*\"]', NULL, '2023-02-16 15:56:32', '2023-02-16 15:56:32'),
(326, 'App\\Models\\Customer', 80, 'auth_token', '5b30afa7439e6b9b6537ff30e1a28c262d3f245eba51d0096fd1ffe42fd28c0c', '[\"*\"]', '2023-02-16 18:46:13', '2023-02-16 16:13:05', '2023-02-16 18:46:13'),
(330, 'App\\Models\\Customer', 80, 'auth_token', '53bad024d42f306bff1efa892dfd4f59d606f24cd09698097e839014cb20451a', '[\"*\"]', '2023-02-16 22:45:52', '2023-02-16 18:46:13', '2023-02-16 22:45:52'),
(333, 'App\\Models\\Customer', 80, 'auth_token', 'cdcf4ff58b6ada90bc0d92d1e15396f12b1c15f402223877f30e6cce3ecf8e45', '[\"*\"]', '2023-02-16 22:47:24', '2023-02-16 22:45:53', '2023-02-16 22:47:24'),
(334, 'App\\Models\\Customer', 79, 'auth_token', 'f3e1a5a6a9c7a77c5cf99e0ffff8b4fcd0f13d5d40a1def24743995ab1c98fae', '[\"*\"]', '2023-02-17 13:14:11', '2023-02-16 22:47:24', '2023-02-17 13:14:11'),
(338, 'App\\Models\\Customer', 80, 'auth_token', '1cbc60f74fd00271882fb257c7f002e8a95701b23257bfbc8a88c238b7d0845d', '[\"*\"]', '2023-02-17 16:22:24', '2023-02-17 13:14:11', '2023-02-17 16:22:24'),
(347, 'App\\Models\\Customer', 80, 'auth_token', '9f970c67f14561f0bcf20d983ad57f985b0f0f99dddc1fa205a2a90a3e39d9c3', '[\"*\"]', '2023-02-17 21:57:29', '2023-02-17 21:22:02', '2023-02-17 21:57:29'),
(348, 'App\\Models\\Customer', 80, 'auth_token', '4eb7b8a677623135f61a027b6456960069bda0c6428bf84f39a0ef9ffbc17707', '[\"*\"]', '2023-02-17 21:26:39', '2023-02-17 21:23:25', '2023-02-17 21:26:39'),
(364, 'App\\Models\\Customer', 81, 'auth_token', '85ed2044c64f6e966d755089ecf1128037ae3c7fd3b90a023f12c6b3c44ace68', '[\"*\"]', '2023-02-20 12:31:05', '2023-02-19 18:19:03', '2023-02-20 12:31:05'),
(396, 'App\\Models\\Customer', 99, 'auth_token', 'b7ee9535234a4d6b9110418059e7eac304770e208ad7ac61b1c88927c0e4db3a', '[\"*\"]', '2023-02-21 09:55:36', '2023-02-21 08:10:45', '2023-02-21 09:55:36'),
(397, 'App\\Models\\Customer', 100, 'auth_token', '939ba86eb045756521e5ae14b1e6d0ba40b411795b509817769a4036a7aca177', '[\"*\"]', '2023-02-21 09:28:11', '2023-02-21 09:27:41', '2023-02-21 09:28:11'),
(398, 'App\\Models\\Customer', 101, 'auth_token', 'eca60d9130d680a75cff02c9ba3da55717f683286aa1f82d256d8a15d3b1ec0f', '[\"*\"]', '2023-02-21 10:00:35', '2023-02-21 09:55:11', '2023-02-21 10:00:35'),
(399, 'App\\Models\\Customer', 99, 'auth_token', '7ba418e42579e18b473d088d28f31e0f1f57e15b233c81b531b38fe3fb6c1590', '[\"*\"]', '2023-02-21 09:58:57', '2023-02-21 09:55:51', '2023-02-21 09:58:57'),
(403, 'App\\Models\\Customer', 97, 'auth_token', '20f6483b406cbb3db86335c6014782b83392d8f3e7cb1c22c4bfcab92386edba', '[\"*\"]', '2023-02-21 10:04:11', '2023-02-21 10:04:04', '2023-02-21 10:04:11'),
(432, 'App\\Models\\Customer', 104, 'auth_token', '7471ac4067ae543448bf85cbc7a7caeb0dea09b3bfbf0d57a1aa6300a088ebab', '[\"*\"]', '2023-02-22 14:33:10', '2023-02-22 12:17:40', '2023-02-22 14:33:10'),
(438, 'App\\Models\\Customer', 106, 'auth_token', '7a831a213efd9353dc6a4f61c596a1b862048aa989764c83e18e75dd291b2765', '[\"*\"]', '2023-02-22 12:53:57', '2023-02-22 12:49:43', '2023-02-22 12:53:57'),
(439, 'App\\Models\\Customer', 106, 'auth_token', '95ec8cc396c56a9415af98d31b6192dc51470930c32bacc58ca41be9f510a7de', '[\"*\"]', '2023-02-22 14:23:44', '2023-02-22 12:53:57', '2023-02-22 14:23:44'),
(440, 'App\\Models\\Customer', 106, 'auth_token', 'a4cece863ef0ddde59ea96b6bb0e2eca5c3e9682473b384feda7c38a4d06c6cd', '[\"*\"]', '2023-02-22 13:48:48', '2023-02-22 13:40:25', '2023-02-22 13:48:48'),
(442, 'App\\Models\\Customer', 104, 'auth_token', '7d123cd4f068be392323a90b7752f81d13b7aae4576ee6cf70d6749751f2c8af', '[\"*\"]', '2023-02-22 14:20:29', '2023-02-22 14:00:10', '2023-02-22 14:20:29'),
(452, 'App\\Models\\Customer', 104, 'auth_token', 'a2f4af858985140d43f68e4635db7770c3cc67b460bdc79a3c872aef823b686b', '[\"*\"]', NULL, '2023-02-22 14:43:53', '2023-02-22 14:43:53'),
(455, 'App\\Models\\Customer', 108, 'auth_token', '7b8b1260c8d931563ab7cd90ffa3cd6b27e5af344cc3780c85824099126fe9fa', '[\"*\"]', '2023-02-22 17:31:54', '2023-02-22 16:22:39', '2023-02-22 17:31:54'),
(456, 'App\\Models\\Customer', 102, 'auth_token', 'c99563219281527c3be320d8edf4088a3bf77f6f057c576f662b6b775ba4106d', '[\"*\"]', '2023-02-22 18:18:40', '2023-02-22 16:31:29', '2023-02-22 18:18:40'),
(460, 'App\\Models\\Customer', 102, 'auth_token', '77b6211a151a17c3cd76f80b42fcec82937467d072d9451159dc869f80217598', '[\"*\"]', '2023-02-22 18:19:47', '2023-02-22 18:18:40', '2023-02-22 18:19:47'),
(469, 'App\\Models\\Customer', 111, 'auth_token', 'a087e07800229afeb0900818889bb031a0d843ddd5e89c536b91a62bb22f1401', '[\"*\"]', '2023-02-23 11:09:33', '2023-02-23 08:03:00', '2023-02-23 11:09:33'),
(474, 'App\\Models\\Customer', 112, 'auth_token', 'ee26e68ebd1b83c2250c8fb1ea991135e0d45a19ff4b336de77ce1d57f5ef7c5', '[\"*\"]', '2023-02-23 14:05:17', '2023-02-23 11:58:23', '2023-02-23 14:05:17'),
(475, 'App\\Models\\Customer', 111, 'auth_token', 'f41f24b205a88857816b6a6b398ab0b0736203bb4ef695b31250859487bae0c1', '[\"*\"]', '2023-02-23 18:46:09', '2023-02-23 12:24:53', '2023-02-23 18:46:09'),
(482, 'App\\Models\\Customer', 102, 'auth_token', 'ad7ddcc6050291fbf594066f1565ad5d53c0c22666e517af61c9e7ebbb0932ce', '[\"*\"]', '2023-02-23 15:28:22', '2023-02-23 15:19:54', '2023-02-23 15:28:22'),
(484, 'App\\Models\\Customer', 109, 'auth_token', '280737fd55dd5b0634991f1de50843c40ef26ed8b8f1e20c1106383110031669', '[\"*\"]', '2023-02-23 15:37:14', '2023-02-23 15:28:22', '2023-02-23 15:37:14'),
(493, 'App\\Models\\Customer', 102, 'auth_token', '1e6cebff24f12b6b2d26293f34ad9aec295d79f38841b57e8da33c033dcc0749', '[\"*\"]', '2023-02-25 06:05:35', '2023-02-23 16:41:19', '2023-02-25 06:05:35'),
(503, 'App\\Models\\Customer', 114, 'auth_token', '73910617ed53366d3964ea760b978389945728ff048df0e5f4c932bd84f9f858', '[\"*\"]', '2023-04-22 13:15:18', '2023-02-23 21:27:43', '2023-04-22 13:15:18'),
(505, 'App\\Models\\Customer', 109, 'auth_token', 'd0f7ffc20239a97b100bac2ee4db7b7c9f4eb3d68578011f3c8d089770ae215d', '[\"*\"]', NULL, '2023-02-24 10:20:54', '2023-02-24 10:20:54'),
(508, 'App\\Models\\Customer', 110, 'auth_token', 'd129be9c77a1f0337284dfa93188787b735c50544fa9ed579e26344be98fad23', '[\"*\"]', '2023-02-25 18:58:49', '2023-02-24 16:23:10', '2023-02-25 18:58:49'),
(510, 'App\\Models\\Customer', 102, 'auth_token', '4a4bce18f0ad6548f2a5f5b0c9fe9ba46cf8c756c9c7f7e7d03f4404058ad17f', '[\"*\"]', '2023-02-28 09:32:45', '2023-02-24 18:57:20', '2023-02-28 09:32:45'),
(514, 'App\\Models\\Customer', 110, 'auth_token', '8764736d9cc7bdfc5339ed2cfa7235fd5189b4bc9153a4ecff38a9dbbcf18bc5', '[\"*\"]', '2023-02-25 20:14:00', '2023-02-25 18:58:49', '2023-02-25 20:14:00'),
(527, 'App\\Models\\Customer', 102, 'auth_token', '9dda5d4707cdb61d34c53217dc5a13276d745afb048f6a0b9512ebe1dc569408', '[\"*\"]', NULL, '2023-02-27 13:46:19', '2023-02-27 13:46:19'),
(541, 'App\\Models\\Customer', 109, 'auth_token', 'e57730f0e9682fc65dde5726f3cfe4d7cc69639df4780974f2e19a489aa447b3', '[\"*\"]', '2023-02-28 10:00:36', '2023-02-28 09:32:45', '2023-02-28 10:00:36'),
(543, 'App\\Models\\User', 4, 'auth_token', '833446cfd6cae842c295d5a4e4c6a6f578b8a15386066bc6763f770e6b99fefc', '[\"*\"]', '2023-02-28 11:26:45', '2023-02-28 10:08:24', '2023-02-28 11:26:45'),
(544, 'App\\Models\\User', 4, 'auth_token', 'e4b9974140a8169cf48acbe34e81cf818d18ab72dec97a929997803e3a8d3ec2', '[\"*\"]', '2023-02-28 11:33:50', '2023-02-28 11:27:12', '2023-02-28 11:33:50'),
(545, 'App\\Models\\Customer', 52, 'auth_token', '7b25ebf0885ad4137c8373240d6d62497aa5b97c5eae7dfb189db3256a7a586e', '[\"*\"]', '2023-02-28 11:57:07', '2023-02-28 11:56:29', '2023-02-28 11:57:07'),
(546, 'App\\Models\\Customer', 52, 'auth_token', '02ca629c5fe369748a9d8939ecb7a616d23777a684bd3e1759917d74a1fd24fe', '[\"*\"]', '2023-02-28 11:58:06', '2023-02-28 11:58:03', '2023-02-28 11:58:06'),
(548, 'App\\Models\\Customer', 52, 'auth_token', '6e14c96b27f8d33d7c8d37321882fd8a3fd81a116cea3eb6a079ff87f4035568', '[\"*\"]', '2023-03-24 20:27:55', '2023-02-28 12:19:55', '2023-03-24 20:27:55'),
(550, 'App\\Models\\User', 12, 'auth_token', 'ae3fd2a4ab1a3f73333d0a9ffc1013732c9f1a35529244cc2f3f080cfb333e9d', '[\"*\"]', '2023-02-28 14:17:05', '2023-02-28 12:36:50', '2023-02-28 14:17:05'),
(551, 'App\\Models\\User', 12, 'auth_token', '8a58ba2d273cfc59a616650c7c4909fd1c1e5b9ad61452d6a46be662cee84e79', '[\"*\"]', NULL, '2023-02-28 12:37:58', '2023-02-28 12:37:58'),
(552, 'App\\Models\\User', 12, 'auth_token', '61d33622e29e182cc790d9c8bab304cd2e09ee168b0f10c604b9a42bef1919be', '[\"*\"]', NULL, '2023-02-28 13:20:31', '2023-02-28 13:20:31'),
(553, 'App\\Models\\User', 12, 'auth_token', '60a4b51583d4ac92be6e0feb965d12caabe3cc54be2c6af60f089e21106cb44d', '[\"*\"]', '2023-02-28 14:28:40', '2023-02-28 14:16:45', '2023-02-28 14:28:40'),
(574, 'App\\Models\\Customer', 109, 'auth_token', '4b8d995d9ddb0d20107225e6683e9cf0c8e0469fd3bd03044ec5ee490a0303b1', '[\"*\"]', '2023-03-01 10:28:06', '2023-03-01 10:26:16', '2023-03-01 10:28:06'),
(588, 'App\\Models\\Customer', 109, 'auth_token', '4955dab1297a3f6092abe4f4f388725b392c1409e61d0f6a2e12d5546d1d0314', '[\"*\"]', '2023-03-03 17:59:38', '2023-03-03 16:36:23', '2023-03-03 17:59:38'),
(591, 'App\\Models\\Customer', 109, 'auth_token', '1f95f3508b832c7a6ba07fed86549fb85c868411b4b50cce88d920fcee637dcd', '[\"*\"]', NULL, '2023-03-03 19:29:55', '2023-03-03 19:29:55'),
(594, 'App\\Models\\Customer', 109, 'auth_token', 'aa4f621e59365d654b097e4d2dd2b27ccaafe9e67f8b4b2b60e6fc9b8b6c6952', '[\"*\"]', '2023-03-06 12:12:23', '2023-03-06 11:12:05', '2023-03-06 12:12:23'),
(595, 'App\\Models\\Customer', 109, 'auth_token', 'f8a1ea5fb33b3977afda7b6f94eae09c86d98b7db6381ec35a815263455aa0b4', '[\"*\"]', '2023-03-06 15:20:20', '2023-03-06 12:01:31', '2023-03-06 15:20:20'),
(597, 'App\\Models\\Customer', 109, 'auth_token', 'e2080bf9dfc94bb402aa6f7d85c186d3e8a448cab0da779cdc0339e29d903fa5', '[\"*\"]', '2023-03-06 16:19:56', '2023-03-06 12:12:23', '2023-03-06 16:19:56'),
(598, 'App\\Models\\Customer', 109, 'auth_token', 'ddacb3a1acc51b7f799f62472860c6c4ec7464cab2321a7ecc2c526b4c786299', '[\"*\"]', NULL, '2023-03-06 12:13:17', '2023-03-06 12:13:17'),
(608, 'App\\Models\\Customer', 116, 'auth_token', 'f0fe57818f8e02fe342f7f76b98a79bdeceaffd470987571a564760baf5b5287', '[\"*\"]', '2023-03-06 18:02:29', '2023-03-06 17:44:53', '2023-03-06 18:02:29'),
(609, 'App\\Models\\Customer', 116, 'auth_token', '934ab1b30aa84c58c5fb13d2e2c554b792ab997f5df5b3ff04b5868e5ba93b84', '[\"*\"]', '2023-03-06 19:20:09', '2023-03-06 18:15:15', '2023-03-06 19:20:09'),
(610, 'App\\Models\\Customer', 116, 'auth_token', '20e815c5d2932691bd305ffb5b634937fa40ecdf68ff2ca5005d103ed2e9b700', '[\"*\"]', '2023-03-07 20:07:30', '2023-03-06 19:22:06', '2023-03-07 20:07:30'),
(638, 'App\\Models\\User', 17, 'auth_token', 'f6b7ed0dc6fccfc1fb16a6ed9e2484b3effd4fcd1fad72eba8994b6672b263fa', '[\"*\"]', '2023-03-09 16:01:45', '2023-03-08 16:40:07', '2023-03-09 16:01:45'),
(640, 'App\\Models\\User', 17, 'auth_token', '3faa7c775d40af7fd43babd7f72da87569824c16321ef3b3feeeb41a2bc50e08', '[\"*\"]', '2023-03-09 15:08:45', '2023-03-08 17:37:23', '2023-03-09 15:08:45'),
(641, 'App\\Models\\Customer', 109, 'auth_token', 'e037d75267e769ba127fa2cd071d0cace3fdc22e0ad9c04b6f3706678215fd02', '[\"*\"]', NULL, '2023-03-08 17:42:04', '2023-03-08 17:42:04'),
(642, 'App\\Models\\User', 17, 'auth_token', '57336835ac250d6664d9f87e03d66612683503080b2dca4c6e3dc1b48624e7ed', '[\"*\"]', '2023-03-09 11:29:23', '2023-03-08 17:43:05', '2023-03-09 11:29:23'),
(645, 'App\\Models\\Customer', 132, 'auth_token', 'dfb247326f2d02a075e7d318beec3b272f861751a9f9fb7e3c7c09d3c45e4dcd', '[\"*\"]', '2023-03-09 11:46:41', '2023-03-09 11:42:36', '2023-03-09 11:46:41'),
(646, 'App\\Models\\Customer', 132, 'auth_token', 'e3be2d7e37ae0e79f23139bf0dda1994aba64c2716b762055c7758dcdfc07fd8', '[\"*\"]', NULL, '2023-03-09 11:46:41', '2023-03-09 11:46:41'),
(647, 'App\\Models\\User', 17, 'auth_token', '154cdeae0de9da473c06bd70b70e7aa2de2250ad90969e48e50cee5a141fe0ec', '[\"*\"]', '2023-03-09 12:50:21', '2023-03-09 11:48:42', '2023-03-09 12:50:21'),
(648, 'App\\Models\\Customer', 132, 'auth_token', '6de046a910dca7f30e6ba15b3a1b8156d08108600c6f3768f113ce4e5cc56a25', '[\"*\"]', '2023-03-09 13:13:00', '2023-03-09 12:54:19', '2023-03-09 13:13:00'),
(650, 'App\\Models\\Customer', 132, 'auth_token', 'f661e403dc5c0253549d808b1d6d002b40986f78cdd94072a057e646b2ab51de', '[\"*\"]', '2023-03-09 13:23:31', '2023-03-09 13:23:04', '2023-03-09 13:23:31'),
(665, 'App\\Models\\Customer', 139, 'auth_token', '19a1f35e65d52a85239459a3f3e63393cb618c6c98b8c2c6228aea9ce75d2871', '[\"*\"]', '2023-04-19 19:46:08', '2023-03-09 19:56:38', '2023-04-19 19:46:08'),
(669, 'App\\Models\\Customer', 132, 'auth_token', 'ed3d0f2609b3aec53ce5a3db3630beef1b6bb1cb20c943bcb8c768ced122ae5d', '[\"*\"]', '2023-03-10 14:06:58', '2023-03-10 13:57:26', '2023-03-10 14:06:58'),
(670, 'App\\Models\\Customer', 132, 'auth_token', 'b2039e2747bed48ba3f9329aabcb5e25d037d7afd73fdc8372b675c0c7d484c8', '[\"*\"]', '2023-03-10 14:47:32', '2023-03-10 14:06:58', '2023-03-10 14:47:32'),
(699, 'App\\Models\\Customer', 144, 'auth_token', 'a571c87b29e52529d2e87071e170826b2c88c9527a537c11401425c95bcb050b', '[\"*\"]', '2023-03-12 11:45:41', '2023-03-12 11:05:41', '2023-03-12 11:45:41'),
(719, 'App\\Models\\Customer', 145, 'auth_token', '6288faee70d503bfd9fbcf1dbd16e0f8a24c0ca6c7f65aa1f8edb07ae110fa2c', '[\"*\"]', '2023-03-15 10:16:43', '2023-03-12 19:01:52', '2023-03-15 10:16:43'),
(727, 'App\\Models\\User', 14, 'auth_token', '228e9f7cb3ac2032a3ae5c6848e0dcdc00134e00aa73356d1e269572b8a40d61', '[\"*\"]', '2023-03-13 16:34:50', '2023-03-13 09:13:34', '2023-03-13 16:34:50'),
(742, 'App\\Models\\Customer', 142, 'auth_token', '5f8d51922e645776de30a96e6738aafb7e87eb6bca86a5df9fc11e63fc2cf9e4', '[\"*\"]', '2023-03-15 10:28:34', '2023-03-14 21:37:54', '2023-03-15 10:28:34'),
(757, 'App\\Models\\Customer', 149, 'auth_token', '1b5e5aa0e591341ef805f3cabe5111b686aa5023f6eb26f7fdf623d258938de0', '[\"*\"]', '2023-03-15 18:16:35', '2023-03-15 17:42:52', '2023-03-15 18:16:35'),
(769, 'App\\Models\\Customer', 148, 'auth_token', '6bd3c31c32928f143053a7abdd152bb565a7630927e39eab9d2426af7b19c182', '[\"*\"]', '2023-05-21 22:00:20', '2023-03-17 09:02:58', '2023-05-21 22:00:20'),
(775, 'App\\Models\\User', 3, 'auth_token', '0f2dbee1c9ae4de07ba7b9a0dd18ec2601df3caea77a2099ef09d9b0fad512ac', '[\"*\"]', '2023-03-19 09:01:38', '2023-03-19 08:58:18', '2023-03-19 09:01:38'),
(777, 'App\\Models\\User', 3, 'auth_token', 'd8e7e0e4d990a7b1d849decab63156c75d49fdfcd342b0eee051c117d0d34ac9', '[\"*\"]', '2023-03-19 09:30:36', '2023-03-19 09:19:17', '2023-03-19 09:30:36'),
(781, 'App\\Models\\User', 3, 'auth_token', '9fc8ef15d8853f10f5bc3b4fc9b9a39175e4a08d934462aee9dfc9fa8e8a8108', '[\"*\"]', '2023-03-19 20:20:26', '2023-03-19 20:19:28', '2023-03-19 20:20:26'),
(785, 'App\\Models\\User', 3, 'auth_token', '18c7724ce6cd0a8ea853b133df953a07402ff6beeee67b58358a0999b9dbb71e', '[\"*\"]', '2023-03-20 10:53:51', '2023-03-20 10:53:16', '2023-03-20 10:53:51'),
(799, 'App\\Models\\User', 3, 'auth_token', '5e6ee3fb217472f8d575de0eefc261c3f0d06532f51a95f52a31c9cc8f12a139', '[\"*\"]', '2023-03-20 14:49:26', '2023-03-20 14:11:20', '2023-03-20 14:49:26'),
(839, 'App\\Models\\User', 15, 'auth_token', '9e65089750dac283538b5531146bbb8dff96a562e00303818d64fd7314b14c19', '[\"*\"]', NULL, '2023-03-23 11:25:09', '2023-03-23 11:25:09'),
(842, 'App\\Models\\Customer', 52, 'auth_token', 'e6329e6e43143a3c71f20c37145c963756af3356a95d45174da907e0b93c15f1', '[\"*\"]', '2023-03-30 15:29:45', '2023-03-23 12:28:11', '2023-03-30 15:29:45'),
(843, 'App\\Models\\User', 15, 'auth_token', 'c87c6ee1df268b0b262121f3e053012bebb0d314f08303f5d08b0b110287786f', '[\"*\"]', NULL, '2023-03-23 13:49:22', '2023-03-23 13:49:22'),
(846, 'App\\Models\\User', 15, 'auth_token', '1ff0f40d6a8bfefd9f5728ccadba1f5fc41c93a571a13ff36284f7a5eac05fe8', '[\"*\"]', '2023-03-23 17:24:32', '2023-03-23 14:19:18', '2023-03-23 17:24:32'),
(847, 'App\\Models\\User', 15, 'auth_token', 'a7191c0d9d03d86b543d3c6128660b467efbd4d4b57e25f1964c0fa09ce30edb', '[\"*\"]', '2023-03-26 17:24:21', '2023-03-26 17:23:41', '2023-03-26 17:24:21'),
(848, 'App\\Models\\Customer', 154, 'auth_token', '14dff6dc0a75ef949ae576a4a97f36b6828049ae48d65160c5fc238dd9767d02', '[\"*\"]', '2023-03-27 16:10:06', '2023-03-27 15:56:18', '2023-03-27 16:10:06'),
(850, 'App\\Models\\User', 14, 'auth_token', '9e89a03291da60ae7fa5453db914560ca270fc5c05fa877a4d4f2bf75fb287ed', '[\"*\"]', '2023-03-30 13:38:31', '2023-03-29 17:58:20', '2023-03-30 13:38:31'),
(855, 'App\\Models\\User', 3, 'auth_token', 'e1e2adc56e309a4cc483d94893a0c9954b0c2f657e7d6ba7d4bac11288c652ac', '[\"*\"]', '2023-04-01 10:55:24', '2023-04-01 10:45:02', '2023-04-01 10:55:24'),
(857, 'App\\Models\\User', 4, 'auth_token', 'fee51c1c3a521b685b30edc84f9a50c8caf856a56cc5bfd2a644a89aceced3fd', '[\"*\"]', '2023-04-01 12:53:02', '2023-04-01 11:12:20', '2023-04-01 12:53:02'),
(858, 'App\\Models\\Customer', 153, 'auth_token', '001b4573862af12b24f8f1996cce9b167e7bf8909966a0395765686f8b5a96bd', '[\"*\"]', '2023-04-01 12:33:48', '2023-04-01 12:04:32', '2023-04-01 12:33:48'),
(859, 'App\\Models\\Customer', 153, 'auth_token', 'd097836eb50e033202c7339b5009c25f27ee909053cd79ea53ca82cf4a7492e3', '[\"*\"]', '2023-04-01 13:25:04', '2023-04-01 12:49:47', '2023-04-01 13:25:04'),
(860, 'App\\Models\\User', 4, 'auth_token', '8522808981bf66d63f9e2266a5772a92af476fedf12068b73d13e56716ca60ee', '[\"*\"]', '2023-04-01 17:29:38', '2023-04-01 13:07:44', '2023-04-01 17:29:38'),
(861, 'App\\Models\\Customer', 153, 'auth_token', 'de26ab83e5c21b6356c0eef7b4a9a86d20c36082ff366d6a3068d5828c84c401', '[\"*\"]', '2023-04-02 08:29:51', '2023-04-01 17:56:42', '2023-04-02 08:29:51'),
(862, 'App\\Models\\Customer', 153, 'auth_token', 'f6ae870779e354da516ec6fb52d587cbfa943e7958156796a3b4f08d75201d93', '[\"*\"]', '2023-04-01 18:05:09', '2023-04-01 17:58:50', '2023-04-01 18:05:09'),
(863, 'App\\Models\\Customer', 32, 'auth_token', '01e9101056fb489a2d91f9b8e039b1650f21628d04ec82bd12f6a5f4bf34331e', '[\"*\"]', '2023-04-17 15:37:53', '2023-04-02 08:26:38', '2023-04-17 15:37:53'),
(864, 'App\\Models\\Customer', 153, 'auth_token', 'd6c55156e4d1b9a70650078c6c81b282cc35b948d3d46933f1ae49a68d364beb', '[\"*\"]', '2023-04-02 13:39:57', '2023-04-02 08:29:51', '2023-04-02 13:39:57'),
(865, 'App\\Models\\User', 4, 'auth_token', 'd9065dcafa24220170a836c3369f6c25803c6726b226ab6b7fbeb8b4717f4037', '[\"*\"]', '2023-04-02 14:12:34', '2023-04-02 14:11:26', '2023-04-02 14:12:34'),
(867, 'App\\Models\\User', 4, 'auth_token', 'f8eb46a323922d385f4cd7b9e7b53544ace12ce8485ad7c38d9efc665f4fa24c', '[\"*\"]', NULL, '2023-04-02 17:56:30', '2023-04-02 17:56:30'),
(875, 'App\\Models\\Customer', 153, 'auth_token', '3be02d5bab260ae26cfabe92749d48d8c56ff448a9ee15433eb974915c7d466f', '[\"*\"]', '2023-04-03 09:54:09', '2023-04-03 09:52:20', '2023-04-03 09:54:09'),
(917, 'App\\Models\\User', 3, 'auth_token', '976230e0dcbd6059aeaa30a1b7bded351312d97a5d6a6f757f45f911a5a589ea', '[\"*\"]', '2023-04-13 12:01:32', '2023-04-13 11:07:43', '2023-04-13 12:01:32'),
(918, 'App\\Models\\Customer', 148, 'auth_token', '82be436ee44b4876642b9467ba52c96b7912194f6b8e48ed8d309ef9d2aa39aa', '[\"*\"]', '2023-04-15 18:06:04', '2023-04-13 11:20:21', '2023-04-15 18:06:04'),
(927, 'App\\Models\\User', 3, 'auth_token', '7968eb51207fff8340143ad57cbdacb15e91d39c1f3d17382fcf9cf018c0aa16', '[\"*\"]', NULL, '2023-04-15 17:52:43', '2023-04-15 17:52:43'),
(928, 'App\\Models\\Customer', 148, 'auth_token', 'b062943e16d36fe3a30cddd31b965b1b1a1e489f68ac96a9a9af7b012b75c0f8', '[\"*\"]', '2023-04-15 20:03:18', '2023-04-15 18:06:04', '2023-04-15 20:03:18'),
(933, 'App\\Models\\User', 3, 'auth_token', 'bae639bb2b7f4bd995155f4a3c4a59dc723b103d72195612c1a8ccc49eabec3c', '[\"*\"]', '2023-04-15 20:26:14', '2023-04-15 20:06:23', '2023-04-15 20:26:14'),
(946, 'App\\Models\\User', 3, 'auth_token', '5c30c1ba0393927d1d0cc061b88fb4136505e1e62673acd99a90e5e5edfb4127', '[\"*\"]', '2023-04-16 12:23:12', '2023-04-16 12:08:18', '2023-04-16 12:23:12'),
(948, 'App\\Models\\Customer', 32, 'auth_token', '7c28b368900a6da5a4dba45ac271fda305ac0b8959681e7d1b33acd213aacdec', '[\"*\"]', '2023-04-17 15:32:11', '2023-04-17 15:21:31', '2023-04-17 15:32:11'),
(949, 'App\\Models\\Customer', 32, 'auth_token', '35f1dde7f9c81c8a085731f38f0d432b19f55ec6994654890430f0ff28627978', '[\"*\"]', '2023-05-11 09:50:21', '2023-04-17 15:44:45', '2023-05-11 09:50:21'),
(950, 'App\\Models\\User', 3, 'auth_token', '649b9b5173534b2c8dc32790231bddb2f29e66e4b535cea7815e83d3de1233ab', '[\"*\"]', NULL, '2023-04-19 17:03:37', '2023-04-19 17:03:37'),
(955, 'App\\Models\\Customer', 32, 'auth_token', 'b60a97fa2f6c6fa49152e3487e0d2a77376e61137c8b3e376072ad75739b98c8', '[\"*\"]', '2023-04-20 10:22:16', '2023-04-20 10:03:46', '2023-04-20 10:22:16'),
(966, 'App\\Models\\User', 3, 'auth_token', 'ec1b051c1449a6db7428943f642ae5c1ae513a832c2b4876e971feb4927f51ac', '[\"*\"]', NULL, '2023-04-25 10:46:33', '2023-04-25 10:46:33'),
(970, 'App\\Models\\User', 20, 'auth_token', '9e49211b36aadf5dddece1dec7bdc1103a4ffe345f38894b394ee2dedaf8d49c', '[\"*\"]', '2023-05-15 22:33:37', '2023-04-26 10:56:38', '2023-05-15 22:33:37'),
(972, 'App\\Models\\User', 20, 'auth_token', '80818ccfe05e8a87871853b754f881e180a2a3b0185589baf9d9087831850d2a', '[\"*\"]', '2023-04-26 11:01:25', '2023-04-26 10:58:30', '2023-04-26 11:01:25'),
(983, 'App\\Models\\User', 3, 'auth_token', '8a530335c228178f1c74df8b57f585e71ce838fec98690efceadc203dd964f57', '[\"*\"]', '2023-05-01 13:35:43', '2023-05-01 12:03:58', '2023-05-01 13:35:43'),
(992, 'App\\Models\\User', 3, 'auth_token', '6897f2c145667f070bc259eabc0bee3d8e6127c1866df9f44a573cd6d10ca4f9', '[\"*\"]', '2023-05-02 09:14:57', '2023-05-02 09:09:30', '2023-05-02 09:14:57'),
(996, 'App\\Models\\User', 20, 'auth_token', 'aff384f9e9f16dbf56ca5daebe32be3f7be0c7f8459559445714fb897778d4b8', '[\"*\"]', '2023-05-02 16:46:18', '2023-05-02 16:43:19', '2023-05-02 16:46:18'),
(1023, 'App\\Models\\User', 3, 'auth_token', '409ca942fc384418f9201cfbb7076400adc5a2dce93ee0c1dbfbf39fb14888c7', '[\"*\"]', '2023-05-24 10:13:10', '2023-05-21 07:24:21', '2023-05-24 10:13:10'),
(1027, 'App\\Models\\User', 20, 'auth_token', '96f3e4734d634dc28b4d053c55affe10b7164a1f7c7cbceacea498f4d70d8cda', '[\"*\"]', NULL, '2023-05-21 08:59:14', '2023-05-21 08:59:14'),
(1030, 'App\\Models\\User', 18, 'auth_token', '46e4c470eee93a942d1985181994795219dbe954f3c59f06c283bc4dae5db54f', '[\"*\"]', '2023-05-21 09:44:02', '2023-05-21 09:41:32', '2023-05-21 09:44:02'),
(1035, 'App\\Models\\User', 20, 'auth_token', '6367cb9a4b0cde9a6b0474565bccd2082202b0a61671fb37aa84547e2226685d', '[\"*\"]', '2023-05-24 17:20:29', '2023-05-24 13:24:47', '2023-05-24 17:20:29'),
(1038, 'App\\Models\\Customer', 87, 'auth_token', '47998fa18d3e5213927411698078bbf2c6b849c9f50f2996d7b0c54808f52dec', '[\"*\"]', '2023-05-26 16:22:22', '2023-05-26 10:51:25', '2023-05-26 16:22:22'),
(1039, 'App\\Models\\User', 20, 'auth_token', 'd46aab7ec9b9ffb1f73f0c3ad6f9da0ef0e4dfebc1aafb62fb45bbbc409936b7', '[\"*\"]', '2023-05-26 13:13:39', '2023-05-26 13:03:20', '2023-05-26 13:13:39'),
(1041, 'App\\Models\\Customer', 87, 'auth_token', '0aaa3ea71cbf69fc675ec8fbf636bc66caaed0281602b3212d50750b0f5a730b', '[\"*\"]', '2023-05-26 17:36:50', '2023-05-26 16:22:22', '2023-05-26 17:36:50'),
(1046, 'App\\Models\\User', 20, 'auth_token', '4128e3eeac1ae8531d7db6407b18bb9cf55f191486ca2fed66768a6113d8be1b', '[\"*\"]', '2023-05-29 10:54:53', '2023-05-29 09:32:38', '2023-05-29 10:54:53'),
(1047, 'App\\Models\\Customer', 87, 'auth_token', 'a9c715f72881389217f5f145f921d3d5b11a054bec783447c5741c76d69b6df9', '[\"*\"]', '2023-05-29 16:52:42', '2023-05-29 11:16:49', '2023-05-29 16:52:42'),
(1048, 'App\\Models\\User', 20, 'auth_token', '9a47ea0573f3acda31a93d64b541447c54a6daafdd436df983bdba08b39bb673', '[\"*\"]', '2023-05-29 16:34:26', '2023-05-29 13:24:17', '2023-05-29 16:34:26'),
(1049, 'App\\Models\\User', 20, 'auth_token', 'bf1d506218424688ef2cb6bf507741f4516b91611eb42f34e21c1ef90beb17dd', '[\"*\"]', NULL, '2023-05-29 16:34:16', '2023-05-29 16:34:16'),
(1072, 'App\\Models\\Customer', 87, 'auth_token', '061f6c1851312644e5e027aa303931f2842ce0f20660e92c1c906da31cae2b31', '[\"*\"]', '2023-05-31 16:11:53', '2023-05-31 15:45:55', '2023-05-31 16:11:53'),
(1074, 'App\\Models\\Customer', 102, 'auth_token', '558be86f0bab3b932c5a6d785c5a88bf6a1c9d3f761d7cc117b1ddfb44755e14', '[\"*\"]', NULL, '2023-05-31 16:47:46', '2023-05-31 16:47:46'),
(1075, 'App\\Models\\Customer', 102, 'auth_token', '3891c52e7f2d8c778c6419dde605eaad1c25e242af3aab74b2ad21691aeb4908', '[\"*\"]', NULL, '2023-05-31 16:47:58', '2023-05-31 16:47:58'),
(1076, 'App\\Models\\Customer', 102, 'auth_token', '7431a997f27d24f27792f168b8b0a881c571faa69906971b441222325fecf0b3', '[\"*\"]', NULL, '2023-05-31 16:54:04', '2023-05-31 16:54:04'),
(1081, 'App\\Models\\Customer', 87, 'auth_token', '0e5666513c11b539cb7fd9d4c3d37d21db08d04759f537669921ef01253a8f93', '[\"*\"]', '2023-05-31 17:16:53', '2023-05-31 17:16:36', '2023-05-31 17:16:53'),
(1083, 'App\\Models\\Customer', 103, 'auth_token', '7e4faacd956ffc26dbb3ec311da5c57a10ddc4b1df03de592f4bc36ebc46f23d', '[\"*\"]', NULL, '2023-05-31 18:29:24', '2023-05-31 18:29:24'),
(1085, 'App\\Models\\Customer', 103, 'auth_token', 'c9ab6145fcc3726b8a4a1139a9a291898febcb0bd5207c1b811de468c673f0db', '[\"*\"]', '2023-06-01 09:06:00', '2023-06-01 08:45:55', '2023-06-01 09:06:00'),
(1086, 'App\\Models\\Customer', 103, 'auth_token', 'a735ecd79d4ecb7cb770aae83f3a4d5243799c7550f634fd9c8cd11e5636b3b7', '[\"*\"]', '2023-06-01 08:48:52', '2023-06-01 08:46:37', '2023-06-01 08:48:52'),
(1087, 'App\\Models\\Customer', 103, 'auth_token', 'a27ff3ae17e12a42814545f1089cfd299db41c2aa298b65645fe1da8426cedc9', '[\"*\"]', '2023-06-01 09:45:25', '2023-06-01 09:40:07', '2023-06-01 09:45:25'),
(1090, 'App\\Models\\Customer', 158, 'auth_token', 'c396536ed44eedf49f5f6ff73cdcca529e9a6fc89e5fb75867e84c65e7a28e4c', '[\"*\"]', '2023-06-01 16:00:19', '2023-06-01 10:23:04', '2023-06-01 16:00:19'),
(1093, 'App\\Models\\Customer', 103, 'auth_token', 'f9806971419a43a38896b51ee72bc5a97238be69edfbd0b6c6755bfa24d0eda4', '[\"*\"]', '2023-06-02 14:35:44', '2023-06-02 14:02:05', '2023-06-02 14:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `pincodes`
--

CREATE TABLE `pincodes` (
  `id` int(11) NOT NULL,
  `outlet_id` int(11) NOT NULL DEFAULT '0',
  `pincode` varchar(100) NOT NULL,
  `place_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pincodes`
--

INSERT INTO `pincodes` (`id`, `outlet_id`, `pincode`, `place_name`, `created_at`, `updated_at`) VALUES
(1, 1, '700064', 'Bidhannagar', '2022-12-06 16:35:33', '2022-12-06 16:35:33'),
(2, 1, '700091', 'Sector V', '2022-12-06 16:35:33', '2022-12-06 16:35:33'),
(5, 3, '700156', 'Chinarpark', '2022-12-21 13:35:15', '2022-12-21 13:35:15'),
(6, 4, '700055', 'Bangur', '2022-12-21 13:37:23', '2022-12-21 13:37:23'),
(7, 5, '711101', 'Howrah', '2022-12-21 13:37:46', '2022-12-21 13:37:46'),
(8, 3, '743222', 'Ashoknagar', '2023-02-10 19:06:03', '2023-02-10 19:06:03'),
(10, 6, '700019', 'Ballygunge', '2023-04-26 16:17:37', '2023-04-26 16:17:37'),
(11, 7, '700156', 'AM', '2023-05-04 16:01:06', '2023-05-04 16:01:06'),
(12, 7, '700163', 'AM', '2023-05-04 16:01:06', '2023-05-04 16:01:06'),
(13, 6, '700016', 'BG', '2023-05-04 16:01:46', '2023-05-04 16:01:46'),
(14, 6, '700017', 'BG', '2023-05-04 16:09:27', '2023-05-04 16:09:27'),
(15, 6, '700020', 'BG', '2023-05-04 16:10:10', '2023-05-04 16:10:10'),
(16, 6, '700025', 'BG', '2023-05-04 16:10:10', '2023-05-04 16:10:10'),
(17, 6, '700039', 'BG2', '2023-05-04 16:10:10', '2023-05-04 16:10:10'),
(18, 6, '700042', 'BG', '2023-05-04 16:13:18', '2023-05-04 16:13:18'),
(19, 6, '700071', 'BG', '2023-05-04 16:13:18', '2023-05-04 16:13:18');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_category_id` bigint(20) DEFAULT NULL,
  `service_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `garment_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pieces` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL,
  `service_category_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `service_details`
--

CREATE TABLE `service_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_price` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_type_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) DEFAULT NULL,
  `default` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_rtl` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` int(11) NOT NULL COMMENT '1 = Admin, 2 = Outlet, 3 = Floor Manager, 4 = Driver',
  `outlet_id` int(11) DEFAULT NULL,
  `workstation_id` int(11) DEFAULT NULL,
  `auth_token` longtext COLLATE utf8mb4_unicode_ci,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_subadmin` int(11) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `image`, `user_type`, `outlet_id`, `workstation_id`, `auth_token`, `phone`, `avatar`, `is_subadmin`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$10$qMjYRHOuTT/pqhH7RB7muerG4WepPsb4v7fY/yzxSdf0LJTlUr2QS', NULL, 1, 0, 0, NULL, NULL, NULL, 0, 1, 1, '2022-11-25 04:33:59', '2023-06-01 10:33:43');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `module` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `module`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'create_order', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(2, 1, 'view_order', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(3, 1, 'edit_order', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(4, 1, 'order_status', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(5, 1, 'assign_driver', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(6, 1, 'rewash_request', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(7, 1, 'cancel_request', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(8, 1, 'order_status_screen', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(9, 1, 'garment_status_screen', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(10, 1, 'packing_sticker', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(11, 1, 'expense_list', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(12, 1, 'expense_category', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(13, 1, 'customer', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(14, 1, 'add_customer', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(15, 1, 'assign_membership', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(16, 1, 'manage_category', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(17, 1, 'manage_service_type', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(18, 1, 'manage_garments', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(19, 1, 'manage_addons', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(20, 1, 'manage_rate_chart', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(21, 1, 'daily_report', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(22, 1, 'order_report', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(23, 1, 'sales_report', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(24, 1, 'expense_report', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(25, 1, 'tax_report', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(26, 1, 'garment_report', 1, '2023-06-02 18:22:59', '2023-06-02 18:22:59'),
(27, 1, 'customer_order_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(28, 1, 'customer_history_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(29, 1, 'outlet_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(30, 1, 'workstation_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(31, 1, 'outstanding_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(32, 1, 'stock_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(33, 1, 'rewash_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(34, 1, 'service_report', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(35, 1, 'financial_year', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(36, 1, 'mail_settings', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(37, 1, 'master_settings', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(38, 1, 'file_tools', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(39, 1, 'sms_settings', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(40, 1, 'membership', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(41, 1, 'manage_user', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(42, 1, 'manage_outlet', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(43, 1, 'manage_workstation', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(44, 1, 'manage_brand', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(45, 1, 'manage_voucher', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(46, 1, 'manage_delivery', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(47, 1, 'manage_promotion', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00'),
(48, 1, 'user_permission', 1, '2023-06-02 18:23:00', '2023-06-02 18:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `membership` int(11) DEFAULT NULL,
  `no_of_users` int(11) DEFAULT NULL COMMENT 'how many users can use',
  `each_user_useable` int(11) DEFAULT NULL COMMENT 'how many times use for each user',
  `total_useable` int(11) DEFAULT NULL COMMENT 'total useable times',
  `total_used` int(11) NOT NULL DEFAULT '0' COMMENT 'no of used by the customers',
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_amount` double(10,2) DEFAULT NULL,
  `cutoff_amount` double(10,2) DEFAULT '0.00',
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `details` text,
  `image` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `receive_amount` int(11) DEFAULT NULL,
  `deducted_amount` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workstations`
--

CREATE TABLE `workstations` (
  `id` int(11) NOT NULL,
  `workstation_name` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_customer_id_foreign` (`customer_id`),
  ADD KEY `cart_items_service_id_foreign` (`service_id`),
  ADD KEY `cart_items_service_type_id_foreign` (`service_type_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_addresses_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_membership_logs`
--
ALTER TABLE `customer_membership_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_queries`
--
ALTER TABLE `customer_queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_verification_codes`
--
ALTER TABLE `customer_verification_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_types`
--
ALTER TABLE `delivery_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_expense_category_id_foreign` (`expense_category_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `financial_years`
--
ALTER TABLE `financial_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_settings`
--
ALTER TABLE `master_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_addon_details`
--
ALTER TABLE `order_addon_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_addon_details_order_id_foreign` (`order_id`),
  ADD KEY `order_addon_details_addon_id_foreign` (`addon_id`),
  ADD KEY `order_addon_details_order_detail_id_foreign` (`order_detail_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_service_id_foreign` (`service_id`),
  ADD KEY `order_details_service_type_id_foreign` (`service_type_id`);

--
-- Indexes for table `order_details_details`
--
ALTER TABLE `order_details_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_details_order_details_id_foreign` (`order_detail_id`);

--
-- Indexes for table `outlets`
--
ALTER TABLE `outlets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outlet_drivers`
--
ALTER TABLE `outlet_drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pincodes`
--
ALTER TABLE `pincodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_details`
--
ALTER TABLE `service_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_details_service_id_foreign` (`service_id`),
  ADD KEY `service_details_service_type_id_foreign` (`service_type_id`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workstations`
--
ALTER TABLE `workstations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_membership_logs`
--
ALTER TABLE `customer_membership_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_queries`
--
ALTER TABLE `customer_queries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_verification_codes`
--
ALTER TABLE `customer_verification_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_types`
--
ALTER TABLE `delivery_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_years`
--
ALTER TABLE `financial_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_settings`
--
ALTER TABLE `master_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_addon_details`
--
ALTER TABLE `order_addon_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details_details`
--
ALTER TABLE `order_details_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlets`
--
ALTER TABLE `outlets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlet_drivers`
--
ALTER TABLE `outlet_drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1094;

--
-- AUTO_INCREMENT for table `pincodes`
--
ALTER TABLE `pincodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_details`
--
ALTER TABLE `service_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workstations`
--
ALTER TABLE `workstations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`);

--
-- Constraints for table `order_addon_details`
--
ALTER TABLE `order_addon_details`
  ADD CONSTRAINT `order_addon_details_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`id`),
  ADD CONSTRAINT `order_addon_details_order_detail_id_foreign` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`),
  ADD CONSTRAINT `order_addon_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `order_details_service_type_id_foreign` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`);

--
-- Constraints for table `order_details_details`
--
ALTER TABLE `order_details_details`
  ADD CONSTRAINT `order_details_details_order_details_id_foreign` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`),
  ADD CONSTRAINT `order_details_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `service_details`
--
ALTER TABLE `service_details`
  ADD CONSTRAINT `service_details_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `service_details_service_type_id_foreign` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
