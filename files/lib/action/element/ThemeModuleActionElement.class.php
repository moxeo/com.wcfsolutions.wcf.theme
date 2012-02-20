<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');

/**
 * Provides default implementations for theme module action elements.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	action.element
 * @category	Community Framework
 */
abstract class ThemeModuleActionElement extends AbstractSecureAction {
	/**
	 * theme module object
	 *
	 * @var	ThemeModule
	 */
	public $themeModule = null;

	/**
	 * Creates a new ThemeModuleActionElement object.
	 *
	 * @param	ThemeModule		$themeModule
	 * @param	string			$themeModulePosition
	 * @param	array			$additionalData
	 */
	public function __construct(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		$this->themeModule = $themeModule;
		$this->themeModulePosition = $themeModulePosition;
		$this->additionalData = $additionalData;
		parent::__construct();
	}
}
?>