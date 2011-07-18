<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');

/**
 * Provides default implementations for theme actions.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class AbstractThemeAction extends AbstractAction {
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
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		$this->theme = new ThemeEditor($this->themeID);
	}
}
?>