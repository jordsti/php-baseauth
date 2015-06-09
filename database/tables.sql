CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT(25) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(40) NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `hash_type` VARCHAR(16) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `created_on` INT(25) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` INT(25) NOT NULL AUTO_INCREMENT,
  `permission_name` VARCHAR(255) NOT NULL,
  `permission_value` INT(25) NOT NULL,
  `permission_description` TEXT NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` INT(25) NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `group_permission_id` INT(25) NOT NULL AUTO_INCREMENT,
  `group_id` INT(25) NOT NULL,
  `permission_id` INT(25) NOT NULL,
  PRIMARY KEY (`group_permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users_groups` ( 
  `user_group_id` INT(25) NOT NULL AUTO_INCREMENT,
  `user_id` INT(25) NOT NULL,
  `group_id` INT(25) NOT NULL,
  PRIMARY KEY(`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `settings` (
	`setting_id` INT(25) NOT NULL AUTO_INCREMENT,
	`setting_name` VARCHAR(64) NOT NULL,
	`setting_value` VARCHAR(128) NOT NULL,
	PRIMARY KEY(`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- inserting default settings #
INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES (1, 'hash_type', 'sha256');
INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES (2, 'username_min', '4');
INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES (3, 'username_max', '12');

-- Adding Default Admin User #
INSERT INTO `users` (`user_id`, `username`, `first_name`, `last_name`, `salt`, `password`, `hash_type`, `email`, `created_on`) VALUES (1, 'admin', 'Admin', '', '', 'admin', 'clear', 'admin@invoice-master', 0);
-- Adding all default permissions #
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_value`, `permission_description`) VALUES (1, 'manage_users', 0, 'Create, delete and update users');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_value`, `permission_description`) VALUES (2, 'manage_groups', 1, 'Create, delete and update groups');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_value`, `permission_description`) VALUES (3, 'manage_permissions', 2, 'Create, delete and update permissions');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_value`, `permission_description`) VALUES (4, 'manage_settings', 3, 'Create, delete and update settings');
-- Creating the Admin group #
INSERT INTO `groups` (`group_id`, `group_name`) VALUES (1, 'Admin');
INSERT INTO `groups_permissions` (`group_id`, `permission_id`) VALUES (1, 1);
INSERT INTO `groups_permissions` (`group_id`, `permission_id`) VALUES (1, 2);
INSERT INTO `groups_permissions` (`group_id`, `permission_id`) VALUES (1, 3);
INSERT INTO `groups_permissions` (`group_id`, `permission_id`) VALUES (1, 4);
-- Adding the admin to the admin group #
INSERT INTO `users_groups` (`user_id`, `group_id`) VALUES (1, 1);
