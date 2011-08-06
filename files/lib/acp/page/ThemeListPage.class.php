<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/ThemeList.class.php');
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows a list of all themees.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.page
 * @category	Community Framework
 */
class ThemeListPage extends SortablePage {
	// system
	public $templateName = 'themeList';
	public $defaultSortField = 'themeName';
	public $neededPermissions = array('admin.theme.canEditTheme', 'admin.theme.canDeleteTheme');
	
	/**
	 * deleted theme id
	 * 
	 * @var	integer
	 */
	public $deletedThemeID = 0;
	
	/**
	 * theme list object
	 * 
	 * @var	ThemeList
	 */
	public $themeList = null;
	
	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedThemeID'])) $this->deletedThemeID = intval($_REQUEST['deletedThemeID']);
		
		// init theme list
		$this->themeList = new ThemeList();
		$this->themeList->sqlConditions = "	theme.packageID IN (
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
		
		// read themes
		$this->themeList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->themeList->sqlLimit = $this->itemsPerPage;
		$this->themeList->sqlOrderBy = ($this->sortField != 'themeLayouts' ? 'theme.' : '').$this->sortField." ".$this->sortOrder;
		$this->themeList->readObjects();
	}
	
	/**
	 * @see	SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'themeID':
			case 'themeName':
			case 'themeLayouts': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see	MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->themeList->countObjects();
	}
	
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'themes' => $this->themeList->getObjects(),
			'deletedThemeID' => $this->deletedThemeID
		));
	}
	
	/**
	 * @see	Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.theme.view');
		
		parent::show();
	}
}
?>