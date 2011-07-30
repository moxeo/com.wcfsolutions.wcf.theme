<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/type/AbstractThemeModuleType.class.php');

/**
 * Represents a html theme module type.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.module.type
 * @category	Community Framework
 */
class HTMLThemeModuleType extends AbstractThemeModuleType {
	// display methods
	/**
	 * @see	ThemeModuleType::getContent()
	 */
	public function getContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		if ($themeModule->dynamicCode) {
			return WCF::getTPL()->fetchString($themeModule->dynamicCode);
		}
		return $themeModule->code;
	}
	
	/**
	 * @see	ThemeModuleType::getSearchableContent()
	 */
	public function getSearchableContent(ThemeModule $themeModule, $themeModulePosition, $additionalData) {
		$code = $this->getContent($themeModule, $themeModulePosition, $additionalData);
		return StringUtil::stripHTML($code);
	}
	
	/**
	 * @see	ThemeModuleType::getPreviewHTML()
	 */
	public function getPreviewHTML(ThemeModule $themeModule) {
		return $themeModule->code;
	}
	
	// form methods
	/**
	 * @see	ThemeModuleType::readFormParameters()
	 */
	public function readFormParameters() {
		$this->formData['code'] = '';
		if (isset($_POST['code'])) $this->formData['code'] = StringUtil::trim($_POST['code']);
	}
	
	/**
	 * @see	ThemeModuleType::validate()
	 */
	public function validate() {
		// code
		if (empty($this->formData['code'])) {
			throw new UserInputException('code');
		}
		
		// compile dynamic code
		$this->formData['dynamicCode'] = '';
		if (strpos($this->formData['code'], '{') !== false) {
			require_once(WCF_DIR.'lib/system/template/TemplateScriptingCompiler.class.php');
			$scriptingCompiler = new TemplateScriptingCompiler(WCF::getTPL());
			try {
				$this->formData['dynamicCode'] = $scriptingCompiler->compileString('htmlThemeModuleType', $this->formData['code']);
			}
			catch (SystemException $e) {
				throw new UserInputException('code', 'syntaxError');
			}
		}
	}
	
	/**
	 * @see	ThemeModuleType::assignVariables()
	 */
	public function assignVariables() {
		WCF::getTPL()->assign(array(
			'code' => (isset($this->formData['code']) ? $this->formData['code'] : '')
		));
	}
	
	/**
	 * @see	ThemeModuleType::getFormTemplateName()
	 */
	public function getFormTemplateName() {
		return 'htmlThemeModuleType';
	}
}
?>