-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `acl`;
CREATE TABLE `acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `privilege_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `access` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gui_acl_ibfk_3` (`role_id`),
  KEY `gui_acl_ibfk_1` (`privilege_id`),
  KEY `gui_acl_ibfk_2` (`resource_id`),
  CONSTRAINT `acl_ibfk_1` FOREIGN KEY (`privilege_id`) REFERENCES `acl_privileges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acl_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `acl_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acl_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `acl` (`id`, `role_id`, `privilege_id`, `resource_id`, `access`) VALUES
(1,	1,	NULL,	NULL,	1);

DROP TABLE IF EXISTS `acl_privileges`;
CREATE TABLE `acl_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `comment` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `acl_resources_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`),
  KEY `acl_resources_v2_id` (`acl_resources_id`),
  CONSTRAINT `acl_privileges_ibfk_2` FOREIGN KEY (`acl_resources_id`) REFERENCES `acl_resources` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `acl_privileges` (`id`, `key_name`, `name`, `comment`, `acl_resources_id`) VALUES
(1,	'display',	'Display',	NULL,	NULL);

DROP TABLE IF EXISTS `acl_resources`;
CREATE TABLE `acl_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `key_name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `comment` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`),
  KEY `gui_acl_resources_v2_ibfk_1` (`parent_id`),
  CONSTRAINT `acl_resources_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `acl_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `acl_resources` (`id`, `parent_id`, `key_name`, `name`, `comment`) VALUES
(1,	NULL,	'acl',	'ACL',	NULL),
(2,	1,	'role',	'Role',	NULL),
(3,	1,	'permission',	'Permission',	NULL),
(4,	1,	'privilege',	'privilege',	NULL),
(5,	1,	'resource',	'Resource',	NULL),
(6,	NULL,	'user',	'User',	NULL),
(7,	NULL,	'dashboard',	'Dashboard',	NULL);

DROP TABLE IF EXISTS `acl_roles`;
CREATE TABLE `acl_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `key_name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `comment` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`),
  KEY `gui_acl_roles_v2_ibfk_1` (`parent_id`),
  CONSTRAINT `acl_roles_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `acl_roles` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `acl_roles` (`id`, `parent_id`, `key_name`, `name`, `comment`) VALUES
(1,	NULL,	'god',	'God',	NULL),
(2,	1,	'admin',	'Admin',	NULL),
(3,	NULL,	'moderator',	'Moderator',	NULL),
(4,	NULL,	'guest',	'Guest',	NULL);

DROP TABLE IF EXISTS `acl_users_roles`;
CREATE TABLE `acl_users_roles` (
  `users_id` int(11) NOT NULL,
  `acl_roles_id` int(11) NOT NULL,
  KEY `users_id` (`users_id`),
  KEY `acl_roles_id` (`acl_roles_id`),
  CONSTRAINT `acl_users_roles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acl_users_roles_ibfk_2` FOREIGN KEY (`acl_roles_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `acl_users_roles` (`users_id`, `acl_roles_id`) VALUES
(1,	1),
(2,	4);

