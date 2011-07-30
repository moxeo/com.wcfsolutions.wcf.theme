<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the theme layouts.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	system.cache
 * @category	Community Framework
 */
class CacheBuilderThemeLayout implements CacheBuilder {
	/**
	 * @see	CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array('layouts' => array(), 'default' => 0, 'modules' => array());
		
		// get theme layout ids
		$themeLayoutIDArray = array();
		$sql = "SELECT		themeLayoutID 
			FROM		wcf".WCF_N."_theme_layout theme_layout,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		theme_layout.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeLayoutIDArray[] = $row['themeLayoutID'];
		}
		
		if (count($themeLayoutIDArray) > 0) {
			require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayout.class.php');
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_theme_layout
				WHERE		themeLayoutID IN (".implode(',', $themeLayoutIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				if ($row['isDefault'] || $data['default'] == 0) $data['default'] = $row['themeLayoutID'];
				$data['layouts'][$row['themeLayoutID']] = new ThemeLayout(null, $row);
			}	
			
			// get theme modules to layout
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_theme_module_to_layout
				WHERE		themeLayoutID IN (".implode(',', $themeLayoutIDArray).")
				ORDER BY	showOrder";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				if (!isset($data['modules'][$row['themeLayoutID']])) {
					$data['modules'][$row['themeLayoutID']] = array();
				}
				if (!isset($data['modules'][$row['themeLayoutID']][$row['themeModulePosition']])) {
					$data['modules'][$row['themeLayoutID']][$row['themeModulePosition']] = array();
				}
				$data['modules'][$row['themeLayoutID']][$row['themeModulePosition']][] = array('themeModuleID' => $row['themeModuleID'], 'showOrder' => $row['showOrder']);
			}
		}
		
		return $data;
	}
}
?>