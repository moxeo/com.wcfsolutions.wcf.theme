<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');
require_once(WCF_DIR.'lib/page/element/AbstractPageElement.class.php');

/**
 * Provides default implementations for theme module page elements.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
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
	private $themeModule = null;

	/**
	 * theme module position
	 *
	 * @var	string
	 */
	private $themeModulePosition = 'main';

	/**
	 * list of additional data
	 *
	 * @var	array
	 */
	private $additionalData = array();

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
	 * @see	PageElement::getIdentifier()
	 */
	public function getIdentifier() {
		return $this->themeModule->themeModuleID.'-'.$this->themeModulePosition.'-'.sha1(serialize($this->additionalData));
	}

	/**
	 * @see	Page::assignVariables()
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

	/**
	 * Returns the theme module object of this page element.
	 *
	 * @return	ThemeModule	The theme module object of this page element.
	 */
	public function getThemeModule() {
		return $this->themeModule;
	}

	/**
	 * Returns the theme module position of this page element.
	 *
	 * @see		ThemeLayout::getThemeModulePositions()		All possible theme module positions.
	 * @return	string						The theme module position of this page element.
	 */
	public function getThemeModulePosition() {
		return $this->themeModulePosition;
	}

	/**
	 * Returns additional data of this page element.
	 *
	 * @return	array		The additional data of this page element.
	 */
	public function getAdditionalData() {
		return $this->additionalData;
	}
}
?>