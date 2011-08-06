<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/type/AbstractThemeModuleType.class.php');

/**
 * Represents a viewable theme module type.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
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
	public $pageElements = array();
	
	/**
	 * page element
	 * 
	 * @var	string
	 */
	public $pageElement = '';
	
	/**
	 * page element type (page/form/action)
	 * 
	 * @var	string
	 */
	public $pageElementType = 'page';
	
	/**
	 * page element dir
	 * 
	 * @var	string
	 */
	public $pageElementDir = WCF_DIR;
	
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
		if ($this->pageElementType != 'action' && isset($this->pageElements[$themeModule->themeModuleID]) && $this->pageElements[$themeModule->themeModuleID]->getContent()) {
			return true;
		}
		return false;
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