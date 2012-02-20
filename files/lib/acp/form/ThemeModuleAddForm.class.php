<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');

/**
 * Shows the theme module add form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeModuleAddForm extends ACPForm {
	// system
	public $templateName = 'themeModuleAdd';
	public $neededPermissions = 'admin.theme.canAddThemeModule';
	public $activeMenuItem = 'wcf.acp.menu.link.theme.module.add';

	/**
	 * theme id
	 *
	 * @var	integer
	 */
	public $themeID = 0;

	/**
	 * theme editor object
	 *
	 * @var	ThemeEditor
	 */
	public $theme = null;

	/**
	 * list of theme module types
	 *
	 * @var	array<ThemeModuleType>
	 */
	public $themeModuleTypes = array();

	/**
	 * theme module type object
	 *
	 * @var	ThemeModuleType
	 */
	public $themeModuleTypeObject = null;

	/**
	 * theme module type id
	 *
	 * @var	integer
	 */
	public $themeModuleTypeID = 0;

	/**
	 * theme module editor object
	 *
	 * @var	ThemeModuleEditor
	 */
	public $themeModule = null;

	/**
	 * list of available box tab types
	 *
	 * @var	array<ThemeModuleType>
	 */
	public $availableThemeModuleTypes = array();

	// parameters
	public $title = '';
	public $cssID = '';
	public $cssClasses = '';
	public $themeModuleType = '';
	public $send = false;

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		if ($this->themeID) {
			$this->theme = new Theme($this->themeID);
		}

		// get available article section types
		$this->availableThemeModuleTypes = ThemeModule::getAvailableThemeModuleTypes();
	}

	/**
	 * @see	Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['cssID'])) $this->cssID = StringUtil::trim($_POST['cssID']);
		if (isset($_POST['cssClasses'])) $this->cssClasses = StringUtil::trim($_POST['cssClasses']);
		if (isset($_POST['themeModuleType'])) $this->themeModuleType = $_POST['themeModuleType'];
		if (isset($_POST['send'])) $this->send = (boolean) $_POST['send'];

		// get theme module type object
		if ($this->themeModuleType && isset($this->availableThemeModuleTypes[$this->themeModuleType])) {
			$this->themeModuleTypeObject = $this->availableThemeModuleTypes[$this->themeModuleType];
		}
		//if ($this->themeModuleTypeObject !== null && $this->send) {
		if ($this->themeModuleTypeObject !== null) {
			$this->themeModuleTypeObject->readFormParameters();
		}
	}

	/**
	 * @see	Form::validate()
	 */
	public function validate() {
		parent::validate();

		// title
		if (empty($this->title)) {
			throw new UserInputException('title');
		}

		// theme module type
		if (!isset($this->availableThemeModuleTypes[$this->themeModuleType])) {
			throw new UserInputException('themeModuleType');
		}
		$this->themeModuleTypeObject->validate();
	}

	/**
	 * @see	Form::submit()
	 */
	public function submit() {
		// call submit event
		EventHandler::fireAction($this, 'submit');

		$this->readFormParameters();

		try {
			// send message or save as draft
			if ($this->send) {
				$this->validate();
				// no errors
				$this->save();
			}
		}
		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->getType();
		}
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		parent::save();

		// create theme module
		$this->themeModule = ThemeModuleEditor::create($this->themeID, $this->title, $this->cssID, $this->cssClasses, $this->themeModuleType, $this->themeModuleTypeObject->getFormData());
		ThemeModuleEditor::clearCache();
		$this->saved();

		// reset values
		$this->title = $this->cssID = $this->cssClasses = $this->themeModuleType = '';
		$this->themeModuleTypeObject = null;

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		if ($this->themeModuleTypeObject !== null) {
			$this->themeModuleTypeObject->assignVariables();
		}
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'availableThemeModuleTypes' => $this->availableThemeModuleTypes,
			'themeModuleTypeOptions' => ThemeModule::getThemeModuleTypeOptions(),
			'themeModuleTypeID' => $this->themeModuleTypeID,
			'themeModuleType' => $this->themeModuleType,
			'themeModuleTypeObject' => $this->themeModuleTypeObject,
			'title' => $this->title,
			'cssID' => $this->cssID,
			'cssClasses' => $this->cssClasses,
			'themeOptions' => Theme::getThemes()
		));
	}
}
?>