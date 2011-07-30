<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutList.class.php');
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows a list of all theme layouts.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.page
 * @category	Community Framework
 */
class ThemeLayoutListPage extends SortablePage {
	// system
	public $templateName = 'themeLayoutList';
	public $defaultSortField = 'title';
	public $neededPermissions = array('admin.theme.canEditTheme', 'admin.theme.canDeleteTheme');
	
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
	 * deleted theme layout id
	 * 
	 * @var	integer
	 */
	public $deletedThemeLayoutID = 0;
	
	/**
	 * theme layout list object
	 * 
	 * @var	ThemeLayoutList
	 */
	public $themeLayoutList = null;
	
	/**
	 * list of available themes
	 * 
	 * @var	array
	 */
	public $themeOptions = array();
	
	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedThemeLayoutID'])) $this->deletedThemeLayoutID = intval($_REQUEST['deletedThemeLayoutID']);
		
		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		if ($this->themeID) {
			$this->theme = new Theme($this->themeID);	
		}
		
		// init theme layout list
		$this->themeLayoutList = new ThemeLayoutList();
		$this->themeLayoutList->sqlConditions = "theme_layout.themeID = ".$this->themeID."
							AND theme_layout.packageID IN (
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
		
		// read theme layouts
		$this->themeLayoutList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->themeLayoutList->sqlLimit = $this->itemsPerPage;
		$this->themeLayoutList->sqlOrderBy = ($this->sortField != 'themeModules' ? 'theme_layout.' : '').$this->sortField." ".$this->sortOrder;
		$this->themeLayoutList->readObjects();
	}
	
	/**
	 * @see	SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'themeLayoutID':
			case 'title':
			case 'themeModules': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see	MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->themeLayoutList->countObjects();
	}
	
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'themeLayouts' => $this->themeLayoutList->getObjects(),
			'themeOptions' => $this->themeOptions,
			'deletedThemeLayoutID' => $this->deletedThemeLayoutID
		));
	}
	
	/**
	 * @see	Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.theme.layout.view');
		
		parent::show();
	}
}
?>