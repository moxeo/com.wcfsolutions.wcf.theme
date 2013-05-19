ALTER TABLE wcf1_theme_layout DROP styleSheets;

DROP TABLE IF EXISTS wcf1_theme_stylesheet;
CREATE TABLE wcf1_theme_stylesheet (
	themeStylesheetID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL DEFAULT 0,
	themeID INT(10) NOT NULL DEFAULT 0,
	title VARCHAR(255) NOT NULL DEFAULT '',
	lessCode TEXT,
	KEY (packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_theme_stylesheet_to_layout;
CREATE TABLE wcf1_theme_stylesheet_to_layout (
	themeStylesheetID INT(10) NOT NULL DEFAULT 0,
	themeLayoutID INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (themeStylesheetID, themeLayoutID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;