DROP TABLE IF EXISTS `api_tokens`;
CREATE TABLE `api_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `api_tokens_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `companies` (`id`, `name`, `token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'iwory\'s pub',	'Zzmg3WvuPXF55Dm1RdqH8PN5pyJPgU',	'2017-01-17 17:06:59',	'2017-01-17 17:06:59',	NULL),
(2,	'don\'t panic',	'3NVV0Dp0CIRBNb48KASNSk2gW1B3Ut',	'2017-01-18 11:16:09',	'2017-01-18 11:16:09',	NULL);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `setting_categories_id` tinyint(3) unsigned DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `setting_categories_id` (`setting_categories_id`),
  CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`setting_categories_id`) REFERENCES `setting_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `setting_categories`;
CREATE TABLE `setting_categories` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `sms_codes`;
CREATE TABLE `sms_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `users_id` int(11) NOT NULL,
  `tests_id` int(10) unsigned NOT NULL,
  `code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `used` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `tests_id` (`tests_id`),
  CONSTRAINT `sms_codes_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sms_codes_ibfk_2` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sms_codes` (`id`, `token`, `users_id`, `tests_id`, `code`, `used`, `created_at`, `updated_at`) VALUES
(1,	'58ywO3TXU7my66wzzNTPnGYmtedp8X',	3,	2,	'123456',	1,	'2017-01-23 16:07:25',	'2017-01-23 16:32:36'),
(2,	'6cZ6Jqd3MiXV8xhz1hG8p2mHOU4A8m',	3,	2,	'123456',	1,	'2017-01-23 16:34:46',	'2017-01-23 16:35:13'),
(3,	'PKxQJAL00erS84Lp0fNd8PW20b1hCW',	3,	2,	'123456',	1,	'2017-01-23 16:35:16',	'2017-01-23 16:35:16'),
(4,	'fdYZ6r7fLQvBIViQmpZG7pdg2obReT',	3,	2,	'123456',	1,	'2017-01-23 16:35:36',	'2017-01-23 16:39:15'),
(5,	'96BWAfgS7oQNB1Mqj7Gkg6dJxcv0Yk',	3,	2,	'123456',	1,	'2017-01-23 16:39:32',	'2017-01-23 16:39:36'),
(6,	'SalIkoKwm02tl7cixlcO0WTIddXwbM',	3,	2,	'123456',	1,	'2017-01-23 16:39:50',	'2017-01-23 16:40:00'),
(7,	'I6VvDG7KJZxuQ56Kel7YKycDvuig12',	3,	2,	'123456',	1,	'2017-01-23 16:40:18',	'2017-01-23 16:40:28'),
(8,	'psEKzW81fpsPwkLp4PfMIYtVGermXC',	3,	2,	'123456',	1,	'2017-01-23 16:40:40',	'2017-01-23 16:40:40'),
(9,	'FHCxJ96PnZ16WxTA7hecjUnwuxqmty',	3,	2,	'123456',	1,	'2017-01-23 16:41:01',	'2017-01-23 16:41:12'),
(10,	'MsYyfvz0F91DaQlVplzNl6hdu4RHR6',	3,	2,	'123456',	1,	'2017-01-23 16:41:23',	'2017-01-23 16:41:23'),
(11,	'n4AaqpU9ZkEz47k6Oq6MBwYYMthwpu',	3,	2,	'123456',	1,	'2017-01-23 16:46:29',	'2017-01-23 16:46:41'),
(12,	'WfaiMgho4L1twQ9kOjXRpQslgnPuAs',	3,	2,	'123456',	1,	'2017-01-23 16:46:56',	'2017-01-23 16:46:56'),
(13,	'E3zYkEGnZdAx1GipyPTGqbfBUE7S7e',	3,	2,	'123456',	1,	'2017-01-23 16:48:08',	'2017-01-23 16:48:12'),
(14,	'gU7orSoFSQYusZygmeST1Xn0Z3gDiG',	3,	2,	'123456',	1,	'2017-01-23 16:49:01',	'2017-01-23 16:49:01'),
(15,	'bp68COuvXZKTi0HhRpsU5KS7KciLlG',	3,	2,	'123456',	1,	'2017-01-23 16:50:45',	'2017-01-23 16:51:30'),
(16,	'pAye4FCmJ2PecPxOAm7uGzbcyG2NOS',	3,	2,	'123456',	1,	'2017-01-23 16:52:20',	'2017-01-23 16:52:41'),
(17,	'mj8Vwpf8mIJhs3sGZSNtzhwqLuiAIo',	5,	2,	'123456',	1,	'2017-01-23 17:22:30',	'2017-01-23 17:22:37'),
(18,	'bJJtrJNzsfZgJ2F6tVtnV7CgUsGef7',	6,	2,	'123456',	1,	'2017-01-23 17:27:25',	'2017-01-23 17:28:27'),
(19,	'XHdgE0rg1Rp0fNsVdyJ9mnZQG991Am',	7,	2,	'123456',	1,	'2017-01-23 17:29:40',	'2017-01-23 17:29:56'),
(20,	'AuR6DIO4fFmgHFpAWYaVEFP0Vmmk2p',	8,	2,	'123456',	1,	'2017-01-23 17:30:55',	'2017-01-23 17:31:00'),
(21,	'kNpmSmclSYLycaGLTtNWc2AXylZKQD',	9,	2,	'123456',	1,	'2017-01-23 17:34:56',	'2017-01-23 17:35:44'),
(22,	'QBwWTYgDfUHHcsJdduOX23pM2tpF5u',	10,	2,	'123456',	1,	'2017-01-23 17:36:09',	'2017-01-23 17:36:14');

DROP TABLE IF EXISTS `tests`;
CREATE TABLE `tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `companies_id` tinyint(3) unsigned DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_id` (`companies_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`companies_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tests_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tests` (`id`, `token`, `name`, `description`, `companies_id`, `users_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'P6vqOtBkiUCCkLv14iAO0KEgLtyNHc',	'Jak se vám daří v práci?',	NULL,	1,	1,	'2017-01-18 10:54:46',	'2017-01-18 10:54:46',	NULL),
(2,	'3NVV0Dp0CIRBNb48KASNSk2gW1B3Ut',	'Jaké kolečko je větší?',	NULL,	1,	1,	'2017-01-18 10:59:02',	'2017-01-18 15:18:17',	NULL);

DROP TABLE IF EXISTS `test_options`;
CREATE TABLE `test_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_questions_id` int(10) unsigned NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annotation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correct` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_questions_id` (`test_questions_id`),
  CONSTRAINT `test_options_ibfk_1` FOREIGN KEY (`test_questions_id`) REFERENCES `test_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `test_options` (`id`, `test_questions_id`, `token`, `answer`, `description`, `annotation`, `correct`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	1,	'soYXMsR9MdlSmlBVfu55mpHoEn0ZOj',	'Odpověd 2',	NULL,	NULL,	0,	'2017-01-19 15:02:55',	'2017-01-20 14:55:40',	'2017-01-20 14:55:40'),
(2,	1,	'bIJT4azpShEeoMdlw6eVnJf1zmtxad',	'Odpověd 3',	NULL,	NULL,	0,	'2017-01-19 15:05:57',	'2017-01-20 14:55:41',	'2017-01-20 14:55:41'),
(3,	1,	'3S6bnqrlcVpRDhaMtWNHbgTgXZ8SHZ',	'Odpověd 4',	NULL,	NULL,	0,	'2017-01-19 15:06:12',	'2017-01-20 14:55:42',	'2017-01-20 14:55:42'),
(4,	1,	'sXQIF4xRCpaO0kp7AYXxB1Us866T6w',	'Odpověd 5',	NULL,	NULL,	0,	'2017-01-19 15:47:46',	'2017-01-20 14:55:43',	'2017-01-20 14:55:43'),
(5,	1,	'AZ1BYFsaBDAkKzuqoTSfiLmemr3dtP',	'Odpověd 6',	NULL,	NULL,	0,	'2017-01-19 15:47:47',	'2017-01-20 14:55:44',	'2017-01-20 14:55:44'),
(6,	1,	'eZeYHjeSzdDegtOTmlOt8ZFhCsKFtD',	'Odpověd 7',	NULL,	NULL,	0,	'2017-01-19 15:47:48',	'2017-01-19 18:32:26',	'2017-01-19 18:32:26'),
(7,	1,	'zrJvevvQUzV73bqJ5J1oaq3XBSVJu5',	'Odpověd 8',	NULL,	NULL,	1,	'2017-01-19 15:47:48',	'2017-01-19 18:32:07',	NULL),
(8,	1,	'HryK8XatMPwDcaGzw0coDIBtQbVplt',	'Odpověd 9',	NULL,	NULL,	0,	'2017-01-19 15:48:11',	'2017-01-20 11:10:44',	'2017-01-20 11:10:44'),
(9,	2,	'Ml2j2BHcW0QMAf3kTS8DLdzmqsE6Ck',	'asdas adas',	NULL,	NULL,	1,	'2017-01-19 16:35:50',	'2017-01-20 11:10:51',	NULL),
(10,	1,	'EPqAENtZ8pN8LDpJ6nusTYjy0SmAiP',	'Odpověd 10',	NULL,	NULL,	0,	'2017-01-19 17:29:17',	'2017-01-19 18:32:07',	NULL),
(11,	2,	'5eFLXB9B4Zg2xvc4qXBpHLjrKkEooV',	'asdasa as das',	NULL,	NULL,	0,	'2017-01-19 18:21:15',	'2017-01-20 11:10:51',	NULL),
(12,	3,	'SgMFI5vZMiHUT4e2mLfrM5P5XmQyS5',	'asdsa',	NULL,	NULL,	1,	'2017-01-20 12:27:28',	'2017-01-20 12:27:39',	NULL),
(13,	3,	'dNs6gGK8d7QdwSfvi4z0T6MARHYb8i',	'asdas',	NULL,	NULL,	1,	'2017-01-20 12:27:32',	'2017-01-20 12:27:39',	NULL),
(14,	3,	'wkfKB8Bzrb54LfiHaKtY7v84Rn71SJ',	'asdas',	NULL,	NULL,	0,	'2017-01-20 12:27:33',	'2017-01-20 12:27:39',	NULL),
(15,	4,	'R0c2qWobxceq0JEbdGkZoVgTh1RCIh',	'asdas',	NULL,	NULL,	3,	'2017-01-20 14:56:12',	'2017-01-20 14:56:36',	NULL),
(16,	4,	'JDQfF4HZAgoP5vVl9NK8GJ3QcyR1XX',	'asdsa',	NULL,	NULL,	2,	'2017-01-20 14:56:20',	'2017-01-20 14:56:36',	NULL),
(17,	4,	'tSy7WP6ov0QvqK9zgd3hyQfmVrsFwP',	'asdas',	NULL,	NULL,	1,	'2017-01-20 14:56:22',	'2017-01-20 14:56:36',	NULL);

DROP TABLE IF EXISTS `test_questions`;
CREATE TABLE `test_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tests_id` int(10) unsigned NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(3) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tests_id` (`tests_id`),
  CONSTRAINT `test_questions_ibfk_1` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `test_questions` (`id`, `tests_id`, `token`, `question`, `description`, `type`, `sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	2,	'iSFvqdb12XaPH0KSJUOETXabUVkuTM',	'Jak ti dneska je?',	'dddasdsa',	'RADIOLIST',	NULL,	'2017-01-18 16:39:12',	'2017-01-19 15:49:02',	NULL),
(2,	2,	'J8kd9kK686sg7QL15gahgI2ud1SSp2',	'Co budeš dělat?',	NULL,	'RADIOLIST',	NULL,	'2017-01-19 11:39:00',	'2017-01-20 14:55:52',	'2017-01-20 14:55:52'),
(3,	2,	'OGeVGEkJZXMmdHOa1aF4KU8JjfCmA0',	'Jak jde pes?',	NULL,	'CHECKBOXLIST',	NULL,	'2017-01-20 12:26:52',	'2017-01-20 12:26:52',	NULL),
(4,	2,	'OSThSxRTv15mLDPZzyd8Y4FGShlCL0',	'Co budeš dělat?',	NULL,	'SORT',	NULL,	'2017-01-20 14:56:05',	'2017-01-20 14:56:05',	NULL);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(70) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `surname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(9) DEFAULT NULL,
  `phone_verification` tinyint(4) NOT NULL DEFAULT '0',
  `sex` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_token` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_expiration_at` datetime DEFAULT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `number_of_logins` smallint(6) NOT NULL DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `username`, `name`, `surname`, `email`, `phone`, `phone_verification`, `sex`, `password`, `password_token`, `password_expiration_at`, `token`, `active`, `number_of_logins`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	NULL,	'Lisa',	'Simpson',	'lisa@dontpanic.cz',	NULL,	0,	NULL,	'$2y$10$zUIOxVWGoi3w5PgDigmtIOPwhA5qH8l1Gj.lXAsEPv15qFGRZ263S',	NULL,	NULL,	'ehf9i35w495tar9zeihghkahrbxv1o',	1,	49,	'2017-01-20 16:48:25',	'2017-01-02 16:05:43',	'0000-00-00 00:00:00',	NULL),
(2,	NULL,	'Bart',	'Simpson',	'bart@dontpanic.cz',	NULL,	0,	NULL,	'$2y$10$peXSb2UXlOMyQPjyvlQGCeUbG/70KzgYnyorJXYIT2JOEZdKlovqy',	NULL,	NULL,	'0v5kaqzoqujnvir4tm3y8h9xg8jdns',	1,	4,	'2017-01-02 17:30:47',	'2017-01-02 17:25:05',	'0000-00-00 00:00:00',	NULL),
(3,	NULL,	'Barbucha',	'Lesní',	'barbucha@lesni.cz',	123456789,	1,	NULL,	NULL,	NULL,	NULL,	'd0Olm64dHCVZhPcAaH99G3kGcq7xR4',	1,	0,	NULL,	'2017-01-23 15:14:40',	'2017-01-23 16:52:41',	NULL),
(4,	NULL,	'Tomáš',	'Kadeřábek',	'tomas@kaderabek.cz',	987654321,	0,	NULL,	NULL,	NULL,	NULL,	'vRcNDSCJw5CulhWmJXRSDFKrLaYDuT',	1,	0,	NULL,	'2017-01-23 15:23:16',	'2017-01-23 15:23:16',	NULL),
(5,	NULL,	'Melichar',	'Nová',	'melicha@novy.cz',	987789987,	1,	NULL,	NULL,	NULL,	NULL,	'iXlH3FPkeDRclASxWUz7SyBWTprq6Z',	1,	0,	NULL,	'2017-01-23 17:22:29',	'2017-01-23 17:22:37',	NULL),
(6,	NULL,	'aas',	'asdas',	'dsfds@sfd.cz',	123123123,	1,	NULL,	NULL,	NULL,	NULL,	'ZBWfDxypmJK1M3Vw0TDT7XPZEblWuw',	1,	0,	NULL,	'2017-01-23 17:25:30',	'2017-01-23 17:28:27',	NULL),
(7,	NULL,	'Petr',	'Noha',	'petr@noha.cz',	543543543,	1,	NULL,	NULL,	NULL,	NULL,	'n4dxAT5V00TWI6XjWAViYwd6IOa15D',	1,	0,	NULL,	'2017-01-23 17:29:40',	'2017-01-23 17:29:56',	NULL),
(8,	NULL,	'Klara',	'Novotna',	'klara@novotna.cz',	765121321,	1,	NULL,	NULL,	NULL,	NULL,	'gSavmjNKv4RpSvmA1gjqzN7z3wGAwn',	1,	0,	NULL,	'2017-01-23 17:30:55',	'2017-01-23 17:31:00',	NULL),
(9,	NULL,	'Bart',	'Noha',	'bbart@noha.cz',	453121587,	1,	NULL,	NULL,	NULL,	NULL,	'cQQfVDBeOz8e0as1OXrL0WkBGwGCBM',	1,	0,	NULL,	'2017-01-23 17:34:55',	'2017-01-23 17:35:44',	NULL),
(10,	NULL,	'Kvetak',	'Noha',	'kvetak@noha.cz',	965456890,	1,	NULL,	NULL,	NULL,	NULL,	'O19W31uqWcfrf3fGvpBJZhqH809jsx',	1,	0,	NULL,	'2017-01-23 17:36:09',	'2017-01-23 17:36:14',	NULL);

DROP TABLE IF EXISTS `user_has_company`;
CREATE TABLE `user_has_company` (
  `users_id` int(11) NOT NULL,
  `companies_id` tinyint(3) unsigned NOT NULL,
  KEY `users_id` (`users_id`),
  KEY `companies_id` (`companies_id`),
  CONSTRAINT `user_has_company_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_has_company_ibfk_2` FOREIGN KEY (`companies_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_has_company` (`users_id`, `companies_id`) VALUES
(1,	1),
(1,	2);

DROP TABLE IF EXISTS `user_test_answers`;
CREATE TABLE `user_test_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_options_id` int(10) unsigned DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `correct` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_options_id` (`test_options_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `user_test_answers_ibfk_1` FOREIGN KEY (`test_options_id`) REFERENCES `test_options` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_test_answers_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_test_answers` (`id`, `test_options_id`, `users_id`, `correct`, `created_at`, `updated_at`) VALUES
(1,	10,	1,	1,	'2017-01-20 16:53:27',	'2017-01-20 16:53:27'),
(2,	12,	1,	1,	'2017-01-20 16:53:27',	'2017-01-20 16:53:27'),
(3,	13,	1,	1,	'2017-01-20 16:53:27',	'2017-01-20 16:53:27'),
(4,	15,	1,	2,	'2017-01-20 16:53:27',	'2017-01-20 16:53:27'),
(5,	16,	1,	3,	'2017-01-20 16:53:27',	'2017-01-20 16:53:27'),
(6,	17,	1,	1,	'2017-01-20 16:53:27',	'2017-01-20 16:53:27'),
(7,	10,	1,	1,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(8,	12,	1,	1,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(9,	13,	1,	1,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(10,	15,	1,	2,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(11,	16,	1,	3,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(12,	17,	1,	1,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(13,	10,	1,	1,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(14,	12,	1,	1,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(15,	13,	1,	1,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(16,	15,	1,	2,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(17,	16,	1,	3,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(18,	17,	1,	1,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(19,	10,	3,	1,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(20,	12,	3,	1,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(21,	13,	3,	1,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(22,	15,	3,	1,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(23,	16,	3,	3,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(24,	17,	3,	2,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(25,	10,	5,	1,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(26,	13,	5,	1,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(27,	14,	5,	1,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(28,	15,	5,	1,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(29,	16,	5,	3,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(30,	17,	5,	4,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(31,	7,	6,	1,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(32,	12,	6,	1,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(33,	13,	6,	1,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(34,	15,	6,	4,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(35,	16,	6,	4,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(36,	17,	6,	1,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(37,	10,	9,	1,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(38,	13,	9,	1,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(39,	14,	9,	1,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(40,	15,	9,	1,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(41,	16,	9,	4,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(42,	17,	9,	1,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(43,	7,	10,	1,	'2017-01-23 17:36:23',	'2017-01-23 17:36:23'),
(44,	12,	10,	1,	'2017-01-23 17:36:23',	'2017-01-23 17:36:23'),
(45,	15,	10,	1,	'2017-01-23 17:36:23',	'2017-01-23 17:36:23'),
(46,	16,	10,	4,	'2017-01-23 17:36:23',	'2017-01-23 17:36:23'),
(47,	17,	10,	2,	'2017-01-23 17:36:23',	'2017-01-23 17:36:23');

DROP TABLE IF EXISTS `user_test_score`;
CREATE TABLE `user_test_score` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `tests_id` int(10) unsigned NOT NULL,
  `correct_answers` int(11) NOT NULL DEFAULT '0',
  `wrong_answers` int(11) NOT NULL DEFAULT '0',
  `done` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `tests_id` (`tests_id`),
  CONSTRAINT `user_test_score_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_test_score_ibfk_2` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_test_score` (`id`, `users_id`, `tests_id`, `correct_answers`, `wrong_answers`, `done`, `created_at`, `updated_at`) VALUES
(1,	1,	2,	0,	0,	0,	'2017-01-20 17:08:17',	'2017-01-20 17:08:17'),
(2,	1,	2,	0,	0,	0,	'2017-01-20 17:12:02',	'2017-01-20 17:12:02'),
(3,	3,	2,	0,	0,	1,	'2017-01-23 17:18:11',	'2017-01-23 17:18:11'),
(4,	5,	2,	0,	0,	1,	'2017-01-23 17:22:46',	'2017-01-23 17:22:46'),
(5,	6,	2,	0,	0,	1,	'2017-01-23 17:28:40',	'2017-01-23 17:28:40'),
(6,	9,	2,	0,	0,	1,	'2017-01-23 17:35:53',	'2017-01-23 17:35:53'),
(7,	10,	2,	0,	0,	1,	'2017-01-23 17:36:23',	'2017-01-23 17:36:23');

DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `_migrations`;
CREATE TABLE `_migrations` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2017-01-23 16:37:04