<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');

/**
 * Represents a theme stylesheet.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.stylesheet
 * @category	Community Framework
 */
class ThemeStylesheet extends DatabaseObject {
	/**
	 * Creates a new ThemeStylesheet object.
	 *
	 * @param 	integer		$themeStylesheetID
	 * @param 	array		$row
	 */
	public function __construct($themeStylesheetID, $row = null, $cacheObject = null) {
		if ($themeStylesheetID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_theme_stylesheet
				WHERE 	themeStylesheetID = ".$themeStylesheetID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}

	/**
	 * Returns the title of this theme layout.
	 *
	 * @return	string
	 */
	public function __toString() {
		return $this->title;
	}

	/**
	 * Returns the theme object of this theme layout.
	 *
	 * @return	Theme
	 */
	public function getTheme() {
		return Theme::getTheme($this->themeID);
	}

	/**
	 * Returns the theme module options.
	 *
	 * @param	integer		$themeID
	 */
	public static function getThemeStylesheetOptions($themeID) {
		$themeStylesheets = array();

		$sql = "SELECT	themeStylesheetID, title
			FROM	wcf".WCF_N."_theme_stylesheet
			WHERE 	themeID = ".$themeID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeStylesheets[$row['themeStylesheetID']] = $row['title'];
		}

		return $themeStylesheets;
	}
}
?>