<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the theme module types.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	system.cache
 * @category	Community Framework
 */
class CacheBuilderThemeModuleTypes implements CacheBuilder {
	/**
	 * @see	CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']);
		$data = array();

		// get box tab type ids
		$themeModuleTypeIDArray = array();
		$sql = "SELECT		themeModuleTypeID
			FROM		wcf".WCF_N."_theme_module_type theme_module_type,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		theme_module_type.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeModuleTypeIDArray[] = $row['themeModuleTypeID'];
		}

		if (count($themeModuleTypeIDArray) > 0) {
			$sql = "SELECT		theme_module_type.*, package.packageDir
				FROM		wcf".WCF_N."_theme_module_type theme_module_type
				LEFT JOIN	wcf".WCF_N."_package package
				ON		(package.packageID = theme_module_type.packageID)
				WHERE		theme_module_type.themeModuleTypeID IN (".implode(',', $themeModuleTypeIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$row['className'] = StringUtil::getClassName($row['classFile']);
				$data[] = $row;
			}
		}

		return $data;
	}
}
?>