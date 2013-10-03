<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');

/**
 * Provides functions to manage themes.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme
 * @category	Community Framework
 */
class ThemeEditor extends Theme {
	const INFO_FILE = 'theme.xml';

	/**
	 * Creates a new ThemeEditor object.
	 */
	public function __construct($themeID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($themeID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_theme
				WHERE 	themeID = ".$themeID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}

	/**
	 * Updates this theme.
	 *
	 * @param	string		$themeName
	 * @param	integer		$templatePackID
	 * @param	string		$themeDescription
	 * @param	string		$themeVersion
	 * @param	string		$themeDate
	 * @param	string		$fileLocation
	 * @param	string		$copyright
	 * @param	string		$license
	 * @param	string		$authorName
	 * @param	string		$authorURL
	 */
	public function update($themeName, $templatePackID = 0, $themeDescription = '', $themeVersion = '', $themeDate = '0000-00-00', $fileLocation = '', $copyright = '', $license = '', $authorName = '', $authorURL = '') {
		$sql = "UPDATE	wcf".WCF_N."_theme
			SET	themeName = '".escapeString($themeName)."',
				templatePackID = ".$templatePackID.",
				themeDescription = '".escapeString($themeDescription)."',
				themeVersion = '".escapeString($themeVersion)."',
				themeDate = '".escapeString($themeDate)."',
				fileLocation = '".escapeString($fileLocation)."',
				copyright = '".escapeString($copyright)."',
				license = '".escapeString($license)."',
				authorName = '".escapeString($authorName)."',
				authorURL = '".escapeString($authorURL)."'
			WHERE	themeID = ".$this->themeID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Exports this theme.
	 *
	 * @param	boolean		$exportTemplates
	 */
	public function export($exportTemplates = false) {
		// create theme tar
		require_once(WCF_DIR.'lib/system/io/TarWriter.class.php');
		$themeTarName = FileUtil::getTemporaryFilename('theme_', '.tgz');
		$themeTar = new TarWriter($themeTarName, true);

		// create theme info file
		$string = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<theme xmlns=\"http://www.wcfsolutions.com\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.wcfsolutions.com http://www.wcfsolutions.com/XSD/theme.xsd\">\n";

		// general block
		$string .= "\t<general>\n";
		$string .= "\t\t<themename><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->themeName) : $this->themeName))."]]></themename>\n"; // theme name
		if ($this->themeDescription) $string .= "\t\t<description><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->themeDescription) : $this->themeDescription))."]]></description>\n"; // theme description
		if ($this->themeVersion) $string .= "\t\t<version><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->themeVersion) : $this->themeVersion))."]]></version>\n"; // theme version
		if ($this->themeDate) $string .= "\t\t<date><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->themeDate) : $this->themeDate))."]]></date>\n"; // theme date
		if ($this->copyright) $string .= "\t\t<copyright><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->copyright) : $this->copyright))."]]></copyright>\n"; // copyright
		if ($this->license) $string .= "\t\t<license><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->license) : $this->license))."]]></license>\n"; // license
		$string .= "\t</general>\n";

		// author block
		$string .= "\t<author>\n";
		if ($this->authorName) $string .= "\t\t<authorname><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->authorName) : $this->authorName))."]]></authorname>\n"; // author name
		if ($this->authorURL) $string .= "\t\t<authorurl><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->authorURL) : $this->authorURL))."]]></authorurl>\n"; // author URL
		$string .= "\t</author>\n";

		// files block
		$string .= "\t<install>\n";
		$string .= "\t\t<data>data.xml</data>\n"; // stylesheets
		if ($exportTemplates && $this->templatePackID) $string .= "\t\t<templates>templates.tar</templates>\n"; // templates
		$string .= "\t\t<files>files.tar</files>\n"; // files
		$string .= "\t</install>\n";

		$string .= "</theme>";
		// append theme info file to theme tar
		$themeTar->addString(self::INFO_FILE, $string);
		unset($string);

		// create data file
		$string = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<data xmlns=\"http://www.wcfsolutions.com\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.wcfsolutions.com http://www.wcfsolutions.com/XSD/theme-data.xsd\">\n";

		// read theme stylesheets
		require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetList.class.php');
		$themeStylesheetList = new ThemeStylesheetList();
		$themeStylesheetList->sqlLimit = 0;
		$themeStylesheetList->sqlConditions = 'theme_stylesheet.themeID = '.$this->themeID;
		$themeStylesheetList->readObjects();

		$string .= "\t<stylesheets>\n";
		$randomThemeStylesheetIDs = array();
		foreach ($themeStylesheetList->getObjects() as $themeStylesheet) {
			$randomThemeStylesheetID = StringUtil::getRandomID();
			$randomThemeStylesheetIDs[$themeStylesheet->themeStylesheetID] = $randomThemeStylesheetID;

			$string .= "\t\t<stylesheet id=\"".$randomThemeStylesheetID."\">\n";
			$string .= "\t\t\t<title><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeStylesheet->title) : $themeStylesheet->title))."]]></title>\n";
			$string .= "\t\t\t<lesscode><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeStylesheet->lessCode) : $themeStylesheet->lessCode))."]]></lesscode>\n";
			$string .= "\t\t</stylesheet>\n";
		}
		$string .= "\t</stylesheets>\n";

		// read theme layouts
		require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutList.class.php');
		$themeLayoutList = new ThemeLayoutList();
		$themeLayoutList->sqlLimit = 0;
		$themeLayoutList->sqlConditions = 'theme_layout.themeID = '.$this->themeID;
		$themeLayoutList->readObjects();

		$themeLayoutIDs = '';
		if (count($themeLayoutList->getObjects())) {
			$string .= "\t<layouts>\n";

			// get theme layout ids
			$randomThemeLayoutIDs = array();
			foreach ($themeLayoutList->getObjects() as $themeLayout) {
				if (!empty($themeLayoutIDs)) $themeLayoutIDs .= ',';
				$themeLayoutIDs .= $themeLayout->themeLayoutID;
			}

			// get mapped stylesheets
			$mappedThemeStylesheetIDs = array();
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_theme_stylesheet_to_layout
				WHERE	themeLayoutID IN (".$themeLayoutIDs.")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				// get random theme stylesheet id
				if (!isset($randomThemeStylesheetIDs[$row['themeStylesheetID']])) continue;
				$randomThemeStylesheetID = $randomThemeStylesheetIDs[$row['themeStylesheetID']];

				if (!isset($mappedThemeStylesheetIDs[$row['themeLayoutID']])) $mappedThemeStylesheetIDs[$row['themeLayoutID']] = array();
				$mappedThemeStylesheetIDs[$row['themeLayoutID']][] = $randomThemeStylesheetID;
			}

			foreach ($themeLayoutList->getObjects() as $themeLayout) {
				$themeStylesheets = array();
				if (isset($mappedThemeStylesheetIDs[$themeLayout->themeLayoutID])) {
					$themeStylesheets = $mappedThemeStylesheetIDs[$themeLayout->themeLayoutID];
				}

				$randomThemeLayoutID = StringUtil::getRandomID();
				$randomThemeLayoutIDs[$themeLayout->themeLayoutID] = $randomThemeLayoutID;

				$string .= "\t\t<layout id=\"".$randomThemeLayoutID."\">\n";
				$string .= "\t\t\t<title><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeLayout->title) : $themeLayout->title))."]]></title>\n";
				$string .= "\t\t\t<default>".$themeLayout->isDefault."</default>\n";

				if (count($themeStylesheets)) {
					$string .= "\t\t\t<stylesheets>\n";
					foreach ($themeStylesheets as $themeStylesheetID) {
						$string .= "\t\t\t\t<stylesheet>".$themeStylesheetID."</stylesheet>\n";
					}
					$string .= "\t\t\t</stylesheets>\n";
				}

				$string .= "\t\t</layout>\n";
			}
			$string .= "\t</layouts>\n";
		}

		// read theme modules
		require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleList.class.php');
		$themeModuleList = new ThemeModuleList();
		$themeModuleList->sqlLimit = 0;
		$themeModuleList->sqlConditions = 'theme_module.themeID = '.$this->themeID;
		$themeModuleList->readObjects();

		if (count($themeModuleList->getObjects())) {
			$string .= "\t<modules>\n";
			$randomThemeModuleIDs = array();
			foreach ($themeModuleList->getObjects() as $themeModule) {
				$randomThemeModuleID = StringUtil::getRandomID();
				$randomThemeModuleIDs[$themeModule->themeModuleID] = $randomThemeModuleID;

				$string .= "\t\t<module id=\"".$randomThemeModuleID."\">\n";
				$string .= "\t\t\t<title><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeModule->title) : $themeModule->title))."]]></title>\n";
				if ($themeModule->cssID) {
					$string .= "\t\t\t<cssid><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeModule->cssID) : $themeModule->cssID))."]]></cssid>\n";
				}
				if ($themeModule->cssClasses) {
					$string .= "\t\t\t<cssclasses><![CDATA[".StringUtil::escapeCDATA((CHARSET != 'UTF-8' ? StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeModule->cssClasses) : $themeModule->cssClasses))."]]></cssclasses>\n";
				}
				$string .= "\t\t\t<type>".$themeModule->themeModuleType."</type>\n";

				// make sure all lines have unix endings
				$themeModuleData = serialize(ArrayUtil::unifyNewlines(unserialize($themeModule->themeModuleData)));

				if (CHARSET != 'UTF-8') {
					$themeModuleData = StringUtil::convertEncoding(CHARSET, 'UTF-8', $themeModuleData);
				}

				$string .= "\t\t\t<data><![CDATA[".StringUtil::escapeCDATA($themeModuleData)."]]></data>\n";
				$string .= "\t\t</module>\n";
			}
			$string .= "\t</modules>\n";
		}

		// read theme module assignments
		if (!empty($themeLayoutIDs)) {
			$sql = "SELECT	*
				FROM	wcf1_theme_module_to_layout
				WHERE	themeLayoutID IN (".$themeLayoutIDs.")";
			$result = WCF::getDB()->sendQuery($sql);
			if (WCF::getDB()->countRows($result)) {
				$string .= "\t<moduleassignments>\n";

				while ($row = WCF::getDB()->fetchArray($result)) {
					// get random theme module id
					if (!isset($randomThemeModuleIDs[$row['themeModuleID']])) continue;
					$randomThemeModuleID = $randomThemeModuleIDs[$row['themeModuleID']];

					// get random theme layout id
					$randomThemeLayoutID = $randomThemeLayoutIDs[$row['themeLayoutID']];

					$string .= "\t\t<moduleassignment>\n";
					$string .= "\t\t\t<moduleid>".$randomThemeModuleID."</moduleid>\n";
					$string .= "\t\t\t<layoutid>".$randomThemeLayoutID."</layoutid>\n";
					$string .= "\t\t\t<position>".$row['themeModulePosition']."</position>\n";
					$string .= "\t\t\t<showorder>".$row['showOrder']."</showorder>\n";
					$string .= "\t\t</moduleassignment>\n";
				}

				$string .= "\t</moduleassignments>\n";
			}
		}

		$string .= "</data>";
		// append data file to theme tar
		$themeTar->addString('data.xml', $string);
		unset($string);

		if ($exportTemplates && $this->templatePackID) {
			require_once(WCF_DIR.'lib/data/template/TemplatePack.class.php');
			$templatePack = new TemplatePack($this->templatePackID);

			// create templates tar
			$templatesTarName = FileUtil::getTemporaryFilename('templates', '.tar');
			$templatesTar = new TarWriter($templatesTarName);
			@chmod($templatesTarName, 0777);

			// append templates to tar
			// get templates
			$sql = "SELECT		template.*, package.package, package.packageDir,
						parent_package.package AS parentPackage, parent_package.packageDir AS parentPackageDir
				FROM		wcf".WCF_N."_template template
				LEFT JOIN	wcf".WCF_N."_package package
				ON		(package.packageID = template.packageID)
				LEFT JOIN	wcf".WCF_N."_package parent_package
				ON		(parent_package.packageID = package.parentPackageID)
				WHERE		template.templatePackID = ".$this->templatePackID;
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$filename = FileUtil::addTrailingSlash(FileUtil::getRealPath(WCF_DIR.$row['packageDir'].'templates/'.$templatePack->templatePackFolderName)).$row['templateName'].'.tpl';
				$templatesTar->add($filename, $row['package'], dirname($filename));
			}

			// append templates tar to theme tar
			$templatesTar->create();
			$themeTar->add($templatesTarName, 'templates.tar', $templatesTarName);
			@unlink($templatesTarName);
		}

		// create file tar
		$fileTarName = FileUtil::getTemporaryFilename('files_', '.tar');
		$fileTar = new TarWriter($fileTarName);
		@chmod($fileTarName, 0777);

		// append files to tar
		$path = WCF_DIR.'theme/'.$this->fileLocation.'/';
		if (file_exists($path) && is_dir($path)) {
			$handle = opendir($path);

			while (($file = readdir($handle)) !== false) {
				if (is_file($path.$file) && self::isValidDataFile($file)) {
					$fileTar->add($path.$file, '', $path);
				}
			}
		}

		// append data tar to theme tar
		$fileTar->create();
		$themeTar->add($fileTarName, 'files.tar', $fileTarName);
		@unlink($fileTarName);

		// output file content
		$themeTar->create();
		readfile($themeTarName);
		@unlink($themeTarName);
	}

	/**
	 * Deletes this theme.
	 */
	public function delete() {
		// get all theme stylesheet ids
		$themeStylesheetIDs = '';
		$sql = "SELECT	themeStylesheetID
			FROM	wcf".WCF_N."_theme_stylesheet
			WHERE	themeID = ".$this->themeID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!empty($themeStylesheetIDs)) $themeStylesheetIDs .= ',';
			$themeStylesheetIDs .= $row['themeStylesheetID'];
		}
		if (!empty($themeStylesheetIDs)) {
			// delete theme modules
			require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetEditor.class.php');
			ThemeStylesheetEditor::deleteAll($themeStylesheetIDs);
		}

		// get all theme module ids
		$themeModuleIDs = '';
		$sql = "SELECT	themeModuleID
			FROM	wcf".WCF_N."_theme_module
			WHERE	themeID = ".$this->themeID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!empty($themeModuleIDs)) $themeModuleIDs .= ',';
			$themeModuleIDs .= $row['themeModuleID'];
		}
		if (!empty($themeModuleIDs)) {
			// delete theme modules
			require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');
			ThemeModuleEditor::deleteAll($themeModuleIDs);
		}

		// get all theme layout ids
		$themeLayoutIDs = '';
		$sql = "SELECT	themeLayoutID
			FROM	wcf".WCF_N."_theme_layout
			WHERE	themeID = ".$this->themeID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!empty($themeLayoutIDs)) $themeLayoutIDs .= ',';
			$themeLayoutIDs .= $row['themeLayoutID'];
		}
		if (!empty($themeLayoutIDs)) {
			// delete theme layouts
			require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
			ThemeLayoutEditor::deleteAll($themeLayoutIDs);
		}

		// delete theme
		$sql = "DELETE FROM	wcf".WCF_N."_theme
			WHERE		themeID = ".$this->themeID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Creates a new theme.
	 *
	 * @param	string		$themeName
	 * @param	integer		$templatePackID
	 * @param	string		$themeDescription
	 * @param	string		$themeVersion
	 * @param	string		$themeDate
	 * @param	string		$fileLocation
	 * @param	string		$copyright
	 * @param	string		$license
	 * @param	string		$authorName
	 * @param	string		$authorURL
	 * @param	integer		$packageID
	 * @return	ThemeEditor
	 */
	public static function create($themeName, $templatePackID = 0, $themeDescription = '', $themeVersion = '', $themeDate = '0000-00-00', $fileLocation = '', $copyright = '', $license = '', $authorName = '', $authorURL = '', $packageID = PACKAGE_ID) {
		$sql = "INSERT INTO	wcf".WCF_N."_theme
					(packageID, themeName, templatePackID, themeDescription, themeVersion, themeDate, fileLocation, copyright, license, authorName, authorURL)
			VALUES		(".$packageID.", '".escapeString($themeName)."', ".$templatePackID.", '".escapeString($themeDescription)."', '".escapeString($themeVersion)."', '".escapeString($themeDate)."', '".escapeString($fileLocation)."', '".escapeString($copyright)."', '".escapeString($license)."', '".escapeString($authorName)."', '".escapeString($authorURL)."')";
		WCF::getDB()->sendQuery($sql);

		$themeID = WCF::getDB()->getInsertID("wcf".WCF_N."_theme", 'themeID');
		return new ThemeEditor($themeID, null, null, false);
	}

	/**
	 * Imports a theme.
	 *
	 * @param	string		$filename
	 * @param	integer		$packageID
	 */
	public static function import($filename, $packageID = PACKAGE_ID) {
		// open file
		require_once(WCF_DIR.'lib/system/io/Tar.class.php');
		$tar = new Tar($filename);

		// get theme data
		$data = self::readThemeData($tar);

		// get file location
		$fileLocation = self::getFilename($data['name']);
		if (empty($fileLocation)) $fileLocation = 'generic'.StringUtil::substring(StringUtil::getRandomID(), 0, 8);
		$originalFileLocation = $fileLocation;

		// create template pack
		$templatePackID = 0;
		if (!empty($data['templates'])) {
			// create template pack
			$originalTemplatePackName = $templatePackName = $data['name'];
			$originalTemplatePackFolderName = $templatePackFolderName = $fileLocation;

			// get unique template pack name
			$i = 1;
			do {
				$sql = "SELECT	COUNT(*) AS count
					FROM	wcf".WCF_N."_template_pack
					WHERE	templatePackName = '".escapeString($templatePackName)."'";
				$row = WCF::getDB()->getFirstRow($sql);
				if (!$row['count']) break;
				$templatePackName = $originalTemplatePackName.' '.$i;
				$i++;
			}
			while (true);

			// get unique folder name
			$i = 1;
			do {
				$sql = "SELECT	COUNT(*) AS count
					FROM	wcf".WCF_N."_template_pack
					WHERE	templatePackFolderName = '".escapeString(FileUtil::addTrailingSlash($templatePackFolderName))."'
						AND parentTemplatePackID = 0";
				$row = WCF::getDB()->getFirstRow($sql);
				if (!$row['count']) break;
				$templatePackFolderName = $originalTemplatePackFolderName.'_'.$i;
				$i++;
			}
			while (true);

			// save template pack
			require_once(WCF_DIR.'lib/data/template/TemplatePackEditor.class.php');
			$templatePackID = TemplatePackEditor::create($templatePackName, FileUtil::addTrailingSlash($templatePackFolderName));
		}

		// files
		if (!empty($data['files'])) {
			// get unique file location name
			$i = 1;
			do {
				$sql = "SELECT	COUNT(*) AS count
					FROM	wcf".WCF_N."_theme
					WHERE	fileLocation = '".escapeString($fileLocation)."'";
				$row = WCF::getDB()->getFirstRow($sql);
				if (!$row['count']) break;
				$fileLocation = $originalFileLocation.'_'.$i;
				$i++;
			}
			while (true);
		}

		// save theme
		$theme = self::create($data['name'], $templatePackID, $data['description'], $data['version'], $data['date'], $fileLocation, $data['copyright'], $data['license'], $data['authorName'], $data['authorURL'], $packageID);

		// import files
		if (!empty($data['files'])) {
			// create file folder if necessary
			if (!file_exists(WCF_DIR.'theme/'.$fileLocation.'/')) {
				@mkdir(WCF_DIR.'theme/'.$fileLocation.'/', 0777);
				@chmod(WCF_DIR.'theme/'.$fileLocation.'/', 0777);
			}

			$i = $tar->getIndexByFilename($data['data']);
			if ($i !== false) {
				// extract data tar
				$destination = FileUtil::getTemporaryFilename('files_');
				$tar->extract($i, $destination);

				// open file tar
				$fileTar = new Tar($destination);
				$contentList = $fileTar->getContentList();
				foreach ($contentList as $key => $val) {
					if ($val['type'] == 'file' && self::isValidDataFile($val['filename'])) {
						$fileTar->extract($key, WCF_DIR.'theme/'.$fileLocation.'/'.basename($val['filename']));
						@chmod(WCF_DIR.'theme/'.$fileLocation.'/'.basename($val['filename']), 0666);
					}
				}

				// delete tmp file
				$fileTar->close();
				@unlink($destination);
			}
		}

		// import templates
		if (!empty($data['templates'])) {
			$i = $tar->getIndexByFilename($data['templates']);
			if ($i !== false) {
				// extract templates tar
				$destination = FileUtil::getTemporaryFilename('templates_');
				$tar->extract($i, $destination);

				// open templates tar and group templates by package
				$templatesTar = new Tar($destination);
				$contentList = $templatesTar->getContentList();
				$packageToTemplates = array();
				foreach ($contentList as $key => $val) {
					if ($val['type'] == 'file') {
						$folders = explode('/', $val['filename']);
						$packageName = array_shift($folders);
						if (!isset($packageToTemplates[$packageName])) {
							$packageToTemplates[$packageName] = array();
						}
						$packageToTemplates[$packageName][] = array('index' => $val['index'], 'filename' => implode('/', $folders));
					}
				}

				// copy templates
				foreach ($packageToTemplates as $package => $templates) {
					// try to find package
					$sql = "SELECT	*
						FROM	wcf".WCF_N."_package
						WHERE	package = '".escapeString($package)."'";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {
						// get icon path
						$templatesDir = FileUtil::addTrailingSlash(FileUtil::getRealPath(WCF_DIR.$row['packageDir']).'templates/'.$templatePackFolderName);

						// create template path
						if (!file_exists($templatesDir)) {
							@mkdir($templatesDir, 0777);
							@chmod($templatesDir, 0777);
						}

						// copy templates
						foreach ($templates as $template) {
							$templatesTar->extract($template['index'], $templatesDir.$template['filename']);

							$sql = "INSERT INTO	wcf".WCF_N."_template
										(packageID, templateName, templatePackID)
								VALUES		(".$row['packageID'].", '".escapeString(str_replace('.tpl', '', $template['filename']))."', ".$templatePackID.")";
							WCF::getDB()->sendQuery($sql);
						}
					}
				}

				// delete tmp file
				$templatesTar->close();
				@unlink($destination);
			}
		}

		// import stylesheets
		require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetEditor.class.php');
		$themeStylesheetIDs = array();
		foreach ($data['stylesheets'] as $id => $themeStylesheetData) {
			// insert new stylesheet
			$themeStylesheet = ThemeStylesheetEditor::create($theme->themeID, $themeStylesheetData['title'], $themeStylesheetData['lessCode'], $packageID);

			$themeStylesheetIDs[$id] = $themeStylesheet->themeStylesheetID;
		}

		// import layouts
		require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
		$themeLayoutIDs = array();
		foreach ($data['layouts'] as $id => $themeLayoutData) {
			// get theme stylesheet ids
			$saveThemeStylesheetIDs = array();
			foreach ($themeLayoutData['themeStylesheetIDs'] as $themeStylesheetID) {
				if (isset($themeStylesheetIDs[$themeStylesheetID])) {
					$saveThemeStylesheetIDs[] = $themeStylesheetIDs[$themeStylesheetID];
				}
			}

			// insert new layout
			$themeLayout = ThemeLayoutEditor::create($theme->themeID, $themeLayoutData['title'], $saveThemeStylesheetIDs, $packageID);

			if ($themeLayoutData['isDefault']) {
				$themeLayout->setAsDefault($packageID);
			}

			$themeLayoutIDs[$id] = $themeLayout->themeLayoutID;
		}

		// import modules
		require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');
		$themeModuleIDs = array();
		foreach ($data['modules'] as $id => $themeModuleData) {
			// check availability of theme module type
			try {
				ThemeModule::getThemeModuleTypeObject($themeModuleData['themeModuleType']);
			}
			catch (SystemException $e) {
				continue;
			}

			// insert new module
			$themeModule = ThemeModuleEditor::create($theme->themeID, $themeModuleData['title'], $themeModuleData['cssID'], $themeModuleData['cssClasses'], $themeModuleData['themeModuleType'], $themeModuleData['themeModuleData'], $packageID);

			$themeModuleIDs[$id] = $themeModule->themeModuleID;
		}

		// import theme module assignments
		if (count($themeLayoutIDs) && count($themeModuleIDs)) {
			foreach ($data['moduleAssignments'] as $assignmentData) {
				// get module id
				if (isset($themeModuleIDs[$assignmentData['themeModuleID']])) {
					$themeModuleID = $themeModuleIDs[$assignmentData['themeModuleID']];
				}
				else {
					continue;
				}

				// get layout id
				if (isset($themeLayoutIDs[$assignmentData['themeLayoutID']])) {
					$themeLayoutID = $themeLayoutIDs[$assignmentData['themeLayoutID']];
				}
				else {
					continue;
				}

				// insert theme module assignment
				$sql = "INSERT INTO	wcf".WCF_N."_theme_module_to_layout
							(themeModuleID, themeLayoutID, themeModulePosition, showOrder)
					VALUES		(".$themeModuleID.", ".$themeLayoutID.", '".escapeString($assignmentData['themeModulePosition'])."', ".$assignmentData['showOrder'].")";
				WCF::getDB()->sendQuery($sql);
			}
		}

		$tar->close();

		return $theme;
	}

	/**
	 * Reads the data of a theme exchange format file.
	 *
	 * @param	Tar		$tar
	 * @return	array
	 */
	public static function readThemeData(Tar $tar) {
		// search theme.xml
		$i = $tar->getIndexByFilename(self::INFO_FILE);
		if ($i === false) {
			throw new SystemException("unable to find required file '".self::INFO_FILE."' in theme archive", 100001);
		}

		// open theme.xml
		$themeXML = new XML();
		$themeXML->loadString($tar->extractToString($i));
		$xmlContent = $themeXML->getElementTree('theme');
		$data = array(
			'name' => '', 'description' => '', 'version' => '', 'date' => '0000-00-00', 'copyright' => '',
			'license' => '', 'authorName' => '', 'authorURL' => '', 'templates' => '', 'files' => '',
			'stylesheets' => array(), 'layouts' => array(), 'modules' => array(), 'moduleAssignments' => array()

		);

		foreach ($xmlContent['children'] as $child) {
			switch ($child['name']) {
				case 'general':
					foreach ($child['children'] as $general) {
						switch ($general['name']) {
							case 'themename':
								$data['name'] = $general['cdata'];
								break;
							case 'description':
							case 'version':
							case 'date':
							case 'copyright':
							case 'license':
								$data[$general['name']] = $general['cdata'];
								break;
						}
					}
					break;

				case 'author':
					foreach ($child['children'] as $author) {
						switch ($author['name']) {
							case 'authorname':
								$data['authorName'] = $author['cdata'];
								break;
							case 'authorurl':
								$data['authorURL'] = $author['cdata'];
								break;
						}
					}
					break;

				case 'install':
					foreach ($child['children'] as $files) {
						switch ($files['name']) {
							case 'stylesheets':
							case 'templates':
							case 'files':
							case 'data':
								$data[$files['name']] = $files['cdata'];
								break;
						}
					}
					break;
			}
		}

		if (empty($data['name'])) {
			throw new SystemException("required tag 'themename' is missing in '".self::INFO_FILE."'", 100002);
		}
		if (empty($data['data'])) {
			throw new SystemException("required tag 'data' is missing in '".self::INFO_FILE."'", 100002);
		}

		// search data.xml
		$i = $tar->getIndexByFilename($data['data']);
		if ($i === false) {
			throw new SystemException("unable to find required file '".$data['data']."' in theme archive", 100001);
		}

		// open data.xml
		if ($i !== false) {
			$data = array_merge($data, self::readContentData($tar->extractToString($i)));
		}

		// convert encoding
		if (CHARSET != 'UTF-8') {
			foreach ($data as $key => $value) {
				if (!in_array($key, array('templates', 'files', 'stylesheets', 'layouts', 'modules', 'moduleAssignments'))) {
					$data[$key] = StringUtil::convertEncoding('UTF-8', CHARSET, $value);
				}
			}
		}

		return $data;
	}

	/**
	 * Reads the data (stylesheets, layouts, modules, module assignments) of a data.xml file.
	 *
	 * @param	string		$string
	 * @return	array		data
	 */
	public static function readContentData($string) {
		// open data.xml
		$dataXML = new XML();
		$dataXML->loadString($string);
		$dataXMLContent = $dataXML->getElementTree('data');

		// get data
		$data = array('stylesheets' => array(), 'layouts' => array(), 'modules' => array(), 'moduleAssignments' => array());
		foreach ($dataXMLContent['children'] as $block) {
			if (count($block['children'])) {
				// stylesheets
				if ($block['name'] == 'stylesheets') {
					foreach ($block['children'] as $stylesheet) {
						// check required id
						if (!isset($stylesheet['attrs']['id'])) {
							throw new SystemException("Required 'id' attribute for stylesheet is missing");
						}

						$stylesheetID = $stylesheet['attrs']['id'];
						$data['stylesheets'][$stylesheetID] = array();

						foreach ($stylesheet['children'] as $stylesheetData) {
							switch ($stylesheetData['name']) {
								case 'title':
									$title = $stylesheetData['cdata'];
									if (CHARSET != 'UTF-8') {
										$title = StringUtil::convertEncoding('UTF-8', CHARSET, $title);
									}

									$data['stylesheets'][$stylesheetID]['title'] = $title;
									break;
								case 'lesscode':
									$lessCode = $stylesheetData['cdata'];
									if (CHARSET != 'UTF-8') {
										$lessCode = StringUtil::convertEncoding('UTF-8', CHARSET, $lessCode);
									}

									$data['stylesheets'][$stylesheetID]['lessCode'] = $lessCode;
									break;
							}
						}

						if (!isset($data['stylesheets'][$stylesheetID]['title'])) {
							throw new SystemException("Required title for stylesheet with the id '".$stylesheetID."' is missing");
						}

						if (!isset($data['stylesheets'][$stylesheetID]['lessCode'])) {
							throw new SystemException("Required less code for stylesheet with the id '".$stylesheetID."' is missing");
						}
					}
				}
				// layouts
				else if ($block['name'] == 'layouts') {
					foreach ($block['children'] as $layout) {
						// check required id
						if (!isset($layout['attrs']['id'])) {
							throw new SystemException("Required 'id' attribute for layout is missing");
						}

						$layoutID = $layout['attrs']['id'];
						$data['layouts'][$layoutID] = array('isDefault' => 0, 'themeStylesheetIDs' => array());

						foreach ($layout['children'] as $layoutData) {
							switch ($layoutData['name']) {
								case 'title':
									$title = $layoutData['cdata'];
									if (CHARSET != 'UTF-8') {
										$title = StringUtil::convertEncoding('UTF-8', CHARSET, $title);
									}

									$data['layouts'][$layoutID]['title'] = $title;
									break;
								case 'default':
									if ($layoutData['cdata'] == 1) {
										$data['layouts'][$layoutID]['isDefault'] = 1;
									}
									break;
								case 'stylesheets':
									foreach ($layoutData['children'] as $stylesheetData) {
										$data['layouts'][$layoutID]['themeStylesheetIDs'][] = $stylesheetData['cdata'];
									}
									break;
							}
						}

						if (!isset($data['layouts'][$layoutID]['title'])) {
							throw new SystemException("Required title for layout with the id '".$layoutID."' is missing");
						}
					}
				}
				// modules
				else if ($block['name'] == 'modules') {
					foreach ($block['children'] as $module) {
						// check required fields
						if (!isset($module['attrs']['id'])) {
							throw new SystemException("Required 'id' attribute for module is missing");
						}

						$moduleID = $module['attrs']['id'];
						$data['modules'][$moduleID] = array(
							'cssID' => '',
							'cssClasses' => '',
							'themeModuleData' => array()
						);

						foreach ($module['children'] as $moduleData) {
							switch ($moduleData['name']) {
								case 'title':
									$title = $moduleData['cdata'];
									if (CHARSET != 'UTF-8') {
										$title = StringUtil::convertEncoding('UTF-8', CHARSET, $title);
									}

									$data['modules'][$moduleID]['title'] = $title;
									break;
								case 'cssid':
									$cssID = $moduleData['cdata'];
									if (CHARSET != 'UTF-8') {
										$cssID = StringUtil::convertEncoding('UTF-8', CHARSET, $cssID);
									}

									$data['modules'][$moduleID]['cssID'] = $cssID;
									break;
								case 'cssclasses':
									$cssClasses = $moduleData['cdata'];
									if (CHARSET != 'UTF-8') {
										$cssClasses = StringUtil::convertEncoding('UTF-8', CHARSET, $cssClasses);
									}

									$data['modules'][$moduleID]['cssClasses'] = $cssClasses;
									break;
								case 'type':
									$themeModuleType = $moduleData['cdata'];
									if (CHARSET != 'UTF-8') {
										$themeModuleType = StringUtil::convertEncoding('UTF-8', CHARSET, $themeModuleType);
									}

									$data['modules'][$moduleID]['themeModuleType'] = $themeModuleType;
									break;
								case 'data':
									$themeModuleData = $moduleData['cdata'];
									if (CHARSET != 'UTF-8') {
										$themeModuleData = StringUtil::convertEncoding('UTF-8', CHARSET, $themeModuleData);
									}

									if (($themeModuleData = @unserialize($themeModuleData)) === false) {
										throw new SystemException("Data for module with the id '".$moduleID."' is invalid");
									}

									$data['modules'][$moduleID]['themeModuleData'] = $themeModuleData;
									break;
							}
						}

						if (!isset($data['modules'][$moduleID]['title'])) {
							throw new SystemException("Required title for module with the id '".$moduleID."' is missing");
						}
						if (!isset($data['modules'][$moduleID]['themeModuleType'])) {
							throw new SystemException("Required type for module with the id '".$moduleID."' is missing");
						}
					}
				}
				// module assignments
				else if ($block['name'] == 'moduleAssignments') {
					foreach ($block['children'] as $moduleAssignment) {
						$moduleAssignmentID = StringUtil::getRandomID();
						$data['moduleAssignments'][$moduleAssignmentID] = array(
							'themeModulePosition' => 'main',
							'showOrder' => 0
						);

						foreach ($moduleAssignment['children'] as $moduleAssignmentData) {
							switch ($moduleAssignmentData['name']) {
								case 'moduleid':
									$data['moduleAssignments'][$moduleAssignmentID]['themeModuleID'] = $moduleAssignmentData['cdata'];
									break;
								case 'layoutid':
									$data['moduleAssignments'][$moduleAssignmentID]['themeLayoutID'] = $moduleAssignmentData['cdata'];
									break;
								case 'position':
									if (!in_array($moduleAssignmentData['cdata'], array('header', 'left', 'main', 'right', 'footer'))) {
										throw new SystemException("Position for module assignment is invalid");
									}

									$data['moduleAssignments'][$moduleAssignmentID]['themeModulePosition'] = $moduleAssignmentData['cdata'];
									break;
								case 'showOrder':
									$data['moduleAssignments'][$moduleAssignmentID]['showOrder'] = intval($moduleAssignmentData['cdata']);
									break;
							}
						}

						if (!isset($data['moduleAssignments'][$moduleAssignmentID]['themeModuleID'])) {
							throw new SystemException("Required module id for module assignment is missing");
						}
						if (!isset($data['moduleAssignments'][$moduleAssignmentID]['themeLayoutID'])) {
							throw new SystemException("Required layout id for module assignment is missing");
						}
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Returns the data of a theme exchange format file.
	 *
	 * @param	string		$filename
	 * @return	array
	 */
	public static function getThemeData($filename) {
		// open file
		require_once(WCF_DIR.'lib/system/io/Tar.class.php');
		$tar = new Tar($filename);

		// get theme data
		$data = self::readThemeData($tar);
		$tar->close();

		return $data;
	}

	/**
	 * Returns true, if the given filename is a valid data file.
	 *
	 * @param	string		$filename
	 * @return	boolean
	 */
	public static function isValidDataFile($filename) {
		$illegalFileExtensions = array('php', 'php3', 'php4', 'php5', 'phtml');

		// get file extension
		$fileExtension = '';
		if (!empty($filename) && StringUtil::indexOf($filename, '.') !== false) {
			$fileExtension = StringUtil::toLowerCase(StringUtil::substring($filename, StringUtil::lastIndexOf($filename, '.') + 1));
		}

		// check file extension
		if (in_array($fileExtension, $illegalFileExtensions)) {
			return false;
		}
		return true;
	}

	/**
	 * Returns the filename of the given string.
	 *
	 * @return	string
	 */
	public static function getFilename($string) {
		return str_replace(' ', '-', preg_replace('/[^a-z0-9 _-]/', '', StringUtil::toLowerCase($string)));
	}
}
?>