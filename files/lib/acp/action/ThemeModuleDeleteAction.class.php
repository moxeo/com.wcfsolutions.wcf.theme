<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeModuleAction.class.php');

/**
 * Deletes a theme module.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeModuleDeleteAction extends AbstractThemeModuleAction {
	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.theme.canDeleteThemeModule');
		
		// delete theme module
		$this->themeModule->delete();
		
		// reset cache
		WCF::getCache()->clearResource('themeModule-'.PACKAGE_ID);
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=ThemeModuleList&themeID='.$this->themeModule->themeID.'&deletedThemeModuleID='.$this->themeModule->themeModuleID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>