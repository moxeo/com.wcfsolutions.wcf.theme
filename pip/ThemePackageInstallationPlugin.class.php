<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractPackageInstallationPlugin.class.php');

/**
 * This PIP installs, updates or deletes themes.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.package.plugin
 * @category	Community Framework
 */
class ThemePackageInstallationPlugin extends AbstractPackageInstallationPlugin {
	public $tagName = 'theme';
	public $tableName = 'theme';

	/**
	 * @see	PackageInstallationPlugin::install()
	 */
	public function install() {
		parent::install();

		// get theme data
		$themeData = $this->installation->getXMLTag('theme');

		// extract theme tar
		$sourceFile = $this->installation->getArchive()->extractTar($themeData['cdata'], 'theme_');

		// import theme
		require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');
		ThemeEditor::import($sourceFile, $this->installation->getPackageID());

		// delete tmp file
		@unlink($sourceFile);
	}

	/**
	 * @see	PackageInstallationPlugin::uninstall()
	 */
	public function uninstall() {
		// call uninstall event
		EventHandler::fireAction($this, 'uninstall');

		// get all themes of this package
		require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');
		$sql = "SELECT	*
			FROM	wcf".WCF_N."_theme
			WHERE	packageID = ".$this->installation->getPackageID();
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			// delete theme
			$theme = new ThemeEditor(null, $row);
			$theme->delete();
		}
	}
}
?>