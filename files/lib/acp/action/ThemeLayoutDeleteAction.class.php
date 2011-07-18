<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeLayoutAction.class.php');

/**
 * Deletes a theme layout.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeLayoutDeleteAction extends AbstractThemeLayoutAction {
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		if ($this->themeLayout->isDefault) {
			throw new IllegalLinkException();
		}
		
		// check permission
		WCF::getUser()->checkPermission('admin.theme.canDeleteThemeLayout');
		
		// delete theme layout
		$this->themeLayout->delete();
		
		// reset cache
		WCF::getCache()->clearResource('themeLayout-'.PACKAGE_ID);
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=ThemeLayoutList&themeID='.$this->themeLayout->themeID.'&deletedThemeLayoutID='.$this->themeLayout->themeLayoutID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>