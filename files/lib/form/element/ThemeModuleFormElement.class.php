<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');
require_once(WCF_DIR.'lib/form/element/AbstractFormElement.class.php');

/**
 * Provides default implementations for theme module form elements.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	form.element
 * @category	Community Framework
 */
abstract class ThemeModuleFormElement extends AbstractFormElement {
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
	 * Creates a new ThemeModuleFormElement object.
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
	 * @see Form::submit()
	 */
	public function submit() {
		// get submitting theme module id
		$themeModuleID = 0;
		if (isset($_POST['themeModuleID'])) $themeModuleID = intval($_POST['themeModuleID']);
		
		// get submitting theme module position
		$themeModulePosition = '';
		if (isset($_POST['themeModulePosition'])) $themeModulePosition = $_POST['themeModulePosition'];
		
		// get submitting additional data
		$encryptedAdditionalData = '';
		if (isset($_POST['additionalData'])) $encryptedAdditionalData = $_POST['additionalData'];
		
		// submit
		if ($themeModuleID == $this->themeModule->themeModuleID && $themeModulePosition == $this->themeModulePosition && $encryptedAdditionalData == sha1(serialize($this->additionalData))) {
			parent::submit();
		}
	}
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// get theme module input tags
		$html = '<input type="hidden" name="themeModuleID" value="'.$this->themeModule->themeModuleID.'" />
			<input type="hidden" name="themeModulePosition" value="'.$this->themeModulePosition.'" />
			<input type="hidden" name="additionalData" value="'.sha1(serialize($this->additionalData)).'" />';
		
		// assign parameters
		WCF::getTPL()->assign(array(
			'themeModule' => $this->themeModule,
			'themeModulePosition' => $this->themeModulePosition,
			'additionalData' => $this->additionalData,
			'themeModuleInputTags' => $html
		));
	}
}
?>