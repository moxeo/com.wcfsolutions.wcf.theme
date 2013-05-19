<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * This PIP installs, updates or deletes theme module types.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.package.plugin
 * @category	Community Framework
 * @todo	Uninstall
 */
class ThemeModuleTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	public $tagName = 'thememoduletype';
	public $tableName = 'theme_module_type';

	/**
	 * @see	PackageInstallationPlugin::install()
	 */
	public function install() {
		parent::install();

		if (!$xml = $this->getXML()) {
			return;
		}

		// Create an array with the data blocks (import or delete) from the xml file.
		$themeModuleTypeXML = $xml->getElementTree('data');

		// Loop through the array and install or uninstall items.
		foreach ($themeModuleTypeXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through items and create or update them.
					foreach ($block['children'] as $themeModuleType) {
						// Extract item properties.
						foreach ($themeModuleType['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$themeModuleType[$child['name']] = $child['cdata'];
						}

						// default values
						$name = $category = $classFile = '';

						// get values
						if (isset($themeModuleType['name'])) $name = $themeModuleType['name'];
						if (isset($themeModuleType['category'])) $category = $themeModuleType['category'];
						if (isset($themeModuleType['classfile'])) $classFile = $themeModuleType['classfile'];

						// insert items
						$sql = "INSERT INTO			wcf".WCF_N."_theme_module_type
											(packageID, themeModuleType, category, classFile)
							VALUES				(".$this->installation->getPackageID().",
											'".escapeString($name)."',
											'".escapeString($category)."',
											'".escapeString($classFile)."')
							ON DUPLICATE KEY UPDATE 	category = VALUES(category),
											classFile = VALUES(classFile)";
						WCF::getDB()->sendQuery($sql);
					}
				}
				// Handle the delete instructions.
				else if ($block['name'] == 'delete' && $this->installation->getAction() == 'update') {
					// Loop through items and delete them.
					$nameArray = array();
					foreach ($block['children'] as $themeModuleType) {
						// Extract item properties.
						foreach ($themeModuleType['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$themeModuleType[$child['name']] = $child['cdata'];
						}

						if (empty($themeModuleType['name'])) {
							throw new SystemException("Required 'name' attribute for theme module type is missing", 13023);
						}
						$nameArray[] = $themeModuleType['name'];
					}
					if (count($nameArray)) {
						$sql = "DELETE FROM	wcf".WCF_N."_theme_module_type
							WHERE		packageID = ".$this->installation->getPackageID()."
									AND themeModuleType IN ('".implode("','", array_map('escapeString', $nameArray))."')";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
	}
}
?>