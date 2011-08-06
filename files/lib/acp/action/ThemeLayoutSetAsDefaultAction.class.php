<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractThemeLayoutAction.class.php');

/**
 * Sets a theme layout as default theme layout for a package.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class ThemeLayoutSetAsDefaultAction extends AbstractThemeLayoutAction {
	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.theme.canEditThemeLayout');
		
		// set as default
		$this->themeLayout->setAsDefault();
		
		// reset cache
		WCF::getCache()->clearResource('themeLayout-'.PACKAGE_ID);
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=ThemeLayoutList&themeID='.$this->themeLayout->themeID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>