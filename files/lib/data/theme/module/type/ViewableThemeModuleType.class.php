<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/type/AbstractThemeModuleType.class.php');

/**
 * Represents a viewable theme module type.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.module.type
 * @category	Community Framework
 */
abstract class ViewableThemeModuleType extends AbstractThemeModuleType {
	/**
	 * list of page elements
	 *
	 * @var	array<PageElement>
	 */
	protected $pageElements = array();

	/**
	 * Returns the name of the page element to be displayed.
	 *
	 * @return	string		The name of the page element to be displayed.
	 */
	public abstract function getPageElement();

	/**
	 * Returns the type of the page element.
	 *
	 * @return	string		The page element type (either page, form or action).
	 */
	public function getPageElementType() {
		return 'page';
	}

	/**
	 * Returns the absolute directory where the page element is located in.
	 *
	 * @return	string		The absolute directory where the page element is located in.
	 */
	public function getPageElementDir() {
		return WCF_DIR;
	}

	/**
	 * @see	ThemeModuleType::cache()
	 */
	public function cache(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		$className = ucfirst($this->pageElement).ucfirst($this->pageElementType).'Element';
		$path = $this->pageElementDir.'lib/'.$this->pageElementType.'/element/'.$className.'.class.php';

		// include class file
		if (!class_exists($className)) {
			if (!file_exists($path)) {
				throw new SystemException("Unable to find class file '".$path."'", 11000);
			}
			require_once($path);
		}

		try {
			$pageElementObj = new $className($themeModule, $themeModulePosition, $additionalData);
			$this->pageElements[$themeModule->themeModuleID] = $pageElementObj;
		}
		catch (UserException $e) {}
	}

	/**
	 * @see	ThemeModuleType::hasContent()
	 */
	public function hasContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		return ($this->pageElementType != 'action' && isset($this->pageElements[$themeModule->themeModuleID])
			&& $this->pageElements[$themeModule->themeModuleID]->getContent());
	}

	/**
	 * @see	ThemeModuleType::getContent()
	 */
	public function getContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		if ($this->pageElementType != 'action' && isset($this->pageElements[$themeModule->themeModuleID])) {
			return $this->pageElements[$themeModule->themeModuleID]->getContent();
		}
		return '';
	}
}
?>