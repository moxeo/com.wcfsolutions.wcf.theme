<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');

/**
 * Provides functions to manage theme modules.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.module
 * @category	Community Framework
 */
class ThemeModuleEditor extends ThemeModule {
	/**
	 * Creates a new ThemeModuleEditor object.
	 * 
	 * @param	integer		$themeModuleID
	 * @param 	array<mixed>	$row
	 * @param	ThemeModule	$cacheObject
	 * @param	boolean		$useCache
	 */
	public function __construct($themeModuleID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($themeModuleID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_theme_module
				WHERE	themeModuleID = ".$themeModuleID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}
	
	/**
	 * Updates this theme module.
	 * 
	 * @param	string		$title
	 * @param	string		$cssID
	 * @param	string		$cssClasses
	 * @param	string		$themeModuleType
	 * @param	array		$themeModuleData
	 */
	public function update($title, $cssID, $cssClasses, $themeModuleType, $themeModuleData = array()) {
		$sql = "UPDATE	wcf".WCF_N."_theme_module
			SET	title = '".escapeString($title)."',
				cssID = '".escapeString($cssID)."',
				cssClasses = '".escapeString($cssClasses)."',
				themeModuleType = '".escapeString($themeModuleType)."',
				themeModuleData = '".escapeString(serialize($themeModuleData))."'
			WHERE	themeModuleID = ".$this->themeModuleID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this theme module.
	 */
	public function delete() {
		self::deleteAll($this->themeModuleID);
	}
	
	/**
	 * Creates a new theme module.
	 * 
	 * @param	string			$title
	 * @param	string			$cssID
 	 * @param	string			$cssClasses
	 * @param	string			$themeModuleType
	 * @param	array			$themeModuleData
	 * @param	integer			$packageID
	 * @return	ThemeModuleEditor
	 */
	public static function create($themeID, $title, $cssID, $cssClasses, $themeModuleType, $themeModuleData = array(), $packageID = PACKAGE_ID) {
		$sql = "INSERT INTO	wcf".WCF_N."_theme_module
					(packageID, themeID, title, cssID, cssClasses, themeModuleType, themeModuleData)
			VALUES		(".$packageID.", ".$themeID.", '".escapeString($title)."', '".escapeString($cssID)."', '".escapeString($cssClasses)."', '".escapeString($themeModuleType)."', '".escapeString(serialize($themeModuleData))."')";
		WCF::getDB()->sendQuery($sql);
		
		$themeModuleID = WCF::getDB()->getInsertID("wcf".WCF_N."_theme_module", 'themeModuleID');
		return new ThemeModuleEditor($themeModuleID, null, null, false);
	}
	
	/**
	 * Deletes all theme modules with the given theme module ids.
	 * 
	 * @param	string		$themeModuleIDs
	 */
	public static function deleteAll($themeModuleIDs) {
		if (empty($themeModuleIDs)) return;
		
		// delete theme module assignments
		$sql = "DELETE FROM	wcf".WCF_N."_theme_module_to_layout
			WHERE		themeModuleID IN (".$themeModuleIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		// delete theme module
		$sql = "DELETE FROM	wcf".WCF_N."_theme_module
			WHERE		themeModuleID IN (".$themeModuleIDs.")";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Clears the theme module cache.
	 */
	public static function clearCache() {
		WCF::getCache()->clear(WCF_DIR.'cache', 'cache.themeModule-*.php');
	}
}
?>