-- Drop existing tables
DROP TABLE IF EXISTS `domain_user`;
DROP TABLE IF EXISTS `setting`;
DROP TABLE IF EXISTS `log`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `domains`;
DROP TABLE IF EXISTS `records`;
DROP TABLE IF EXISTS `supermasters`;

-- Create new tables
create table domains (
 id		 INT auto_increment,
 name		 VARCHAR(255) NOT NULL,
 master		 VARCHAR(128) DEFAULT NULL,
 last_check	 INT DEFAULT NULL,
 type		 VARCHAR(6) NOT NULL,
 notified_serial INT DEFAULT NULL, 
 account         VARCHAR(40) DEFAULT NULL,
 primary key (id)
) Engine=InnoDB;

CREATE UNIQUE INDEX name_index ON domains(name);

CREATE TABLE records (
  id              INT auto_increment,
  domain_id       INT DEFAULT NULL,
  name            VARCHAR(255) DEFAULT NULL,
  type            VARCHAR(10) DEFAULT NULL,
  content         VARCHAR(4096) DEFAULT NULL,
  ttl             INT DEFAULT NULL,
  prio            INT DEFAULT NULL,
  change_date     INT DEFAULT NULL,
  primary key(id)
) Engine=InnoDB;

CREATE INDEX rec_name_index ON records(name);
CREATE INDEX nametype_index ON records(name,type);
CREATE INDEX domain_id ON records(domain_id);

create table supermasters (
  ip VARCHAR(25) NOT NULL, 
  nameserver VARCHAR(255) NOT NULL, 
  account VARCHAR(40) DEFAULT NULL
) Engine=InnoDB;

CREATE TABLE domain_user (
	`domain_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	PRIMARY KEY(domain_id,user_id)
) Engine=InnoDB;

CREATE TABLE setting (
	`key` VARCHAR(128) NOT NULL UNIQUE PRIMARY KEY,
	`value` VARCHAR(128) NOT NULL
) Engine=InnoDB;

CREATE TABLE log (
	`id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`action` VARCHAR (256) NOT NULL,
	`created` DATETIME NOT NULL
) Engine=InnoDB;

CREATE TABLE user (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(128) NOT NULL,
    `password` VARCHAR(128) NOT NULL,
    `email` VARCHAR(128) NOT NULL,
	`admin` TINYINT(1) DEFAULT 0
) Engine=InnoDB;

-- Add seed data
INSERT INTO user (username, password, email, admin) VALUES ('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@pdnsops.com', 1);
INSERT INTO user (username, password, email, admin) VALUES ('demo', '89e495e7941cf9e40e6980d14a16bf023ccd4c91', 'demo@pdnsops.com', 0);

INSERT INTO setting (`key`, `value`) VALUES
	('domainMasterIP', ''),
	('defaultTTL', '7200'),
	('soaRetry', '3600'),
	('soaRefresh', '10800'),
	('soaExpire', '1814400'),
	('ns1', '127.0.0.1'),
	('ns2', ''),
	('ns3', ''),
	('ns4', ''),
	('ns5', ''),
	('ns6', ''),
	('ns7', ''),
	('ns8', '');

-- Foreign Keys
ALTER TABLE `domain_user` ADD FOREIGN KEY `fk_domain_user` (`domain_id`) REFERENCES `domains` (`id`);
ALTER TABLE `domain_user` ADD FOREIGN KEY `fk_user_domain` (`user_id`) REFERENCES `user` (`id`);