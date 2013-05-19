<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayout.class.php');

/**
 * Represents a list of theme layouts.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.layout
 * @category	Community Framework
 */
class ThemeLayoutList extends DatabaseObjectList {
	/**
	 * list of theme layouts
	 *
	 * @var array<ThemeLayout>
	 */
	public $themeLayouts = array();

	/**
	 * @see	DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_theme_layout theme_layout
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see	DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					theme_layout.*,
					(SELECT COUNT(*) FROM wcf".WCF_N."_theme_module_to_layout WHERE themeLayoutID = theme_layout.themeLayoutID) AS themeModules
			FROM		wcf".WCF_N."_theme_layout theme_layout
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->themeLayouts[] = new ThemeLayout(null, $row);
		}
	}

	/**
	 * @see	DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->themeLayouts;
	}
}
?>