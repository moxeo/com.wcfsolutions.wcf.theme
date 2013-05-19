<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');
require_once(WCF_DIR.'lib/data/theme/module/type/ThemeModuleType.class.php');

/**
 * Provides default implementations for theme module types.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.module.type
 * @category	Community Framework
 */
class AbstractThemeModuleType implements ThemeModuleType {
	/**
	 * theme module type form data
	 *
	 * @var	array
	 */
	public $formData = array();

	// display methods
	/**
	 * @see	ThemeModuleType::cache()
	 */
	public function cache(ThemeModule $themeModule, $themeModulePosition, $additionalData) {}

	/**
	 * @see	ThemeModuleType::hasContent()
	 */
	public function hasContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		return true;
	}

	/**
	 * @see	ThemeModuleType::getContent()
	 */
	public function getContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		return '';
	}

	/**
	 * @see	ThemeModuleType::getSearchableContent()
	 */
	public function getSearchableContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		return '';
	}

	/**
	 * @see	ThemeModuleType::getPreviewHTML()
	 */
	public function getPreviewHTML(ThemeModule $themeModule) {
		return '### '.WCF::getLanguage()->get('wcf.theme.module.type.'.$themeModule->themeModuleType).' ###';
	}

	// form methods
	/**
	 * @see	ThemeModuleType::readFormParameters()
	 */
	public function readFormParameters() {}

	/**
	 * @see	ThemeModuleType::validate()
	 */
	public function validate() {}

	/**
	 * @see	ThemeModuleType::getFormData()
	 */
	public function getFormData() {
		return $this->formData;
	}

	/**
	 * @see	ThemeModuleType::readFormData()
	 */
	public function setFormData($data) {
		$this->formData = $data;
	}

	/**
	 * @see	ThemeModuleType::assignVariables()
	 */
	public function assignVariables() {}

	/**
	 * @see	ThemeModuleType::getFormTemplateName()
	 */
	public function getFormTemplateName() {
		return '';
	}
}
?>