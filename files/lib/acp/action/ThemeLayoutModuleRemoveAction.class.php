<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeLayoutAction.class.php');
require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');

/**
 * Removes a theme module from a theme layout.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeLayoutModuleRemoveAction extends AbstractThemeLayoutAction {
	/**
	 * theme module id
	 *
	 * @var	integer
	 */
	public $themeModuleID = 0;

	/**
	 * theme module editor object
	 *
	 * @var	ThemeModuleEditor
	 */
	public $themeModule = null;

	/**
	 * theme module position
	 *
	 * @var	string
	 */
	public $themeModulePosition = '';

	/**
	 * new show order
	 *
	 * @var integer
	 */
	public $showOrder = 0;

	/**
	 * @see	Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get theme module
		if (isset($_REQUEST['themeModuleID'])) $this->themeModuleID = intval($_REQUEST['themeModuleID']);
		$this->themeModule = new ThemeModuleEditor($this->themeModuleID);

		// get theme module position
		if (isset($_REQUEST['themeModulePosition'])) $this->themeModulePosition = StringUtil::trim($_REQUEST['themeModulePosition']);

		// get show order
		if (isset($_REQUEST['showOrder'])) $this->showOrder = intval($_REQUEST['showOrder']);
	}

	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.theme.canEditThemeLayout');

		// remove theme module
		$this->themeLayout->removeThemeModule($this->themeModule->themeModuleID, $this->themeModulePosition, $this->showOrder);

		// reset cache
		ThemeLayoutEditor::clearCache();
		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=ThemeLayoutModuleAssignment&themeLayoutID='.$this->themeLayout->themeLayoutID.'&themeModulePosition='.$this->themeModulePosition.'&removedThemeModuleID='.$this->themeModuleID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>