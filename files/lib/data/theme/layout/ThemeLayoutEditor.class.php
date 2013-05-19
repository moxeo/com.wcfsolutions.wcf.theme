<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayout.class.php');

/**
 * Provides functions to manage theme layouts.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.layout
 * @category	Community Framework
 */
class ThemeLayoutEditor extends ThemeLayout {
	/**
	 * Creates a new ThemeLayoutEditor object.
	 *
	 * @param	integer		$themeLayoutID
	 * @param 	array<mixed>	$row
	 * @param	ThemeLayout	$cacheObject
	 * @param	boolean		$useCache
	 */
	public function __construct($themeLayoutID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($themeLayoutID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_theme_layout
				WHERE	themeLayoutID = ".$themeLayoutID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}

	/**
	 * Creates a new theme layout.
	 *
	 * @param	integer			$themeID
	 * @param	string			$title
	 * @param	array			$themeStylesheetIDs
	 * @param	integer			$packageID
	 * @return	ThemeLayoutEditor
	 */
	public static function create($themeID, $title, $themeStylesheetIDs, $packageID = PACKAGE_ID) {
		// create theme layout
		$sql = "INSERT INTO	wcf".WCF_N."_theme_layout
					(packageID, themeID, title)
			VALUES		(".$packageID.", ".$themeID.", '".escapeString($title)."')";
		WCF::getDB()->sendQuery($sql);

		// get new theme layout
		$themeLayoutID = WCF::getDB()->getInsertID("wcf".WCF_N."_theme_layout", 'themeLayoutID');
		$themeLayout = new ThemeLayoutEditor($themeLayoutID, null, null, false);

		// assign stylesheets
		if (count($themeStylesheetIDs)) {
			$themeLayout->assignThemeStylesheets($themeStylesheetIDs);
		}

		// compile stylesheet
		$themeLayout->compileStylesheet();

		// return theme layout
		return $themeLayout;
	}

	/**
	 * Assigns stylesheets to this theme layout.
	 *
	 * @param	array		$themeStylesheetIDs
	 */
	public function assignThemeStylesheets($themeStylesheetIDs) {
		$themeStylesheetIDs = array_unique($themeStylesheetIDs);

		$inserts = '';
		foreach ($themeStylesheetIDs as $themeStylesheetID) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= '('.$themeStylesheetID.', '.$this->themeLayoutID.')';
		}

		// insert assignments
		$sql = "INSERT  INTO 	wcf".WCF_N."_theme_stylesheet_to_layout
					(themeStylesheetID, themeLayoutID)
			VALUES		".$inserts;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Removes assigned theme stylesheets.
	 */
	public function removeAssignedThemeStylesheets() {
		$sql = "DELETE FROM 	wcf".WCF_N."_theme_stylesheet_to_layout
			WHERE		themeLayoutID = ".$this->themeLayoutID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Returns the list of assigned theme stylesheets.
	 *
	 * @return	array
	 */
	public function getAssignedThemeStylesheets() {
		$themeStylesheetIDs = array();
		$sql = "SELECT	themeStylesheetID
			FROM	wcf".WCF_N."_theme_stylesheet_to_layout
			WHERE	themeLayoutID = ".$this->themeLayoutID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeStylesheetIDs[] = $row['themeStylesheetID'];
		}
		return $themeStylesheetIDs;
	}

	/**
	 * Sets this theme layout as default theme layout for the package with the given package id.
	 *
	 * @param	integer			$packageID
	 */
	public function setAsDefault($packageID = PACKAGE_ID) {
		// remove old default
		$sql = "UPDATE	wcf".WCF_N."_theme_layout
			SET	isDefault = 0
			WHERE	isDefault = 1
				AND packageID = ".$packageID;
		WCF::getDB()->sendQuery($sql);

		// set new default
		$sql = "UPDATE	wcf".WCF_N."_theme_layout
			SET	isDefault = 1
			WHERE	themeLayoutID = ".$this->themeLayoutID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Updates this theme layout.
	 *
	 * @param	string		$title
	 * @param	array		$themeStylesheetIDs
	 */
	public function update($title, $themeStylesheetIDs) {
		// update theme layout
		$sql = "UPDATE	wcf".WCF_N."_theme_layout
			SET	title = '".escapeString($title)."'
			WHERE	themeLayoutID = ".$this->themeLayoutID;
		WCF::getDB()->sendQuery($sql);

		$this->data['title'] = $title;

		// assign stylesheets
		$this->removeAssignedThemeStylesheets();

		if (count($themeStylesheetIDs)) {
			$this->assignThemeStylesheets($themeStylesheetIDs);
		}

		// compile stylesheet
		$this->compileStylesheet();
	}

	/**
	 * Compiles the stylesheet of this theme layout.
	 */
	public function compileStylesheet() {
		// import compiler
		require_once(WCF_DIR.'lib/system/theme/3rdParty/lessc.inc.php');
		$compiler = new lessc();
		$compiler->setFormatter("compressed");
		$compiler->setImportDir(array(WCF_DIR));

		// get theme
		$theme = Theme::getTheme($this->themeID);

		// get theme stylesheet ids
		$themeStylesheetIDs = $this->getAssignedThemeStylesheets();

		// read theme stylesheets
		require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetList.class.php');
		$themeStylesheetList = new ThemeStylesheetList();
		$themeStylesheetList->sqlLimit = 0;
		$themeStylesheetList->sqlConditions = 'theme_stylesheet.themeStylesheetID IN ('.implode(',', $themeStylesheetIDs).')';
		$themeStylesheetList->readObjects();

		// get less code
		$less = '@import "theme/reset.less";';
		foreach ($themeStylesheetList->getObjects() as $themeStylesheet) {
			$less .= $themeStylesheet->lessCode;
		}

		// compile code
		$css = "/* stylesheet for the layout '".$this->title."' of the theme '".$theme->themeName."', generated on ".gmdate('r')." -- DO NOT EDIT */\n\n";
		try {
			$css .= $compiler->compile($less);
		}
		catch (Exception $e) {
			throw new SystemException("Could not compile LESS code: ".$e->getMessage());
		}

		// write stylesheet
		file_put_contents(WCF_DIR.'theme/theme'.$theme->themeID.'-'.$this->themeLayoutID.'.css', $css);
	}

	/**
	 * Deletes this theme layout.
	 */
	public function delete() {
		self::deleteAll($this->themeLayoutID);
	}

	/**
	 * Adds the theme module with the given theme module id to this layout.
	 *
	 * @param	integer		$themeModuleID
	 * @param	string		$themeModulePosition
	 */
	public function addThemeModule($themeModuleID, $themeModulePosition) {
		// get next number in row
		$sql = "SELECT	MAX(showOrder) AS showOrder
			FROM	wcf".WCF_N."_theme_module_to_layout
			WHERE	themeLayoutID = ".$this->themeLayoutID."
				AND themeModulePosition = '".escapeString($themeModulePosition)."'";
		$row = WCF::getDB()->getFirstRow($sql);
		if (!empty($row)) $showOrder = intval($row['showOrder']) + 1;
		else $showOrder = 1;

		// add module
		$sql = "REPLACE INTO	wcf".WCF_N."_theme_module_to_layout
					(themeModuleID, themeLayoutID, themeModulePosition, showOrder)
			VALUES		(".$themeModuleID.", ".$this->themeLayoutID.", '".escapeString($themeModulePosition)."', ".$showOrder.")";
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Updates the position of a theme module directly.
	 *
	 * @param	string		$themeModulePosition
	 * @param	integer		$themeModuleID
	 * @param	integer		$oldShowOrder
	 * @param	integer		$showOrder
	 */
	public function updateThemeModuleShowOrder($themeModulePosition, $themeModuleID, $oldShowOrder, $showOrder) {
		$sql = "UPDATE	wcf".WCF_N."_theme_module_to_layout
			SET	showOrder = ".$showOrder."
			WHERE 	themeLayoutID = ".$this->themeLayoutID."
				AND themeModulePosition = '".escapeString($themeModulePosition)."'
				AND themeModuleID = ".$themeModuleID."
				AND showOrder = ".$oldShowOrder;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Removes the theme module with the given theme module id from this layout.
	 *
	 * @param	integer		$themeModuleID
	 * @param	string		$themeModulePosition
	 * @param	integer		$showOrder
	 */
	public function removeThemeModule($themeModuleID, $themeModulePosition, $showOrder) {
		$sql = "DELETE FROM	wcf".WCF_N."_theme_module_to_layout
			WHERE		themeLayoutID = ".$this->themeLayoutID."
					AND themeModulePosition = '".escapeString($themeModulePosition)."'
					AND themeModuleID = ".$themeModuleID."
					AND showOrder = ".$showOrder;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes all theme layouts with the given theme layout ids.
	 *
	 * @param	string		$themeLayoutIDs
	 */
	public static function deleteAll($themeLayoutIDs) {
		if (empty($themeLayoutIDs)) return;

		// delete theme stylesheet assignments
		$sql = "DELETE FROM	wcf".WCF_N."_theme_stylesheet_to_layout
			WHERE		themeLayoutID IN (".$themeLayoutIDs.")";
		WCF::getDB()->sendQuery($sql);

		// delete theme module assignments
		$sql = "DELETE FROM	wcf".WCF_N."_theme_module_to_layout
			WHERE		themeLayoutID IN (".$themeLayoutIDs.")";
		WCF::getDB()->sendQuery($sql);

		// delete theme layout
		$sql = "DELETE FROM	wcf".WCF_N."_theme_layout
			WHERE		themeLayoutID IN (".$themeLayoutIDs.")";
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Clears the theme layout cache.
	 */
	public static function clearCache() {
		WCF::getCache()->clear(WCF_DIR.'cache', 'cache.themeLayout-*.php');
	}
}
?>