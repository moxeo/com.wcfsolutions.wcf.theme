<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeLayoutAction.class.php');
require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');

/**
 * Sorts the structure of theme modules.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeLayoutModuleSortAction extends AbstractThemeLayoutAction {
	/**
	 * theme module id
	 *
	 * @var integer
	 */
	public $themeModuleID = 0;

	/**
	 * theme module object
	 *
	 * @var ThemeModuleEditor
	 */
	public $themeModule = null;

	/**
	 * theme position id
	 *
	 * @var	integer
	 */
	public $themePositionID = 0;

	/**
	 * theme object
	 *
	 * @var	ThemePosition
	 */
	public $themePosition = null;

	/**
	 * new show order
	 *
	 * @var integer
	 */
	public $showOrder = 0;

	/**
	 * old show order
	 *
	 * @var integer
	 */
	public $oldShowOrder = 0;

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
		if (isset($_REQUEST['oldShowOrder'])) $this->oldShowOrder = intval($_REQUEST['oldShowOrder']);
		if (isset($_REQUEST['showOrder'])) $this->showOrder = intval($_REQUEST['showOrder']);
	}

	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.theme.canEditThemeLayout');

		// update show order
		$this->themeLayout->updateThemeModuleShowOrder($this->themeModulePosition, $this->themeModuleID, $this->oldShowOrder, $this->showOrder);

		// reset cache
		ThemeLayoutEditor::clearCache();
		$this->executed();
	}
}
?>