CREATE TABLE "sites" (
	  "site_url"      TEXT( 50, 0)  NOT NULL  PRIMARY KEY
	, "site_title"    TEXT(255, 0)
	, "site_content"  TEXT
	, "site_order"    INTEGER
);

INSERT INTO sites (site_url, site_content) VALUES ('infos', '<h1>Infos</h1>');
INSERT INTO sites (site_url, site_content) VALUES ('imprint', '<h1>Impressum</h1>');
INSERT INTO sites (site_url, site_content) VALUES ('privacy', '<h1>Datenschutz</h1>');