<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');

/**
 * Provides default implementations for theme layout actions.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.action
 * @category	Community Framework
 */
class AbstractThemeLayoutAction extends AbstractAction {
	/**
	 * theme layout id
	 *
	 * @var	integer
	 */
	public $themeLayoutID = 0;

	/**
	 * theme layout editor object
	 *
	 * @var	ThemeLayoutEditor
	 */
	public $themeLayout = null;

	/**
	 * @see	Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get theme layout
		if (isset($_REQUEST['themeLayoutID'])) $this->themeLayoutID = intval($_REQUEST['themeLayoutID']);
		$this->themeLayout = new ThemeLayoutEditor($this->themeLayoutID);
	}
}
?>