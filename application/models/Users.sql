CREATE TABLE `user` (
	`u_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`u_email` VARCHAR(80),
	`u_passwd` VARCHAR(40),
	`u_salt` VARCHAR(40),
	`u_role` ENUM("user","mod","admin"),
	`u_status` ENUM("inactive","active","banned"),
	PRIMARY KEY(`u_id`)
) ENGINE=InnoDB;
CREATE TABLE `user_e_moderator` (
	`u_id` INT(10) UNSIGNED,
	PRIMARY KEY(`u_id`)
) ENGINE=InnoDB;
CREATE TABLE `user_e_admin` (
	`u_id` INT(10) UNSIGNED,
	`uea_login_attempts` VARCHAR(255),
	PRIMARY KEY(`u_id`)
) ENGINE=InnoDB;
CREATE TABLE `user_c_detailsu` (
	`ucd_id` INT(10) UNSIGNED,
	`ucd_name` VARCHAR(80),
	`ucd_surname` VARCHAR(80),
	`ucd_index_id` INT(11),
	PRIMARY KEY(`ucd_id`)
) ENGINE=InnoDB;
CREATE TABLE `user_e_moderator_c_detailsm` (
	`uemcd_id` INT(10) UNSIGNED,
	`uemcd_name` VARCHAR(80),
	`uemcd_surname` VARCHAR(80),
	PRIMARY KEY(`uemcd_id`)
) ENGINE=InnoDB;


ALTER TABLE `user_e_moderator`
	ADD CONSTRAINT `user_e_moderator_ibfk_u_id` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `user_e_admin`
	ADD CONSTRAINT `user_e_admin_ibfk_u_id` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `user_c_detailsu`
	ADD CONSTRAINT `user_c_detailsu_ibfk_ucd_id` FOREIGN KEY (`ucd_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `user_e_moderator_c_detailsm`
	ADD CONSTRAINT `user_e_moderator_c_detailsm_ibfk_uemcd_id` FOREIGN KEY (`uemcd_id`) REFERENCES `user_e_moderator` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
