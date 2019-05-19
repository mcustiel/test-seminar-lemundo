CREATE TABLE translations (
	translation_id VARCHAR(32) NOT NULL,
	locale 		   CHAR(5),
	text		   TINYTEXT,
	
	PRIMARY KEY (translation_id, locale)
);
