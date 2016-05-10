--
-- 01-structre.sql
--
-- This file contains the basic data for this website.
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
-- Insert the required data
-- Initial Password: p@ssw0rd
--
INSERT INTO settings VALUES (1, '2015-09-15', '2016-08-26');
INSERT INTO users (usr_email,usr_name, usr_password, usr_permissions)
VALUES('admin@example.com', 'Administrator', '$2a$07$06b6438b8a609ba7eb765uiRxDTXjtkWFB5weeTFY1VNggzjxuLhq', 'manage,admin');