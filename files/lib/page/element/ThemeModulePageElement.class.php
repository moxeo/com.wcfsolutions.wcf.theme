<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');
require_once(WCF_DIR.'lib/page/element/AbstractPageElement.class.php');

/**
 * Provides default implementations for theme module page elements.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	page.element
 * @category	Community Framework
 */
abstract class ThemeModulePageElement extends AbstractPageElement {
	/**
	 * theme module object
	 * 
	 * @var	ThemeModule
	 */
	public $themeModule = null;	
	
	/**
	 * theme module position
	 * 
	 * @var	string
	 */
	public $themeModulePosition = 'main';
	
	/**
	 * list of additional data
	 * 
	 * @var	array
	 */
	public $additionalData = array();
	
	/**
	 * Creates a new ThemeModulePageElement object.
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
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// assign parameters
		WCF::getTPL()->assign(array(
			'themeModule' => $this->themeModule,
			'themeModulePosition' => $this->themeModulePosition,
			'additionalData' => $this->additionalData
		));
	}
}
?>