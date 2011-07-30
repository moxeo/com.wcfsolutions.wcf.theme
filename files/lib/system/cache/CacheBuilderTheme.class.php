<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the themes.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	system.cache
 * @category	Community Framework
 */
class CacheBuilderTheme implements CacheBuilder {
	/**
	 * @see	CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array('themes' => array(), 'default' => 0);
		
		// get theme ids
		$themeIDArray = array();
		$sql = "SELECT		themeID 
			FROM		wcf".WCF_N."_theme theme,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		theme.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$themeIDArray[] = $row['themeID'];
		}
		
		if (count($themeIDArray) > 0) {
			require_once(WCF_DIR.'lib/data/theme/Theme.class.php');
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_theme
				WHERE		themeID IN (".implode(',', $themeIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$data['themes'][$row['themeID']] = new Theme(null, $row);
			}
		}
		
		return $data;
	}
}
?>