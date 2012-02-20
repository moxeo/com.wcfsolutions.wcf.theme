<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');

/**
 * Represents a list of themes.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme
 * @category	Community Framework
 */
class ThemeList extends DatabaseObjectList {
	/**
	 * list of themes
	 *
	 * @var array<Theme>
	 */
	public $themes = array();

	/**
	 * @see	DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_theme theme
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see	DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					theme.*,
					(SELECT COUNT(*) FROM wcf".WCF_N."_theme_layout WHERE themeID = theme.themeID) AS themeLayouts
			FROM		wcf".WCF_N."_theme theme
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->themes[] = new Theme(null, $row);
		}
	}

	/**
	 * @see	DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->themes;
	}
}
?>