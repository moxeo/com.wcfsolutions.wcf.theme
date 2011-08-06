<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');
require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleList.class.php');
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

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
class ThemeModuleListPage extends SortablePage {
	// system
	public $templateName = 'themeModuleList';
	public $defaultSortField = 'themeModuleID';
	public $neededPermissions = array('admin.theme.canEditThemeModule', 'admin.theme.canDeleteThemeModule');
	
	/**
	 * theme id
	 * 
	 * @var	integer
	 */
	public $themeID = 0;
	
	/**
	 * theme editor object
	 * 
	 * @var	ThemeEditor
	 */
	public $theme = null;
	
	/**
	 * theme module list object
	 * 
	 * @var	ThemeModuleList
	 */
	public $themeModuleList = null;
	
	/**
	 * deleted theme module id
	 * 
	 * @var	integer
	 */
	public $deletedThemeModuleID = 0;
	
	/**
	 * True, if the list was sorted successfully.
	 * 
	 * @var boolean
	 */
	public $successfulSorting = false;
	
	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedThemeModuleID'])) $this->deletedThemeModuleID = intval($_REQUEST['deletedThemeModuleID']);
		if (isset($_REQUEST['successfulSorting'])) $this->successfulSorting = true;
		
		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		if ($this->themeID) {
			$this->theme = new Theme($this->themeID);	
		}
		
		// init theme module list
		$this->themeModuleList = new ThemeModuleList();
		$this->themeModuleList->sqlConditions = "theme_module.themeID = ".$this->themeID."
							AND	theme_module.packageID IN (
								SELECT	dependency
								FROM	wcf".WCF_N."_package_dependency
								WHERE	packageID = ".PACKAGE_ID."
							)";
	}
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function readData() {
		parent::readData();
		
		// get theme options
		$this->themeOptions = Theme::getThemes();
		
		// read theme modules
		$this->themeModuleList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->themeModuleList->sqlLimit = $this->itemsPerPage;
		$this->themeModuleList->sqlOrderBy = 'theme_module.'.$this->sortField." ".$this->sortOrder;
		$this->themeModuleList->readObjects();
	}
	
	/**
	 * @see	SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'themeModuleID':
			case 'title':
			case 'themeModuleType': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see	MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->themeModuleList->countObjects();
	}
		
	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'themeModules' => $this->themeModuleList->getObjects(),
			'themeOptions' => $this->themeOptions,
			'deletedThemeModuleID' => $this->deletedThemeModuleID,
			'successfulSorting' => $this->successfulSorting
		));
	}
	
	/**
	 * @see	Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.theme.module.view');
		
		parent::show();
	}
}
?>