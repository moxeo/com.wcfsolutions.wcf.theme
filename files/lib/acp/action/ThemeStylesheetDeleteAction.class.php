<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeLayoutAction.class.php');

/**
 * Deletes a theme stylesheet.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeStylesheetDeleteAction extends AbstractThemeLayoutAction {
	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.theme.canDeleteThemeStylesheet');

		// delete theme stylesheet
		$this->themeStylesheet->delete();
		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=ThemeStylesheetList&themeID='.$this->themeStylesheet->themeID.'&deletedThemeStylesheetID='.$this->themeStylesheet->themeStylesheetID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>