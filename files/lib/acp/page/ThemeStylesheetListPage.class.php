<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetList.class.php');
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows a list of all theme stylesheets.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.page
 * @category	Community Framework
 */
class ThemeStylesheetListPage extends SortablePage {
	// system
	public $templateName = 'themeStylesheetList';
	public $defaultSortField = 'title';
	public $neededPermissions = array('admin.theme.canEditThemeStylesheet', 'admin.theme.canDeleteThemeStylesheet');

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
	public $deletedThemeStylesheetID = 0;

	/**
	 * theme layout list object
	 *
	 * @var	ThemeStylesheetList
	 */
	public $themeStylesheetList = null;

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

		if (isset($_REQUEST['deletedThemeStylesheetID'])) $this->deletedThemeStylesheetID = intval($_REQUEST['deletedThemeStylesheetID']);

		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		if ($this->themeID) {
			$this->theme = new Theme($this->themeID);
		}

		// init theme layout list
		$this->themeStylesheetList = new ThemeStylesheetList();
		$this->themeStylesheetList->sqlConditions = "theme_stylesheet.themeID = ".$this->themeID."
							AND theme_stylesheet.packageID IN (
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
		$this->themeStylesheetList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->themeStylesheetList->sqlLimit = $this->itemsPerPage;
		$this->themeStylesheetList->sqlOrderBy = 'theme_stylesheet.'.$this->sortField." ".$this->sortOrder;
		$this->themeStylesheetList->readObjects();
	}

	/**
	 * @see	SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();

		switch ($this->sortField) {
			case 'themeStylesheetID':
			case 'title': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}

	/**
	 * @see	MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();

		return $this->themeStylesheetList->countObjects();
	}


	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'themeStylesheets' => $this->themeStylesheetList->getObjects(),
			'themeOptions' => $this->themeOptions,
			'deletedThemeStylesheetID' => $this->deletedThemeStylesheetID
		));
	}

	/**
	 * @see	Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.theme.stylesheet.view');

		parent::show();
	}
}
?>