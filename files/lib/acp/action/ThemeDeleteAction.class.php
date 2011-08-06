<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeAction.class.php');

/**
 * Deletes a theme.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeDeleteAction extends AbstractThemeAction {
	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.theme.canDeleteTheme');
		
		// delete theme
		$this->theme->delete();
		
		// reset cache
		WCF::getCache()->clearResource('theme-'.PACKAGE_ID);
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=ThemeList&deletedThemeID='.$this->theme->themeID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>