<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ThemeModuleAddForm.class.php');

/**
 * Shows the theme module edit form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeModuleEditForm extends ThemeModuleAddForm {
	// system
	public $neededPermissions = 'admin.theme.canEditThemeModule';
	public $activeMenuItem = 'wcf.acp.menu.link.theme.module';

	/**
	 * article section id
	 *
	 * @var	integer
	 */
	public $themeModuleID = 0;

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		ACPForm::readParameters();

		// get theme module
		if (isset($_REQUEST['themeModuleID'])) $this->themeModuleID = intval($_REQUEST['themeModuleID']);
		$this->themeModule = new ThemeModuleEditor($this->themeModuleID);
		if (!$this->themeModule->themeModuleID) {
			throw new IllegalLinkException();
		}

		// get theme
		$this->theme = new Theme($this->themeModule->themeID);

		// get available theme module types
		$this->availableThemeModuleTypes = ThemeModule::getAvailableThemeModuleTypes();
	}

	/**
	 * @see	Page::readData()
	 */
	public function readData() {
		parent::readData();

		// default values
		if (!count($_POST)) {
			$this->title = $this->themeModule->title;
			$this->cssID = $this->themeModule->cssID;
			$this->cssClasses = $this->themeModule->cssClasses;
			$this->themeModuleType = $this->themeModule->themeModuleType;

			// theme module type object
			$this->themeModuleTypeObject = $this->availableThemeModuleTypes[$this->themeModuleType];
			$this->themeModuleTypeObject->setFormData(unserialize($this->themeModule->themeModuleData));
		}
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'themeModuleID' => $this->themeModuleID,
			'themeModule' => $this->themeModule
		));
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		AbstractForm::save();

		// update theme module
		$this->themeModule->update($this->title, $this->cssID, $this->cssClasses, $this->themeModuleType, $this->themeModuleTypeObject->getFormData());

		// reset cache
		ThemeModuleEditor::clearCache();
		$this->saved();

		// show success message
		WCF::getTPL()->assign('success', true);
	}
}
?>