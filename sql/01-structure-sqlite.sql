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
CREATE TABLE "users" (
	  "usr_id"             INTEGER       NOT NULL  PRIMARY KEY  AUTOINCREMENT
	, "usr_email"          TEXT(100, 0)  NOT NULL
	, "usr_name"           TEXT( 50, 0)  NOT NULL
	, "usr_firstname"      TEXT( 50, 0)  NOT NULL
	, "usr_password"       TEXT(255, 0)  NOT NULL
	, "usr_mobile"         TEXT( 30, 0)
	, "usr_class"          TEXT( 20, 0)
	, "usr_room"           TEXT( 20, 0)
	, "usr_qualification"  TEXT
	, "usr_permissions"    TEXT(255,0)
	, "usr_login"          TEXT(20,0)
	, CONSTRAINT "usr_email_uq" UNIQUE ("usr_email" ASC)
);

CREATE TABLE "attendences" (
	  "att_user"  INTEGER        NOT NULL
	, "att_week"  INTEGER        NOT NULL
	, "att_year"  INTEGER        NOT NULL
	, "att_mon"   INTEGER(1, 0)
	, "att_tue"   INTEGER(1, 0)
	, "att_wed"   INTEGER(1, 0)
	, "att_thu"   INTEGER(1, 0)
	, "att_fri"   INTEGER(1, 0)
	, PRIMARY KEY("att_user", "att_week", "att_year")
	, CONSTRAINT "att_user_fk" FOREIGN KEY ("att_user") REFERENCES "users" ("usr_id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "duties" (
	  "dut_user"  INTEGER        NOT NULL
	, "dut_week"  INTEGER        NOT NULL
	, "dut_year"  INTEGER        NOT NULL
	, "dut_mon"   INTEGER(1, 0)
	, "dut_tue"   INTEGER(1, 0)
	, "dut_wed"   INTEGER(1, 0)
	, "dut_thu"   INTEGER(1, 0)
	, "dut_fri"   INTEGER(1, 0)
	, PRIMARY KEY("dut_user", "dut_week", "dut_year")
	, CONSTRAINT "dut_user_fk" FOREIGN KEY ("dut_user") REFERENCES "users" ("usr_id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "settings" (
	  "set_id"     INTEGER      NOT NULL  PRIMARY KEY  AUTOINCREMENT
	, "set_start"  TEXT(20, 0)  NOT NULL
	, "set_end"    TEXT(20, 0)  NOT NULL
);

CREATE TABLE "holidays" (
	  "hol_id"     INTEGER       NOT NULL  PRIMARY KEY  AUTOINCREMENT
	, "hol_start"  TEXT( 10, 0)  NOT NULL
	, "hol_end"    TEXT( 10, 0)  NOT NULL
	, "hol_weeks"  TEXT(100, 0)
	, CONSTRAINT "hol_uq" UNIQUE ("hol_start" ASC, "hol_end" ASC)
);
