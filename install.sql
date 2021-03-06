DROP TABLE IF EXISTS wcf1_theme;
CREATE TABLE wcf1_theme (
	themeID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL DEFAULT 0,
	themeName VARCHAR(255) NOT NULL DEFAULT '',
	templatePackID INT(10) NOT NULL DEFAULT 0,
	themeDescription TEXT,
	themeVersion VARCHAR(255) NOT NULL DEFAULT '',
	themeDate CHAR(10) NOT NULL DEFAULT '0000-00-00',
	fileLocation VARCHAR(255) NOT NULL DEFAULT '',
	copyright VARCHAR(255) NOT NULL DEFAULT '',
	license VARCHAR(255) NOT NULL DEFAULT '',
	authorName VARCHAR(255) NOT NULL DEFAULT '',
	authorURL VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_theme_layout;
CREATE TABLE wcf1_theme_layout (
	themeLayoutID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL DEFAULT 0,
	themeID INT(10) NOT NULL DEFAULT 0,
	title VARCHAR(255) NOT NULL DEFAULT '',
	isDefault TINYINT(1) NOT NULL DEFAULT 0,
	KEY (packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_theme_module;
CREATE TABLE wcf1_theme_module (
	themeModuleID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL DEFAULT 0,
	themeID INT(10) NOT NULL DEFAULT 0,
	title VARCHAR(255) NOT NULL DEFAULT '',
	cssID VARCHAR(255) NOT NULL DEFAULT '',
	cssClasses VARCHAR(255) NOT NULL DEFAULT '',
	themeModuleType VARCHAR(125) NOT NULL DEFAULT '',
	themeModuleData MEDIUMTEXT,
	KEY (packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_theme_module_to_layout;
CREATE TABLE wcf1_theme_module_to_layout (
	themeModuleID INT(10) NOT NULL DEFAULT 0,
	themeLayoutID INT(10) NOT NULL DEFAULT 0,
	themeModulePosition VARCHAR(255) NOT NULL DEFAULT '',
	showOrder INT(10) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_theme_module_type;
CREATE TABLE wcf1_theme_module_type (
	themeModuleTypeID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	themeModuleType VARCHAR(125) NOT NULL,
	category VARCHAR(255) NOT NULL,
	classFile VARCHAR(255) NOT NULL,
	UNIQUE KEY (packageID, themeModuleType),
	KEY (packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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