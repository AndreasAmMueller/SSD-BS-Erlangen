CREATE TABLE sites (
	  site_url      VARCHAR(50)   NOT NULL
	, site_title    VARCHAR(255)
	, site_content  TEXT
	, site_order    INT(11)
	, PRIMARY KEY (site_url)
);

INSERT INTO sites (site_url, site_content) VALUES ('infos', '<h1>Infos</h1>');
INSERT INTO sites (site_url, site_content) VALUES ('imprint', '<h1>Impressum</h1>');
INSERT INTO sites (site_url, site_content) VALUES ('privacy', '<h1>Datenschutz</h1>');