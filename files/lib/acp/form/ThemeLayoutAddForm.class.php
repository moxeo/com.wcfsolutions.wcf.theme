<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');

/**
 * Shows the theme layout add form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeLayoutAddForm extends ACPForm {
	// system
	public $templateName = 'themeLayoutAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.theme.layout.add';
	public $neededPermissions = 'admin.theme.canAddThemeLayout';

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
	 * theme layout editor object
	 *
	 * @var	ThemeLayoutEditor
	 */
	public $themeLayout = null;

	// parameters
	public $title = '';
	public $styleSheets = '';

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
	}

	/**
	 * @see	Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['styleSheets'])) $this->styleSheets = StringUtil::trim($_POST['styleSheets']);
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
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		parent::save();

		// save theme layout
		$this->themeLayout = ThemeLayoutEditor::create($this->themeID, $this->title, $this->styleSheets);

		// reset cache
		WCF::getCache()->clearResource('themeLayout-'.PACKAGE_ID);
		$this->saved();

		// reset values
		$this->themeID = 0;
		$this->title = '';

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'add',
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'title' => $this->title,
			'styleSheets' => $this->styleSheets,
			'themeOptions' => Theme::getThemes()
		));
	}
}
?>