<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');

/**
 * Provides default implementations for theme module actions.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class AbstractThemeModuleAction extends AbstractAction {
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
	 * @see	Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get theme module
		if (isset($_REQUEST['themeModuleID'])) $this->themeModuleID = intval($_REQUEST['themeModuleID']);
		$this->themeModule = new ThemeModuleEditor($this->themeModuleID);
	}
}
?>