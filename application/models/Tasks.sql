CREATE TABLE `task` (
	`t_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`t_author` INT(10) UNSIGNED,
	`t_name` VARCHAR(255),
	`t_description` TEXT,
	`t_input` TEXT,
	`t_output` TEXT,
	PRIMARY KEY(`t_id`)
) ENGINE=InnoDB;
CREATE TABLE `solution` (
	`s_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`s_task` INT(10) UNSIGNED,
	`s_author` INT(10) UNSIGNED,
	`s_code` TEXT,
	`s_info` TEXT,
	`s_run_time` INT(11),
	`s_worker_id` INT(11),
	`s_language` ENUM("php"),
	`s_status` ENUM("new","testing","success","error"),
	PRIMARY KEY(`s_id`)
) ENGINE=InnoDB;


ALTER TABLE `task`
	ADD CONSTRAINT `task_ibfk_t_author` FOREIGN KEY (`t_author`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `solution`
	ADD CONSTRAINT `solution_ibfk_s_task` FOREIGN KEY (`s_task`) REFERENCES `task` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `solution_ibfk_s_author` FOREIGN KEY (`s_author`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
