CREATE TABLE IF NOT EXISTS staff (
	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	nickname VARCHAR(32) NOT NULL,
	password VARCHAR(60) NOT NULL,
	email VARCHAR(255),
	cell VARCHAR(16),
	position VARCHAR(255),
	auth SMALLINT UNSIGNED DEFAULT 0,
	PRIMARY KEY (id),
	UNIQUE KEY (nickname)
) Type=InnoDB, CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS projects (
	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(128) NOT NULL,
	shortname VARCHAR(16) NOT NULL,
	episodes MEDIUMINT DEFAULT 0,
	leader MEDIUMINT UNSIGNED NOT NULL,
	description MEDIUMTEXT,
	PRIMARY KEY (id),
	UNIQUE KEY (name),
	UNIQUE KEY (shortname),
	INDEX (leader),
	FOREIGN KEY (leader) REFERENCES staff (id)
) Type=InnoDB, CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS permissions (
	project MEDIUMINT UNSIGNED NOT NULL,
	staff MEDIUMINT UNSIGNED NOT NULL,
	auth SMALLINT UNSIGNED DEFAULT 0,
	UNIQUE KEY (project,staff),
	INDEX (project),
	INDEX (staff),
	FOREIGN KEY (project) REFERENCES projects (id),
	FOREIGN KEY (staff) REFERENCES staff (id)
) Type=InnoDB, CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS tasktypes (
	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY (name)
) Type=InnoDB, CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS tasks (
	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	series MEDIUMINT UNSIGNED NOT NULL,
	episode MEDIUMINT NOT NULL,
	tasktype MEDIUMINT UNSIGNED NOT NULL,
	description MEDIUMTEXT,
	assignedto MEDIUMINT UNSIGNED NOT NULL,
	active BOOLEAN DEFAULT 0,
	finished BOOLEAN DEFAULT 0,
	PRIMARY KEY (id),
	INDEX (series),
	INDEX (tasktype),
	INDEX (assignedto),
	FOREIGN KEY (series) REFERENCES projects (id),
	FOREIGN KEY (tasktype) REFERENCES tasktypes (id),
	FOREIGN KEY (assignedto) REFERENCES staff (id)
) Type=InnoDB, CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS tasktree (
	prevtask MEDIUMINT UNSIGNED NOT NULL,
	nexttask MEDIUMINT UNSIGNED NOT NULL,
	UNIQUE KEY (prevtask,nexttask),
	INDEX (prevtask),
	INDEX (nexttask),
	FOREIGN KEY (prevtask) REFERENCES tasks (id),
	FOREIGN KEY (nexttask) REFERENCES tasks (id)
) Type=InnoDB, CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS templates (
	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	model TEXT,
	PRIMARY KEY (id)
) Type=InnoDB, CHARSET=UTF8;

INSERT INTO staff VALUES (0,'Nobody/Unassigned','','','','Dummy user',0);
INSERT INTO projects VALUES (0,'NO PROJECT','NONE',0,0,'Dummy project');
INSERT INTO tasktypes VALUES
	( 1,'Raw Cap'),
	( 2,'Translate'),
	( 3,'Time'),
	( 4,'Translation Check'),
	( 5,'Typeset'),
	( 6,'Edit'),
	( 7,'Encode'),
	( 8,'Quality Check'),
	( 9,'Karaoke'),
	(10,'Miscellaneous'),
	(11,'Translate Signs'),
	(12,'Release');
