<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheet.class.php');

/**
 * Provides functions to manage theme stylesheets.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.stylesheet
 * @subpackage	data.theme.stylesheet
 * @category	Community Framework
 */
class ThemeStylesheetEditor extends ThemeStylesheet {
	/**
	 * Creates a new theme stylesheet.
	 *
	 * @param	integer			$themeID
	 * @param	string			$title
	 * @param	string			$lessCode
	 * @param	integer			$packageID
	 * @return	ThemeStylesheetEditor
	 */
	public static function create($themeID, $title, $lessCode, $packageID = PACKAGE_ID) {
		// create theme stylesheet
		$sql = "INSERT INTO	wcf".WCF_N."_theme_stylesheet
					(packageID, themeID, title, lessCode)
			VALUES		(".$packageID.", ".$themeID.", '".escapeString($title)."', '".escapeString($lessCode)."')";
		WCF::getDB()->sendQuery($sql);

		// return new theme stylesheet
		$themeStylesheetID = WCF::getDB()->getInsertID("wcf".WCF_N."_theme_stylesheet", 'themeStylesheetID');
		return new ThemeStylesheetEditor($themeStylesheetID, null, null, false);
	}

	/**
	 * Updates this theme stylesheet.
	 *
	 * @param	string		$title
	 * @param	string		$lessCode
	 */
	public function update($title, $lessCode) {
		$sql = "UPDATE	wcf".WCF_N."_theme_stylesheet
			SET	title = '".escapeString($title)."',
				lessCode = '".escapeString($lessCode)."'
			WHERE	themeStylesheetID = ".$this->themeStylesheetID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes this theme stylesheet.
	 */
	public function delete() {
		self::deleteAll($this->themeStylesheetID);
	}

	/**
	 * Returns a list of layout ids of the theme layouts which are affected by a change of this stylesheet.
	 *
	 * @return array
	 */
	public function getAffectedThemeLayoutIDs() {
		$themeLayoutIDs = array();
		$sql = "SELECT	themeLayoutID
			FROM	wcf".WCF_N."_theme_stylesheet_to_layout
			WHERE	themeStylesheetID = ".$this->themeStylesheetID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeLayoutIDs[] = $row['themeLayoutID'];
		}
		return $themeLayoutIDs;
	}

	/**
	 * Recompiles the stylesheets of the theme layouts which are affected by a change of this stylesheet.
	 *
	 * @return array
	 */
	public function recompileAffectedThemeLayoutStylesheets() {
		$affectedThemeLayoutIDs = $this->getAffectedThemeLayoutIDs();
		if (empty($affectedThemeLayoutIDs)) return;

		$sql = "SELECT	themeLayoutID, themeID, title
			FROM	wcf".WCF_N."_theme_layout
			WHERE	themeLayoutID IN (".implode(',', $affectedThemeLayoutIDs).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
			$themeLayoutEditor = new ThemeLayoutEditor(null, $row);
			$themeLayoutEditor->compileStylesheet();
		}
	}

	/**
	 * Deletes all theme stylesheets with the given theme stylesheet ids.
	 *
	 * @param	string		$themeStylesheetIDs
	 */
	public static function deleteAll($themeStylesheetIDs) {
		if (empty($themeStylesheetIDs)) return;

		// delete theme stylesheet assignments
		$sql = "DELETE FROM	wcf".WCF_N."_theme_stylesheet_to_layout
			WHERE		themeStylesheetID IN (".$themeStylesheetIDs.")";
		WCF::getDB()->sendQuery($sql);

		// delete theme stylesheet
		$sql = "DELETE FROM	wcf".WCF_N."_theme_stylesheet
			WHERE		themeStylesheetID IN (".$themeStylesheetIDs.")";
		WCF::getDB()->sendQuery($sql);
	}
}
?>