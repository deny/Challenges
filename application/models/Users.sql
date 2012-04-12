CREATE TABLE `user` (
	`u_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`u_email` VARCHAR(80),
	`u_passwd` VARCHAR(40),
	`u_salt` VARCHAR(40),
	`u_name` VARCHAR(80),
	`u_surname` VARCHAR(80),
	`u_role` ENUM("user","mod","admin"),
	`u_status` ENUM("inactive","active","banned"),
	PRIMARY KEY(`u_id`)
) ENGINE=InnoDB;
CREATE TABLE `user_e_admin` (
	`u_id` INT(10) UNSIGNED,
	`uea_login_attempts` VARCHAR(255),
	PRIMARY KEY(`u_id`)
) ENGINE=InnoDB;


ALTER TABLE `user_e_admin`
	ADD CONSTRAINT `user_e_admin_ibfk_u_id` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
