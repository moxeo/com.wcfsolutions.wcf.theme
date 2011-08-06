<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');

/**
 * Shows the theme layout module add form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeLayoutModuleAddForm extends AbstractForm {
	// parameters
	public $themeModuleID = 0;
	
	/**
	 * theme layout object
	 * 
	 * @var ThemeLayout
	 */
	public $themeLayout = null;
	
	/**
	 * theme module position
	 * 
	 * @var string
	 */
	public $themePosition = '';
	
	/**
	 * Creates a new ThemeLayoutModuleAddForm object.
	 * 
	 * @param	ThemeLayout	$themeLayout
	 * @param	string		$themeModulePosition
	 */
	public function __construct(ThemeLayout $themeLayout, $themeModulePosition) {
		$this->themeLayout = $themeLayout;
		$this->themeModulePosition = $themeModulePosition;
		parent::__construct();
	}
	
	/**
	 * @see	Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['themeModuleID'])) $this->themeModuleID = intval($_POST['themeModuleID']);
	}
	
	/**
	 * @see	Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		$themeModule = new ThemeModule($this->themeModuleID);
		if (!$themeModule->themeModuleID) {
			throw new UserInputException('themeModuleID', 'invalid');
		}
	}
	
	/**
	 * @see	Form::save()
	 */
	public function save() {
		parent::save();
		
		// add theme module
		$this->themeLayout->addThemeModule($this->themeModuleID, $this->themeModulePosition);
		
		// reset cache
		ThemeLayoutEditor::clearCache();
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ThemeLayoutModuleAssignment&themeLayoutID='.$this->themeLayout->themeLayoutID.'&themeModulePosition='.$this->themeModulePosition.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'themeModuleID' => $this->themeModuleID,
			'themeModuleOptions' => ThemeModule::getThemeModuleOptions($this->themeLayout->getTheme()->themeID)
		));
	}
}
?>