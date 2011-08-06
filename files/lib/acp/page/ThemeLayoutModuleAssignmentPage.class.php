<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Shows a list of all theme modules.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.page
 * @category	Community Framework
 */
class ThemeLayoutModuleAssignmentPage extends AbstractPage {
	// system
	public $templateName = 'themeLayoutModuleAssignment';
	public $neededPermissions = 'admin.theme.canEditThemeLayout';
	
	public $removedThemeModuleID = 0;
	public $themeLayoutID = 0;
	public $themeLayoutOptions = array();
	public $themeLayout = null;
	public $themeModulePosition = 'main';
	public $themeModuleList = array();
	
	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['removedThemeModuleID'])) $this->removedThemeModuleID = intval($_REQUEST['removedThemeModuleID']);
		
		// get theme layout
		if (isset($_REQUEST['themeLayoutID'])) $this->themeLayoutID = intval($_REQUEST['themeLayoutID']);
		if ($this->themeLayoutID) {
			$this->themeLayout = new ThemeLayoutEditor($this->themeLayoutID);
		}
		
		// get theme module position
		if (isset($_REQUEST['themeModulePosition'])) $this->themeModulePosition = StringUtil::trim($_REQUEST['themeModulePosition']);
	}
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function readData() {
		parent::readData();
		
		// get theme layout options
		$this->themeLayoutOptions = ThemeLayout::getThemeLayoutOptions();
		
		// get theme modules
		if ($this->themeLayout !== null) {
			$themeModules = WCF::getCache()->get('themeModule-'.PACKAGE_ID);
			$themeModuleToLayouts = WCF::getCache()->get('themeLayout-'.PACKAGE_ID, 'modules');
			$themeModuleIDArray = (isset($themeModuleToLayouts[$this->themeLayoutID][$this->themeModulePosition]) ? $themeModuleToLayouts[$this->themeLayoutID][$this->themeModulePosition] : array());
			foreach ($themeModuleIDArray as $themeModuleData) {
				$this->themeModuleList[] = array(
					'themeModule' => $themeModules[$themeModuleData['themeModuleID']],
					'showOrder' => $themeModuleData['showOrder']
				);
			}
		}
	}
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// init form
		if ($this->themeLayout !== null) {
			require_once(WCF_DIR.'lib/acp/form/ThemeLayoutModuleAddForm.class.php');
			new ThemeLayoutModuleAddForm($this->themeLayout, $this->themeModulePosition);
		}
		
		WCF::getTPL()->assign(array(
			'themeLayoutID' => $this->themeLayoutID,
			'themeLayout' => $this->themeLayout,
			'themeLayoutOptions' => $this->themeLayoutOptions,
			'themeModulePositions' => ThemeLayout::$themeModulePositions,
			'themeModulePosition' => $this->themeModulePosition,
			'themeModules' => $this->themeModuleList,
			'removedThemeModuleID' => $this->removedThemeModuleID
		));
	}
	
	/**
	 * @see	Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.theme.layout.moduleAssignment');
		
		parent::show();
	}
}
?>