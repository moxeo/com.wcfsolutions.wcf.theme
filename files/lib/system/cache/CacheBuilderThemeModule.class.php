<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the theme modules.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	system.cache
 * @category	Community Framework
 */
class CacheBuilderThemeModule implements CacheBuilder {
	/**
	 * @see	CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();
		
		// get theme module ids
		$themeModuleIDArray = array();
		$sql = "SELECT		themeModuleID 
			FROM		wcf".WCF_N."_theme_module theme_module,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		theme_module.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeModuleIDArray[] = $row['themeModuleID'];
		}
		
		if (count($themeModuleIDArray) > 0) {
			require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_theme_module
				WHERE		themeModuleID IN (".implode(',', $themeModuleIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$data[$row['themeModuleID']] = new ThemeModule(null, $row);
			}	
		}
		
		return $data;
	}
}
?>