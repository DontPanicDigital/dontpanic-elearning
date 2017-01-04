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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(70) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `surname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(9) DEFAULT NULL,
  `sex` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_token` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_expiration_at` datetime DEFAULT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `number_of_logins` smallint(6) NOT NULL DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `username`, `name`, `surname`, `email`, `phone`, `sex`, `password`, `password_token`, `password_expiration_at`, `token`, `active`, `number_of_logins`, `last_login_at`, `created_at`, `deleted_at`) VALUES
(1,	NULL,	'Lisa',	'Simpson',	'lisa@dontpanic.cz',	NULL,	NULL,	'$2y$10$zUIOxVWGoi3w5PgDigmtIOPwhA5qH8l1Gj.lXAsEPv15qFGRZ263S',	NULL,	NULL,	'ehf9i35w495tar9zeihghkahrbxv1o',	1,	33,	'2017-01-02 17:45:29',	'2017-01-02 16:05:43',	NULL),
(2,	NULL,	'Bart',	'Simpson',	'bart@dontpanic.cz',	NULL,	NULL,	'$2y$10$peXSb2UXlOMyQPjyvlQGCeUbG/70KzgYnyorJXYIT2JOEZdKlovqy',	NULL,	NULL,	'0v5kaqzoqujnvir4tm3y8h9xg8jdns',	1,	4,	'2017-01-02 17:30:47',	'2017-01-02 17:25:05',	NULL);

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


-- 2017-01-02 17:27:13