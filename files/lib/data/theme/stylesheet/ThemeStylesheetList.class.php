<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheet.class.php');

/**
 * Represents a list of theme stylesheets.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.stylesheet
 * @category	Community Framework
 */
class ThemeStylesheetList extends DatabaseObjectList {
	/**
	 * list of theme stylesheets
	 *
	 * @var array<ThemeStylesheet>
	 */
	private $themeStylesheets = array();

	/**
	 * @see	DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_theme_stylesheet theme_stylesheet
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see	DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					theme_stylesheet.*
			FROM		wcf".WCF_N."_theme_stylesheet theme_stylesheet
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->themeStylesheets[] = new ThemeStylesheet(null, $row);
		}
	}

	/**
	 * @see	DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->themeStylesheets;
	}
}
?>