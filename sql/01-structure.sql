--
-- 01-structre.sql
--
-- This file contains the basic structure for this website.
-- Updates will be added in the folder.
--
-- Requirements for a easy usage:
-- - Engine: InnoDB
-- - Character set: utf8
-- - Collation: utf8_bin
--
-- @author     Andreas Mueller <webmaster@am-wd.de>
-- @copyright  (c) 2016 Andreas Mueller
-- @license    MIT - http://am-wd.de/?p=about#license
--

--
-- Create the tables
--
CREATE TABLE users (
	  usr_id             INT(11)       NOT NULL  AUTO_INCREMENT
	, usr_email          VARCHAR(100)  NOT NULL
	, usr_name           VARCHAR(50)   NOT NULL
	, usr_firstname      VARCHAR(50)   NOT NULL
	, usr_password       VARCHAR(255)  NOT NULL
	, usr_mobile         VARCHAR(30)
	, usr_class          VARCHAR(20)
	, usr_room           VARCHAR(20)
	, usr_qualification  TEXT
	, usr_permissions    VARCHAR(255)
	, PRIMARY KEY (usr_id)
	, UNIQUE KEY (usr_email)
) COMMENT='Initial Account is admin@example.com - p@ssw0rd';

CREATE TABLE attendences (
	  att_user  INT(11)  NOT NULL
	, att_week  INT(3)   NOT NULL
	, att_year  INT(5)   NOT NULL
	, att_mon   BIT(1)
	, att_tue   BIT(1)
	, att_wed   BIT(1)
	, att_thu   BIT(1)
	, att_fri   BIT(1)
	, PRIMARY KEY (att_user, att_week, att_year)
	, CONSTRAINT att_user_fk FOREIGN KEY (att_user) REFERENCES users(usr_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE duties (
	  dut_user  INT(11)  NOT NULL
	, dut_week  INT(3)   NOT NULL
	, dut_year  INT(5)   NOT NULL
	, dut_mon   BIT(1)
	, dut_tue   BIT(1)
	, dut_wed   BIT(1)
	, dut_thu   BIT(1)
	, dut_fri   BIT(1)
	, PRIMARY KEY (dut_user, dut_week, dut_year)
	, CONSTRAINT dut_user_fk FOREIGN KEY (dut_user) REFERENCES users(usr_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE settings (
	  set_id        INT(11)       NOT NULL  AUTO_INCREMENT
	, set_start     DATE          NOT NULL                  COMMENT 'The first day of the school year'
	, set_end       DATE          NOT NULL                  COMMENT 'The last day of the school year'
	, PRIMARY KEY (set_id)
);

CREATE TABLE holidays (
	  hol_id     INT(11)       NOT NULL  AUTO_INCREMENT
	, hol_start  DATE          NOT NULL                  COMMENT 'The first day of the holidays'
	, hol_end    DATE          NOT NULL                  COMMENT 'The last day of the holidays'
	, hol_weeks  VARCHAR(100)                            COMMENT 'Contains all weeks of the holidays. Also weeks with only one holiday!'
	, PRIMARY KEY (hol_id)
	, UNIQUE KEY (hol_start, hol_end)
);
