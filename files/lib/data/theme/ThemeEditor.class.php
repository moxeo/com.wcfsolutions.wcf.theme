<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');

/**
 * Provides functions to manage themes.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
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
	 * @param	string		$dataLocation
	 * @param	string		$copyright
	 * @param	string		$license
	 * @param	string		$authorName
	 * @param	string		$authorURL
	 */
	public function update($themeName, $templatePackID = 0, $themeDescription = '', $themeVersion = '', $themeDate = '0000-00-00', $dataLocation = '', $copyright = '', $license = '', $authorName = '', $authorURL = '') {
		$sql = "UPDATE	wcf".WCF_N."_theme
			SET	themeName = '".escapeString($themeName)."',
				templatePackID = ".$templatePackID.",
				themeDescription = '".escapeString($themeDescription)."',
				themeVersion = '".escapeString($themeVersion)."',
				themeDate = '".escapeString($themeDate)."',
				dataLocation = '".escapeString($dataLocation)."',
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
		$string .= "\t<files>\n";
		if ($exportTemplates && $this->templatePackID) $string .= "\t\t<templates>templates.tar</templates>\n"; // templates
		$string .= "\t\t<data>data.tar</data>\n"; // data
		$string .= "\t</files>\n";

		$string .= "</theme>";
		// append theme info file to theme tar
		$themeTar->addString(self::INFO_FILE, $string);
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

		// create data tar
		$dataTarName = FileUtil::getTemporaryFilename('data_', '.tar');
		$dataTar = new TarWriter($dataTarName);
		@chmod($dataTarName, 0777);

		// append files to tar
		$path = WCF_DIR.'theme/'.$this->dataLocation.'/';
		if (file_exists($path) && is_dir($path)) {
			$handle = opendir($path);

			while (($file = readdir($handle)) !== false) {
				if (is_file($path.$file) && self::isValidDataFile($file)) {
					$dataTar->add($path.$file, '', $path);
				}
			}
		}

		// append data tar to theme tar
		$dataTar->create();
		$themeTar->add($dataTarName, 'data.tar', $dataTarName);
		@unlink($dataTarName);

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
	 * @param	string		$dataLocation
	 * @param	string		$copyright
	 * @param	string		$license
	 * @param	string		$authorName
	 * @param	string		$authorURL
	 * @param	integer		$packageID
	 * @return	ThemeEditor
	 */
	public static function create($themeName, $templatePackID = 0, $themeDescription = '', $themeVersion = '', $themeDate = '0000-00-00', $dataLocation = '', $copyright = '', $license = '', $authorName = '', $authorURL = '', $packageID = PACKAGE_ID) {
		$sql = "INSERT INTO	wcf".WCF_N."_theme
					(packageID, themeName, templatePackID, themeDescription, themeVersion, themeDate, dataLocation, copyright, license, authorName, authorURL)
			VALUES		(".$packageID.", '".escapeString($themeName)."', ".$templatePackID.", '".escapeString($themeDescription)."', '".escapeString($themeVersion)."', '".escapeString($themeDate)."', '".escapeString($dataLocation)."', '".escapeString($copyright)."', '".escapeString($license)."', '".escapeString($authorName)."', '".escapeString($authorURL)."')";
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

		// get data location
		$dataLocation = self::getFilename($data['name']);
		if (empty($dataLocation)) $dataLocation = 'generic'.StringUtil::substring(StringUtil::getRandomID(), 0, 8);
		$originalDataLocation = $dataLocation;

		// create template pack
		$templatePackID = 0;
		if (!empty($data['templates'])) {
			// create template pack
			$originalTemplatePackName = $templatePackName = $data['name'];
			$originalTemplatePackFolderName = $templatePackFolderName = $dataLocation;

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

		// data
		if (!empty($data['data'])) {
			// get unique data location name
			$i = 1;
			do {
				$sql = "SELECT	COUNT(*) AS count
					FROM	wcf".WCF_N."_theme
					WHERE	dataLocation = '".escapeString($dataLocation)."'";
				$row = WCF::getDB()->getFirstRow($sql);
				if (!$row['count']) break;
				$dataLocation = $originalDataLocation.'_'.$i;
				$i++;
			}
			while (true);
		}

		// save theme
		$theme = self::create($data['name'], $templatePackID, $data['description'], $data['version'], $data['date'], $dataLocation, $data['copyright'], $data['license'], $data['authorName'], $data['authorURL'], $packageID);

		// import data
		if (!empty($data['data'])) {
			// get unique data location name
			$i = 1;
			do {
				$sql = "SELECT	COUNT(*) AS count
					FROM	wcf".WCF_N."_theme
					WHERE	dataLocation = '".escapeString(FileUtil::addTrailingSlash($dataLocation))."'";
				$row = WCF::getDB()->getFirstRow($sql);
				if (!$row['count']) break;
				$dataLocation = $originalDataLocation.'_'.$i;
				$i++;
			}
			while (true);

			// create data folder if necessary
			if (!file_exists(WCF_DIR.'theme/'.$dataLocation.'/')) {
				@mkdir(WCF_DIR.'theme/'.$dataLocation.'/', 0777);
				@chmod(WCF_DIR.'theme/'.$dataLocation.'/', 0777);
			}

			$i = $tar->getIndexByFilename($data['data']);
			if ($i !== false) {
				// extract data tar
				$destination = FileUtil::getTemporaryFilename('data_');
				$tar->extract($i, $destination);

				// open data tar
				$dataTar = new Tar($destination);
				$contentList = $dataTar->getContentList();
				foreach ($contentList as $key => $val) {
					if ($val['type'] == 'file' && self::isValidDataFile($val['filename'])) {
						$dataTar->extract($key, WCF_DIR.'theme/'.$dataLocation.'/'.basename($val['filename']));
						@chmod(WCF_DIR.'theme/'.$dataLocation.'/'.basename($val['filename']), 0666);
					}
				}

				// delete tmp file
				$dataTar->close();
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
			'license' => '', 'authorName' => '', 'authorURL' => '', 'templates' => '', 'data' => ''

		);

		foreach ($xmlContent['children'] as $child) {
			switch ($child['name']) {
				case 'general':
					foreach ($child['children'] as $general) {
						switch ($general['name']) {
							case 'themename':
								$data['name'] = StringUtil::convertEncoding('UTF-8', CHARSET, $general['cdata']);
								break;
							case 'description':
							case 'version':
							case 'date':
							case 'copyright':
							case 'license':
								$data[$general['name']] = StringUtil::convertEncoding('UTF-8', CHARSET, $general['cdata']);
								break;
						}
					}
					break;

				case 'author':
					foreach ($child['children'] as $author) {
						switch ($author['name']) {
							case 'authorname':
								$data['authorName'] = StringUtil::convertEncoding('UTF-8', CHARSET, $author['cdata']);
								break;
							case 'authorurl':
								$data['authorURL'] = StringUtil::convertEncoding('UTF-8', CHARSET, $author['cdata']);
								break;
						}
					}
					break;

				case 'files':
					foreach ($child['children'] as $files) {
						switch ($files['name']) {
							case 'templates':
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