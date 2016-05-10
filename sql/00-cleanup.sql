--
-- 00-cleanup.sql
--
-- This file deletes all tables for a nice cleanup.
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
-- Clear database
--
DROP TABLE IF EXISTS holidays;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS duties;
DROP TABLE IF EXISTS attendences;
DROP TABLE IF EXISTS users;